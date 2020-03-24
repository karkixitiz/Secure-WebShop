<?php
  require_once __DIR__ . '/BaseModel.php';

  class Auth extends BaseModel {
    //Register New User
    public function register($userData)
    {
      $usertype = 'customer';
      $pass = $userData['password']; //assume that every register user is customer
      if (!$this->isUserExist($userData['name'], $userData['email'])) {
        $query = 'INSERT INTO tbl_user (name,email,password,userType) VALUES (:name, :email, :password, :userType)';
        //Prepare our statement using the SQL query.
        $statement = $this->db->prepare($query);
        //Bind our values to our parameters
        $statement->bindValue(':name', $userData['name']);
        $statement->bindValue(':email', $userData['email']);
        $hashPassword = password_hash($pass, PASSWORD_DEFAULT); // Creates a password hash
        $statement->bindValue(':password', $hashPassword);
        $statement->bindValue(':userType', $usertype);

        //Execute the statement and insert our values.
        return $statement->execute() > 0;
      } else {
        return false;
      }
    }

    //update user
    public function updateUser($old_pass, $new_pass, $id)
    {
      //get old password first
      $query = $this->db->prepare("SELECT password FROM tbl_user WHERE (id=:id)");
      $query->bindParam("id", $id, PDO::PARAM_STR);
      $query->execute();
      if ($query->rowCount() > 0) {
        $result = $query->fetch(PDO::FETCH_OBJ);
        $hashedPassword = $result->password;
        if (password_verify($old_pass, $hashedPassword)) {
          $newHashPassword = password_hash($new_pass, PASSWORD_DEFAULT);
          $sql = "UPDATE tbl_user SET  password=? WHERE id=?";
          $stmt = $this->db->prepare($sql);
          $stmt->execute([$newHashPassword, $id]);

          return $stmt->execute() > 0;
        } else {
          return false;
        }
      } else {
        return false;
      }
    }

    /**
     * Update shipping information of the logged in user
     */
    public function updateShippingData($data)
    {
      $query = 'UPDATE tbl_user
                    SET name=:name,
                        address=:address,
                        address2=:address2,
                        state=:state,
                        zipCode=:zipCode
                    WHERE id=:userId';

      $statement = $this->db->prepare($query);

      $name = "{$data['firstName']} {$data['lastName']}";
      $statement->bindParam('name', $name);
      $statement->bindParam('address', $data['address']);
      $statement->bindParam('address2', $data['address2']);
      $statement->bindParam('state', $data['state']);
      $statement->bindParam('zipCode', $data['zipCode']);

      $user = $this->getLoggedInUser();
      $statement->bindParam('userId', $user['id']);

      return $statement->execute() > 0;
    }

    //check user is already exist or not?
    public function isUserExist($name, $email)
    {
      $query = $this->db->prepare("SELECT id FROM tbl_user WHERE email=:email OR name=:name");
      $query->bindParam("email", $email, PDO::PARAM_STR);
      $query->bindParam("name", $name, PDO::PARAM_STR);
      $query->execute();

      return $query->rowCount() > 0;
    }

    //login
    public function login($email, $pass)
    {
      $result = false;
      $query = $this->db->prepare("SELECT id,password,name,userType FROM tbl_user WHERE (email=:email)");
      $query->bindParam("email", $email, PDO::PARAM_STR);
      $query->execute();
      if ($query->rowCount() > 0) {
        $result = $query->fetch(PDO::FETCH_OBJ);
        $hashedPassword = $result->password;
        //update hash password if necessary
        if (password_verify($pass, $hashedPassword)) {
          if (password_needs_rehash($hashedPassword, PASSWORD_DEFAULT)) {
            $hash = password_hash($pass, PASSWORD_DEFAULT);
          }
          //session start
          $_SESSION["user_id"] = $result->id;
          $_SESSION["username"] = $result->name;
          $_SESSION["usertype"] = $result->userType;
          $result = true;
        } else {
          $result = false;
        }
      }

      return $result;
    }

    //get user information by user id
    function getUser($id)
    {
      $sql = 'SELECT id,name,address,phone,email,userType from tbl_user where id=' . $id . '';

      return $this->db->query($sql);
    }

    //check user email is already exist or not? If exist send password reset link to the email.
    function isUserEmailExist($email)
    {
      $exist = false;

      try {
        $query = $this->db->prepare("SELECT id FROM tbl_user WHERE email=:email");
        $query->bindParam("email", $email, PDO::PARAM_STR);
        $query->execute();
        $row = $query->fetch(PDO::FETCH_ASSOC);
        if ($query->rowCount() > 0) {
          $id = base64_encode($row['id']);
          $code = md5(uniqid(rand()));
          $count = 0;
          $sql = "UPDATE tbl_user SET tokenCode=:token,count=:count WHERE email=:email";
          $stmt = $this->db->prepare($sql);
          $stmt->execute([$code, $count, $email]);

          $pwResetUrl = 'http://localhost' . url("/resetpassword?id=$id&code=$code");

          $message = "Hello , $email <br /><br /> We got requested to reset your password, if you do this then just click the following link to reset your password, if not just ignore this email,
                  <br /><br />Click Following Link To Reset Your Password <br /><br />
                  <a href='$pwResetUrl'>click here to reset your password</a>
                  <br /><br />  thank you :)";
          $subject = "Password Reset";

          $this->send_mail($email, $message, $subject);

          $exist = true;
        }
      } catch (Exception $e) {
        exit($e->getMessage());
      }

      return $exist;
    }

    //check user id and token code receive that sended to user email ?
    function getUserByTokenId($id, $token)
    {
      $count = 0;
      $query = $this->db->prepare("SELECT * FROM tbl_user WHERE id=:id AND tokenCode=:token AND count=:count");
      $query->bindParam("id", $id, PDO::PARAM_STR);
      $query->bindParam("token", $token, PDO::PARAM_STR);
      $query->bindParam("count", $count, PDO::PARAM_STR);
      $query->execute();

      return $query->fetch(PDO::FETCH_ASSOC);
    }

    //Update user password
    function updateUserPassword($userpassword, $id, $token)
    {
      $count = 1;
      $hashPassword = password_hash($userpassword, PASSWORD_DEFAULT);
      $sql = "UPDATE tbl_user SET password=?,count=? WHERE id=? AND tokenCode=?";
      $stmt = $this->db->prepare($sql);
      $stmt->execute([$hashPassword, $count, $id, $token, $count]);

      return $stmt->execute() > 0;
    }

    //send mail function
    function send_mail($email, $message, $subject)
    {
      require_once __DIR__ . '/../config/email.php';
      $mail->AddAddress($email);
      $mail->Subject = $subject;
      $mail->MsgHTML($message);
      $send = $mail->Send();
      if (!$send) {
        $_SESSION["success"] = "Message could not be sent.'. $mail->ErrorInfo;";
        exit;
      }
      $_SESSION["success"] = "Message has been sent!!";
    }

    /**
     * Get current logged in user
     */
    function getLoggedInUser()
    {
      $id = $_SESSION['user_id'];

      $statement = $this->db->prepare("SELECT * from tbl_user where id=:id");
      $statement->bindParam("id", $id, PDO::PARAM_STR);
      $statement->execute();

      $user = $statement->fetch();

      $nameSplit = explode(' ', $user['name']);
      $user['firstName'] = $nameSplit[0];
      $user['lastName'] = $nameSplit[1];

      return $user;
    }
  }
