<?php include_once __DIR__ . '/../_partials/head.php' ?>

<?php include_once __DIR__ . '/../_partials/nav.php' ?>

<div class="container mt-5">
  <div class="row mb-2">
    <div class="col-md-10">
      <h2>Product Details</h2>
    </div>
  </div>

  <div class="row">
    <div class="col-md-8">
      <h3><?php echo $product['name'] ?></h3>
    </div>
    <div class="col-md-4">
      <?php require __DIR__ . '/_adminButtons.php' ?>
    </div>
    <div class="col-md-12">

      <img class="card-img-top mb-2" src="<?php echo public_path("images/uploads/{$product['image']}") ?>" alt="">

      <p><?php echo nl2br($product['description']) ?></p>

      <b>Price:</b> €<?php echo $product['price'] ?>

      <?php require __DIR__ . '/_addToCart.php' ?>

    </div>
  </div>
</div>

<?php include_once __DIR__ . '/../_partials/foot.php' ?>
