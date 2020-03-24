<?php include_once __DIR__ . '/_partials/head.php' ?>

<?php include_once __DIR__ . '/_partials/nav.php' ?>

  <header>
    <div id="carouselIndicators" class="carousel slide" data-ride="carousel">
      <ol class="carousel-indicators">
        <?php foreach ($featuredProducts as $i => $featuredProduct) { ?>
          <li data-target="#carouselIndicators" data-slide-to="<?php echo $i ?>"
              class="<?php echo $i == 0 ? 'active' : '' ?>"></li>
        <?php } ?>
      </ol>
      <div class="carousel-inner" role="listbox">
        <!-- Slide One - Set the background image for this slide in the line below -->
        <?php foreach ($featuredProducts as $i => $featuredProduct) { ?>
          <div class="carousel-item <?php echo $i == 0 ? 'active' : '' ?>">
            <img class="img-fluid" src="<?php echo public_path("images/uploads/{$featuredProduct['image']}") ?>" alt="">
            <div class="carousel-caption d-none d-md-block">
              <h3><?php echo $featuredProduct['name'] ?></h3>
              <p>
                <?php echo nl2br($featuredProduct['description']) ?>
                <a href="<?php echo url("/products/{$featuredProduct['id']}") ?>">View</a>
              </p>
            </div>
          </div>
        <?php } ?>
      </div>
      <a class="carousel-control-prev" href="#carouselIndicators" role="button" data-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="sr-only">Previous</span>
      </a>
      <a class="carousel-control-next" href="#carouselIndicators" role="button" data-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="sr-only">Next</span>
      </a>
    </div>
  </header>

  <div class="container mt-5">
    <!-- Recent Products -->
    <div class="row mb-2">
      <div class="col-md-10">
        <h2>Recent Products</h2>
      </div>
      <div class="col-md-2">
        <a href="<?php echo url("/products") ?>" type="button" class="btn btn-primary w-100">View All Products</a>
      </div>
    </div>

    <div class="row">
      <?php include_once __DIR__ . '/products/_products.php' ?>
    </div>
    <!-- /.row -->
  </div>

<?php include_once __DIR__ . '/_partials/foot.php' ?>
