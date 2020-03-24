<ul class="list-group mb-3">
    <li class="list-group-item d-flex justify-content-between">
        <strong>Product</strong>
        <strong>Price</strong>
        <strong>Quantity</strong>
    </li>
    <?php
        $sum=0;
        foreach ($products as $p) {
            $sum += intval($p['price']*$p['quantity']);
            ?>

            <li class="list-group-item d-flex justify-content-between">

                <div>
                    <h6 class="my-0"><?=$p['name']?></h6>
                    <small class="text-muted"><?=$p['description']?></small>
                </div>
                <span class="text-muted">€<?=$p['price']?></span>
                <span class="text-muted"><?=$p['quantity']?></span>
            </li>

            <?php
        } ?>
    <li class="list-group-item d-flex justify-content-between">
        <span>Total (EUR)</span>
        <strong>€<?=$sum?></strong>
    </li>
</ul>