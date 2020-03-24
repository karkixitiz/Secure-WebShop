<?php include_once __DIR__ . '/../_partials/head.php' ?>
<?php include_once __DIR__ . '/../_partials/nav.php' ?>

<div class="container mt-5">
  <div class="row mb-2">
    <div class="col-md-10">
      <h2>Checkout <small>(Step 2/3)</small></h2>
    </div>
  </div>

  <div class="row">
    <?php include_once __DIR__ . '/_cart.php' ?>

    <div class="col-md-8 order-md-1">
      <h4 class="mb-3 text-muted">Payment Information</h4>
      <form action="<?php echo url('/checkout/payment') ?>" method="POST" class="payment-form">
        <?php echo csrf_token() ?>
        <article class="card">
          <div class="card-body">
            <ul class="nav bg-light nav-pills rounded nav-fill mb-3" role="tablist">
              <li class="nav-item">
                <a class="nav-link active" data-toggle="pill" href="#nav-tab-card" data-type="credit-card">
                <i class="fa fa-credit-card"></i> Credit Card</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" data-toggle="pill" href="#nav-tab-paypal" data-type="paypal">
                <i class="fab fa-paypal"></i> Paypal</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" data-toggle="pill" href="#nav-tab-bank" data-type="bank-transfer">
                <i class="fa fa-university"></i> Bank Transfer</a>
              </li>
            </ul>

            <input type="hidden" name="payment-type" id="payment-type" value="credit-card">

            <div class="tab-content">
              <div class="tab-pane fade show active" id="nav-tab-card">
                <div class="form-group">
                  <label for="full-name">Full name (on the card)</label>
                  <input type="text" class="form-control" name="full-name" required>
                </div>
                <!-- form-group.// -->
                <div class="form-group">
                  <label for="cardNumber">Card number</label>
                  <div class="input-group">
                    <input type="number" class="form-control" name="card-number" required>
                    <div class="input-group-append">
                      <span class="input-group-text text-muted">
                      <i class="fab fa-cc-visa mr-2" title="VISA"></i>
                      <i class="fab fa-cc-mastercard mr-2" title="Mastercard"></i>
                      <i class="fab fa-cc-amex" title="American Express"></i>
                      </span>
                    </div>
                  </div>
                </div>
                <!-- form-group.// -->
                <div class="row">
                  <div class="col-sm-8">
                    <div class="form-group">
                      <label><span class="hidden-xs">Expiration</span> </label>
                      <div class="input-group">
                        <input type="number" class="form-control" placeholder="MM" name="expiry-month" min="1" max="12" required>
                        <input type="number" class="form-control" placeholder="YY" name="expiry-year" min="19" max="99" required>
                      </div>
                    </div>
                  </div>
                  <div class="col-sm-4">
                    <div class="form-group">
                      <label title="3 digits code on back side of the card">
                        CVV <i class="fa fa-question-circle"></i>
                      </label>
                      <input type="number" name="cvv" class="form-control" min="000" max="999" required>
                    </div>
                    <!-- form-group.// -->
                  </div>
                </div>
                <!-- row.// -->
              </div>
              <!-- tab-pane.// -->
              <div class="tab-pane fade" id="nav-tab-paypal">
                <p>Paypal is easiest way to pay online</p>
                <p>
                  <button type="button" class="btn btn-primary"> <i class="fab fa-paypal"></i> Authenticate with Paypal </button>
                </p>
              </div>
              <div class="tab-pane fade" id="nav-tab-bank">
                <p>Please deposit the card amount to the following bank details. We'll start processing your request once we've received the payment.</p>

                <dl class="param">
                  <dt>BANK: </dt>
                  <dd>The Iron Bank</dd>
                </dl>
                <dl class="param">
                  <dt>IBAN: </dt>
                  <dd>DE1234567890123</dd>
                </dl>
                <dl class="param">
                  <dt>BIC: </dt>
                  <dd>ABCDEFGH</dd>
                </dl>
                <dl class="param">
                  <dt>Payment Reason: </dt>
                  <dd>Order#123213213</dd>
                </dl>
                <p><strong>Note:</strong> It make take some days before the deposited amount becomes available in our account, so please have patience
                </p>
              </div>
              <!-- tab-pane.// -->
            </div>
            <!-- tab-content .// -->
          </div>
          <!-- card-body.// -->
        </article> <!-- card.// -->

        <div class="row mt-4">
          <div class="col-md-3">
            <a href="<?php echo url('/checkout/address') ?>" class="btn btn-secondary btn-block"><i class="fa fa-angle-left"></i> Previous Step</a>
          </div>
          <div class="col-md-3 offset-md-6">
            <button class="btn btn-primary btn-block" type="submit">Next Step <i class="fa fa-angle-right"></i></button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
<?php include_once __DIR__ . '/../_partials/foot.php' ?>

