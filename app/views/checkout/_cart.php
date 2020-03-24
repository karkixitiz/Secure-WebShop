<div class="col-md-4 order-md-2 mb-4">
    <h4 class="d-flex justify-content-between align-items-center mb-3">
        <span class="text-muted">Your cart</span>
        <span class="badge badge-secondary badge-pill">
            <?php echo sizeof($products) ?>
        </span>
    </h4>

    <?php include_once __DIR__ . '/_cartItems.php' ?>
</div>