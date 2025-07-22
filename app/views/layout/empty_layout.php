<?php

use Core\View;
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title><?= View::renderSection('title'); ?></title>
    <link rel="icon" href="<?= base_url() ?>/assets/images/favicon.ico" type="image/x-icon">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/malihu-custom-scrollbar-plugin/3.1.5/jquery.mCustomScrollbar.min.css">

    <link rel="stylesheet" href="<?= base_url() ?>/assets/modules/bootstrap-5.3.3/themes/sandstone.min.css">
    <link rel="stylesheet" href="<?= base_url() ?>/assets/modules/fontawesome-free-6.7.1/css/all.min.css">
    <link rel="stylesheet" href="<?= base_url() ?>/assets/modules/datatables/datatables.min.css">

    <link rel="stylesheet" href="<?= base_url() ?>/assets/css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.14.5/dist/sweetalert2.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

    <style>
        body {
            min-height: 100dvh;
            display: flex;
            flex-direction: column;
        }

        .main-wrapper {
            display: flex;
            flex: 1;
        }

        #sidebar {
            width: 250px;
            height: 100dvh;
            position: fixed;
            left: 0;
            top: 0;
            background-color: #f8f9fa;
            transition: transform 0.3s ease;
            z-index: 1000;
            transform: translateX(0);
        }

        #sidebar.hide {
            transform: translateX(-100%);
        }

        #sidebar.brand {
            padding: 15px 20px;
            font-size: 1.5rem;
            font-weight: bold;
            border-bottom: 1px solid #dee2e6;
        }

        #sidebar-content {
            flex-grow: 1;
            overflow-y: auto;
            max-height: calc(100vh - 150px);
        }

        #content-area {
            flex: 1;
            margin-left: 250px;
            width: calc(100% - 250px);
            display: flex;
            flex-direction: column;
            min-height: 100dvh;
            transition: margin-left 0.3s ease;
        }

        #content-area.expanded {
            margin-left: 0;
        }

        .sidebar-backdrop {
            display: none;
        }

        .navbar-wrapper {
            position: sticky;
            top: 0;
            z-index: 990;
        }

        .main-content {
            flex: 1;
            padding-top: 5px;
        }

        .footer {
            margin-top: auto;
        }

        #sidebar-footer {
            margin-top: auto;
        }

        .container-fluid {
            height: 95%;
        }

        @media (max-width: 768px) {
            .sidebar-backdrop.show {
                display: block;
                background-color: rgb(0, 0, 0, 0.5);
                position: fixed;
                width: 100vw;
                height: 100vh;
                z-index: 999;
                top: 0;
                left: 0;
            }
        }
    </style>

    <?= View::renderSection('styles') ?>
</head>

<body>
    <?= View::renderSection('content') ?>
    <?= View::include('layout/components/scripts') ?>
    <?= View::renderSection('scripts') ?>
</body>

</html>