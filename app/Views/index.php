<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>La Buona Salsa - Sabor Artesanal Auténtico</title>
    
    <!-- Bootstrap 5 & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <!-- Custom Immersive Styles -->
    <link rel="stylesheet" href="<?= base_url('css/estilos.css') ?>">
</head>
<body>

    <!-- 1. Top Navbar (Barra Fija Superior con Registro / Perfil) -->
    <nav class="navbar navbar-expand-lg navbar-dark navbar-salsa">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center gap-2" href="<?= base_url() ?>">
                <img src="<?= base_url('assets/img/LOGO BORDO 1.png') ?>" alt="La Buona Salsa" class="nav-brand-img">
            </a>

            <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#navMain">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navMain">
                <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                    <li class="nav-item"><a class="nav-link text-white fw-semibold" href="#experiencia">Experiencia</a></li>
                    <li class="nav-item"><a class="nav-link text-white fw-semibold" href="#catalogo">Catálogo</a></li>
                    <li class="nav-item"><a class="nav-link text-white fw-semibold" href="#historia">Nuestra Receta</a></li>
                </ul>

                <!-- Acceso / Registro Rápido en Navbar -->
                <div class="d-flex align-items-center gap-3">
                    <?php if (!empty($isLoggedIn)): ?>
                        <?php if (session()->get('isAdmin')): ?>
                            <a href="<?= base_url('admin/dashboard') ?>" class="btn btn-sm btn-danger rounded-pill px-3 fw-bold shadow-sm">
                                <i class="bi bi-shield-lock-fill me-1"></i> Panel Admin
                            </a>
                        <?php endif; ?>
                        <a href="<?= base_url('perfil') ?>" class="user-pill">
                            <img src="<?= base_url('uploads/perfiles/' . ($avatar ?? 'default-user.png')) ?>" 
                                 onerror="this.src='https://cdn-icons-png.flaticon.com/512/847/847969.png'" 
                                 class="user-pill-avatar" alt="Avatar">
                            <span class="small fw-bold">Hola, <?= esc($nombre) ?></span>
                        </a>
                        <a href="<?= base_url('salir') ?>" class="btn btn-sm btn-outline-light rounded-pill px-3" title="Cerrar sesión">
                            <i class="bi bi-box-arrow-right"></i>
                        </a>
                    <?php else: ?>
                        <div class="d-flex align-items-center gap-2">
                            <button class="btn btn-sm btn-outline-light rounded-pill px-3 fw-bold" data-bs-toggle="modal" data-bs-target="#loginModal">
                                <i class="bi bi-box-arrow-in-right me-1"></i> Iniciar Sesión
                            </button>
                            <button class="btn btn-sm btn-salsa-gold rounded-pill px-3 fw-bold" data-bs-toggle="modal" data-bs-target="#registroModal">
                                <i class="bi bi-person-plus-fill me-1"></i> Registrarse
                            </button>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>

    <!-- Notificaciones Flash -->
    <?php if (session()->getFlashdata('success')): ?>
        <div class="container mt-3">
            <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 rounded-4" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i> <?= session()->getFlashdata('success') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('msg_info')): ?>
        <div class="container mt-3">
            <div class="alert alert-warning alert-dismissible fade show shadow-sm border-0 rounded-4" role="alert">
                <i class="bi bi-info-circle-fill me-2"></i> <?= session()->getFlashdata('msg_info') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="container mt-3">
            <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0 rounded-4" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i> <?= session()->getFlashdata('error') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('errors')): ?>
        <div class="container mt-3">
            <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0 rounded-4" role="alert">
                <h6 class="fw-bold mb-2"><i class="bi bi-exclamation-triangle-fill me-2"></i>Por favor revisa los siguientes campos:</h6>
                <ul class="mb-0 small">
                    <?php foreach (session()->getFlashdata('errors') as $err): ?>
                        <li><?= esc($err) ?></li>
                    <?php endforeach; ?>
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    <?php endif; ?>

    <!-- 2. Hero Section Inmersivo -->
    <header class="hero-section">
        <div class="container">
            <div class="row align-items-center min-vh-75">
                <div class="col-lg-6 mb-5 mb-lg-0">
                    <div class="hero-badge">
                        <i class="bi bi-stars text-warning"></i> Sabor 100% Artesanal & Natural
                    </div>
                    <h1 class="hero-title">
                        El auténtico sabor que <span class="text-gradient-salsa font-display">transforma</span> cada momento.
                    </h1>
                    <p class="hero-subtitle">
                        Elaborada a fuego lento con tomates madurados al sol, hierbas frescas de huerta y aceite de oliva virgen extra. Sin conservantes artificiales.
                    </p>
                    <div class="d-flex flex-wrap gap-3 align-items-center">
                        <button onclick="handleComprar(event, '<?= base_url('carrito/checkout/1') ?>', <?= !empty($isLoggedIn) ? 'true' : 'false' ?>)" class="btn-salsa-primary">
                            <i class="bi bi-bag-check-fill"></i> Comprar Ahora
                        </button>
                        <a href="#experiencia" class="btn-salsa-outline">
                            Explorar Inmersión <i class="bi bi-arrow-down-short fs-5"></i>
                        </a>
                    </div>

                    <div class="mt-4 pt-3 d-flex align-items-center gap-4 text-muted small">
                        <div><i class="bi bi-check-circle-fill text-success me-1"></i> Envío rápido (Rappi/DiDi/Uber)</div>
                        <div><i class="bi bi-shield-lock-fill text-danger me-1"></i> Compra 100% Segura</div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="hero-img-container">
                        <div class="hero-glow-bg"></div>
                        
                        <!-- Floating Badges Parallax -->
                        <div class="floating-card-tag tag-1">
                            <div class="d-flex align-items-center gap-2">
                                <span class="fs-4">🍅</span>
                                <div>
                                    <div class="fw-bold small text-dark">Tomates de Huerta</div>
                                    <div class="text-muted" style="font-size: 0.75rem;">Maduración natural</div>
                                </div>
                            </div>
                        </div>

                        <img src="<?= base_url('assets/img/producto1.png') ?>" alt="Salsa La Buona" class="hero-product-img">

                        <div class="floating-card-tag tag-2">
                            <div class="d-flex align-items-center gap-2">
                                <span class="fs-4">🌿</span>
                                <div>
                                    <div class="fw-bold small text-dark">Albahaca & Oliva</div>
                                    <div class="text-muted" style="font-size: 0.75rem;">Receta italiana de autor</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- 3. Experiencia Inmersiva de Scroll (Sticky Scroll Experience) -->
    <section id="experiencia" class="immersive-scroll-section">
        <div class="container">
            <div class="text-center mb-5 pb-3">
                <span class="badge bg-danger-subtle text-danger px-3 py-2 rounded-pill fw-bold mb-2">EXPERIENCIA INMERSIVA</span>
                <h2 class="display-5 fw-bold font-display">Descubre La Buona Salsa en Movimiento</h2>
                <p class="text-muted max-w-600 mx-auto">
                    Haz scroll hacia abajo para sumergirte en los secretos de nuestra elaboración paso a paso.
                </p>
            </div>

            <div class="sticky-showcase-container row">
                <!-- Columna Fija: Frasco con movimiento por scroll -->
                <div class="col-lg-6">
                    <div class="sticky-product-track">
                        <img src="<?= base_url('assets/img/producto1.png') ?>" 
                             alt="Frasco en movimiento" 
                             class="scroll-animated-jar">
                    </div>
                </div>

                <!-- Columna de Pasos y Relato -->
                <div class="col-lg-6">
                    <div class="story-step-card">
                        <div class="story-step-number">01</div>
                        <h3 class="fw-bold mb-3 text-gradient-salsa">Ingredientes Nobles y Frescura Pura</h3>
                        <p class="text-secondary mb-0">
                            Cosechamos los tomates en su punto justo de madurez aromática. Combinamos aceite de oliva virgen extra de primera prensada en frío y hojas de albahaca fresca recolectadas el mismo día.
                        </p>
                    </div>

                    <div class="story-step-card">
                        <div class="story-step-number">02</div>
                        <h3 class="fw-bold mb-3 text-gradient-salsa">Cocción Lenta y Tradicional</h3>
                        <p class="text-secondary mb-0">
                            Sin prisas ni químicos. Nuestra salsa reduce suavemente durante horas en ollas de hierro, concentrando los azúcares naturales del tomate hasta alcanzar una textura espesa y untuosa.
                        </p>
                    </div>

                    <div class="story-step-card">
                        <div class="story-step-number">03</div>
                        <h3 class="fw-bold mb-3 text-gradient-salsa">El Toque Secreto en Tu Mesa</h3>
                        <p class="text-secondary mb-3">
                            Ideal para bañar pastas artesanales, coronar pizzas crocantes o disfrutar como dip con focaccias y panes de masa madre.
                        </p>
                        <button onclick="handleComprar(event, '<?= base_url('carrito/checkout/1') ?>', <?= !empty($isLoggedIn) ? 'true' : 'false' ?>)" class="btn btn-sm btn-danger rounded-pill px-4 fw-bold">
                            Probar este sabor <i class="bi bi-arrow-right ms-1"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 4. Catálogo de Productos con Efecto 3D Tilt -->
    <section id="catalogo" class="catalog-section">
        <div class="container">
            <div class="d-flex flex-wrap justify-content-between align-items-end mb-5">
                <div>
                    <span class="badge bg-warning-subtle text-warning-emphasis px-3 py-2 rounded-pill fw-bold mb-2">NUESTRA COLECCIÓN</span>
                    <h2 class="display-6 fw-bold">Elige Tu Variedad Favorita</h2>
                </div>
                <div>
                    <button class="btn btn-outline-dark rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#flyerModal">
                        <i class="bi bi-card-image me-1"></i> Ver Sugerencias de Menú
                    </button>
                </div>
            </div>

            <div class="row g-4">
                <?php foreach ($productos as $item): ?>
                    <div class="col-md-6 col-lg-4">
                        <div class="product-tilt-card">
                            <div class="card-img-wrapper">
                                <span class="stock-badge">
                                    <i class="bi bi-box-seam me-1"></i> <?= $item['stock'] ?> disponibles
                                </span>
                                <img src="<?= base_url('assets/img/' . ($item['imagen_principal'] ?? 'producto1.png')) ?>" 
                                     alt="<?= esc($item['nombre']) ?>" 
                                     onerror="this.src='<?= base_url('assets/img/producto1.png') ?>'">
                            </div>

                            <div class="product-info">
                                <div>
                                    <h4 class="fw-bold mb-2"><?= esc($item['nombre']) ?></h4>
                                    <p class="text-muted small mb-3">
                                        <?= esc($item['descripcion']) ?>
                                    </p>
                                </div>

                                <div>
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <span class="text-muted small fw-bold">PRECIO UNITARIO</span>
                                        <span class="product-price-tag">$ <?= number_format($item['precio'], 2, ',', '.') ?></span>
                                    </div>

                                    <div class="d-grid gap-2">
                                        <button onclick="handleComprar(event, '<?= base_url('carrito/checkout/' . $item['id']) ?>', <?= !empty($isLoggedIn) ? 'true' : 'false' ?>)" 
                                                class="btn btn-salsa-primary w-100 justify-content-center">
                                            <i class="bi bi-cart-plus-fill"></i> Hacer Pedido
                                        </button>
                                        <a href="<?= base_url('pedido') ?>" class="btn btn-sm btn-link text-decoration-none text-muted">
                                            Ver detalles completos <i class="bi bi-chevron-right"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- 5. Modal Iniciar Sesión -->
    <div class="modal fade modal-salsa" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-salsa-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="modal-title fw-bold mb-0" id="loginModalLabel">
                            <i class="bi bi-box-arrow-in-right me-2"></i> Iniciar Sesión
                        </h4>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <p class="small text-white-50 mb-0 mt-2">
                        Accede a tu cuenta para ordenar salsas y gestionar tus pedidos.
                    </p>
                </div>
                <div class="modal-body p-4 bg-white">
                    <form action="<?= base_url('login') ?>" method="POST">
                        <?= csrf_field() ?>
                        <div class="mb-3">
                            <label class="form-label fw-bold text-dark small">Correo Electrónico</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="bi bi-envelope text-muted"></i></span>
                                <input type="email" name="email" class="form-control salsa-input border-start-0" placeholder="tu@correo.com" required autocomplete="username">
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold text-dark small mb-1">Contraseña</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="bi bi-key text-muted"></i></span>
                                <input type="password" id="login_password" name="password" class="form-control salsa-input border-start-0 border-end-0" placeholder="Tu contraseña" required autocomplete="current-password">
                                <button type="button" class="input-group-text bg-light border-start-0 text-muted" onclick="togglePasswordVisibility('login_password', this)">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-salsa-primary w-100 justify-content-center py-3 mb-3">
                            <i class="bi bi-box-arrow-in-right me-2"></i> Ingresar a mi Cuenta
                        </button>

                        <div class="text-center pt-2 border-top">
                            <span class="small text-muted">¿Aún no tienes cuenta?</span>
                            <a href="javascript:void(0)" class="small fw-bold text-decoration-none ms-1 text-danger" data-bs-dismiss="modal" data-bs-toggle="modal" data-bs-target="#registroModal">
                                Regístrate aquí
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Registro Seguro con Contraseña y Validación de Correo -->
    <div class="modal fade modal-salsa" id="registroModal" tabindex="-1" aria-labelledby="registroModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-salsa-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="modal-title fw-bold mb-0" id="registroModalLabel">
                            <i class="bi bi-shield-lock-fill me-2"></i> Registro Seguro
                        </h4>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <p class="small text-white-50 mb-0 mt-2">
                        Crea tu cuenta protegida con contraseña y verificación de correo activo.
                    </p>
                </div>
                <div class="modal-body p-4 bg-white">
                    <form action="<?= base_url('registrar-basico') ?>" method="POST">
                        <?= csrf_field() ?>
                        <div class="mb-3">
                            <label class="form-label fw-bold text-dark small">Nombre Completo</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="bi bi-person text-muted"></i></span>
                                <input type="text" name="nombre" class="form-control salsa-input border-start-0" placeholder="Ej: Jean Almenar" required autocomplete="name">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold text-dark small">Correo Electrónico (Debe estar activo)</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="bi bi-envelope text-muted"></i></span>
                                <input type="email" name="email" class="form-control salsa-input border-start-0" placeholder="ejemplo@correo.com" required autocomplete="email">
                            </div>
                            <div class="form-text text-muted" style="font-size: 0.78rem;">
                                <i class="bi bi-shield-check me-1 text-success"></i> Te enviaremos un código de seguridad de 6 dígitos a este correo para validarlo.
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold text-dark small">Contraseña (Mínimo 6 caracteres)</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="bi bi-lock text-muted"></i></span>
                                <input type="password" id="reg_password" name="password" class="form-control salsa-input border-start-0 border-end-0" placeholder="••••••••" required minlength="6" autocomplete="new-password">
                                <button type="button" class="input-group-text bg-light border-start-0 text-muted" onclick="togglePasswordVisibility('reg_password', this)">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold text-dark small">Confirmar Contraseña</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="bi bi-lock-fill text-muted"></i></span>
                                <input type="password" id="reg_password_confirm" name="password_confirm" class="form-control salsa-input border-start-0 border-end-0" placeholder="••••••••" required minlength="6" autocomplete="new-password">
                                <button type="button" class="input-group-text bg-light border-start-0 text-muted" onclick="togglePasswordVisibility('reg_password_confirm', this)">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-salsa-primary w-100 justify-content-center py-3 mb-3">
                            <i class="bi bi-envelope-check me-2"></i> Crear Cuenta y Validar Email
                        </button>

                        <div class="text-center pt-2 border-top">
                            <span class="small text-muted">¿Ya tienes cuenta creada?</span>
                            <a href="javascript:void(0)" class="small fw-bold text-decoration-none ms-1 text-danger" data-bs-dismiss="modal" data-bs-toggle="modal" data-bs-target="#loginModal">
                                Iniciar sesión
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- 6. Modal Flyer Menú -->
    <div class="modal fade" id="flyerModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content rounded-4 border-0 overflow-hidden">
                <div class="modal-header border-0 bg-dark text-white">
                    <h5 class="modal-title fw-bold">Sugerencias y Usos de La Buona Salsa</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-0 text-center bg-black">
                    <img src="<?= base_url('assets/img/flyer.jpg') ?>" class="img-fluid" alt="Flyer La Buona Salsa">
                </div>
            </div>
        </div>
    </div>

    <!-- 7. Footer Premium -->
    <footer class="footer-salsa">
        <div class="container">
            <div class="row g-4 align-items-center">
                <div class="col-md-5 text-center text-md-start">
                    <img src="<?= base_url('assets/img/LOGO BORDO 1.png') ?>" alt="Logo Salsa" height="48" class="mb-3">
                    <p class="text-white-50 small mb-0">
                        La Buona Salsa es una marca registrada de salsas gourmet artesanales. Comprometidos con el sabor auténtico, ingredientes de primera calidad y recetas que unen a la familia.
                    </p>
                </div>

                <div class="col-md-4 text-center">
                    <h6 class="text-uppercase fw-bold text-warning mb-3">Atención y Pedidos</h6>
                    <p class="small text-white-50 mb-1">
                        <i class="bi bi-envelope-fill me-1"></i> <a href="mailto:info@labuonasalsa.com" class="text-white-50 text-decoration-none">info@labuonasalsa.com</a>
                    </p>
                    <p class="small text-white-50">
                        <i class="bi bi-geo-alt-fill me-1"></i> Envíos en el día vía Rappi, DiDi y Uber Flash.
                    </p>
                </div>

                <div class="col-md-3 text-center text-md-end">
                    <h6 class="text-uppercase fw-bold text-warning mb-3">Síguenos</h6>
                    <div class="d-flex justify-content-center justify-content-md-end gap-2">
                        <a href="#" class="footer-social-icon" title="Instagram"><i class="bi bi-instagram"></i></a>
                        <a href="#" class="footer-social-icon" title="Facebook"><i class="bi bi-facebook"></i></a>
                        <a href="#" class="footer-social-icon" title="WhatsApp"><i class="bi bi-whatsapp"></i></a>
                    </div>
                </div>
            </div>

            <hr class="border-secondary my-4">

            <div class="text-center text-white-50 small">
                © <?= date('Y') ?> La Buona Salsa. Todos los derechos reservados. · 
                <a href="<?= base_url('admin/login') ?>" class="text-white-50 text-decoration-none hover-underline">
                    <i class="bi bi-shield-lock me-1"></i>Acceso Superusuario
                </a>
            </div>
        </div>
    </footer>

    <!-- Bootstrap Bundle JS & Immersive Script -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?= base_url('js/immersive.js') ?>"></script>
    <script>
        function togglePasswordVisibility(inputId, btn) {
            const input = document.getElementById(inputId);
            const icon = btn.querySelector('i');
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('bi-eye');
                icon.classList.add('bi-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('bi-eye-slash');
                icon.classList.add('bi-eye');
            }
        }
    </script>
</body>
</html>