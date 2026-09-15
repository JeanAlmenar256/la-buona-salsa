<?= $this->extend('admin/layout') ?>

<?= $this->section('contenido') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">Registro de Compras & Ventas Realizadas</h4>
        <p class="text-muted small mb-0">Historial completo de pedidos pagados, datos de entrega y rentabilidad de cada orden.</p>
    </div>
</div>

<div class="data-card">
    <?php if (empty($pedidos)): ?>
        <div class="text-center py-5 text-muted">
            <i class="bi bi-cart-x fs-1 d-block mb-3 text-secondary"></i>
            <h5>Aún no se registran compras confirmadas</h5>
            <p class="small text-muted">A medida que los clientes completen pagos en Mercado Pago, se listarán automáticamente aquí con sus datos de despacho.</p>
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th># Orden</th>
                        <th>Fecha</th>
                        <th>Cliente & Contacto</th>
                        <th>Dirección de Despacho</th>
                        <th>Producto</th>
                        <th>Envío</th>
                        <th>Total</th>
                        <th>Ganancia</th>
                        <th>Mercado Pago</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($pedidos as $p): ?>
                        <tr>
                            <td class="fw-bold">
                                <span class="badge bg-dark rounded-pill">#<?= $p['id'] ?></span>
                            </td>
                            <td class="small text-muted">
                                <?= date('d/m/Y', strtotime($p['created_at'])) ?><br>
                                <span class="text-secondary" style="font-size: 0.75rem;"><?= date('H:i', strtotime($p['created_at'])) ?> hs</span>
                            </td>
                            <td>
                                <div class="fw-bold text-dark"><?= esc($p['cliente_nombre'] ?? 'Cliente') ?></div>
                                <div class="small text-muted">
                                    <i class="bi bi-envelope me-1"></i><?= esc($p['cliente_email'] ?? '-') ?>
                                </div>
                                <div class="small text-muted">
                                    <i class="bi bi-telephone me-1"></i><strong><?= esc($p['cliente_telefono'] ?? '-') ?></strong>
                                </div>
                            </td>
                            <td>
                                <div class="small fw-semibold text-dark">
                                    <i class="bi bi-geo-alt-fill text-danger me-1"></i><?= esc($p['cliente_direccion'] ?? 'Retiro en local') ?>
                                </div>
                            </td>
                            <td>
                                <div class="fw-bold text-dark"><?= esc($p['producto_nombre'] ?? 'Salsa') ?></div>
                                <span class="badge bg-secondary-subtle text-secondary rounded-pill px-2">
                                    <?= $p['cantidad'] ?> frasco(s)
                                </span>
                            </td>
                            <td>
                                <div class="badge bg-info-subtle text-info-emphasis rounded-pill px-3 py-1">
                                    <?= esc($p['metodo_envio']) ?>
                                </div>
                                <div class="small text-muted mt-1">$ <?= number_format($p['costo_envio'], 0, ',', '.') ?></div>
                            </td>
                            <td class="fw-bold text-dark fs-6">
                                $ <?= number_format($p['total'], 2, ',', '.') ?>
                            </td>
                            <td class="fw-bold text-success fs-6">
                                +$ <?= number_format($p['ganancia_neta'], 2, ',', '.') ?>
                            </td>
                            <td>
                                <span class="badge bg-success-subtle text-success rounded-pill px-2 py-1 mb-1 d-block text-center">
                                    <i class="bi bi-check-circle-fill me-1"></i> <?= esc($p['estado_pago']) ?>
                                </span>
                                <?php if (!empty($p['mp_payment_id'])): ?>
                                    <span class="text-muted d-block text-center" style="font-size: 0.7rem;">
                                        ID: <?= esc($p['mp_payment_id']) ?>
                                    </span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>
