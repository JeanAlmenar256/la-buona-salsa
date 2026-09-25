<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Validar Correo Electrónico - La Buona Salsa</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="<?= base_url('css/estilos.css') ?>">
    <style>
        .verify-card {
            background: #ffffff;
            border-radius: 24px;
            box-shadow: 0 15px 35px rgba(155, 18, 18, 0.08);
            border: 1px solid rgba(253, 88, 15, 0.15);
            padding: 2.5rem;
        }
        .code-input {
            font-size: 2.4rem;
            letter-spacing: 12px;
            font-weight: 800;
            text-align: center;
            border: 2px solid #e2dcd5;
            border-radius: 16px;
            color: #9B1212;
            background: #fffbf7;
            transition: all 0.3s ease;
        }
        .code-input:focus {
            border-color: #fd580f;
            box-shadow: 0 0 0 0.25rem rgba(253, 88, 15, 0.18);
            background: #ffffff;
        }
        .icon-circle-lg {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 2.5rem;
            background: linear-gradient(135deg, rgba(253,88,15,0.15), rgba(155,18,18,0.15));
            color: #9B1212;
            margin-bottom: 1.5rem;
        }
    </style>
</head>
<body style="background-color: var(--fondo-calido); min-height: 100vh; display: flex; flex-direction: column;">

    <!-- Navbar simple -->
    <nav class="navbar navbar-dark navbar-salsa">
        <div class="container justify-content-between">
            <a class="navbar-brand d-flex align-items-center gap-2" href="<?= base_url() ?>">
                <img src="<?= base_url('assets/img/LOGO BORDO 1.png') ?>" alt="La Buona Salsa" height="40">
            </a>
            <span class="text-white-50 small">
                <i class="bi bi-shield-lock-fill text-warning me-1"></i> Verificación de Seguridad
            </span>
        </div>
    </nav>

    <div class="container my-auto py-5">
        <div class="row justify-content-center">
            <div class="col-lg-6 col-md-8">

                <!-- Alertas Flash -->
                <?php if (session()->getFlashdata('success')): ?>
                    <div class="alert alert-success rounded-4 shadow-sm border-0 mb-4" role="alert">
                        <i class="bi bi-check-circle-fill me-2"></i> <?= session()->getFlashdata('success') ?>
                    </div>
                <?php endif; ?>

                <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger rounded-4 shadow-sm border-0 mb-4" role="alert">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i> <?= session()->getFlashdata('error') ?>
                    </div>
                <?php endif; ?>

                <!-- Modo de prueba local informativo -->
                <?php if (session()->getFlashdata('demo_codigo')): ?>
                    <div class="alert alert-warning rounded-4 shadow-sm border-0 mb-4" role="alert">
                        <div class="d-flex align-items-center gap-2 mb-1">
                            <i class="bi bi-cpu-fill fs-5 text-dark"></i>
                            <strong>🧪 Modo de Prueba Local (Simulador de Email)</strong>
                        </div>
                        <div class="small">
                            Tu código de 6 dígitos generado es: <span class="badge bg-dark fs-6 px-3 py-1"><?= session()->getFlashdata('demo_codigo') ?></span>
                        </div>
                        <?php if (session()->getFlashdata('demo_token')): ?>
                            <div class="mt-2 small">
                                O activa directamente haciendo clic aquí: 
                                <a href="<?= base_url('verificar-email/' . session()->getFlashdata('demo_token')) ?>" class="fw-bold text-decoration-underline text-dark">
                                    &rarr; Validar automáticamente por enlace
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

                <div class="verify-card text-center">
                    <div class="icon-circle-lg">
                        <i class="bi bi-envelope-check"></i>
                    </div>

                    <h2 class="fw-bold text-dark font-display mb-2">Valida tu Correo</h2>
                    <p class="text-muted mb-4">
                        Hemos enviado un código de 6 dígitos a tu casilla:<br>
                        <strong class="text-dark fs-6"><?= esc( ?? 'tu correo registrado') ?></strong>
                    </p>

                    <form action="<?= base_url('verificar-codigo') ?>" method="POST" class="mb-4">
                        <?= csrf_field() ?>
                        <div class="mb-4">
                            <label class="form-label text-muted small fw-bold text-uppercase">Ingresa el código de 6 dígitos</label>
                            <input type="text" name="codigo" maxlength="6" pattern="[0-9]{6}" 
                                   class="form-control code-input shadow-none" 
                                   placeholder="000000" 
                                   required autofocus autocomplete="one-time-code">
                        </div>

                        <button type="submit" class="btn btn-salsa-primary w-100 justify-content-center py-3 fs-6">
                            <i class="bi bi-check2-circle me-2"></i> Activar y Continuar
                        </button>
                    </form>

                    <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                        <form action="<?= base_url('reenviar-verificacion') ?>" method="POST">
                            <?= csrf_field() ?>
                            <button type="submit" class="btn btn-link text-decoration-none text-muted small p-0">
                                <i class="bi bi-arrow-clockwise me-1"></i> Reenviar código
                            </button>
                        </form>
                        <a href="<?= base_url('salir') ?>" class="text-muted small text-decoration-none">
                            <i class="bi bi-box-arrow-left me-1"></i> Usar otro correo
                        </a>
                    </div>
                </div>

                <div class="text-center mt-4">
                    <p class="small text-muted mb-0">
                        <i class="bi bi-shield-fill-check text-success me-1"></i> La Buona Salsa protege tu cuenta con encriptación segura.
                    </p>
                </div>

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>