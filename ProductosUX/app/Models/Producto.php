<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $table = 'productos'; // Asegúrate de que coincida con el nombre de la tabla

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
        // Agrega aquí otros campos que necesites
    ];

    public $timestamps = false;

}