<?php
  require_once __DIR__ . '/../SessionManager.php';
  require_once __DIR__ . '/../models/Auth.php';

  class AuthController {
    protected $authModel;
    protected $sessionManager;

    function __construct()
    {
      $this->authModel = new Auth;
      $this->sessionManager = new SessionManager;
    }

    /**
     * Sanitize user provided input before
     * updating them to database
     */
    private function sanitizeInput()
    {
      // remove whitespaces to the left and right of every values
      $_POST = array_map('trim', $_POST);
      $sanitizationParams = [
        'name'  => FILTER_SANITIZE_STRING,
        'email' => FILTER_SANITIZE_EMAIL,
      ];

      $user = filter_var_array($_POST, $sanitizationParams);
      //$user['address']=trim($_POST['address']);
      $user['password'] = trim($_POST['password']);

      return $user;
    }

    //User Registration Get method
    function userRegister()
    {
      //  echo filter_var($string, FILTER_SANITIZE_STRING); // quotes are encoded
      $pageTitle = 'User Registration';
      require_once __DIR__ . '/../views/auth/userRegister.php';
    }

    function userRegisterPost()
    {
      if (!filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL)) {
        $_SESSION['success'] = 'Please enter a valid email';
        redirect('/register');
      }
      if ($_POST['password'] != $_POST['confirm_password']) {
        $_SESSION['success'] = 'Confirm Password does not match !!';
        redirect('/register');
      }
      $userData = $this->sanitizeInput();
      $isCreated = $this->authModel->register($userData);
      if ($isCreated) {
        $_SESSION["success"] = "New user is created successfully";
        redirect('/login');
      } else {
        $_SESSION["success"] = "This user is already exist";
        redirect('/register');
      }
    }

    function userLoginPost()
    {
      if (!filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL)) {
        $_SESSION['success'] = 'Please enter a valid email';
        redirect('/login');
      }

      $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
      $password = trim($_POST['password']);
      $isLogin = $this->authModel->login($email, $password);

      $current_time = time();
      $current_date = date("Y-m-d H:i:s", $current_time);
      $cookie_expiration_time = $current_time + (30 * 24 * 60 * 60);  // for 1 month

      if ($isLogin) {
        if(isset($_POST['remember'])){
          setcookie("member_login", $email, $cookie_expiration_time);
          setcookie("member_password", $password, $cookie_expiration_time);
        }else {
          $this->clearAuthCookie();
        }
        $_SESSION['success'] = "User Logged In Successfully";
        redirect('/');
      } else {
        $_SESSION['success'] = "User Name or Password is incorrect";
        redirect('/login');
      }
    }

    //Get method for user Login
    function userLogin()
    {
      $pageTitle = 'User Login';
      require_once __DIR__ . '/../views/auth/userLogin.php';
    }

    //Post method for user edit
    function changePasswordUpdate()
    {
      $id = trim($_SESSION['user_id']);
      $old_pass = trim($_POST['old_password']);
      $new_pass = trim($_POST['password']);
      $confirm_pass = trim($_POST['confirm_password']);
      $isUpdated = $this->authModel->updateUser($old_pass, $new_pass, $id);

      if ($new_pass != $confirm_pass) {
        $_SESSION['error'] = "The new password and confirmation don't match!!!";
      } else {
        if ($isUpdated) {
          $_SESSION['success'] = "User Password is Updated Successfully !!!";
          redirect('/');
        } else {
          $_SESSION['error'] = "Current password is incorrect !!!";
        }
      }

      redirect('/changepassword');
    }

    //Get method for user edit
    function changePassword()
    {
      $pageTitle = 'Change Your Password';
      require_once __DIR__ . '/../views/auth/userEdit.php';
    }

    //Get methor for forget user password
    function ForgetPassword()
    {
      $pageTitle = 'Forgot User Password?';

      require_once __DIR__ . '/../views/auth/userForgetPassword.php';
    }

    //Post method for reset password
    function ForgetPasswordUpdate()
    {
      if (!isset($_POST['email']) || !filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL)) {
        $_SESSION["success"] = "Invalid Email!!";

        redirect('/forgetpassword');
      }

      $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
      $this->authModel->isUserEmailExist($email);

      // Show the same message regardless of if email exists or not in order to
      // prevent malicious actor from testing if some email is registerd or not
      $_SESSION["success"] = "We've sent an email to $email. Please click on the password reset link in the email to generate new password.";

      redirect('/forgetpassword');
    }

    //Get methor for reset user password
    function ResetPassword()
    {
      $pageTitle = 'Reset User Password';
      if (isset($_GET['id']) && isset($_GET['code'])) {
        $id = base64_decode($_GET['id']);
        $token = $_GET['code'];
        $rows = $this->authModel->getUserByTokenId($id, $token);
        if ($rows<=0) {
            $_SESSION['success'] = "This Token is already used!! Please resend reset password link again";
            require_once __DIR__ . '/../views/auth/userForgetPassword.php';
        } else {
          require_once __DIR__ . '/../views/auth/resetPassword.php';
        }
      } else {
        require_once __DIR__ . '/../views/auth/userForgetPassword.php';
      }
    }

    //Post method for User reset PASSWORD
    function ResetPasswordUpdate()
    {
      $pass = trim($_POST['password']);
      $cpass = trim($_POST['confirm-password']);
      $id = base64_decode($_POST['userid']);
      $token = trim($_POST['code']);
      if ($pass != $cpass) {
        $_SESSION['success'] = "Confirm Password is not match!!!";
      } else {
        $isUpdate = $this->authModel->updateUserPassword($cpass, $id, $token);
        if ($isUpdate) {
          $_SESSION["success"] = "Password Updated successfully!! Please login with new password !!!";
          redirect('/login');
        } else {
          $_SESSION["success"] = "Error while updating the password OR you have already reset the password using this token!!";
        }
      }
      require_once __DIR__ . '/../views/auth/resetPassword.php';
    }

    //user logout
    function userLogout()
    {
      $this->sessionManager->destroySession();

      redirect('/login');
    }

    //clear cookies
      function clearAuthCookie() {
        if (isset($_COOKIE["member_login"])) {
            setcookie("member_login", "");
        }
        if (isset($_COOKIE["member_password"])) {
            setcookie("member_password", "");
        }
      }
  }
