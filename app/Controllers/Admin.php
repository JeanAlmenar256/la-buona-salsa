<?php

namespace App\Controllers;

use App\Models\UsuarioModel;
use App\Models\ProductoModel;
use App\Models\PedidoModel;
use App\Models\EnvioModel;

class Admin extends BaseController
{
    /**
     * Pantalla de Login de Superusuario
     */
    public function login()
    {
        $session = session();
        if ($session->get('isAdmin')) {
            return redirect()->to(base_url('admin/dashboard'));
        }

        return view('admin/login');
    }

    /**
     * Procesar Login de Superusuario
     */
    public function autenticar()
    {
        $email    = trim($this->request->getPost('email') ?? '');
        $password = trim($this->request->getPost('password') ?? '');

        if (empty($email) || empty($password)) {
            return redirect()->back()->with('error', 'Por favor ingresa tu email y contraseña.');
        }

        $usuarioModel = new UsuarioModel();
        $user = $usuarioModel->where('email', $email)->first();

        if (!$user) {
            return redirect()->back()->with('error', 'Credenciales incorrectas o usuario no encontrado.');
        }

        // Verificar si es superadmin o admin
        if (!in_array($user['rol'] ?? 'cliente', ['admin', 'superadmin'], true)) {
            return redirect()->back()->with('error', 'No tienes permisos de superusuario para ingresar a este panel.');
        }

        // Verificar hash de contraseña
        $passwordValida = false;
        if (!empty($user['password_hash'])) {
            $passwordValida = password_verify($password, $user['password_hash']);
        } elseif ($password === 'L2b3t*nO5g4t215' && $email === 'jean.almenar@labuonasalsa.com.ar') {
            // Migración automática al hash seguro si aún no tenía hash
            $passwordValida = true;
            $usuarioModel->update($user['id'], [
                'password_hash' => password_hash($password, PASSWORD_DEFAULT),
                'rol'           => 'superadmin'
            ]);
        }

        if (!$passwordValida) {
            return redirect()->back()->with('error', 'Contraseña incorrecta.');
        }

        // Establecer sesión de Superusuario
        session()->set([
            'usuario_id' => $user['id'],
            'admin_id'   => $user['id'],
            'nombre'     => $user['nombre'],
            'email'      => $user['email'],
            'rol'        => $user['rol'] ?? 'superadmin',
            'isAdmin'    => true,
            'isLoggedIn' => true
        ]);

        return redirect()->to(base_url('admin/dashboard'))->with('success', '¡Bienvenido al Panel de Administración, ' . esc($user['nombre']) . '!');
    }

    /**
     * Cerrar sesión de Superusuario
     */
    public function logout()
    {
        $session = session();
        $session->remove(['admin_id', 'isAdmin', 'rol']);
        return redirect()->to(base_url('admin/login'))->with('info', 'Has cerrado sesión correctamente.');
    }

    /**
     * Dashboard Ejecutivo / Métricas Financieras
     */
    public function dashboard()
    {
        $pedidoModel   = new PedidoModel();
        $productoModel = new ProductoModel();
        $usuarioModel  = new UsuarioModel();

        $metricas     = $pedidoModel->getMetricasFinancieras();
        $pedidosRec   = $pedidoModel->getPedidosConDetalles(10);
        $productos    = $productoModel->findAll();
        $totalClientes= $usuarioModel->where('rol !=', 'superadmin')->countAllResults();

        return view('admin/dashboard', [
            'metricas'      => $metricas,
            'pedidos'       => $pedidosRec,
            'productos'     => $productos,
            'totalClientes' => $totalClientes
        ]);
    }

    /**
     * Gestión de Productos, Precios, Costos y Stock
     */
    public function productos()
    {
        $productoModel = new ProductoModel();
        $productos = $productoModel->findAll();

        return view('admin/productos', [
            'productos' => $productos
        ]);
    }

