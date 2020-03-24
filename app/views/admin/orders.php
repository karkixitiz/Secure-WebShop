<?php include_once __DIR__ . '/../_partials/head.php' ?>

<?php include_once __DIR__ . '/../_partials/nav.php' ?>

<div class="container mt-5">
  <div class="row mb-2">
    <div class="col-md-10">
      <h2>Customer Orders</h2>
    </div>
  </div>

  <div class="row">
    <div class="col-md-8">
      <?php foreach ($allOrders as $i => $allOrder ) { ?>
        <label><strong>Order ID:</strong></label>
        <?php echo $orderids[$i]['order_id'] ?>

        <table class="table table-bordered">
          <tr>
            <th width="20%">Product</th>
            <th width="10%">Quantity</th>
            <th width="20%">Price</th>
            <th width="15%">Total</th>
          </tr>
          <?php
          $totalPrice = 0;
          foreach ($allOrder as $order) {
            $totalPrice += $order['total_price'];
          ?>
            <tr>
              <td>
                <a href="<?php echo url("/products/{$order['id_item']}") ?>">
                  <?php echo $order["name"] ?>
                </a>
              </td>
              <td><?php echo $order["quantity"] ?></td>
              <td>€<?php echo $order["price"] ?></td>
              <td>€<?php echo $order["total_price"] ?></td>
            </tr>
          <?php } ?>
          <tr>
            <td></td>
            <td></td>
            <td><strong>Grand Total</strong></td>
            <td>€<?php echo $totalPrice ?></td>
          </tr>
        </table>
      <?php } ?>
    </div>
  </div>
</div>

<?php include_once __DIR__ . '/../_partials/foot.php' ?>
