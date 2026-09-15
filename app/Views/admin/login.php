<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acceso Superusuario - La Buona Salsa</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <style>
        body {
            background: linear-gradient(135deg, #141419 0%, #2b1212 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', system-ui, sans-serif;
        }

        .login-card {
            background: #ffffff;
            border-radius: 24px;
            padding: 40px;
            width: 100%;
            max-width: 440px;
            box-shadow: 0 20px 50px rgba(0,0,0,0.3);
        }

        .btn-salsa-admin {
            background: linear-gradient(135deg, #9B1212 0%, #fd580f 100%);
            color: #fff;
            border: none;
            padding: 14px;
            border-radius: 12px;
            font-weight: 600;
            width: 100%;
            transition: all 0.3s ease;
        }

        .btn-salsa-admin:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(253, 88, 15, 0.4);
            color: #fff;
        }

        .form-control-custom {
            border-radius: 12px;
            padding: 12px 16px;
            border: 1px solid #dee2e6;
            background-color: #f8f9fa;
        }

        .form-control-custom:focus {
            background-color: #fff;
            border-color: #fd580f;
            box-shadow: 0 0 0 0.25rem rgba(253, 88, 15, 0.15);
        }
    </style>
</head>
<body>

    <div class="login-card">
        <div class="text-center mb-4">
            <img src="<?= base_url('assets/img/LOGO BORDO 1.png') ?>" alt="La Buona Salsa" height="52" class="mb-3">
            <h4 class="fw-bold text-dark">Panel de Control</h4>
            <p class="text-muted small">Acceso exclusivo para Superusuario y Gestión Financiera</p>
        </div>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger border-0 rounded-3 p-3 small mb-3">
                <i class="bi bi-exclamation-triangle-fill me-1"></i> <?= session()->getFlashdata('error') ?>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('info')): ?>
            <div class="alert alert-info border-0 rounded-3 p-3 small mb-3">
                <i class="bi bi-info-circle-fill me-1"></i> <?= session()->getFlashdata('info') ?>
            </div>
        <?php endif; ?>

        <form action="<?= base_url('admin/autenticar') ?>" method="POST">
            <?= csrf_field() ?>

            <div class="mb-3">
                <label class="form-label fw-semibold text-dark small">Correo de Superusuario</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0 rounded-start-3"><i class="bi bi-envelope text-muted"></i></span>
                    <input type="email" name="email" class="form-control form-control-custom border-start-0" 
                           placeholder="jean.almenar@labuonasalsa.com.ar" 
                           value="<?= old('email', 'jean.almenar@labuonasalsa.com.ar') ?>" required>
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label fw-semibold text-dark small">Contraseña</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0 rounded-start-3"><i class="bi bi-lock text-muted"></i></span>
                    <input type="password" name="password" class="form-control form-control-custom border-start-0" 
                           placeholder="••••••••••••" required>
                </div>
            </div>

            <button type="submit" class="btn-salsa-admin fs-6 mb-3">
                <i class="bi bi-shield-lock-fill me-2"></i> Ingresar al Panel
            </button>

            <div class="text-center">
                <a href="<?= base_url('/') ?>" class="text-decoration-none small text-muted">
                    <i class="bi bi-arrow-left me-1"></i> Volver a la Tienda
                </a>
            </div>
        </form>
    </div>

</body>
</html>
