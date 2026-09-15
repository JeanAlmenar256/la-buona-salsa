<?php

namespace App\Models;

use CodeIgniter\Model;

class EnvioModel extends Model
{
    protected $table            = 'configuracion_envios';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'empresa',
        'costo',
        'activo',
        'tiempo_estimado',
        'icono'
    ];

    /**
     * Obtener todas las tarifas activas ordenadas
     */
    public function getTarifasActivas(): array
    {
        $tarifas = $this->where('activo', 1)->orderBy('costo', 'ASC')->findAll();

        if (empty($tarifas)) {
            // Fallback por defecto si aún no se inicializó la tabla
            return [
                ['id' => 1, 'empresa' => 'Rappi', 'costo' => 1200.00, 'activo' => 1, 'tiempo_estimado' => '30-45 min'],
                ['id' => 2, 'empresa' => 'DiDi', 'costo' => 950.00, 'activo' => 1, 'tiempo_estimado' => '40-50 min'],
                ['id' => 3, 'empresa' => 'Uber', 'costo' => 1200.00, 'activo' => 1, 'tiempo_estimado' => '30-40 min'],
                ['id' => 4, 'empresa' => 'Retiro en local', 'costo' => 0.00, 'activo' => 1, 'tiempo_estimado' => 'Inmediato']
            ];
        }

        return $tarifas;
    }

    /**
     * Obtener costo por nombre de empresa
     */
    public function getCostoEmpresa(string $empresa): float
    {
        $reg = $this->where('empresa', $empresa)->first();
        if ($reg) {
            return (float)$reg['costo'];
        }

        if ($empresa === 'DiDi') return 950.00;
        if ($empresa === 'Retiro en local') return 0.00;
        return 1200.00;
    }
}
