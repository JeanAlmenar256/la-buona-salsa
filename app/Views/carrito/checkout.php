<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Confirmar Pedido - La Buona Salsa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('css/estilos.css') ?>">
    <script src="https://sdk.mercadopago.com/js/v2"></script>
    <style>
        :root { --rojo-salsa: #9B1212; }
        .btn-pagar { background-color: var(--rojo-salsa); color: white; font-weight: bold; border: none; }
        .btn-pagar:hover { background-color: #7a0e0e; }
        .card-header { background-color: var(--rojo-salsa) !important; color: white; }
    </style>
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="row">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header">
                    <h5 class="mb-0"><?= isset($preferenceId) ? 'Finalizar Pago' : 'Configura tu pedido' ?></h5>
                </div>
                <div class="card-body">
                    <?php if (!isset($preferenceId)): ?>
                        <form action="<?= base_url('carrito/procesarPago') ?>" method="POST">
                            <?= csrf_field() ?> 
                            <input type="hidden" name="id_producto" value="<?= $producto['id'] ?>">
                            <input type="hidden" name="usuario_id" value="<?= $usuario_id ?>">

                            <div class="mb-3">
                                <label class="fw-bold">Cantidad:</label>
                                <input type="number" name="cantidad" id="inputCantidad" class="form-control" value="1" min="1" max="<?= $producto['stock'] ?>">
                            </div>

                            <div class="mb-3">
                                <label class="fw-bold">Envío:</label>
                                <div class="form-check border p-2 mb-2 rounded">
                                    <input class="form-check-input" type="radio" name="metodo_envio" id="retiro" value="0" checked>
                                    <label class="form-check-label" for="retiro">Retiro en Local ($0)</label>
                                </div><br>
                                <div class="mt-4">
                                <h5 class="fw-bold mb-3" style="color: #9B1212;">Elegí cómo recibir tu salsa:</h5>
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <input type="radio" class="btn-check" name="metodo_envio" id="envio_rappi" value="Rappi" checked autocomplete="off">
                                        <label class="btn btn-outline-light border shadow-sm w-100 p-3 delivery-card" for="envio_rappi">
                                            <img src="https://upload.wikimedia.org/wikipedia/commons/0/06/Rappi_logo.svg" alt="Rappi" height="30" class="mb-2">
                                            <div class="small text-dark fw-bold">Rappi Entregas</div>
                                        </label>
                                    </div>

                                    <div class="col-md-4">
                                        <input type="radio" class="btn-check" name="metodo_envio" id="envio_didi" value="DiDi" autocomplete="off">
                                        <label class="btn btn-outline-light border shadow-sm w-100 p-3 delivery-card" for="envio_didi">
                                            <img src="https://upload.wikimedia.org/wikipedia/commons/a/af/DiDi_logo.svg" alt="DiDi" height="30" class="mb-2">
                                            <div class="small text-dark fw-bold">DiDi Entrega</div>
                                        </label>
                                    </div>

                                    <div class="col-md-4">
                                        <input type="radio" class="btn-check" name="metodo_envio" id="envio_uber" value="Uber" autocomplete="off">
                                        <label class="btn btn-outline-light border shadow-sm w-100 p-3 delivery-card" for="envio_uber">
                                            <img src="https://upload.wikimedia.org/wikipedia/commons/5/58/Uber_logo_2018.svg" alt="Uber" height="20" class="mb-2 mt-2">
                                            <div class="small text-dark fw-bold">Uber Flash</div>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            </div>
                            <button type="submit" class="btn btn-pagar w-100 btn-lg">CONTINUAR AL PAGO</button>
                        </form>
                    <?php else: ?>
                        <div class="text-center py-4">
                            <div id="wallet_container"></div> 
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow border-success">
                <div class="card-body text-center">
                    <h5 class="text-muted">Total</h5>
                    <h2 class="text-success fw-bold" id="displayTotal">$ <?= number_format($total ?? $producto['precio'], 2, ',', '.') ?></h2>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const precioUnitario = <?= $producto['precio'] ?>;
    const inputCant = document.getElementById('inputCantidad');
    const display = document.getElementById('displayTotal');

    function actualizar() {
        if(!inputCant) return;
        let t = (precioUnitario * parseInt(inputCant.value)) + (document.getElementById('delivery').checked ? 800 : 0);
        display.innerText = '$ ' + t.toLocaleString('es-AR', {minimumFractionDigits: 2});
    }

    if(inputCant){
        inputCant.addEventListener('input', actualizar);
        document.getElementsByName('metodo_envio').forEach(r => r.addEventListener('change', actualizar));
    }

    // 3. INICIALIZACIÓN DE MERCADO PAGO
    <?php if (isset($preferenceId)): ?>
        const mp = new MercadoPago('<?= $publicKey ?>', { locale: 'es-AR' });
        
        // CORRECCIÓN: El ID aquí debe ser igual al del DIV de arriba ('walletBrick_container')
        mp.bricks().create('wallet', 'wallet_container', {
            initialization: { 
                preferenceId: '<?= $preferenceId ?>', 
                redirectMode: 'modal' 
            },
        });
    <?php endif; ?>
</script>
</body>
</html>