<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Completar Datos de Envío - La Buona Salsa</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="<?= base_url('css/estilos.css') ?>">
</head>
<body style="background-color: var(--fondo-calido);">

    <!-- Navbar simple de Checkout -->
    <nav class="navbar navbar-dark navbar-salsa">
        <div class="container justify-content-between">
            <a class="navbar-brand d-flex align-items-center gap-2" href="<?= base_url() ?>">
                <img src="<?= base_url('assets/img/LOGO BORDO 1.png') ?>" alt="La Buona Salsa" height="40">
            </a>
            <span class="text-white-50 small d-none d-sm-inline">
                <i class="bi bi-shield-check text-warning me-1"></i> Proceso de Compra Seguro
            </span>
        </div>
    </nav>

    <div class="container py-5">
        <!-- Step Progress Indicator -->
        <div class="step-indicator">
            <div class="step-item active">
                <div class="step-circle"><i class="bi bi-check-lg"></i></div>
                <span class="d-none d-sm-inline">1. Registro</span>
            </div>
            <div class="step-line"></div>
            <div class="step-item active">
                <div class="step-circle">2</div>
                <span>Datos de Envío</span>
            </div>
            <div class="step-line"></div>
            <div class="step-item">
                <div class="step-circle">3</div>
                <span class="d-none d-sm-inline">Confirmación</span>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-7 col-md-9">

                <?php if (session()->getFlashdata('errors')): ?>
                    <div class="alert alert-danger rounded-4 shadow-sm border-0 mb-4" role="alert">
                        <h6 class="fw-bold mb-2"><i class="bi bi-exclamation-triangle-fill me-2"></i>Por favor revisa los siguientes campos:</h6>
                        <ul class="mb-0 small">
                        <?php foreach (session()->getFlashdata('errors') as $error): ?>
                            <li><?= esc($error) ?></li>
                        <?php endforeach ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <div class="salsa-form-card">
                    <div class="text-center mb-4">
                        <span class="badge bg-danger-subtle text-danger px-3 py-1 rounded-pill fw-bold mb-2">PASO 2 DE 3</span>
                        <h2 class="fw-bold text-dark font-display">¿A dónde te enviamos tu Salsa?</h2>
                        <p class="text-muted small">
                            Completa tu dirección y teléfono para que el repartidor pueda entregarte el pedido sin demoras.
                        </p>
                    </div>

                    <form action="<?= base_url('guardar-detalles') ?>" method="POST">
                        <?= csrf_field() ?>
                        <input type="hidden" name="id" value="<?= $usuario_id ?>">

                        <!-- Dirección Principal -->
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-dark">
                                <i class="bi bi-geo-alt-fill text-danger me-1"></i> Dirección Completa (Calle y Altura)
                            </label>
                            <input type="text" 
                                   name="direccion" 
                                   class="form-control salsa-input" 
                                   placeholder="Ej: Av. Corrientes 1234, Piso 3 Depto B" 
                                   value="<?= old('direccion', $usuario['direccion'] ?? '') ?>" 
                                   required>
                        </div>

                        <!-- Entre Calles -->
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold small text-muted">Entre Calle 1 (Opcional)</label>
                                <input type="text" 
                                       name="entre_calle_1" 
                                       class="form-control salsa-input" 
                                       placeholder="Ej: Reconquista"
                                       value="<?= old('entre_calle_1', $usuario['entre_calle_1'] ?? '') ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold small text-muted">Entre Calle 2 (Opcional)</label>
                                <input type="text" 
                                       name="entre_calle_2" 
                                       class="form-control salsa-input" 
                                       placeholder="Ej: San Martín"
                                       value="<?= old('entre_calle_2', $usuario['entre_calle_2'] ?? '') ?>">
                            </div>
                        </div>

                        <!-- Teléfono y Código Postal -->
                        <div class="row g-3 mb-4">
                            <div class="col-md-7">
                                <label class="form-label fw-bold small text-dark">
                                    <i class="bi bi-telephone-fill text-danger me-1"></i> Teléfono de Contacto (WhatsApp)
                                </label>
                                <input type="tel" 
                                       name="telefono" 
                                       class="form-control salsa-input" 
                                       placeholder="Ej: 1155443322" 
                                       value="<?= old('telefono', $usuario['telefono'] ?? '') ?>"
                                       required>
                                <div class="form-text small">Te escribiremos por acá al despachar tu pedido.</div>
                            </div>
                            <div class="col-md-5">
                                <label class="form-label fw-bold small text-dark">Código Postal</label>
                                <input type="text" 
                                       name="cp" 
                                       class="form-control salsa-input" 
                                       placeholder="Ej: 1414"
                                       value="<?= old('cp', $usuario['cp'] ?? '') ?>" 
                                       required>
                            </div>
                        </div>

                        <div class="d-grid pt-2">
                            <button type="submit" class="btn btn-salsa-primary justify-content-center py-3 fs-6">
                                Continuar a la Confirmación del Pedido <i class="bi bi-arrow-right-circle-fill fs-5"></i>
                            </button>
                        </div>
                    </form>
                </div>
                
                <div class="text-center mt-4">
                    <a href="<?= base_url() ?>" class="text-muted small text-decoration-none">
                        <i class="bi bi-arrow-left me-1"></i> Volver a la tienda
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>