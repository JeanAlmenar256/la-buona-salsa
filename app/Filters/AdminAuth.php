<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AdminAuth implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();
        $isAdmin = $session->get('isAdmin') ?? false;
        $rol     = $session->get('rol') ?? '';

        if (!$isAdmin || !in_array($rol, ['admin', 'superadmin'], true)) {
            return redirect()->to(base_url('admin/login'))->with('error', 'Debes iniciar sesión como superusuario para acceder a esta sección.');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // No action needed after request
    }
}
