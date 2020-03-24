<?php include_once __DIR__ . '/../_partials/head.php' ?>
<?php include_once __DIR__ . '/../_partials/nav.php' ?>

<div class="container mt-5">
  <div class="row mb-2">
    <div class="col-md-10">
      <h2>Checkout <small>(Step 3/3)</small></h2>
    </div>
  </div>

  <div class="row">
    <?php include_once __DIR__ . '/_cart.php' ?>

    <div class="col-md-8 order-md-1">
      <h4 class="mb-3 text-muted">Confirm checkout details</h4>
      <form action="<?php echo url('/checkout/confirm') ?>" method="POST" class="needs-validation">
		  <?php
              foreach ($products as $p) { ?>
				  <input type="hidden" name="p_id[]" value="<?=$p['id']?>">
				  <input type="hidden" name="p_quantity[]" value="<?=$p['quantity']?>">
		  <?php
              }
		  ?>

        <?php echo csrf_token() ?>
        <article class="card">
          <div class="card-body">
            <p class="alert alert-info">
              Please confirm that all the details are correct before confirming the checkout. <br>
              <strong>Note:</strong> There's no way to cancel the order once you've confirmed it.
            </p>

            <?php include_once __DIR__ . '/_shippingDetails.php' ?>

            <?php include_once __DIR__ . '/_paymentDetails.php' ?>
          </div>
          <!-- card-body.// -->
        </article> <!-- card.// -->

        <div class="row mt-4">
          <div class="col-md-3">
            <a href="<?php echo url('/checkout/payment') ?>" class="btn btn-secondary btn-block"><i class="fa fa-angle-left"></i> Previous Step</a>
          </div>
          <div class="col-md-3 offset-md-6">
            <button class="btn btn-primary btn-block" type="submit"><i class="fa fa-check"></i> Confirm</button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
<?php include_once __DIR__ . '/../_partials/foot.php' ?>
