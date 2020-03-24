<?php include_once __DIR__ . '/../_partials/head.php' ?>
<?php include_once __DIR__ . '/../_partials/nav.php' ?>

<div class="container mt-5">
  <div class="row justify-content-center">
    <div class="col-12 col-md-8 col-lg-6 pb-5">
      <form id="signup-form" class="needs-validation" role="form" method="post"
            action="<?php echo url('/register') ?>"
            oninput='confirm_password.setCustomValidity(confirm_password.value != password.value ? "Passwords do not match." : "")'
            novalidate>
        <?php echo csrf_token(); ?>
        <div class="card border-primary rounded-0">
          <div class="card-header p-0">
            <div class="bg-info text-white text-center py-2">
              <h3><span class="glyphicon glyphicon-envelope"></span></i>Registration</h3>
            </div>
          </div>

          <div class="card-body">
            <div class="form-group">
              <label class="control-label" for="username">Name</label>
              <input type="text" class="form-control" name="name" id="name" placeholder="Enter your Name"
                     required/>
              <div class="invalid-feedback">
                Please enter name
              </div>
            </div>
            <div class="form-group">
              <label for="useremail" class="control-label">Email</label>
              <input type="email" class="form-control" name="email" id="email" placeholder="Enter your Email"
                     required>
              <div class="invalid-feedback">
                Please enter Email
              </div>
            </div>


            <div class="form-group">
              <label for="userpassword" class="control-label">Password</label>
              <div class="form-group">
                <input type="password" minlength="8" class="form-control" name="password" id="password"
                       placeholder="Enter your Password" autocomplete="off" required>

                <div class="invalid-feedback">
                  Password must be minimum 8 characters
                </div>
              </div>
              <div class="form-group">
                <label for="userconfirmpassword" class="control-label">Confirm Password</label>
                <div class="form-group">
                  <input type="password" minlength="8" class="form-control" name="confirm_password"
                         id="confirm_password" autocomplete="off"
                         placeholder="Enter your confirm Password">

                  <div class="invalid-feedback">
                    Passwords do not match.
                  </div>
                </div>
              </div>

              <div class="text-center">
                <input type="hidden" name="form-submitted" value="1"/>
                <input type="submit" id="register_btn" class="btn btn-primary" value="Submit">
                <input type="reset" id="refresh_btn" class="btn btn-default" value="Reset">
              </div>

              <div class="login-register mt-3">
                <p>Already have an account? <a href="<?php echo url('/login') ?>">Login here</a></p>
              </div>
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
<?php include_once __DIR__ . '/../_partials/foot.php' ?>
