<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Perfil - La Buona Salsa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Helvetica:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body { background-color: #f6f7f8; font-family: 'Helvetica', sans-serif; }
        .navbar { background-color: #9B1212; }
        .btn-salsa { background-color: #9B1212; color: white; font-weight: bold; }
        .btn-salsa:hover { background-color: #fd580f; color: white; }
        .card-header-salsa { background-color: #9B1212; color: white; font-weight: bold; }
        .perfil-avatar { width: 100px; height: 100px; object-fit: cover; border: 4px solid #f29418; }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold" href="<?= base_url() ?>">La Buona Salsa</a>
        <div class="ms-auto">
            <a href="<?= base_url('salir') ?>" class="btn btn-outline-light btn-sm">Cerrar Sesión</a>
        </div>
    </div>
</nav>

<div class="container mt-5">
    <?php if(session()->getFlashdata('msg')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= session()->getFlashdata('msg') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <img src="<?= base_url('uploads/perfiles/default-user.png') ?>" class="rounded-circle perfil-avatar mb-3" alt="Usuario">
                    <form action="<?= base_url('usuario/subir-avatar') ?>" method="POST" enctype="multipart/form-data" class="mb-3">
                    <?= csrf_field() ?>
                    <div class="input-group input-group-sm">
                        <input type="file" name="avatar" class="form-control" id="inputGroupFile02" accept="image/*" required>
                        <button class="btn btn-dark" type="submit">Subir</button>
                    </div>
                    <div class="form-text small">Elegí una foto cuadrada para que se vea mejor.</div>
                    </form>
                    <h4 class="mb-0"><?= session()->get('nombre') ?></h4>
                    <p class="text-muted"><?= session()->get('email') ?></p>
                    <hr>
                    
                    <form action="<?= base_url('usuario/guardar-password') ?>" method="POST">
                        <?= csrf_field() ?>
                        <div class="text-start mb-3">
                            <label class="form-label small fw-bold">¿Querés crear una clave?</label>
                            <input type="password" name="password" class="form-control" placeholder="Escribí tu nueva contraseña">
                            <div class="form-text">Usala para entrar más rápido la próxima vez.</div>
                        </div>
                        <button type="submit" class="btn btn-salsa w-100">GUARDAR CONTRASEÑA</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header card-header-salsa py-3">
                    Mis Pedidos Realizados
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Fecha</th>
                                    <th>Pedido</th>
                                    <th>Total</th>
                                    <th>Envío</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(empty($compras)): ?>
                                    <tr>
                                        <td colspan="5" class="text-center py-5">
                                            <p class="text-muted">Todavía no has realizado pedidos.</p>
                                            <a href="<?= base_url() ?>" class="btn btn-sm btn-outline-danger">Ir a la tienda</a>
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>