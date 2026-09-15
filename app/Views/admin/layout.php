<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $titulo ?? 'Panel Superusuario' ?> - La Buona Salsa</title>
    
    <!-- Bootstrap 5 & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <style>
        :root {
            --salsa-bordo: #9B1212;
            --salsa-rojo: #fd580f;
            --salsa-amarillo: #f29418;
            --salsa-fondo: #f8f9fa;
            --salsa-dark: #1e1e24;
            --salsa-sidebar: #141419;
        }

        body {
            background-color: var(--salsa-fondo);
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            color: #333;
        }

        .admin-sidebar {
            width: 260px;
            min-height: 100vh;
            background: var(--salsa-sidebar);
            color: #fff;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1000;
            transition: all 0.3s ease;
            box-shadow: 4px 0 15px rgba(0,0,0,0.1);
        }

        .sidebar-brand {
            padding: 24px 20px;
            background: linear-gradient(135deg, #1e1e24 0%, #141419 100%);
            border-bottom: 1px solid rgba(255,255,255,0.08);
        }

        .sidebar-nav {
            padding: 20px 12px;
            list-style: none;
            margin: 0;
        }

        .sidebar-nav .nav-item {
            margin-bottom: 6px;
        }

        .sidebar-nav .nav-link {
            color: #a0a5b5;
            padding: 12px 16px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            gap: 12px;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .sidebar-nav .nav-link:hover {
            color: #fff;
            background: rgba(255,255,255,0.06);
            transform: translateX(3px);
        }

        .sidebar-nav .nav-link.active {
            color: #fff;
            background: linear-gradient(135deg, var(--salsa-bordo) 0%, var(--salsa-rojo) 100%);
            box-shadow: 0 4px 12px rgba(253, 88, 15, 0.3);
        }

        .sidebar-nav .nav-link i {
            font-size: 1.25rem;
        }

        .admin-main {
            margin-left: 260px;
            padding: 30px;
            min-height: 100vh;
        }

        .admin-topbar {
            background: #fff;
            border-radius: 16px;
            padding: 16px 24px;
            margin-bottom: 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.03);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .stat-card {
            background: #fff;
            border-radius: 18px;
            padding: 24px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.04);
            border: 1px solid rgba(0,0,0,0.03);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            position: relative;
            overflow: hidden;
        }

        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.08);
        }

        .stat-icon-badge {
            width: 54px;
            height: 54px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.6rem;
        }

        .bg-gradient-salsa {
            background: linear-gradient(135deg, var(--salsa-bordo) 0%, var(--salsa-rojo) 100%);
            color: #fff;
        }

        .bg-gradient-warning-salsa {
            background: linear-gradient(135deg, #f29418 0%, #fd580f 100%);
            color: #fff;
        }

        .bg-gradient-success-salsa {
            background: linear-gradient(135deg, #198754 0%, #20c997 100%);
            color: #fff;
        }

        .bg-gradient-info-salsa {
            background: linear-gradient(135deg, #0dcaf0 0%, #0d6efd 100%);
            color: #fff;
        }

        .data-card {
            background: #fff;
            border-radius: 18px;
            padding: 24px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.04);
            border: 1px solid rgba(0,0,0,0.03);
            margin-bottom: 24px;
        }

        .badge-pill-custom {
            padding: 6px 14px;
            border-radius: 50px;
            font-size: 0.82rem;
            font-weight: 600;
        }

        @media (max-width: 991px) {
            .admin-sidebar {
                margin-left: -260px;
            }
            .admin-sidebar.active {
                margin-left: 0;
            }
            .admin-main {
                margin-left: 0;
                padding: 16px;
            }
        }
    </style>
</head>
<body>

    <!-- Sidebar de Navegación -->
    <aside class="admin-sidebar" id="sidebar">
        <div class="sidebar-brand d-flex align-items-center justify-content-between">
            <a href="<?= base_url('admin/dashboard') ?>" class="d-flex align-items-center gap-2 text-decoration-none text-white">
                <img src="<?= base_url('assets/img/LOGO BORDO 1.png') ?>" alt="La Buona Salsa" height="36">
                <div>
                    <div class="fw-bold fs-6">La Buona Salsa</div>
                    <div class="badge bg-danger text-uppercase" style="font-size: 0.65rem;">Superusuario</div>
                </div>
            </a>
        </div>

        <ul class="sidebar-nav">
            <li class="nav-item">
                <a class="nav-link <?= uri_string() === 'admin/dashboard' || uri_string() === 'admin' ? 'active' : '' ?>" href="<?= base_url('admin/dashboard') ?>">
                    <i class="bi bi-speedometer2"></i>
                    <span>Dashboard & Rentabilidad</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= strpos(uri_string(), 'admin/productos') !== false ? 'active' : '' ?>" href="<?= base_url('admin/productos') ?>">
                    <i class="bi bi-box-seam"></i>
                    <span>Salsas y Stock</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= strpos(uri_string(), 'admin/envios') !== false ? 'active' : '' ?>" href="<?= base_url('admin/envios') ?>">
                    <i class="bi bi-truck"></i>
                    <span>Tarifas Delivery</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= strpos(uri_string(), 'admin/pedidos') !== false ? 'active' : '' ?>" href="<?= base_url('admin/pedidos') ?>">
                    <i class="bi bi-receipt"></i>
                    <span>Registro de Compras</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= strpos(uri_string(), 'admin/usuarios') !== false ? 'active' : '' ?>" href="<?= base_url('admin/usuarios') ?>">
                    <i class="bi bi-people"></i>
                    <span>Clientes Registrados</span>
                </a>
            </li>

            <hr class="border-secondary my-3 opacity-25">

            <li class="nav-item">
                <a class="nav-link text-white-50" href="<?= base_url() ?>" target="_blank">
                    <i class="bi bi-shop-window"></i>
                    <span>Ver Tienda Pública</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-danger" href="<?= base_url('admin/logout') ?>">
                    <i class="bi bi-box-arrow-right"></i>
                    <span>Cerrar Sesión</span>
                </a>
            </li>
        </ul>
    </aside>

    <!-- Contenido Principal -->
    <main class="admin-main">
        <!-- Topbar -->
        <div class="admin-topbar">
            <div class="d-flex align-items-center gap-3">
                <button class="btn btn-sm btn-outline-dark d-lg-none" id="btnToggleSidebar">
                    <i class="bi bi-list fs-5"></i>
                </button>
                <h4 class="mb-0 fw-bold"><?= $titulo ?? 'Panel de Administración' ?></h4>
            </div>

            <div class="d-flex align-items-center gap-3">
                <div class="text-end d-none d-sm-block">
                    <div class="fw-bold small"><?= esc(session()->get('nombre') ?? 'Superusuario') ?></div>
                    <div class="text-muted" style="font-size: 0.75rem;"><?= esc(session()->get('email') ?? '') ?></div>
                </div>
                <div class="stat-icon-badge bg-gradient-salsa" style="width: 42px; height: 42px; font-size: 1.2rem;">
                    <i class="bi bi-person-fill-gear"></i>
                </div>
            </div>
        </div>

        <!-- Alertas Flash -->
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success border-0 rounded-4 shadow-sm mb-4 alert-dismissible fade show">
                <i class="bi bi-check-circle-fill me-2"></i> <?= session()->getFlashdata('success') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger border-0 rounded-4 shadow-sm mb-4 alert-dismissible fade show">
                <i class="bi bi-exclamation-triangle-fill me-2"></i> <?= session()->getFlashdata('error') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- Contenido Específico -->
        <?= $this->renderSection('contenido') ?>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const btnToggle = document.getElementById('btnToggleSidebar');
        const sidebar = document.getElementById('sidebar');
        if (btnToggle && sidebar) {
            btnToggle.addEventListener('click', () => {
                sidebar.classList.toggle('active');
            });
        }
    </script>
</body>
</html>
