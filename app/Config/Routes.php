<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

$routes->get('/', 'Home::index');
$routes->post('registrar-basico', 'Home::registrarBasico');
$routes->get('registrar-basico', static function() { return redirect()->to(base_url('/')); });
$routes->post('login', 'Home::login');
$routes->get('login', static function() { return redirect()->to(base_url('/')); });
$routes->get('verificar-email', 'Home::mostrarVerificacion');
$routes->get('verificar-email/(:segment)', 'Home::verificarEmail/$1');
$routes->post('verificar-codigo', 'Home::procesarCodigoVerificacion');
$routes->post('reenviar-verificacion', 'Home::reenviarVerificacion');
$routes->get('completar-datos/(:num)', 'Home::completarDatos/$1');
$routes->post('guardar-detalles', 'Home::guardarDetalles');
$routes->get('repararImagenes', 'Home::repararImagenes');
$routes->get('pedido', 'Home::pedido');
$routes->get('salir', 'Home::salir');

// Rutas del Carrito y Pago
$routes->post('carrito/checkout', 'Carrito::checkout');
$routes->get('carrito/checkout/(:num)', 'Carrito::checkout/$1'); 
$routes->post('carrito/procesarPago', 'Carrito::procesarPago');
$routes->get('pago/exitoso', 'Carrito::exito');
$routes->get('pago/fallido', 'Carrito::fallo');

// Rutas de Usuario
$routes->get('perfil', 'Usuario::perfil');
$routes->post('usuario/subir-avatar', 'Usuario::subirAvatar');

// Rutas de Superusuario / Administración
$routes->get('admin/login', 'Admin::login');
$routes->post('admin/autenticar', 'Admin::autenticar');
$routes->get('admin/logout', 'Admin::logout');

$routes->group('admin', ['filter' => 'adminAuth'], static function ($routes) {
    $routes->get('/', 'Admin::dashboard');
    $routes->get('dashboard', 'Admin::dashboard');
    $routes->get('productos', 'Admin::productos');
    $routes->post('productos/guardar', 'Admin::guardarProducto');
    $routes->get('envios', 'Admin::envios');
    $routes->post('envios/guardar', 'Admin::guardarEnvio');
    $routes->get('pedidos', 'Admin::pedidos');
    $routes->get('usuarios', 'Admin::usuarios');
});