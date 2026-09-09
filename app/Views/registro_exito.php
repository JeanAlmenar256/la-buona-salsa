<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>¡Pedido Confirmado! - La Buona Salsa</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="<?= base_url('css/estilos.css') ?>">
</head>
<body style="background-color: var(--fondo-calido);">

    <nav class="navbar navbar-dark navbar-salsa">
        <div class="container justify-content-between">
            <a class="navbar-brand d-flex align-items-center gap-2" href="<?= base_url() ?>">
                <img src="<?= base_url('assets/img/LOGO BORDO 1.png') ?>" alt="La Buona Salsa" height="40">
            </a>
            <span class="badge bg-success px-3 py-2 rounded-pill fw-bold">
                <i class="bi bi-check-circle-fill me-1"></i> Pago Aprobado
            </span>
        </div>
    </nav>

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-7">
                <div class="salsa-form-card text-center py-5">
                    <div class="mb-3">
                        <span class="display-1 text-success"><i class="bi bi-check-circle-fill"></i></span>
                    </div>
                    <h2 class="fw-bold font-display text-dark mb-2">¡Pago Confirmado con Éxito!</h2>
                    <p class="text-muted mb-4">
                        Tu pedido ha sido registrado y abonado mediante <strong>Mercado Pago</strong>.
                    </p>

                    <div class="alert alert-info border-0 rounded-4 text-start p-3 mb-4 d-flex align-items-center gap-3">
                        <i class="bi bi-envelope-check-fill fs-2 text-primary"></i>
                        <div>
                            <div class="fw-bold">Notificación enviada por Email</div>
                            <div class="small text-muted">Se envió el detalle completo del pedido para su preparación.</div>
                        </div>
                    </div>

                    <?php if (!empty($producto)): ?>
                        <div class="bg-light p-4 rounded-4 text-start mb-4 border">
                            <h5 class="fw-bold font-display text-dark border-bottom pb-2 mb-3">Resumen de Entrega</h5>
                            
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Producto:</span>
                                <span class="fw-bold text-dark"><?= esc($producto['nombre']) ?> (x<?= esc($cantidad ?? 1) ?>)</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Método de Envío:</span>
                                <span class="fw-bold text-dark"><?= esc($metodo_envio ?? 'Rappi') ?></span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Cliente:</span>
                                <span class="fw-bold text-dark"><?= esc($usuario['nombre'] ?? 'Cliente') ?></span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Dirección de Entrega:</span>
                                <span class="fw-bold text-dark"><?= esc($usuario['direccion'] ?? 'Dirección registrada') ?></span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Teléfono de Contacto:</span>
                                <span class="fw-bold text-dark"><?= esc($usuario['telefono'] ?? '-') ?></span>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between fs-5 fw-bold">
                                <span>Total Abonado:</span>
                                <span class="text-danger">$ <?= number_format($total ?? 6500, 2, ',', '.') ?></span>
                            </div>
                        </div>

                        <?php 
                            $textoWhatsapp = "🍅 *¡NUEVO PEDIDO LA BUONA SALSA!*" . "%0A"
                                           . "• *Producto:* " . urlencode($producto['nombre']) . " (x" . ($cantidad ?? 1) . ")" . "%0A"
                                           . "• *Total:* $" . number_format($total ?? 6500, 2, ',', '.') . "%0A"
                                           . "• *Envío:* " . urlencode($metodo_envio ?? 'Rappi') . "%0A"
                                           . "• *Cliente:* " . urlencode($usuario['nombre'] ?? '') . "%0A"
                                           . "• *Dirección:* " . urlencode($usuario['direccion'] ?? '') . "%0A"
                                           . "• *Teléfono:* " . urlencode($usuario['telefono'] ?? '') . "%0A"
                                           . "• *Estado:* Pago confirmado en Mercado Pago";
                        ?>

                        <div class="d-grid gap-2 mb-3">
                            <a href="https://api.whatsapp.com/send?text=<?= $textoWhatsapp ?>" target="_blank" class="btn btn-success btn-lg py-3 fw-bold rounded-pill">
                                <i class="bi bi-whatsapp me-2"></i> Enviar Notificación a mi WhatsApp
                            </a>
                        </div>
                    <?php endif; ?>

                    <div class="d-grid pt-2">
                        <a href="<?= base_url() ?>" class="btn btn-salsa-primary justify-content-center py-3">
                            <i class="bi bi-house-door-fill me-1"></i> Volver a la Tienda
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>