<?php include_once __DIR__ . '/../_partials/head.php' ?>
<?php include_once __DIR__ . '/../_partials/nav.php' ?>

<div class="container mt-5">
  <div class="row justify-content-center">
    <div class="col-12 col-md-8 col-lg-6 pb-5">
      <form class="needs-validation" id="signin-form" method="POST" action="<?php url('/login') ?>" novalidate>
        <?php echo csrf_token(); ?>
        <div class="card border-primary">
          <div class="card-header p-0">
            <div class="bg-info text-white text-center py-2">
              <div class="social-buttons">
                <img id="profile-img" class="profile-img-card" src="<?php echo public_path('images/avatar_2x.png') ?>"/>
                <p id="profile-name" class="profile-name-card"></p>
              </div>
              <h3><span class="glyphicon glyphicon-envelope"></span></i> Login Credentials</h3>
            </div>
          </div>
          <div class="card-body p-3">
            <div class="form-group">
              <div class="input-group mb-2">
                <div class="input-group-prepend">
                  <div class="input-group-text"><i class='fas fa-user'></i></div>
                </div>
                <input type="text" class="form-control" name="email" id="email"  value="<?php if(isset($_COOKIE["member_login"])) { echo $_COOKIE["member_login"]; } ?>"  placeholder="Email address" required>
                <div class="invalid-feedback">
                  Please enter your email.
                </div>
              </div>
            </div>
            <div class="form-group">
              <div class="input-group mb-2">
                <div class="input-group-prepend">
                  <div class="input-group-text"><i class="fa fa-envelope text-info"></i></div>
                </div>
                <input type="password" class="form-control" name="password" id="password"  value="<?php if(isset($_COOKIE["member_password"])) { echo $_COOKIE["member_password"]; } ?>"
                       placeholder="Enter your Password" autocomplete="off" required>
                <div class="invalid-feedback">
                  Please enter your password.
                </div>
              </div>
            </div>

            <div class="form-group">
              <input type="submit" name="login" id="login_btn" class="btn btn-primary btn-block" value="Sign In">
            </div>

            <div class="form-group">
              <div class="row">
                <div class="col-md-6">

                  <div class="field-group">
                      <input type="checkbox" name="remember" id="remember"
                      <?php if(isset($_COOKIE["member_login"])) { ?> checked
                      <?php } ?> /> <label for="remember-me">Keep me logged in</label>
                      </div>
                </div>
                <div class="col-md-6 text-right">
                  <a href="<?php echo url('/forgetpassword') ?>">Forgot password?</a>
                </div>
              </div>
            </div>
          </div>

        </div>
      </form>
      <!--Form with header-->
    </div>
  </div>
</div>

<?php include_once __DIR__ . '/../_partials/foot.php' ?>
