<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmar Pedido - La Buona Salsa</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="<?= base_url('css/estilos.css') ?>">
    
    <!-- SDK Mercado Pago -->
    <script src="https://sdk.mercadopago.com/js/v2"></script>
    <style>
        .btn-mp-pago {
            background-color: #009ee3;
            color: #ffffff !important;
            font-weight: 600;
            font-size: 1.15rem;
            padding: 14px 28px;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            transition: background-color 0.2s ease, transform 0.15s ease;
            box-shadow: 0 4px 14px rgba(0, 158, 227, 0.35);
            border: none;
            width: 100%;
            max-width: 400px;
        }
        .btn-mp-pago:hover {
            background-color: #0081ba;
            color: #ffffff !important;
            transform: translateY(-2px);
            box-shadow: 0 6px 18px rgba(0, 158, 227, 0.45);
        }
    </style>
</head>
<body style="background-color: var(--fondo-calido);">

    <!-- Navbar Simple -->
    <nav class="navbar navbar-dark navbar-salsa">
        <div class="container justify-content-between">
            <a class="navbar-brand d-flex align-items-center gap-2" href="<?= base_url() ?>">
                <img src="<?= base_url('assets/img/LOGO BORDO 1.png') ?>" alt="La Buona Salsa" height="40">
            </a>
            <span class="text-white-50 small d-none d-sm-inline">
                <i class="bi bi-lock-fill text-warning me-1"></i> Checkout 100% Protegido
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
                <div class="step-circle"><i class="bi bi-check-lg"></i></div>
                <span class="d-none d-sm-inline">2. Datos de Envío</span>
            </div>
            <div class="step-line"></div>
            <div class="step-item active">
                <div class="step-circle">3</div>
                <span>Confirmar Compra</span>
            </div>
        </div>

        <?php if (!empty($pedidoConfirmado)): ?>
            <!-- Pantalla de Pedido Confirmado -->
            <div class="row justify-content-center">
                <div class="col-lg-7">
                    <div class="salsa-form-card text-center py-5">
                        <div class="mb-3">
                            <span class="display-1 text-success"><i class="bi bi-check-circle-fill"></i></span>
                        </div>
                        <h2 class="fw-bold font-display text-dark mb-2">¡Pedido Confirmado con Éxito!</h2>
                        <p class="text-muted mb-4">
                            Muchas gracias por tu compra, <strong><?= esc($usuario['nombre'] ?? 'Cliente') ?></strong>. Ya estamos preparando tu salsa artesanal para el envío.
                        </p>

                        <div class="alert alert-success border-0 rounded-4 text-start p-3 mb-4 d-flex align-items-center gap-3">
                            <i class="bi bi-envelope-check-fill fs-2 text-success"></i>
                            <div>
                                <div class="fw-bold">Notificación por Email Enviada</div>
                                <div class="small">Se envió el detalle de la entrega a <strong>jean.caret.almenar256@gmail.com</strong>.</div>
                            </div>
                        </div>

                        <div class="bg-light p-4 rounded-4 text-start mb-4 border">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Producto:</span>
                                <span class="fw-bold text-dark"><?= esc($producto['nombre']) ?> (x<?= esc($cantidad ?? 1) ?>)</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Entrega:</span>
                                <span class="fw-bold text-dark"><?= esc($metodo_envio ?? 'Rappi') ?></span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Dirección de Envío:</span>
                                <span class="fw-bold text-dark"><?= esc($usuario['direccion'] ?? 'Retiro en local') ?></span>
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
                                           . "• *Teléfono:* " . urlencode($usuario['telefono'] ?? '');
                        ?>

                        <div class="d-grid gap-2 mb-3">
                            <a href="https://api.whatsapp.com/send?text=<?= $textoWhatsapp ?>" target="_blank" class="btn btn-success btn-lg py-3 fw-bold rounded-pill">
                                <i class="bi bi-whatsapp me-2"></i> Enviar Notificación a mi WhatsApp
                            </a>
                        </div>

                        <a href="<?= base_url() ?>" class="btn btn-salsa-primary px-5 py-3">
                            <i class="bi bi-house-door-fill me-1"></i> Volver a la Tienda
                        </a>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <!-- Formulario de Configuración y Pago del Pedido -->
            <div class="row g-4">
                <!-- Columna Izquierda: Configuración del Pedido -->
                <div class="col-lg-8">
                    <div class="salsa-form-card mb-4">
                        <h4 class="fw-bold mb-3 font-display text-dark d-flex align-items-center gap-2">
                            <i class="bi bi-geo-alt-fill text-danger"></i> Datos de Entrega y Destinatario
                        </h4>

                        <div class="p-3 bg-light rounded-4 border d-flex justify-content-between align-items-center">
                            <div>
                                <div class="fw-bold text-dark"><?= esc($usuario['nombre'] ?? 'Usuario') ?></div>
                                <div class="text-muted small"><?= esc($usuario['direccion'] ?? 'Sin dirección registrada') ?></div>
                                <div class="text-muted small">
                                    <i class="bi bi-telephone me-1"></i> <?= esc($usuario['telefono'] ?? '-') ?> | CP: <?= esc($usuario['cp'] ?? '-') ?>
                                </div>
                            </div>
                            <div>
                                <a href="<?= base_url('completar-datos/' . $usuario_id) ?>" class="btn btn-sm btn-outline-secondary rounded-pill">
                                    <i class="bi bi-pencil me-1"></i> Modificar
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="salsa-form-card">
                        <h4 class="fw-bold mb-4 font-display text-dark">
                            <i class="bi bi-credit-card-2-front-fill text-danger me-2"></i> Forma de Pago & Envío
                        </h4>

                        <?php if (!isset($preferenceId)): ?>
                            <?php if (!empty($mpError)): ?>
                                <div class="alert alert-warning border-0 rounded-4 p-3 mb-4 d-flex align-items-start gap-3 shadow-sm">
                                    <i class="bi bi-exclamation-triangle-fill fs-4 text-warning"></i>
                                    <div>
                                        <div class="fw-bold text-dark">Aviso de Mercado Pago</div>
                                        <div class="small text-muted"><?= esc($mpError) ?></div>
                                        <div class="small mt-1 text-secondary">Asegúrate de haber configurado tu <code>MP_ACCESS_TOKEN</code> y <code>MP_PUBLIC_KEY</code> en el archivo <code>.env</code> del servidor.</div>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <form id="formPedido" action="<?= base_url('carrito/procesarPago') ?>" method="POST">
                                <?= csrf_field() ?>
                                <input type="hidden" name="id_producto" value="<?= $producto['id'] ?>">
                                <input type="hidden" name="usuario_id" value="<?= $usuario_id ?>">

                                <!-- Selección de Entrega -->
                                <div class="row g-3 mb-4">
                                    <div class="col-md-6 col-xl-3">
                                        <input type="radio" class="btn-check" name="metodo_envio" id="envio_rappi" value="Rappi" checked data-costo="1200">
                                        <label class="delivery-option-card" for="envio_rappi">
                                            <img src="https://upload.wikimedia.org/wikipedia/commons/0/06/Rappi_logo.svg" alt="Rappi" height="24" class="mb-2">
                                            <div class="small fw-bold text-dark">Rappi Entregas</div>
                                            <div class="text-success fw-bold small">+$1.200</div>
                                        </label>
                                    </div>

                                    <div class="col-md-6 col-xl-3">
                                        <input type="radio" class="btn-check" name="metodo_envio" id="envio_didi" value="DiDi" data-costo="950">
                                        <label class="delivery-option-card" for="envio_didi">
                                            <img src="https://upload.wikimedia.org/wikipedia/commons/a/af/DiDi_logo.svg" alt="DiDi" height="24" class="mb-2">
                                            <div class="small fw-bold text-dark">DiDi Entrega</div>
                                            <div class="text-success fw-bold small">+$950</div>
                                        </label>
                                    </div>

                                    <div class="col-md-6 col-xl-3">
                                        <input type="radio" class="btn-check" name="metodo_envio" id="envio_uber" value="Uber" data-costo="1200">
                                        <label class="delivery-option-card" for="envio_uber">
                                            <img src="https://upload.wikimedia.org/wikipedia/commons/5/58/Uber_logo_2018.svg" alt="Uber" height="18" class="mb-2 mt-1">
                                            <div class="small fw-bold text-dark">Uber Flash</div>
                                            <div class="text-success fw-bold small">+$1.200</div>
                                        </label>
                                    </div>

                                    <div class="col-md-6 col-xl-3">
                                        <input type="radio" class="btn-check" name="metodo_envio" id="envio_retiro" value="Retiro en local" data-costo="0">
                                        <label class="delivery-option-card" for="envio_retiro">
                                            <div class="fs-4 mb-1 text-danger"><i class="bi bi-shop"></i></div>
                                            <div class="small fw-bold text-dark">Retiro en Local</div>
                                            <div class="text-muted fw-bold small">Gratis ($0)</div>
                                        </label>
                                    </div>
                                </div>

                                <!-- Cantidad de Frascos -->
                                <div class="mb-4">
                                    <label class="form-label fw-bold text-dark small">Cantidad de frascos a comprar:</label>
                                    <div class="d-flex align-items-center gap-3">
                                        <button type="button" class="btn btn-outline-dark rounded-circle" id="btnRestar" style="width: 40px; height: 40px;">-</button>
                                        <input type="number" name="cantidad" id="inputCantidad" class="form-control text-center salsa-input fw-bold fs-5" value="1" min="1" max="<?= $producto['stock'] ?? 10 ?>" style="width: 90px;" readonly>
                                        <button type="button" class="btn btn-outline-dark rounded-circle" id="btnSumar" style="width: 40px; height: 40px;">+</button>
                                        <span class="text-muted small">(Máximo stock disponible: <?= $producto['stock'] ?? 10 ?> un.)</span>
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-salsa-primary w-100 justify-content-center py-3 fs-5">
                                    <i class="bi bi-shield-check me-2"></i> Pagar con Mercado Pago
                                </button>
                            </form>
                        <?php else: ?>
                            <div class="text-center py-4">
                                <div class="mb-3">
                                    <img src="https://http2.mlstatic.com/frontend-assets/ui-navigation/5.18.9/mercadopago/logo__large.png" alt="Mercado Pago" height="38">
                                </div>
                                <h4 class="fw-bold mb-2 text-dark font-display">Confirmar y Pagar</h4>
                                <p class="text-muted small mb-4">Total a abonar: <strong class="text-danger fs-5">$ <?= number_format($total ?? 6500, 2, ',', '.') ?></strong></p>
                                
                                <!-- Botón Oficial Único de Mercado Pago (Garantizado) -->
                                <div class="d-flex justify-content-center my-3">
                                    <a id="btn_directo_mp" href="<?= esc($initPoint) ?>" class="btn-mp-pago">
                                        <i class="bi bi-wallet2 me-2"></i> Pagar con Mercado Pago
                                    </a>
                                </div>

                                <!-- Contenedor para Wallet Brick (se activa si el script JS del navegador lo soporta) -->
                                <div id="wallet_container" class="mb-3" style="display: none;"></div>

                                <div class="mt-4 pt-2 border-top">
                                    <a href="<?= base_url('carrito/checkout/' . $producto['id']) ?>" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
                                        <i class="bi bi-arrow-left me-1"></i> Modificar cantidad o envío
                                    </a>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Columna Derecha: Resumen de Orden -->
                <div class="col-lg-4">
                    <div class="salsa-form-card position-sticky" style="top: 100px;">
                        <h5 class="fw-bold mb-3 font-display border-bottom pb-2">Resumen de Tu Pedido</h5>

                        <div class="d-flex align-items-center gap-3 mb-4">
                            <img src="<?= base_url('assets/img/' . ($producto['imagen_principal'] ?? 'producto1.png')) ?>" 
                                 alt="<?= esc($producto['nombre']) ?>" 
                                 height="75" 
                                 class="rounded-3 p-1 bg-light border"
                                 onerror="this.src='<?= base_url('assets/img/producto1.png') ?>'">
                            <div class="flex-grow-1">
                                <h6 class="fw-bold text-dark mb-1"><?= esc($producto['nombre']) ?></h6>
                                <div class="small text-muted mb-1">
                                    Precio: <strong>$ <?= number_format($producto['precio'], 2, ',', '.') ?></strong> c/u
                                </div>
                                <span class="badge bg-danger-subtle text-danger fw-bold">
                                    Total Salsas: <span id="displayTotalProducto">$ <?= number_format($producto['precio'] * ($cantidad ?? 1), 2, ',', '.') ?></span>
                                </span>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between mb-2 small text-muted">
                            <span>Cantidad:</span>
                            <span class="fw-bold text-dark"><span id="summaryCantidad"><?= esc($cantidad ?? 1) ?></span> frasco(s)</span>
                        </div>

                        <div class="d-flex justify-content-between mb-2 small text-muted">
                            <span>Subtotal productos:</span>
                            <span class="fw-bold text-dark" id="displaySubtotal">$ <?= number_format($producto['precio'] * ($cantidad ?? 1), 2, ',', '.') ?></span>
                        </div>

                        <div class="d-flex justify-content-between mb-3 small text-muted">
                            <span>Envío (<span id="summaryMetodoEnvio"><?= esc($metodo_envio ?? 'Rappi') ?></span>):</span>
                            <span class="fw-bold text-dark" id="displayEnvio">$ <?= number_format($costo_envio ?? 1200, 2, ',', '.') ?></span>
                        </div>

                        <hr>

                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="fs-5 fw-bold text-dark">Total a Pagar:</span>
                            <span class="fs-3 fw-extrabold text-gradient-salsa font-display" id="displayTotal">
                                $ <?= number_format($total ?? ($producto['precio'] + ($costo_envio ?? 1200)), 2, ',', '.') ?>
                            </span>
                        </div>

                        <div class="p-3 bg-light rounded-3 text-muted small">
                            <i class="bi bi-shield-check text-success me-1"></i> Garantía de satisfacción artesanal.
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <!-- Scripts de Cálculo Dinámico y Mercado Pago -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const precioUnitario = <?= (float)($producto['precio'] ?? 6500) ?>;
        const maxStock = <?= (int)($producto['stock'] ?? 10) ?>;
        const inputCant = document.getElementById('inputCantidad');
        const displaySubtotal = document.getElementById('displaySubtotal');
        const displayEnvio = document.getElementById('displayEnvio');
        const displayTotal = document.getElementById('displayTotal');
        const summaryCantidad = document.getElementById('summaryCantidad');
        const btnRestar = document.getElementById('btnRestar');
        const displayTotalProducto = document.getElementById('displayTotalProducto');
        const summaryMetodoEnvio = document.getElementById('summaryMetodoEnvio');

        function calcularTotales() {
            if (!inputCant) return;
            const cant = parseInt(inputCant.value) || 1;
            
            // Obtener costo de envío del radio button seleccionado
            let costoEnvio = 0;
            let metodoEnvioNombre = 'Rappi';
            const radios = document.getElementsByName('metodo_envio');
            for (const r of radios) {
                if (r.checked) {
                    costoEnvio = parseFloat(r.getAttribute('data-costo')) || 0;
                    metodoEnvioNombre = r.value;
                    break;
                }
            }

            const subtotal = precioUnitario * cant;
            const total = subtotal + costoEnvio;

            if (summaryCantidad) summaryCantidad.innerText = cant;
            if (displayTotalProducto) displayTotalProducto.innerText = '$ ' + subtotal.toLocaleString('es-AR', {minimumFractionDigits: 2});
            if (displaySubtotal) displaySubtotal.innerText = '$ ' + subtotal.toLocaleString('es-AR', {minimumFractionDigits: 2});
            if (displayEnvio) displayEnvio.innerText = '$ ' + costoEnvio.toLocaleString('es-AR', {minimumFractionDigits: 2});
            if (summaryMetodoEnvio) summaryMetodoEnvio.innerText = metodoEnvioNombre;
            if (displayTotal) displayTotal.innerText = '$ ' + total.toLocaleString('es-AR', {minimumFractionDigits: 2});
        }

        if (btnRestar && inputCant) {
            btnRestar.addEventListener('click', () => {
                let v = parseInt(inputCant.value) || 1;
                if (v > 1) {
                    inputCant.value = v - 1;
                    calcularTotales();
                }
            });
        }

        if (btnSumar && inputCant) {
            btnSumar.addEventListener('click', () => {
                let v = parseInt(inputCant.value) || 1;
                if (v < maxStock) {
                    inputCant.value = v + 1;
                    calcularTotales();
                }
            });
        }

        const deliveryRadios = document.getElementsByName('metodo_envio');
        deliveryRadios.forEach(r => r.addEventListener('change', calcularTotales));

        // Inicializar Mercado Pago si existe preferenceId
        <?php if (isset($preferenceId) && !empty($publicKey)): ?>
            try {
                const mp = new MercadoPago('<?= $publicKey ?>', { locale: 'es-AR' });
                const bricksBuilder = mp.bricks();
                bricksBuilder.create('wallet', 'wallet_container', {
                    initialization: { 
                        preferenceId: '<?= $preferenceId ?>', 
                        redirectMode: 'self' 
                    },
                    customization: {
                        texts: {
                            action: 'pay',
                            valueProp: 'security_safety'
                        }
                    },
                    callbacks: {
                        onReady: () => {
                            // Cuando el Brick oficial termine de renderizar con éxito, lo mostramos y ocultamos el botón directo
                            const directBtn = document.getElementById('btn_directo_mp');
                            const walletDiv = document.getElementById('wallet_container');
                            if (directBtn && walletDiv) {
                                directBtn.style.display = 'none';
                                walletDiv.style.display = 'block';
                            }
                        },
                        onSubmit: () => {
                            console.log('Iniciando pago Mercado Pago...');
                        },
                        onError: (error) => {
                            console.warn('Wallet Brick no disponible, usando botón directo:', error);
                        }
                    }
                });
            } catch (err) {
                console.warn('Error inicializando SDK de Mercado Pago:', err);
            }
        <?php endif; ?>
    </script>
</body>
</html>