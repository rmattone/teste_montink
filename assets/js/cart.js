function getCart() {
    return JSON.parse(localStorage.getItem('cart') || '[]');
}

function setCart(cart) {
    localStorage.setItem('cart', JSON.stringify(cart));
    updateCartCount();
}

function addItemToCart(newItem) {
    let cart = JSON.parse(localStorage.getItem('cart')) || [];

    console.log(newItem);
    
    const existingIndex = cart.findIndex(item =>
        item.produto_id === newItem.produto_id &&
        item.variacao_id === newItem.variacao_id
    );

    if (existingIndex !== -1) {
        cart[existingIndex].quantidade += newItem.quantidade;
    } else {
        cart.push(newItem);
    }

    localStorage.setItem('cart', JSON.stringify(cart));
}


function updateCartCount() {
    const cart = getCart();
    const count = cart.reduce((sum, item) => sum + item.quantidade, 0);
    document.getElementById('cart-count').innerText = count;
}

function renderCartItems() {
    const cart = getCart();
    
    const container = document.getElementById('cart-items-container');
    container.innerHTML = '';

    if (cart.length === 0) {
        container.innerHTML = '<p>Seu carrinho está vazio.</p>';
        return;
    }

    let subtotal = 0;

    cart.forEach((item, index) => {
        const total = item.price * item.quantidade;
        subtotal += total;

        container.innerHTML += `
            <div class="d-flex justify-content-between align-items-center mb-3 border-bottom pb-2">
                <div>
                    <strong>${item.nome}</strong><br>
                    Variação: ${item.variacao_nome} <br>
                    Quantidade: ${item.quantidade}<br>
                    Preço: R$ ${item.price.toFixed(2)}<br>
                </div>
                <button class="btn btn-sm btn-danger mt-1" onclick="removeCartItem(${index})">
                    <i class="fa-solid fa-trash"></i>
                </button>
            </div>
        `;
    });

    let frete = getFreteValor(subtotal);

    container.innerHTML += `
        <div class="mt-3">
            <p>Subtotal: R$ ${subtotal.toFixed(2)}</p>
            <p>Frete: R$ ${frete.toFixed(2)}</p>
            <p><strong>Total: R$ ${(subtotal + frete).toFixed(2)}</strong></p>
        </div>
        <div class="mt-3">
            <a href="${base_url()}checkout" class="btn btn-primary w-100">Finalizar compra</a>
        </div>
    `;
}

function getFreteValor(subtotal) {
    let frete = 20;
    if (subtotal >= 52 && subtotal <= 166.59) frete = 15;
    else if (subtotal > 200) frete = 0;
    return frete;
}

function removeCartItem(index) {
    const cart = getCart();
    cart.splice(index, 1);
    setCart(cart);
    renderCartItems();
}

document.addEventListener('DOMContentLoaded', () => {
    updateCartCount();

    const cartOffcanvas = document.getElementById('cartOffcanvas');
    cartOffcanvas.addEventListener('shown.bs.offcanvas', () => {
        renderCartItems();
    });
});
