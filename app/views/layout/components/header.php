<header>
    <nav class="navbar navbar-expand-lg border-bottom" style="height: 50px;">
        <div class="container-fluid">
            <span id="sidebar-toggle" class="pointer ms-4">
                <i class="fas fa-table-columns"></i>
            </span>
            <div class="d-flex align-items-center justify-content-end ms-auto me-4">
                <div class="position-relative me-3">
                    <i class="fas fa-shopping-cart fa-lg pointer" id="cart-icon" data-bs-toggle="offcanvas" data-bs-target="#cartOffcanvas"></i>
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" id="cart-count">
                        0
                    </span>
                </div>
            </div>
        </div>
    </nav>
</header>
<div class="offcanvas offcanvas-end" tabindex="-1" id="cartOffcanvas" aria-labelledby="cartOffcanvasLabel">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="cartOffcanvasLabel">Carrinho</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Fechar"></button>
    </div>
    <div class="offcanvas-body" id="cart-items-container">
    </div>
</div>
