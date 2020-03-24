<?php if (isset($_SESSION['error']) && $_SESSION['error'] !== '') { ?>
  <div class="row">
    <div class="col-md-12">
      <div class="alert alert-danger">
        <?php
          echo $_SESSION['error'];
          unset($_SESSION['error']);
        ?>
      </div>
    </div>
  </div>
  <?php } ?>