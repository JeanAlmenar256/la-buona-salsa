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
        'rol', 'password_hash'
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
