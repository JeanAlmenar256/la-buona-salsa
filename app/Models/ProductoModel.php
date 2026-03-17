<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductoModel extends Model
{
    // Nombre de la tabla en tu base de datos (asegurate que sea este)
    protected $table      = 'productos'; 
    
    // Tu llave primaria
    protected $primaryKey = 'id';

    // Los campos que el sistema tiene permiso de leer/escribir
    protected $allowedFields = ['nombre', 'descripcion', 'precio', 'stock', 'imagen_principal', 'imagen_2', 'imagen_3'];

    // Esto nos devuelve los datos en formato de array, que es lo que espera el controlador
    protected $returnType = 'array';
}