    /**
     * Guardar cambios en un producto (Precio, Costo, Stock)
     */
    public function guardarProducto()
    {
        $id = (int)$this->request->getPost('id');
        $productoModel = new ProductoModel();

        $producto = $productoModel->find($id);
        if (!$producto) {
            return redirect()->back()->with('error', 'Producto no encontrado.');
        }

        $precio = (float)$this->request->getPost('precio');
        $costo  = (float)$this->request->getPost('costo_produccion');
        $stock  = (int)$this->request->getPost('stock');
        $nombre = trim($this->request->getPost('nombre') ?? $producto['nombre']);
        $desc   = trim($this->request->getPost('descripcion') ?? $producto['descripcion']);

        if ($precio <= 0) {
            return redirect()->back()->with('error', 'El precio debe ser mayor a 0.');
        }

        $productoModel->update($id, [
            'nombre'           => $nombre,
            'descripcion'      => $desc,
            'precio'           => $precio,
            'costo_produccion' => $costo,
            'stock'            => max(0, $stock)
        ]);

        return redirect()->to(base_url('admin/productos'))->with('success', '¡Producto "' . esc($nombre) . '" actualizado con éxito!');
    }

    /**
     * Gestión de Tarifas de Envío (Rappi, DiDi, Uber, etc.)
     */
    public function envios()
    {
        $envioModel = new EnvioModel();
        $envios = $envioModel->findAll();

        if (empty($envios)) {
            $envios = $envioModel->getTarifasActivas();
        }

        return view('admin/envios', [
            'envios' => $envios
        ]);
    }

    /**
     * Guardar tarifas de envío
     */
    public function guardarEnvio()
    {
        $id     = (int)$this->request->getPost('id');
        $costo  = (float)$this->request->getPost('costo');
        $tiempo = trim($this->request->getPost('tiempo_estimado') ?? '');
        $activo = (int)($this->request->getPost('activo') ?? 1);

        $envioModel = new EnvioModel();
        $envio = $envioModel->find($id);

        if ($envio) {
            $envioModel->update($id, [
                'costo'           => max(0, $costo),
                'tiempo_estimado' => $tiempo,
                'activo'          => $activo
            ]);
            $nombreEmpresa = $envio['empresa'];
        } else {
            $empresa = trim($this->request->getPost('empresa') ?? 'Nuevo Envío');
            $envioModel->insert([
                'empresa'         => $empresa,
                'costo'           => max(0, $costo),
                'tiempo_estimado' => $tiempo,
                'activo'          => 1
            ]);
            $nombreEmpresa = $empresa;
        }

        return redirect()->to(base_url('admin/envios'))->with('success', 'Tarifa de "' . esc($nombreEmpresa) . '" actualizada correctamente.');
    }

    /**
     * Registro de Compras Realizadas
     */
    public function pedidos()
    {
        $pedidoModel = new PedidoModel();
        $pedidos = $pedidoModel->getPedidosConDetalles(100);

        return view('admin/pedidos', [
            'pedidos' => $pedidos
        ]);
    }

    /**
     * Listado de Personas Registradas
     */
    public function usuarios()
    {
        $usuarioModel = new UsuarioModel();
        $db = \Config\Database::connect();

        // Obtener clientes junto con cantidad de pedidos realizados y total gastado
        $usuarios = $db->table('usuarios')
            ->select('usuarios.*, COUNT(pedidos.id) as total_compras, COALESCE(SUM(pedidos.total), 0) as total_gastado')
            ->join('pedidos', 'pedidos.usuario_id = usuarios.id AND pedidos.estado_pago = "aprobado"', 'left')
            ->where('usuarios.rol !=', 'superadmin')
            ->groupBy('usuarios.id')
            ->orderBy('usuarios.id', 'DESC')
            ->get()
            ->getResultArray();

        return view('admin/usuarios', [
            'usuarios' => $usuarios
        ]);
    }
}
