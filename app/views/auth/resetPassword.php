<?php include_once __DIR__ . '/../_partials/head.php' ?>
<?php include_once __DIR__ . '/../_partials/nav.php' ?>

<div class="container mt-5">
  <div class="row justify-content-center">
    <div class="col-12 col-md-8 col-lg-6 pb-5">
      <div class="card border-primary">
        <div class="card-header p-0">
          <div class="bg-info text-white text-center py-2">
            <h3 >Reset Your Password</h3>
          </div>
        </div>
        <div class="card-body">
          <form class="needs-validation" id="signin-form" method="POST" action="<?php echo url('/resetpassword') ?>" novalidate>
            <?php echo csrf_token(); ?>

            <input type="hidden" class="input-block-level"" name="userid" value="<?php echo htmlspecialchars($_GET['id']); ?>" />
            <input type="hidden" class="input-block-level"" name="code"
            value="<?php echo htmlspecialchars($_GET['code']); ?>" />

            <div class="form-group">
              <label for="userpassword" class="control-label">New Password</label>
              <div class="form-group">
                <input type="password" minlength="6" class="form-control" name="password" id="password"
                      placeholder="Enter New Password" autocomplete="off" required>

                <div class="invalid-feedback">
                  must enter minimum of 6 characters
                </div>
              </div>
            </div>
            <div class="form-group">
              <label for="userconfirmpassword" class="control-label">Confirm New Password</label>
              <div class="form-group">
                <input type="password" minlength="6" class="form-control" name="confirm-password" id="confirm-password"
                      placeholder="Enter Confirm New Password" autocomplete="off" required>

                <div class="invalid-feedback">
                  must enter minimum of 6 characters
                </div>
              </div>
            </div>
            <div class="text-center">
              <input type="submit" id="btn-reset-pass" name="btn-reset-pass" class="btn btn-primary"
                    value="Reset Password">
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
<?php include_once __DIR__ . '/../_partials/foot.php' ?>
