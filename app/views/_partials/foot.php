</div><!-- .main -->

<footer class="py-5 bg-dark mt-5">
    <div class="container-fluid">
        <p class="m-0 text-center text-white">Copyright &copy; WebShop <?php echo date('Y') ?></p>
    </div>
</footer>

<script src="<?php echo public_path('/js/jquery-3.3.1.min.js') ?>"></script>
<script src="<?php echo public_path('/js/bootstrap.min.js') ?>"></script>
<script src="<?php echo public_path('/js/scripts.js') ?>"></script>

<?php if (!empty($_SESSION['username'])) { ?>
<script src="<?php echo public_path('/js/admin.js') ?>"></script>
<?php } ?>

</body>
</html>
