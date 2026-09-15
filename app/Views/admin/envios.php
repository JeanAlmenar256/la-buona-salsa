<?= $this->extend('admin/layout') ?>

<?= $this->section('contenido') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">Tarifas de Empresas de Pedido & Delivery</h4>
        <p class="text-muted small mb-0">Configura los precios de envío cobrados a los clientes para Rappi, DiDi, Uber y Retiro en local.</p>
    </div>
</div>

<div class="row g-4">
    <?php foreach ($envios as $e): ?>
        <div class="col-md-6 col-xl-3">
            <div class="data-card h-100 d-flex flex-column justify-content-between">
                <div>
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <?php if ($e['empresa'] === 'Rappi'): ?>
                            <img src="https://upload.wikimedia.org/wikipedia/commons/0/06/Rappi_logo.svg" alt="Rappi" height="28">
                        <?php elseif ($e['empresa'] === 'DiDi'): ?>
                            <img src="https://upload.wikimedia.org/wikipedia/commons/a/af/DiDi_logo.svg" alt="DiDi" height="28">
                        <?php elseif ($e['empresa'] === 'Uber'): ?>
                            <img src="https://upload.wikimedia.org/wikipedia/commons/5/58/Uber_logo_2018.svg" alt="Uber" height="20" class="mt-1">
                        <?php else: ?>
                            <span class="fs-2 text-danger"><i class="bi bi-shop"></i></span>
                        <?php endif; ?>

                        <span class="badge <?= ($e['activo'] ?? 1) ? 'bg-success-subtle text-success' : 'bg-secondary' ?> rounded-pill px-3 py-1">
                            <?= ($e['activo'] ?? 1) ? 'Activo' : 'Desactivado' ?>
                        </span>
                    </div>

                    <h5 class="fw-bold mb-1 text-dark"><?= esc($e['empresa']) ?></h5>
                    <p class="text-muted small mb-3">
                        <i class="bi bi-clock me-1"></i> Tiempo: <?= esc($e['tiempo_estimado'] ?? '30-45 min') ?>
                    </p>

                    <div class="p-3 bg-light rounded-4 border mb-3">
                        <span class="text-muted small d-block mb-1">Tarifa al cliente:</span>
                        <span class="fs-4 fw-bold text-danger">$ <?= number_format((float)$e['costo'], 2, ',', '.') ?></span>
                    </div>
                </div>

                <button class="btn btn-outline-dark w-100 rounded-pill" data-bs-toggle="modal" data-bs-target="#modalEnvio<?= $e['id'] ?>">
                    <i class="bi bi-pencil me-1"></i> Editar Tarifa
                </button>
            </div>
        </div>

        <!-- Modal Editar Tarifa -->
        <div class="modal fade" id="modalEnvio<?= $e['id'] ?>" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content rounded-4 border-0 shadow">
                    <div class="modal-header bg-dark text-white border-0">
                        <h5 class="modal-title fw-bold">Modificar Tarifa: <?= esc($e['empresa']) ?></h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <form action="<?= base_url('admin/envios/guardar') ?>" method="POST">
                        <?= csrf_field() ?>
                        <input type="hidden" name="id" value="<?= $e['id'] ?>">

                        <div class="modal-body p-4">
                            <div class="mb-3">
                                <label class="form-label fw-bold small">Costo de Envío ($)</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light">$</span>
                                    <input type="number" step="50" name="costo" class="form-control fw-bold fs-5" value="<?= (float)$e['costo'] ?>" min="0" required>
                                </div>
                                <div class="form-text small">Monto que se sumará al total en la pasarela de pago</div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold small">Tiempo Estimado de Entrega</label>
                                <input type="text" name="tiempo_estimado" class="form-control rounded-3" value="<?= esc($e['tiempo_estimado'] ?? '') ?>" placeholder="Ej: 30-45 min">
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold small">Estado</label>
                                <select name="activo" class="form-select rounded-3">
                                    <option value="1" <?= ($e['activo'] ?? 1) ? 'selected' : '' ?>>Habilitado en la tienda</option>
                                    <option value="0" <?= !($e['activo'] ?? 1) ? 'selected' : '' ?>>Deshabilitado temporalmente</option>
                                </select>
                            </div>
                        </div>

                        <div class="modal-footer border-0 p-4 pt-0">
                            <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-danger rounded-pill px-4 fw-bold">Guardar Tarifa</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<?= $this->endSection() ?>
