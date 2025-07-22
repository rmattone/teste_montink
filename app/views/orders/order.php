<?php

use Core\View;
?>
<?php View::section('title'); ?>
Pedido #<?= $pedido['id'] ?>
<?php View::endSection(); ?>

<?php View::section('content'); ?>
<div class="container mt-5">
    <h2 class="mb-4">Detalhes do Pedido #<?= $pedido['id'] ?></h2>

    <div class="card mb-4">
        <div class="card-header">
            <strong>Informações do Cliente</strong>
        </div>
        <div class="card-body">
            <p><strong>Nome:</strong> <?= htmlspecialchars($pedido['nome_cliente']) ?></p>
            <p><strong>Email:</strong> <?= htmlspecialchars($pedido['email_cliente']) ?></p>
            <p><strong>Endereço:</strong> <?= htmlspecialchars($pedido['endereco']) ?>, <?= htmlspecialchars($pedido['numero']) ?> <?= $pedido['complemento'] ? ' - ' . htmlspecialchars($pedido['complemento']) : '' ?><br>
                <?= htmlspecialchars($pedido['bairro']) ?>, <?= htmlspecialchars($pedido['cidade']) ?> - <?= htmlspecialchars($pedido['estado']) ?> | CEP: <?= htmlspecialchars($pedido['cep']) ?></p>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header">
            <strong>Itens do Pedido</strong>
        </div>
        <div class="card-body table-responsive">
            <table class="table table-bordered">
                <thead class="thead-light">
                    <tr>
                        <th>Produto</th>
                        <th>Descrição</th>
                        <th>Quantidade</th>
                        <th>Preço Unitário</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($pedido['itens'] as $item): ?>
                        <tr>
                            <td><?= htmlspecialchars($item['nome']) ?></td>
                            <td><?= htmlspecialchars($item['descricao']) ?></td>
                            <td><?= $item['quantidade'] ?></td>
                            <td>R$ <?= number_format($item['preco_unitario'], 2, ',', '.') ?></td>
                            <td>R$ <?= number_format($item['quantidade'] * $item['preco_unitario'], 2, ',', '.') ?></td>
                        </tr>
                    <?php endforeach; ?>
                    <tr>
                        <td>Frete</td>
                        <td></td>
                        <td>1</td>
                        <td>R$ <?= number_format($pedido['frete'], 2, ',', '.') ?></td>
                        <td>R$ <?= number_format($pedido['frete'], 2, ',', '.') ?></td>
                    </tr>
                </tbody>
                <tfooter>
                    <tr>
                        <td colspan="4"><strong>Total:</strong></td>
                        <td><strong>R$ <?= number_format($pedido['total'], 2, ',', '.') ?></strong></td>
                    </tr>
                </tfooter>
            </table>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <strong>Resumo do Pedido</strong>
        </div>
        <div class="card-body">
            <p><strong>Status:</strong> <?= ucfirst($pedido['status']) ?></p>
            <p><strong>Data do Pedido:</strong> <?= date('d/m/Y H:i', strtotime($pedido['criado_em'])) ?></p>
        </div>
    </div>
</div>
<?php View::endSection(); ?>

<?php View::section('scripts'); ?>
<?php View::endSection(); ?>

<?php View::extend('sidebar_layout'); ?>
