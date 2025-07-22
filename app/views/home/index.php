<?php

use Core\View;

?>
<?php View::section('title'); ?>
Produtos
<?php View::endSection(); ?>

<?php View::section('content'); ?>

<div class="d-flex justify-content-center px-2">
    <div class="row w-100">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Produtos</h5>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#productModal" id="btn-add-product">Adicionar Produto</button>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Nome</th>
                                <th>Descrição</th>
                                <th>Preço</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($products as $product): ?>
                                <tr>
                                    <td><?= htmlspecialchars($product['nome']) ?></td>
                                    <td><?= htmlspecialchars($product['descricao']) ?></td>
                                    <td>R$ <?= number_format($product['preco'], 2, ',', '.') ?></td>
                                    <td>
                                        <button class="btn btn-sm btn-warning edit-product"
                                            data-id="<?= $product['id'] ?>"
                                            data-nome="<?= htmlspecialchars($product['nome']) ?>"
                                            data-descricao="<?= htmlspecialchars($product['descricao']) ?>"
                                            data-preco="<?= $product['preco'] ?>"
                                            data-variacoes='<?= json_encode($product['variacoes'] ?? []) ?>'
                                            data-bs-toggle="modal" data-bs-target="#productModal">
                                            Editar
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="productModal" tabindex="-1" aria-labelledby="productModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form id="product-form">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="productModalLabel">Adicionar Produto</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="produto_id">
                    <div class="mb-3">
                        <label for="nome" class="form-label">Nome</label>
                        <input type="text" class="form-control" id="nome" name="nome" required>
                    </div>
                    <div class="mb-3">
                        <label for="descricao" class="form-label">Descrição</label>
                        <input type="text" class="form-control" id="descricao" name="descricao" required>
                    </div>
                    <div class="mb-3">
                        <label for="preco" class="form-label">Preço</label>
                        <input type="number" step="0.01" class="form-control" id="preco" name="preco" required>
                    </div>

                    <hr>
                    <h5>Variações</h5>
                    <div id="variacoes-container" class="mb-3 row"></div>
                    <button type="button" class="btn btn-outline-secondary btn-sm" id="add-variacao-btn">+ Adicionar Variação</button>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Salvar</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </form>
    </div>
</div>

<?php View::endSection(); ?>

<?php View::section('scripts'); ?>
<script>
    $(document).ready(function() {
        let variacaoIndex = 0;

        function criarVariacaoHtml(variacao = {nome: '', preco_adicional: ''}) {
            return `
                <div class="col-md-6 p-2">
                    <div class="variacao border rounded p-3 mb-2 position-relative">
                        <button type="button" class="btn-close position-absolute top-0 end-0 remove-variacao" aria-label="Remover"></button>
                        <div class="mb-2">
                            <label class="form-label">Nome</label>
                            <input type="text" name="variacoes[${variacaoIndex}][nome]" class="form-control" value="${variacao.nome}" required>
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Preço (R$)</label>
                            <input type="number" step="0.01" name="variacoes[${variacaoIndex}][preco_adicional]" class="form-control" value="${variacao.preco}" required>
                        </div>
                    </div>
                </div>
            `;
        }

        function limparVariacoes() {
            $('#variacoes-container').empty();
            variacaoIndex = 0;
        }

        function popularVariacoes(variacoes) {
            limparVariacoes();
            if (variacoes.length === 0) {
                $('#variacoes-container').append(criarVariacaoHtml());
                variacaoIndex++;
                return;
            }
            variacoes.forEach(v => {
                $('#variacoes-container').append(criarVariacaoHtml(v));
                variacaoIndex++;
            });
        }

        $('#btn-add-product').on('click', function() {
            $('#productModalLabel').text('Adicionar Produto');
            $('#produto_id').val('');
            $('#nome').val('');
            $('#descricao').val('');
            $('#preco').val('');
            popularVariacoes([]);
        });

        $('.edit-product').on('click', function() {
            const id = $(this).data('id');
            const nome = $(this).data('nome');
            const descricao = $(this).data('descricao');
            const preco = $(this).data('preco');
            const variacoes = $(this).data('variacoes') || [];

            $('#productModalLabel').text('Editar Produto');
            $('#produto_id').val(id);
            $('#nome').val(nome);
            $('#descricao').val(descricao);
            $('#preco').val(preco);
            popularVariacoes(variacoes);
        });

        $('#add-variacao-btn').on('click', function() {
            $('#variacoes-container').append(criarVariacaoHtml());
            variacaoIndex++;
        });

        $(document).on('click', '.remove-variacao', function() {
            $(this).closest('.variacao').remove();
        });

        $('#product-form').on('submit', function(e) {
            e.preventDefault();

            const data = $(this).serialize();
            const produto_id = $('#produto_id').val();
            const isEdit = produto_id !== '';

            $.ajax({
                url: isEdit ? `<?= base_url() ?>products/${produto_id}` : '<?= base_url() ?>products',
                type: 'POST',
                data: data,
                success: function(response) {
                    toastr.success('Produto salvo com sucesso');
                    setTimeout(function() {
                        location.reload();
                    }, 1500);
                },
                error: function(xhr) {
                    alert('Erro ao salvar produto');
                    console.error(xhr.responseText);
                }
            });
        });
    });
</script>
<?php View::endSection(); ?>

<?php View::extend('sidebar_layout'); ?>
