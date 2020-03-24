<nav class="navbar fixed-top navbar-expand-lg navbar-dark bg-dark fixed-top">
  <div class="container">
    <a class="navbar-brand" href="<?php echo url('/') ?>">WebShop</a>
    <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarResponsive">
      <ul class="navbar-nav ml-auto">
        <li class="nav-item">
          <a class="nav-link" href="<?php echo url('/products') ?>">Products</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="<?php echo url('/about-us') ?>">About Us</a>
        </li>
        <?php if (isAdmin()) { ?>
        <li class="nav-item">
          <a class="nav-link" href="<?php echo url('/admin/vieworders') ?>">View Orders</a>
        </li>
        <?php } ?>
        <li class="nav-item ml-md-5 mr-md-2 mb-2 mb-md-0">
          <a href="#" id="cart_button" class="nav-link">
            <i class="fa fa-shopping-cart"></i>
          </a>
        </li>
        <?php
          if(empty($_SESSION['username'])) {
        ?>
        <li class="nav-item ml-md-5 mr-md-2 mb-2 mb-md-0">
          <a class="btn btn-primary" href="<?php echo url('/login') ?>">Login</a>
        </li>
        <li class="nav-item">
          <a class="btn btn-primary" href="<?php echo url('/register') ?>">Register</a>
        </li>
        <?php } else { ?>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              Account
            </a>
            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
              <p class="dropdown-item"><?php echo $_SESSION['username']?>(<?php echo $_SESSION['usertype']?>)</p>
              <a class="dropdown-item" href="<?php echo url('/changepassword') ?>">Change Password</a>
              <div class="dropdown-divider"></div>

              <form action="<?php echo url('/logout') ?>" method="POST">
                <?php echo csrf_token() ?>
                <button class="dropdown-item" type="submit">Logout</button>
              </form>
            </div>
          </li>
        <?php } ?>
      </ul>
    </div>
  </div>
</nav>

<div class="main">
  <?php if (isset($_SESSION['success']) || isset($_SESSION['error'])) { ?>
    <div class="p-4" id="messageAlert" align="center">
      <div class="col-4">
        <?php if (isset($_SESSION['success'])) { ?>
          <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <?php
            echo $_SESSION['success'];
            unset($_SESSION['success']);
            ?>
          </div>
        <?php } else { ?>
          <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <?php
            echo $_SESSION['error'];
            unset($_SESSION['error']);
            ?>
          </div>
        <?php } ?>
      </div>
    </div>
  <?php } ?>

	<div id="mycart">
	</div>
