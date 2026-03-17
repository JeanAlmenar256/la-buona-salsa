<div class="container mt-5">
    <h2 class="mb-4"><i class="fas fa-shopping-basket"></i> Tu Carrito de Compras</h2>

    <?php if (empty($items)): ?>
        <div class="alert alert-info">
            Tu carrito está vacío. <a href="<?= base_url('/') ?>" class="alert-link">¡Mira nuestras salsas!</a>
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>Producto</th>
                        <th class="text-center">Cantidad</th>
                        <th>Precio Unitario</th>
                        <th>Subtotal</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($items as $id => $item): ?>
                    <tr>
                        <td><strong><?= $item['nombre'] ?></strong></td>
                        <td class="text-center"><?= $item['cantidad'] ?></td>
                        <td>$<?= number_format($item['precio'], 2) ?></td>
                        <td>$<?= number_format($item['subtotal'], 2) ?></td>
                        <td>
                            <a href="<?= base_url('carrito/eliminar/'.$id) ?>" class="btn btn-outline-danger btn-sm">
                                <i class="fas fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr class="table-light">
                        <td colspan="3" class="text-end"><strong>TOTAL PRODUCTOS:</strong></td>
                        <td colspan="2"><strong>$<?= number_format(session()->get('total_carrito'), 2) ?></strong></td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <div class="d-flex justify-content-between mt-4">
            <a href="<?= base_url('/') ?>" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Continuar comprando
            </a>
            <a href="<?= base_url('carrito/checkout') ?>" class="btn btn-success btn-lg">
                Siguiente: Datos de envío <i class="fas fa-arrow-right"></i>
            </a>
        </div>
    <?php endif; ?>
</div>