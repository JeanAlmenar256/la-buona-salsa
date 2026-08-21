<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($producto['nombre']) ?> - La Buona Salsa</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="<?= base_url('css/estilos.css') ?>">
</head>
<body style="background-color: var(--fondo-calido);">

    <!-- Navbar Superior -->
    <nav class="navbar navbar-expand-lg navbar-dark navbar-salsa">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center gap-2" href="<?= base_url() ?>">
                <img src="<?= base_url('assets/img/LOGO BORDO 1.png') ?>" alt="La Buona Salsa" height="42">
            </a>
            <div class="ms-auto d-flex align-items-center gap-3">
                <a href="<?= base_url() ?>" class="btn btn-outline-light btn-sm rounded-pill px-3">
                    <i class="bi bi-arrow-left me-1"></i> Volver a la Tienda
                </a>
            </div>
        </div>
    </nav>

    <div class="container py-5">
        <div class="salsa-form-card p-0 overflow-hidden">
            <div class="row g-0 bg-white">
                <div class="col-lg-6 p-4 d-flex align-items-center justify-content-center bg-light">
                    <div id="productCarousel" class="carousel slide w-100" data-bs-ride="carousel">
                        <div class="carousel-inner text-center">
                            <div class="carousel-item active">
                                <img src="<?= base_url('assets/img/' . ($producto['imagen_principal'] ?? 'producto1.png')) ?>" 
                                     class="d-block mx-auto img-fluid" style="max-height: 420px; object-fit: contain;" alt="Vista Principal">
                            </div>
                            <div class="carousel-item">
                                <img src="<?= base_url('assets/img/producto2.png') ?>" 
                                     class="d-block mx-auto img-fluid" style="max-height: 420px; object-fit: contain;" alt="Vista 2">
                            </div>
                            <div class="carousel-item">
                                <img src="<?= base_url('assets/img/producto3.png') ?>" 
                                     class="d-block mx-auto img-fluid" style="max-height: 420px; object-fit: contain;" alt="Vista 3">
                            </div>
                        </div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#productCarousel" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon bg-dark rounded-circle p-2"></span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#productCarousel" data-bs-slide="next">
                            <span class="carousel-control-next-icon bg-dark rounded-circle p-2"></span>
                        </button>
                    </div>
                </div>

                <div class="col-lg-6 p-5 d-flex flex-column justify-content-center">
                    <span class="badge bg-warning-subtle text-warning-emphasis px-3 py-1 rounded-pill fw-bold mb-2 align-self-start">
                        CATEGORÍA: SALSAS ARTESANALES
                    </span>
                    <h1 class="display-6 fw-bold mb-3 font-display"><?= esc($producto['nombre']) ?></h1>
                    
                    <p class="text-secondary mb-4 fs-6">
                        <?= esc($producto['descripcion']) ?>
                    </p>

                    <div class="d-flex align-items-baseline gap-3 mb-4">
                        <span class="product-price-tag">$ <?= number_format($producto['precio'], 2, ',', '.') ?></span>
                        <span class="text-muted small">IVA Incluido</span>
                    </div>

                    <div class="d-flex align-items-center mb-4 p-3 bg-light rounded-4">
                        <span class="me-3 fw-bold text-dark"><i class="bi bi-box-seam me-1 text-danger"></i> Stock disponible:</span>
                        <span class="badge bg-success px-3 py-2 rounded-pill fs-6 fw-bold"><?= $producto['stock'] ?> unidades</span>
                    </div>

                    <button onclick="handleComprar(event, '<?= base_url('carrito/checkout/' . $producto['id']) ?>', <?= !empty($isLoggedIn) ? 'true' : 'false' ?>)" 
                            class="btn btn-salsa-primary btn-lg justify-content-center py-3">
                        <i class="bi bi-bag-check-fill me-2"></i> Realizar Pedido Ahora
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Registro -->
    <div class="modal fade modal-salsa" id="registroModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-salsa-header">
                    <h5 class="fw-bold mb-0">Regístrate para comprar</h5>
                </div>
                <div class="modal-body p-4 bg-white">
                    <form action="<?= base_url('registrar-basico') ?>" method="POST">
                        <?= csrf_field() ?>
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Nombre</label>
                            <input type="text" name="nombre" class="form-control salsa-input" placeholder="Tu nombre" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Email</label>
                            <input type="email" name="email" class="form-control salsa-input" placeholder="Tu correo" required>
                        </div>
                        <button type="submit" class="btn btn-salsa-primary w-100 justify-content-center">Continuar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?= base_url('js/immersive.js') ?>"></script>
</body>
</html>