<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetallesProducto extends Model
{
    use HasFactory;

    protected $table = 'detalles_producto';

    public $timestamps = false;

    protected $fillable = [
        'producto_id',
        'precio_unitario',
        'unidades_stock',
    ];

}

