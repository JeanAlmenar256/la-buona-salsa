<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?= $producto['nombre'] ?> - La Buona Salsa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        @import url('https://fonts.cdnfonts.com/css/helvetica-neue-55');

        :root {
            --naranja-marca: #f29418;
            --rojo-salsa: #9B1212;
            --naranja-fuego: #fd580f;
            --blanco-estudio: #f6f7f8;
        }

        body {
            background-color: var(--blanco-estudio);
            font-family: 'Helvetica', Arial, sans-serif;
        }

        .product-card {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 20px 50px rgba(0,0,0,0.08);
            border: 1px solid rgba(0,0,0,0.05);
        }

        .carousel-item img {
            width: 100%;
            height: 500px;
            object-fit: contain;
            background: radial-gradient(circle, #ffffff 0%, #f0f0f0 100%);
            padding: 20px;
        }

        .product-price { 
            color: var(--naranja-fuego); 
            font-size: 2.8rem; 
            font-weight: bold; 
        }

        /* Tu círculo verde de stock */
        .stock-circle {
            background-color: #28a745;
            color: white;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 1.2rem;
            box-shadow: 0 4px 10px rgba(40, 167, 69, 0.3);
        }

        .btn-salsa {
            background-color: var(--rojo-salsa);
            color: white;
            border: none;
            padding: 18px;
            font-weight: bold;
            text-transform: uppercase;
            border-radius: 12px;
            transition: 0.3s;
        }

        .btn-salsa:hover {
            background-color: var(--naranja-marca);
            transform: translateY(-3px);
            color: white;
        }
    </style>
</head>
<body>

<div class="container mt-5 mb-5">
    <div class="product-card">
        <div class="row g-0 bg-white p-4">
            <div class="col-md-6">
                <div id="productCarousel" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner">
                        <div class="carousel-item active">
                            <img src="<?= base_url('assets/img/' . $producto['imagen_principal']) ?>" class="d-block w-100" alt="Principal">
                        </div>
                        <div class="carousel-item">
                            <img src="<?= base_url('assets/img/producto2.png') ?>" class="d-block w-100" alt="Vista 2">
                        </div>
                        <div class="carousel-item">
                            <img src="<?= base_url('assets/img/producto3.png') ?>" class="d-block w-100" alt="Vista 3">
                        </div>
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#productCarousel" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon bg-dark rounded-circle"></span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#productCarousel" data-bs-slide="next">
                        <span class="carousel-control-next-icon bg-dark rounded-circle"></span>
                    </button>
                </div>
            </div>

            <div class="col-md-6 ps-md-5 d-flex flex-column justify-content-center">
                <h1 class="display-5 fw-bold mb-2"><?= $producto['nombre'] ?></h1>
                <p class="text-muted mb-4">Categoría: Salsas Artesanales</p>
                
                <p class="fs-5 text-secondary mb-4">
                    <?= $producto['descripcion'] ?>
                </p>

                <div class="mb-4">
                    <span class="product-price">$ <?= number_format($producto['precio'], 2, ',', '.') ?></span>
                </div>

                <div class="d-flex align-items-center mb-4">
                    <span class="me-3 fw-bold text-muted">Stock disponible:</span>
                    <div class="stock-circle"><?= $producto['stock'] ?></div>
                </div>

                <form action="<?= base_url('carrito/checkout') ?>" method="POST">
                    <input type="hidden" name="id" value="<?= $producto['id'] ?>">
                    <input type="hidden" name="nombre" value="<?= $producto['nombre'] ?>">
                    <input type="hidden" name="precio" value="<?= $producto['precio'] ?>">
                    <input type="hidden" name="stock" value="<?= $producto['stock'] ?>">
                    
                    <div class="d-grid">
                        <button type="submit" class="btn btn-salsa btn-lg shadow">
                            Hacer Pedido
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>