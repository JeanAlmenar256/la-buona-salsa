<?= $this->extend('admin/layout') ?>

<?= $this->section('contenido') ?>

<!-- 1. Tarjetas de Métricas Financieras y Rentabilidad -->
<div class="row g-4 mb-4">
    <!-- Ingresos Brutos -->
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <span class="text-muted fw-bold small text-uppercase">Ingresos por Ventas</span>
                <div class="stat-icon-badge bg-gradient-salsa">
                    <i class="bi bi-cash-stack"></i>
                </div>
            </div>
            <h3 class="fw-bold mb-1">$ <?= number_format($metricas['ingresos_brutos'] ?? 0, 2, ',', '.') ?></h3>
            <span class="text-muted small">Total facturado en tienda</span>
        </div>
    </div>

    <!-- Ganancia Neta -->
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <span class="text-muted fw-bold small text-uppercase">Ganancia Neta Real</span>
                <div class="stat-icon-badge bg-gradient-success-salsa">
                    <i class="bi bi-graph-up-arrow"></i>
                </div>
            </div>
            <h3 class="fw-bold text-success mb-1">$ <?= number_format($metricas['ganancia_neta_total'] ?? 0, 2, ',', '.') ?></h3>
            <span class="text-success small fw-semibold">
                <i class="bi bi-pie-chart-fill me-1"></i> <?= $metricas['margen_rentabilidad'] ?? 0 ?>% Margen de beneficio
            </span>
        </div>
    </div>

    <!-- Costos de Producción -->
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <span class="text-muted fw-bold small text-uppercase">Costo Total Producción</span>
                <div class="stat-icon-badge bg-gradient-warning-salsa">
                    <i class="bi bi-boxes"></i>
                </div>
            </div>
            <h3 class="fw-bold mb-1 text-danger">$ <?= number_format($metricas['costo_total_produccion'] ?? 0, 2, ',', '.') ?></h3>
            <span class="text-muted small"><?= $metricas['frascos_vendidos'] ?? 0 ?> frasco(s) despachados</span>
        </div>
    </div>

    <!-- Clientes Registrados -->
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <span class="text-muted fw-bold small text-uppercase">Clientes Registrados</span>
                <div class="stat-icon-badge bg-gradient-info-salsa">
                    <i class="bi bi-people-fill"></i>
                </div>
            </div>
            <h3 class="fw-bold mb-1 text-primary"><?= $totalClientes ?? 0 ?></h3>
            <a href="<?= base_url('admin/usuarios') ?>" class="text-decoration-none small text-primary fw-semibold">
                Ver directorio <i class="bi bi-chevron-right"></i>
            </a>
        </div>
    </div>
</div>

