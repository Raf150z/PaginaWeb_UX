<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ColoresProducto extends Model
{
    use HasFactory;

    protected $table = 'colores_producto';

    public $timestamps = false;

    protected $fillable = [
        'producto_id',
        'color_hex',
        // Agrega aquí otros campos que necesites
    ];

}
