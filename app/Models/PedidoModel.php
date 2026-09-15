<?php

namespace App\Models;

use CodeIgniter\Model;

class PedidoModel extends Model
{
    protected $table            = 'pedidos';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'usuario_id',
        'producto_id',
        'cantidad',
        'precio_unitario',
        'costo_produccion_unitario',
        'metodo_envio',
        'costo_envio',
        'total',
        'ganancia_neta',
        'estado_pago',
        'metodo_pago',
        'mp_payment_id',
        'created_at'
    ];

    protected $useTimestamps = false;

    /**
     * Obtener listado de pedidos con datos del cliente y producto
     */
    public function getPedidosConDetalles(int $limit = 50): array
    {
        return $this->select('pedidos.*, usuarios.nombre as cliente_nombre, usuarios.email as cliente_email, usuarios.telefono as cliente_telefono, usuarios.direccion as cliente_direccion, productos.nombre as producto_nombre')
            ->join('usuarios', 'usuarios.id = pedidos.usuario_id', 'left')
            ->join('productos', 'productos.id = pedidos.producto_id', 'left')
            ->orderBy('pedidos.id', 'DESC')
            ->limit($limit)
            ->findAll();
    }

    /**
     * Calcular métricas financieras globales de rentabilidad
     */
    public function getMetricasFinancieras(): array
    {
        $db = \Config\Database::connect();
        
        $builder = $db->table($this->table);
        $builder->select('
            COUNT(id) as total_pedidos,
            COALESCE(SUM(cantidad), 0) as frascos_vendidos,
            COALESCE(SUM(total), 0) as ingresos_brutos,
            COALESCE(SUM(costo_produccion_unitario * cantidad), 0) as costo_total_produccion,
            COALESCE(SUM(costo_envio), 0) as costo_total_envios,
            COALESCE(SUM(ganancia_neta), 0) as ganancia_neta_total
        ');
        $builder->where('estado_pago', 'aprobado');
        $query = $builder->get()->getRowArray();

        $ingresos = (float)($query['ingresos_brutos'] ?? 0);
        $costoProd = (float)($query['costo_total_produccion'] ?? 0);
        $costoEnvio = (float)($query['costo_total_envios'] ?? 0);
        $ganancia = (float)($query['ganancia_neta_total'] ?? 0);

        // Si total sin envíos para margen de producto:
        $ingresoNetoSalsas = $ingresos - $costoEnvio;
        $margenRentabilidad = ($ingresoNetoSalsas > 0) 
            ? round(($ganancia / $ingresoNetoSalsas) * 100, 1) 
            : 0;

        return [
            'total_pedidos'           => (int)($query['total_pedidos'] ?? 0),
            'frascos_vendidos'        => (int)($query['frascos_vendidos'] ?? 0),
            'ingresos_brutos'         => $ingresos,
            'costo_total_produccion'  => $costoProd,
            'costo_total_envios'      => $costoEnvio,
            'ganancia_neta_total'     => $ganancia,
            'margen_rentabilidad'     => $margenRentabilidad
        ];
    }
}
