<?php if (session()->getFlashdata('errors')): ?>
    <div class="alert alert-danger">
        <ul>
        <?php foreach (session()->getFlashdata('errors') as $error): ?>
            <li><?= esc($error) ?></li>
        <?php endforeach ?>
        </ul>
    </div>
<?php endif; ?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Completar Datos - La Buona Salsa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
    body { 
        background-color: #fdf2e9; /* Tono crema cálido */
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    .form-container { 
        background: white; 
        padding: 40px; 
        border-radius: 20px; 
        box-shadow: 0 15px 35px rgba(217, 67, 36, 0.1); /* Sombra sutil color salsa */
        margin-top: 50px;
        border-top: 5px solid #9B1212; /* Línea superior color marca */
    }
    .btn-salsa { 
        background-color: #fd580f; 
        color: white; 
        font-weight: bold;
        border: none;
        transition: 0.3s;
    }
    .btn-salsa:hover { 
        background-color: #fd580f; 
        transform: translateY(-2px);
    }
</style>
</head>
<body>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6 form-container">
            <h2 class="text-center mb-4" style="color: #d94324;">¡Ya casi estás!</h2>
            <p class="text-muted text-center">Necesitamos estos datos para tus pedidos</p>

            <form action="<?= base_url('guardar-detalles') ?>" method="POST">
                <?= csrf_field() ?> <input type="hidden" name="id" value="<?= $usuario_id ?>">

                <div class="mb-3">
                    <label class="form-label">Dirección Completa</label>
                    <input type="text" name="direccion" class="form-control" placeholder="Calle y número" required>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Entre Calle 1</label>
                        <input type="text" name="entre_calle_1" class="form-control">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Entre Calle 2</label>
                        <input type="text" name="entre_calle_2" class="form-control">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Teléfono</label>
                        <input type="text" name="telefono" class="form-control" 
                            pattern="[0-9]+" 
                            title="Por favor, ingrese solo números" 
                            oninput="this.value = this.value.replace(/[^0-9]/g, '');" 
                            placeholder="Ej: 1155443322" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Código Postal</label>
                        <input type="text" name="cp" class="form-control" required>
                    </div>
                </div>

                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-salsa btn-lg">Finalizar Registro</button>
                </div>
            </form>
        </div>
    </div>
</div>

</body>
</html>