<?php include_once __DIR__ . '/../_partials/head.php' ?>

<?php include_once __DIR__ . '/../_partials/nav.php' ?>

<div class="container mt-5">
  <div class="row mb-2">
    <div class="col-md-10">
      <h2>Checkout <small>(Step 1/3)</small></h2>
    </div>
  </div>

  <div class="row">
    <?php include_once __DIR__ . '/_cart.php' ?>

    <div class="col-md-8 order-md-1">
      <h4 class="mb-3 text-muted">Shipping/Billing address</h4>

      <form action="<?php url('/checkout/payment') ?>" method="POST" class="needs-validation" novalidate>
        <?php echo csrf_token() ?>
        <article class="card">
          <div class="card-body">
            <?php include_once __DIR__ . '/_flashMessage.php' ?>

            <div class="row">
              <div class="col-md-6 mb-3">
                <label for="firstName">First name</label>
                <input type="text" class="form-control" id="firstName" name="firstName" value="<?php echo $user['firstName'] ?>" required>
                <div class="invalid-feedback">
                  Valid first name is required.
                </div>
              </div>
              <div class="col-md-6 mb-3">
                <label for="lastName">Last name</label>
                <input type="text" class="form-control" id="lastName" name="lastName" value="<?php echo $user['lastName'] ?>" required>
                <div class="invalid-feedback">
                  Valid last name is required.
                </div>
              </div>
            </div>

            <div class="mb-3">
              <label for="address">Address</label>
              <input type="text" class="form-control" id="address" name="address" value="<?php echo $user['address'] ?>" required>
              <div class="invalid-feedback">
                Please enter your shipping address.
              </div>
            </div>

            <div class="mb-3">
              <label for="address2">Address 2 <span class="text-muted">(Optional)</span></label>
              <input type="text" class="form-control" id="address2" name="address2" placeholder="Apartment or suite" value="<?php echo $user['address2'] ?>">
            </div>

            <div class="row">
              <div class="col-md-9 mb-3">
                <label for="state">State</label>
                <select class="custom-select d-block w-100" id="state" name="state" required>
                  <option value="" selected disabled>Choose...</option>
                  <?php foreach ($germanStates as $germanState) { ?>
                    <option <?php echo $user['state'] === $germanState ? 'selected' : '' ?>>
                      <?php echo $germanState ?>
                    </option>
                  <?php } ?>
                </select>
                <div class="invalid-feedback">
                  Please provide a valid state.
                </div>
              </div>
              <div class="col-md-3 mb-3">
                <label for="zip">Zip Code</label>
                <input type="number" class="form-control" id="zip" name="zipCode" value="<?php echo $user['zipCode'] ?>" required>
                <div class="invalid-feedback">
                  Zip code is required.
                </div>
              </div>
            </div>
          </div>
        </article> <!-- card.// -->

        <div class="row mt-4">
          <div class="col-md-3 offset-md-9">
            <button class="btn btn-primary btn-block" type="submit">Next Step <i class="fa fa-angle-right"></i></button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>

<?php include_once __DIR__ . '/../_partials/foot.php' ?>
