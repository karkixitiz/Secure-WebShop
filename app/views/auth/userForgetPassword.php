<?php include_once __DIR__ . '/../_partials/head.php' ?>
<?php include_once __DIR__ . '/../_partials/nav.php' ?>

<div class="container mt-5">

  <div class="row justify-content-center">
    <div class="col-12 col-md-8 col-lg-6 pb-5">
      <div class="card border-primary">
        <div class="card-header p-0">
          <div class="bg-info text-white text-center py-2">
            <h3>Forgot your Password?</h3>
          </div>
        </div>
        <div class="card-body">
          <form class="form-signin" method="post">
            <?php echo csrf_token(); ?>

            <div class='alert alert-info'>
              Please enter your email address. You will receive a link to create a new password via email.!
            </div>
            <div class="form-group">
              <label class="control-label" for="username">Enter Your Registered Email ID</label>
              <input type="email" class="form-control" name="email" id="email" placeholder="Email address" required/>
            </div>
            <div class="text-center">
              <button class="btn btn-danger btn-primary" type="submit" name="btn-submit">Generate new Password</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<?php include_once __DIR__ . '/../_partials/foot.php' ?>