<!-- 2. Estado de Inventario y Alertas de Stock -->
<div class="row g-4 mb-4">
    <div class="col-lg-5">
        <div class="data-card h-100">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="fw-bold mb-0"><i class="bi bi-box-seam me-2 text-danger"></i> Stock de Salsas</h5>
                <a href="<?= base_url('admin/productos') ?>" class="btn btn-sm btn-outline-danger rounded-pill">
                    Editar Stock & Precios
                </a>
            </div>

            <div class="list-group list-group-flush">
                <?php foreach ($productos as $p): ?>
                    <div class="list-group-item px-0 py-3 d-flex justify-content-between align-items-center border-bottom">
                        <div class="d-flex align-items-center gap-3">
                            <img src="<?= base_url('assets/img/' . ($p['imagen_principal'] ?? 'producto1.png')) ?>" 
                                 height="45" class="rounded-3 border p-1 bg-light" alt="Salsa">
                            <div>
                                <div class="fw-bold text-dark"><?= esc($p['nombre']) ?></div>
                                <div class="small text-muted">
                                    Venta: <strong>$ <?= number_format($p['precio'], 2, ',', '.') ?></strong> | 
                                    Costo: $ <?= number_format($p['costo_produccion'] ?? 2500, 2, ',', '.') ?>
                                </div>
                            </div>
                        </div>

                        <div class="text-end">
                            <?php if ($p['stock'] <= 5): ?>
                                <span class="badge bg-danger rounded-pill px-3 py-2 fw-bold">
                                    <i class="bi bi-exclamation-circle me-1"></i> <?= $p['stock'] ?> un. (Bajo)
                                </span>
                            <?php else: ?>
                                <span class="badge bg-success-subtle text-success rounded-pill px-3 py-2 fw-bold">
                                    <?= $p['stock'] ?> un. disponible
                                </span>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- Resumen de Rentabilidad -->
    <div class="col-lg-7">
        <div class="data-card h-100">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="fw-bold mb-0"><i class="bi bi-pie-chart me-2 text-success"></i> Desglose de Rentabilidad</h5>
                <span class="badge bg-success rounded-pill px-3 py-2">
                    Rentabilidad: <?= $metricas['margen_rentabilidad'] ?? 0 ?>%
                </span>
            </div>

            <div class="p-3 bg-light rounded-4 border mb-4">
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Total Facturado en Productos:</span>
                    <span class="fw-bold text-dark">$ <?= number_format($metricas['ingresos_brutos'] - ($metricas['costo_total_envios'] ?? 0), 2, ',', '.') ?></span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">(-) Costos de Ingredientes & Elaboración:</span>
                    <span class="fw-bold text-danger">-$ <?= number_format($metricas['costo_total_produccion'] ?? 0, 2, ',', '.') ?></span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Ingresos por Envío (Cobrado a clientes):</span>
                    <span class="fw-bold text-secondary">+$ <?= number_format($metricas['costo_total_envios'] ?? 0, 2, ',', '.') ?></span>
                </div>
                <hr>
                <div class="d-flex justify-content-between align-items-center fs-5">
                    <span class="fw-bold text-dark">Ganancia Neta Obtenida:</span>
                    <span class="fw-extrabold text-success">$ <?= number_format($metricas['ganancia_neta_total'] ?? 0, 2, ',', '.') ?></span>
                </div>
            </div>

            <div class="d-flex justify-content-between align-items-center">
                <a href="<?= base_url('admin/pedidos') ?>" class="btn btn-dark rounded-pill px-4">
                    <i class="bi bi-receipt me-1"></i> Ver Registro de Compras
                </a>
                <a href="<?= base_url('admin/envios') ?>" class="btn btn-outline-secondary rounded-pill px-4">
                    <i class="bi bi-truck me-1"></i> Ajustar Tarifas Delivery
                </a>
            </div>
        </div>
    </div>
</div>

<!-- 3. Últimos Pedidos Recibidos -->
<div class="data-card">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h5 class="fw-bold mb-0"><i class="bi bi-clock-history me-2 text-warning"></i> Últimas Compras Realizadas</h5>
        <a href="<?= base_url('admin/pedidos') ?>" class="text-decoration-none small text-muted">
            Ver todas (<?= $metricas['total_pedidos'] ?? 0 ?>) <i class="bi bi-chevron-right"></i>
        </a>
    </div>

    <?php if (empty($pedidos)): ?>
        <div class="text-center py-4 text-muted">
            <i class="bi bi-inbox fs-1 d-block mb-2 text-secondary"></i>
            No se han registrado pedidos pagados todavía.
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#ID</th>
                        <th>Fecha</th>
                        <th>Cliente</th>
                        <th>Producto</th>
                        <th>Cant.</th>
                        <th>Envío</th>
                        <th>Total</th>
                        <th>Ganancia</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($pedidos as $ped): ?>
                        <tr>
                            <td class="fw-bold">#<?= $ped['id'] ?></td>
                            <td class="small text-muted"><?= date('d/m/Y H:i', strtotime($ped['created_at'])) ?></td>
                            <td>
                                <div class="fw-bold text-dark"><?= esc($ped['cliente_nombre'] ?? 'Cliente') ?></div>
                                <div class="small text-muted"><?= esc($ped['cliente_telefono'] ?? '-') ?></div>
                            </td>
                            <td><?= esc($ped['producto_nombre'] ?? 'Salsa') ?></td>
                            <td><span class="badge bg-secondary rounded-pill"><?= $ped['cantidad'] ?></span></td>
                            <td><span class="badge bg-info-subtle text-info-emphasis rounded-pill"><?= esc($ped['metodo_envio']) ?></span></td>
                            <td class="fw-bold text-dark">$ <?= number_format($ped['total'], 2, ',', '.') ?></td>
                            <td class="fw-bold text-success">+$ <?= number_format($ped['ganancia_neta'], 2, ',', '.') ?></td>
                            <td>
                                <span class="badge bg-success-subtle text-success rounded-pill px-3">
                                    <i class="bi bi-check-circle me-1"></i> Aprobado
                                </span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>
