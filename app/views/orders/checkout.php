<?php

use Core\View;

?>
<?php View::section('title'); ?>
Finalizar Compra
<?php View::endSection(); ?>

<?php View::section('styles'); ?>
<?php View::endSection(); ?>

<?php View::section('content'); ?>
<div class="container mt-4 row">
    <div class="col-md-6">
        <h2>Dados do Cliente</h2>
        <form id="checkout-form">
            <div class="mb-3">
                <label for="name" class="form-label">Nome completo</label>
                <input type="text" class="form-control" id="name" name="name" required />
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">E-mail</label>
                <input type="email" class="form-control" id="email" name="email" required />
            </div>

            <div class="mb-3">
                <label for="cep" class="form-label">CEP</label>
                <input type="text" class="form-control" id="cep" name="cep" required />
            </div>
            <div class="mb-3">
                <label for="cidade" class="form-label">Cidade</label>
                <input type="text" class="form-control" id="cidade" name="cidade" readonly />
            </div>
            <div class="mb-3">
                <label for="estado" class="form-label">Estado</label>
                <input type="text" class="form-control" id="estado" name="estado" readonly />
            </div>
            <div class="mb-3">
                <label for="endereco" class="form-label">Endereço</label>
                <input type="text" class="form-control" id="endereco" name="endereco" readonly />
            </div>

            <div class="mb-3">
                <label for="numero" class="form-label">Número</label>
                <input type="text" class="form-control" id="numero" name="numero" required />
            </div>
            <div class="mb-3">
                <label for="complemento" class="form-label">Complemento</label>
                <input type="text" class="form-control" id="complemento" name="complemento" required />
            </div>

            <div class="mb-3">
                <label for="bairro" class="form-label">Bairro</label>
                <input type="text" class="form-control" id="bairro" name="bairro" readonly />
            </div>

            <div class="mb-3">
                <label for="payment" class="form-label">Forma de Pagamento</label>
                <select class="form-select" id="payment" name="payment" required>
                    <option value="">Selecione</option>
                    <option value="pix">Pix</option>
                    <option value="dinheiro">Dinheiro</option>
                    <option value="cartao">Cartão de Crédito</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Confirmar Pedido</button>
        </form>
    </div>
    <div class="col-md-6">
        <h2 class="mb-4">Resumo do Carrinho</h2>
        <div id="cart-items" class="mb-5"></div>
    </div>
</div>
<?php View::endSection(); ?>

<?php View::section('scripts'); ?>
<script>
    function renderCart() {
        const cart = JSON.parse(localStorage.getItem('cart') || '[]');
        const container = $('#cart-items');

        if (!cart.length) {
            container.html('<p>Seu carrinho está vazio.</p>');
            $('#checkout-form').hide();
            return;
        }

        let total = 0;
        let html = '<ul class="list-group">';
        cart.forEach(item => {
            const subtotal = item.price * item.quantidade;
            total += subtotal;

            html += `
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <div>
                        <strong>${item.quantidade}x ${item.nome} - ${item.variacao_nome}</strong><br />
                    </div>
                    R$ ${subtotal.toFixed(2)}
                </li>
            `;
        });
        const frete = getFreteValor(total);

        html += `<li class="list-group-item d-flex justify-content-between align-items-center">
                    <div><strong>Frete</strong></div>
                    R$ ${frete.toFixed(2)}
                </li>`;

        total += frete;
        html += `</ul><div class="mt-3 text-end"><strong>Total: R$ ${total.toFixed(2)}</strong></div>`;

        container.html(html);
    }

    function getFreteValor(subtotal) {
        if (subtotal >= 52 && subtotal <= 166.59) {
            return 15;
        } else if (subtotal > 200) {
            return 0;
        } else {
            return 20;
        }
    }

    $('#cep').on('keyup', function() {
        const cep = $(this).val().replace(/\D/g, '');

        if(cep.length < 8) {
            return;
        }
        if (cep.length !== 8) {
            alert('CEP inválido');
            return;
        }

        $.ajax({
            type: "GET",
            url: `https://viacep.com.br/ws/${cep}/json/`,
            dataType: "json",
            success: function(response) {
                if (response.erro) {
                    alert('CEP não encontrado');
                    return;
                }

                $('#cidade').val(response.localidade);
                $('#estado').val(response.uf);
                $('#endereco').val(response.logradouro);
                $('#bairro').val(response.bairro);
            },
            error: function() {
                toastr.error('Erro ao consultar o CEP');
            }
        });
    });

    $('#checkout-form').on('submit', function(e) {
        e.preventDefault();

        const cart = JSON.parse(localStorage.getItem('cart') || '[]');
        const data = $(this).serializeArray();
        data.push({
            name: 'cart',
            value: JSON.stringify(cart)
        });

        $.post('<?= base_url() ?>checkout', $.param(data), function(response) {
            if (response.success) {
                alert('Pedido realizado com sucesso!');
                localStorage.removeItem('cart');
                window.location.href = '<?= base_url() ?>';
            } else {
                alert('Erro ao finalizar pedido.');
            }
        }, 'json');
    });

    $(document).ready(renderCart);
</script>
<?php View::endSection(); ?>

<?php View::extend('sidebar_layout'); ?>