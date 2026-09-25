<?= $this->extend('admin/layout') ?>

<?= $this->section('contenido') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">Directorio de Personas Registradas</h4>
        <p class="text-muted small mb-0">Listado de clientes registrados en la tienda, datos de contacto, direcciones y récord de compras.</p>
    </div>
    <div>
        <span class="badge bg-dark rounded-pill px-3 py-2 fs-6">
            Total: <?= count($usuarios ?? []) ?> Clientes
        </span>
    </div>
</div>

<div class="data-card">
    <?php if (empty($usuarios)): ?>
        <div class="text-center py-5 text-muted">
            <i class="bi bi-people fs-1 d-block mb-3 text-secondary"></i>
            <h5>Aún no hay clientes registrados</h5>
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#ID</th>
                        <th>Cliente</th>
                        <th>Contacto</th>
                        <th>Estado Email</th>
                        <th>Dirección de Entrega</th>
                        <th>Entre Calles</th>
                        <th>CP</th>
                        <th>Compras Realizadas</th>
                        <th>Total Gastado</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($usuarios as $u): ?>
                        <tr>
                            <td class="fw-bold text-muted">#<?= $u['id'] ?></td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="stat-icon-badge bg-light text-dark rounded-circle" style="width: 38px; height: 38px; font-size: 1.1rem;">
                                        <i class="bi bi-person"></i>
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark"><?= esc($u['nombre']) ?></div>
                                        <div class="text-muted" style="font-size: 0.75rem;">
                                            Registrado: <?= !empty($u['created_at']) ? date('d/m/Y', strtotime($u['created_at'])) : '-' ?>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="small"><i class="bi bi-envelope me-1 text-muted"></i><?= esc($u['email']) ?></div>
                                <div class="small fw-semibold text-dark">
                                    <i class="bi bi-whatsapp me-1 text-success"></i><?= esc($u['telefono'] ?? '-') ?>
                                </div>
                            </td>
                            <td>
                                <?php if (!empty($u['email_verificado'])): ?>
                                    <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2 py-1 small">
                                        <i class="bi bi-patch-check-fill me-1"></i> Activo
                                    </span>
                                <?php else: ?>
                                    <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle rounded-pill px-2 py-1 small">
                                        <i class="bi bi-clock-history me-1"></i> Pendiente
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="small fw-semibold text-dark">
                                    <?= esc(!empty($u['direccion']) ? $u['direccion'] : 'Pendiente') ?>
                                </span>
                            </td>
                            <td class="small text-muted">
                                <?php if (!empty($u['entre_calle_1']) || !empty($u['entre_calle_2'])): ?>
                                    <?= esc($u['entre_calle_1'] ?? '') ?> y <?= esc($u['entre_calle_2'] ?? '') ?>
                                <?php else: ?>
                                    -
                                <?php endif; ?>
                            </td>
                            <td class="small text-muted"><?= esc($u['cp'] ?? '-') ?></td>
                            <td>
                                <span class="badge <?= ($u['total_compras'] ?? 0) > 0 ? 'bg-success' : 'bg-secondary-subtle text-secondary' ?> rounded-pill px-3 py-1">
                                    <?= $u['total_compras'] ?? 0 ?> compra(s)
                                </span>
                            </td>
                            <td class="fw-bold text-dark">
                                $ <?= number_format((float)($u['total_gastado'] ?? 0), 2, ',', '.') ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>
