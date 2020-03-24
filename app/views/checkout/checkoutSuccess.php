<?php include_once __DIR__ . '/../_partials/head.php' ?>
<?php include_once __DIR__ . '/../_partials/nav.php' ?>

<link href="<?php echo public_path('css/print.css') ?>" rel="stylesheet" media="print">

<div class="container mt-5">
  <div class="row mb-2">
    <div class="col-md-12">
      <h2 class="text-center">Purchase Success</small></h2>
    </div>
  </div>

  <div class="row">
    <div class="col-md-8 offset-md-2">
      <article class="card">
        <div class="card-body">
          <p class="alert alert-success mb-3">
            Your purchase has been successfully completed. Thank you for shopping with us. <br>
            Below you can find information about your purchase that you can print as well.
          </p>

          <button class="col-md-3 offset-md-9 btn btn-primary btn-block mb-5" id="print-receipt" type="button">
            <i class="fa fa-print"></i> Print Receipt
          </button>

          <h4 class="mb-3">Order Receipt</h4>

          <p>
            <label>Order Number:</label> <?=$rand?>
          </p>

          <h5 class="mb-3">Order Items</h5>

          <?php include_once __DIR__ . '/_cartItems.php' ?>

          <hr class="mb-4">

          <?php include_once __DIR__ . '/_shippingDetails.php' ?>

          <?php include_once __DIR__ . '/_paymentDetails.php' ?>
        </div>
        <!-- card-body.// -->
      </article> <!-- card.// -->
    </div>
  </div>
</div>
<?php include_once __DIR__ . '/../_partials/foot.php' ?>
