<?php

use Core\View;

?>
<?php View::section('title'); ?>
Produtos
<?php View::endSection(); ?>

<?php View::section('styles'); ?>
<style>
    .product-card:hover {
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
        transform: translateY(-5px);
    }
</style>
<?php View::endSection(); ?>
<?php View::section('content'); ?>
<div class="container mt-4 d-flex justify-content-center text-center">
    <div class="col-md-6" id="product-list">
        <h3>Buscar pedido pelo id</h3>
        <input type="text" class="form-control" id="id" name="id">
        <button class="btn btn-primary mt-3 w-100" id="search">Buscar</button>
    </div>
</div>

<?php View::endSection(); ?>

<?php View::section('scripts'); ?>
<script>
    $(document).ready(function () {
        $('#search').on('click', function () {
            const id = $('#id').val();
            window.location.href = '/orders/' + id;
        });
    });
</script>
<?php View::endSection(); ?>
<?php View::extend('sidebar_layout'); ?>