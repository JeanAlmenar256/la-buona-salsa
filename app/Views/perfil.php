<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Perfil - La Buona Salsa</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="<?= base_url('css/estilos.css') ?>">
</head>
<body style="background-color: var(--fondo-calido);">

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark navbar-salsa">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center gap-2" href="<?= base_url() ?>">
                <img src="<?= base_url('assets/img/LOGO BORDO 1.png') ?>" alt="La Buona Salsa" height="42">
            </a>
            <div class="ms-auto d-flex align-items-center gap-2">
                <a href="<?= base_url() ?>" class="btn btn-outline-light btn-sm rounded-pill px-3">
                    <i class="bi bi-shop me-1"></i> Ir a la Tienda
                </a>
                <a href="<?= base_url('salir') ?>" class="btn btn-danger btn-sm rounded-pill px-3">
                    <i class="bi bi-box-arrow-right me-1"></i> Salir
                </a>
            </div>
        </div>
    </nav>

    <div class="container py-5">
        <?php if(session()->getFlashdata('msg')): ?>
            <div class="alert alert-success alert-dismissible fade show rounded-4 border-0 shadow-sm mb-4" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i> <?= session()->getFlashdata('msg') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="row g-4">
            <!-- Tarjeta de Usuario -->
            <div class="col-lg-4">
                <div class="salsa-form-card text-center">
                    <div class="position-relative d-inline-block mb-3">
                        <img src="<?= base_url('uploads/perfiles/' . (session()->get('avatar') ?? 'default-user.png')) ?>" 
                             onerror="this.src='https://cdn-icons-png.flaticon.com/512/847/847969.png'"
                             class="rounded-circle shadow-sm" 
                             style="width: 110px; height: 110px; object-fit: cover; border: 4px solid var(--naranja-marca);"
                             alt="Avatar">
                    </div>

                    <h4 class="fw-bold font-display text-dark mb-1"><?= esc(session()->get('nombre')) ?></h4>
                    <p class="text-muted small mb-4"><?= esc(session()->get('email')) ?></p>

                    <form action="<?= base_url('usuario/subir-avatar') ?>" method="POST" enctype="multipart/form-data" class="mb-4 text-start bg-light p-3 rounded-4 border">
                        <?= csrf_field() ?>
                        <label class="form-label small fw-bold text-dark"><i class="bi bi-camera-fill me-1 text-danger"></i> Cambiar Foto</label>
                        <div class="input-group input-group-sm">
                            <input type="file" name="avatar" class="form-control" accept="image/*" required>
                            <button class="btn btn-dark" type="submit">Subir</button>
                        </div>
                    </form>

                    <hr class="my-4">

                    <div class="d-grid">
                        <a href="<?= base_url('completar-datos/' . session()->get('usuario_id')) ?>" class="btn btn-outline-dark rounded-pill py-2">
                            <i class="bi bi-geo-alt me-1"></i> Modificar Datos de Entrega
                        </a>
                    </div>
                </div>
            </div>

            <!-- Historial de Pedidos -->
            <div class="col-lg-8">
                <div class="salsa-form-card">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="fw-bold font-display text-dark mb-0">
                            <i class="bi bi-receipt text-danger me-2"></i> Mis Pedidos Realizados
                        </h4>
                        <a href="<?= base_url('carrito/checkout/1') ?>" class="btn btn-sm btn-salsa-primary">
                            <i class="bi bi-plus-circle me-1"></i> Nuevo Pedido
                        </a>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Fecha</th>
                                    <th>Detalle</th>
                                    <th>Envío</th>
                                    <th>Total</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(empty($compras)): ?>
                                    <tr>
                                        <td colspan="5" class="text-center py-5">
                                            <i class="bi bi-bag-x display-4 text-muted mb-2 d-block"></i>
                                            <p class="text-muted mb-2">Todavía no has realizado pedidos.</p>
                                            <a href="<?= base_url('#catalogo') ?>" class="btn btn-sm btn-outline-danger rounded-pill px-4">
                                                Explorar Catálogo
                                            </a>
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($compras as $c): ?>
                                        <tr>
                                            <td><?= esc($c['fecha']) ?></td>
                                            <td><?= esc($c['detalle']) ?></td>
                                            <td><?= esc($c['envio']) ?></td>
                                            <td class="fw-bold">$ <?= number_format($c['total'], 2, ',', '.') ?></td>
                                            <td><span class="badge bg-success">Completado</span></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>