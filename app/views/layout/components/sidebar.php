<?php

$menus = [
    [
        'title' => 'Produtos',
        'icon' => 'fa-solid fa-home',
        'url' => base_url()
    ],
    [
        'title' => 'Fazer pedido',
        'icon' => 'fa-solid fa-box-open',
        'url' => base_url() . 'order'
    ],
    [
        'title' => 'Pedidos',
        'icon' => 'fa-solid fa-list',
        'url' => base_url() . 'orders'
    ]
];
?>

<aside id="sidebar" class="sidebar bg-light h-100 d-flex flex-column">
    <div class="p-3 flex-grow-1 d-flex flex-column border">
        <div id="sidebar-header" class="mb-3 d-flex justify-content-center">
            <span class="fw-bold">
                <?= APP_NAME ?>
            </span>
        </div>

        <div id="sidebar-content">
            <?php foreach ($menus as $index => $menu): ?>
                <?php
                $hasChildren = isset($menu['items']) && is_array($menu['items']);
                $collapseId = 'menu-collapse-' . $index;
                ?>

                <?php if ($hasChildren): ?>
                    <button class="btn w-100 text-start fw-bold d-flex align-items-center" data-bs-toggle="collapse" data-bs-target="#<?= $collapseId ?>" aria-expanded="false" aria-controls="<?= $collapseId ?>">
                        <?php if (!empty($menu['icon'])): ?>
                            <i class="<?= $menu['icon'] ?> me-2"></i>
                        <?php endif; ?>
                        <span><?= $menu['title'] ?></span>
                    </button>

                    <div id="<?= $collapseId ?>" class="collapse mt-2">
                        <div class="nav flex-column">
                            <?php foreach ($menu['items'] as $item): ?>
                                <a href="<?= $item['url'] ?>" class="mb-1 btn btn-light text-start <?= $item['url'] == current_url() ? 'active' : '' ?>">
                                    <?php if (!empty($item['icon'])): ?>
                                        <i class="<?= $item['icon'] ?>"></i>
                                    <?php endif; ?>
                                    <span class="ms-2"><?= $item['title'] ?></span>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php else: ?>
                    <a href="<?= $menu['url'] ?>" class="btn w-100 text-start fw-bold d-flex align-items-center mb-2 <?= $menu['url'] == current_url() ? 'active' : '' ?>">
                        <?php if (!empty($menu['icon'])): ?>
                            <i class="<?= $menu['icon'] ?> me-2"></i>
                        <?php endif; ?>
                        <span><?= $menu['title'] ?></span>
                    </a>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </div>
</aside>