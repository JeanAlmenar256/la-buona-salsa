<?php

namespace App\Models;

use CodeIgniter\Model;

class UsuarioModel extends Model
{
    protected $table            = 'usuarios';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'nombre', 'email', 'direccion', 'telefono', 
        'entre_calle_1', 'entre_calle_2', 'cp', 'avatar',
        'rol', 'password_hash', 'email_verificado',
        'token_verificacion', 'token_creado_at'
    ];

    /**
     * Verificar si un usuario tiene permisos de administrador
     */
    public function esAdmin($usuarioId): bool
    {
        $user = $this->find($usuarioId);
        return $user && in_array($user['rol'] ?? 'cliente', ['admin', 'superadmin'], true);
    }

    /**
     * Autenticar cliente regular por email y contraseña
     */
    public function autenticarCliente(string $email, string $password): ?array
    {
        $user = $this->where('email', trim($email))->first();
        if (!$user) {
            return null;
        }

        // Verificar contraseña hasheada
        if (!empty($user['password_hash']) && password_verify($password, $user['password_hash'])) {
            return $user;
        }

        // Compatibilidad con registros antiguos en texto plano
        if (!empty($user['password']) && $user['password'] === $password) {
            // Actualizar a hash seguro de forma transparente
            $this->update($user['id'], [
                'password_hash' => password_hash($password, PASSWORD_DEFAULT)
            ]);
            $user['password_hash'] = password_hash($password, PASSWORD_DEFAULT);
            return $user;
        }

        return null;
    }

    /**
     * Generar y guardar código de 6 dígitos y token seguro para verificación de email
     */
    public function generarTokenVerificacion(int $usuarioId): array
    {
        $codigo = sprintf('%06d', mt_rand(100000, 999999));
        $token  = bin2hex(random_bytes(20));
        $dbToken = $codigo . ':' . $token;

        $this->update($usuarioId, [
            'token_verificacion' => $dbToken,
            'token_creado_at'    => date('Y-m-d H:i:s'),
            'email_verificado'   => 0
        ]);

        return [
            'codigo' => $codigo,
            'token'  => $token
        ];
    }

    /**
     * Verificar cuenta mediante código numérico de 6 dígitos
     */
    public function verificarPorCodigo(int $usuarioId, string $codigo): bool
    {
        $user = $this->find($usuarioId);
        if (!$user || empty($user['token_verificacion'])) {
            return false;
        }

        $partes = explode(':', $user['token_verificacion']);
        $codigoGuardado = $partes[0] ?? '';

        if (trim($codigo) === $codigoGuardado) {
            $this->update($usuarioId, [
                'email_verificado'   => 1,
                'token_verificacion' => null,
                'token_creado_at'    => null
            ]);
            return true;
        }

        return false;
    }

    /**
     * Verificar cuenta mediante token de enlace directo
     */
    public function verificarPorToken(string $token): ?array
    {
        $users = $this->where('email_verificado', 0)->findAll();
        foreach ($users as $user) {
            if (!empty($user['token_verificacion'])) {
                $partes = explode(':', $user['token_verificacion']);
                $tokenGuardado = $partes[1] ?? '';
                if ($tokenGuardado === $token) {
                    $this->update($user['id'], [
                        'email_verificado'   => 1,
                        'token_verificacion' => null,
                        'token_creado_at'    => null
                    ]);
                    $user['email_verificado'] = 1;
                    return $user;
                }
            }
        }
        return null;
    }

    /**
     * Autenticar superusuario por email y contraseña
     */
    public function autenticarSuperusuario(string $email, string $password): ?array
    {
        $user = $this->where('email', trim($email))->first();
        if (!$user) {
            return null;
        }

        // Verificar rol
        if (!in_array($user['rol'] ?? 'cliente', ['admin', 'superadmin'], true)) {
            return null;
        }

        // Verificar contraseña con password_verify
        if (!empty($user['password_hash']) && password_verify($password, $user['password_hash'])) {
            return $user;
        }

        return null;
    }
    

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];
}
