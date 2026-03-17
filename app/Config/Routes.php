<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

$routes->get('/', 'Home::index');
$routes->post('registrar-basico', 'Home::registrarBasico');
$routes->get('completar-datos/(:num)', 'Home::completarDatos/$1');
$routes->post('guardar-detalles', 'Home::guardarDetalles');
$routes->get('repararImagenes', 'Home::repararImagenes');
$routes->get('pedido', 'Home::pedido');

// Rutas del Carrito y Pago
$routes->post('carrito/checkout', 'Carrito::checkout');
$routes->get('carrito/checkout/(:num)', 'Carrito::checkout/$1'); 
$routes->post('carrito/procesarPago', 'Carrito::procesarPago');
$routes->get('pago/exitoso', 'Carrito::exito');
$routes->get('pago/fallido', 'Carrito::fallo');
$routes->post('pago/notificacion', 'Carrito::notificacion');

$routes->post('registrar-basico', 'Usuario::registrarBasico');
$routes->get('perfil', 'Usuario::perfil');
$routes->post('usuario/guardar-password', 'Usuario::guardarPassword');
$routes->post('usuario/subir-avatar', 'Usuario::subirAvatar');