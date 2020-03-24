<h5 class="mb-3">Payment Details</h5>

<p class="mb-1">
  <label>Type:</label>
  <?php echo $paymentData['typeName'] ?>
</p>

<?php if ($paymentData['type'] === 'credit-card') { ?>
<p class="mb-1">
  <label>Card number:</label>
  <?php echo $paymentData['card-number-obfuscated'] ?>
  <i class="fa fa-question-circle" title="Some part of your information is hidden with X for security purposes"></i>
</p>

<p class="mb-1">
  <label>Expiration Date:</label>
  XX/20
  <i class="fa fa-question-circle" title="Some part of your information is hidden with X for security purposes"></i>
</p>

<?php } ?>