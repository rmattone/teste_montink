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
<div class="container mt-4">
    <div class="row" id="product-list">
        <?php foreach ($products as $product): ?>
            <div class="col-md-4 mb-4">
                <div class="card product-card" data-product='<?= json_encode($product) ?>' data-bs-toggle="modal" data-bs-target="#productModal" style="cursor: pointer;">
                    <div class="card-body">
                        <h5 class="card-title"><?= $product['nome'] ?></h5>
                        <p class="card-text"><?= $product['descricao'] ?></p>
                        <p class="card-text">A partir de <strong>R$ <?= number_format($product['preco'], 2, ',', '.') ?></strong></p>
                    </div>
                </div>
            </div>

        <?php endforeach; ?>
    </div>
</div>

<div class="modal fade" id="productModal" tabindex="-1" aria-labelledby="productModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="productModalLabel">Adicionar ao Carrinho</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="selectedProductId">
                <div class="mb-3">
                    <label for="variationSelect" class="form-label">Variação</label>
                    <select class="form-select" id="variationSelect"></select>
                </div>
                <div class="mb-3">
                    <label for="quantityInput" class="form-label">Quantidade</label>
                    <input type="number" class="form-control" id="quantityInput" value="1" min="1">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="confirmAddToCart">Adicionar ao Carrinho</button>
            </div>
        </div>
    </div>
</div>
<?php View::endSection(); ?>

<?php View::section('scripts'); ?>
<script>
    let selectedProduct = null;

    $(document).on('click', '.product-card', function() {
        selectedProduct = $(this).data('product');
        $('#selectedProductId').val(selectedProduct.id);
        $('#productModalLabel').text(selectedProduct.nome);

        const $variationSelect = $('#variationSelect');
        $variationSelect.empty();

        if (selectedProduct.variacoes?.length) {
            selectedProduct.variacoes.forEach(variacao => {
                const preco = parseFloat(variacao.preco).toFixed(2).replace('.', ',');
                $variationSelect.append(`<option value="${variacao.id}" data-preco="${variacao.preco}">${variacao.nome} - R$ ${preco}</option>`);
            });
        } else {
            $variationSelect.append(`<option value="">Sem variações disponíveis</option>`);
        }

        $('#quantityInput').val(1);
    });

    $('#confirmAddToCart').on('click', function() {
        const variationId = $('#variationSelect').val();
        const variationText = $('#variationSelect option:selected').text();
        const quantity = parseInt($('#quantityInput').val());
        const price = parseFloat($('#variationSelect option:selected').data('preco'));

        if (!variationId || quantity <= 0) {
            alert("Selecione uma variação e quantidade válida.");
            return;
        }

        const cart = JSON.parse(localStorage.getItem('cart') || '[]');

        addItemToCart({
            produto_id: selectedProduct.id,
            nome: selectedProduct.nome,
            variacao_id: variationId,
            variacao_nome: variationText,
            quantidade: quantity,
            price: price
        });

        toastr.success('Produto adicionado ao carrinho!');
        $('#productModal').modal('hide');

        updateCartCount();
    });

    function updateCartCount() {
        const cart = JSON.parse(localStorage.getItem('cart') || '[]');
        const count = cart.reduce((sum, item) => sum + item.quantidade, 0);
        $('#cart-count').text(count);
    }

    $(document).ready(() => {
        updateCartCount();
    });
</script>
<?php View::endSection(); ?>


<?php View::extend('sidebar_layout'); ?>