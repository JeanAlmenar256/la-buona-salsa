<?= $this->extend('admin/layout') ?>

<?= $this->section('contenido') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">Catálogo de Salsas & Control de Stock</h4>
        <p class="text-muted small mb-0">Modifica precios de venta, costos de producción y unidades disponibles en tiempo real.</p>
    </div>
</div>

<div class="row g-4">
    <?php foreach ($productos as $p): ?>
        <?php 
            $precio = (float)$p['precio'];
            $costo  = (float)($p['costo_produccion'] ?? 2500.00);
            $gananciaUnitaria = $precio - $costo;
            $margenPct = ($precio > 0) ? round(($gananciaUnitaria / $precio) * 100, 1) : 0;
        ?>
        <div class="col-md-6 col-xl-4">
            <div class="data-card h-100 d-flex flex-column justify-content-between">
                <div>
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <img src="<?= base_url('assets/img/' . ($p['imagen_principal'] ?? 'producto1.png')) ?>" 
                             alt="<?= esc($p['nombre']) ?>" 
                             height="70" class="rounded-3 border p-1 bg-light">
                        <div>
                            <h5 class="fw-bold mb-1 text-dark"><?= esc($p['nombre']) ?></h5>
                            <span class="badge <?= $p['stock'] <= 5 ? 'bg-danger' : 'bg-success' ?> rounded-pill px-3">
                                <i class="bi bi-box-seam me-1"></i> Stock: <?= $p['stock'] ?> un.
                            </span>
                        </div>
                    </div>

                    <p class="text-muted small mb-4">
                        <?= esc($p['descripcion']) ?>
                    </p>

                    <div class="bg-light p-3 rounded-4 border mb-4">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted small">Precio de Venta Público:</span>
                            <span class="fw-bold text-dark fs-6">$ <?= number_format($precio, 2, ',', '.') ?></span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted small">Costo de Elaboración:</span>
                            <span class="fw-bold text-danger fs-6">$ <?= number_format($costo, 2, ',', '.') ?></span>
                        </div>
                        <hr class="my-2">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-muted small fw-bold">Ganancia por Frasco:</span>
                            <span class="fw-bold text-success fs-6">+$ <?= number_format($gananciaUnitaria, 2, ',', '.') ?> (<?= $margenPct ?>%)</span>
                        </div>
                    </div>
                </div>

                <button class="btn btn-dark w-100 rounded-pill py-2" data-bs-toggle="modal" data-bs-target="#modalEditarProd<?= $p['id'] ?>">
                    <i class="bi bi-pencil-square me-1"></i> Modificar Precio y Stock
                </button>
            </div>
        </div>

        <!-- Modal Editar Producto -->
        <div class="modal fade" id="modalEditarProd<?= $p['id'] ?>" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content rounded-4 border-0 shadow">
                    <div class="modal-header bg-dark text-white border-0">
                        <h5 class="modal-title fw-bold">Editar <?= esc($p['nombre']) ?></h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <form action="<?= base_url('admin/productos/guardar') ?>" method="POST">
                        <?= csrf_field() ?>
                        <input type="hidden" name="id" value="<?= $p['id'] ?>">

                        <div class="modal-body p-4">
                            <div class="mb-3">
                                <label class="form-label fw-bold small">Nombre de la Salsa</label>
                                <input type="text" name="nombre" class="form-control rounded-3" value="<?= esc($p['nombre']) ?>" required>
                            </div>

                            <div class="row g-3 mb-3">
                                <div class="col-6">
                                    <label class="form-label fw-bold small text-success">Precio de Venta ($)</label>
                                    <input type="number" step="50" name="precio" class="form-control rounded-3 fw-bold" value="<?= $precio ?>" required>
                                    <div class="form-text small">Precio que abona el cliente</div>
                                </div>
                                <div class="col-6">
                                    <label class="form-label fw-bold small text-danger">Costo Elaboración ($)</label>
                                    <input type="number" step="50" name="costo_produccion" class="form-control rounded-3 fw-bold" value="<?= $costo ?>" required>
                                    <div class="form-text small">Costo ingredientes por frasco</div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold small">Stock Disponible (Unidades)</label>
                                <input type="number" name="stock" class="form-control rounded-3 fw-bold" value="<?= $p['stock'] ?>" min="0" required>
                                <div class="form-text small">Disponibilidad para compras en la tienda</div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold small">Descripción</label>
                                <textarea name="descripcion" class="form-control rounded-3" rows="3"><?= esc($p['descripcion']) ?></textarea>
                            </div>
                        </div>

                        <div class="modal-footer border-0 p-4 pt-0">
                            <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-danger rounded-pill px-4 fw-bold">Guardar Cambios</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<?= $this->endSection() ?>
