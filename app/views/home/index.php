<?php

use Core\View;
?>
<?php View::section('title'); ?>
Template
<?php View::endSection(); ?>

<?php View::section('content'); ?>

<div class="d-flex justify-content-center px-2">
    <div class="row w-100">
    </div>
</div>

<?php View::endSection(); ?>

<?php View::extend('sidebar_layout'); ?>