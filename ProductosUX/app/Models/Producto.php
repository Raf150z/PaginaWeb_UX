<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $table = 'productos';

    // Relación con la tabla detalles_producto
    public function detalles()
    {
        return $this->hasOne(DetallesProducto::class);
    }

    // Relación con la tabla colores
    public function colores()
    {
        return $this->hasOne(ColoresProducto::class);
    }

    protected $fillable = [
        'nombre',
        'descripcion_corta',
        'categoria',
    ];

    public $timestamps = false;

}