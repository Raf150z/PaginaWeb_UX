<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;
use App\Models\DetallesProducto;
use App\Models\ColoresProducto;

class ProductoController extends Controller
{
    public function index()
    {
        $productos = Producto::with('detalles')->get();
        return view('inicio', compact('productos'));
        
    }

//     public function index()
// {
//     $productos = collect([
//         (object) [
//             'id' => 1,
//             'nombre' => 'Producto de prueba',
//             'descripcion_corta' => 'Descripción de prueba',
//             'categoria' => 'Electrónica',
//             'detalles' => (object) [
//                 'precio_unitario' => 99.99,
//                 'unidades_stock' => 10,
//                 'fecha_ultima_actualizacion' => now(),
//             ],
//             'colores' => (object) [
//                 'color_hex' => '#FF0000',
//             ],
//         ],
//     ]);

//     return view('inicio', compact('productos'));
// }


    public function show($id)
    {
        $producto = Producto::with('detalles')->findOrFail($id);
        return response()->json($producto);
    }
    
    public function update(Request $request, $id)
    {
        $producto = Producto::findOrFail($id);
        $producto->nombre = $request->nombre;
        $producto->descripcion_corta = $request->descripcion;
        $producto->categoria = $request->categoria;
        $producto->detalles->precio_unitario = $request->precio_unitario;
        $producto->detalles->unidades_stock = $request->unidades_stock;
        $producto->detalles->save();
        $producto->save();
    
        return redirect('/')->with('success', 'Producto actualizado correctamente.');
    }
    

    public function destroy($id)
    {
        $producto = Producto::findOrFail($id);
        $producto->delete();
    
        return response()->json(['success' => true]); // Respuesta JSON para el frontend
    }

    public function mostrarProductos()
{
    $productos = Producto::all(); // Obtén todos los productos desde la base de datos
    return view('inicio', compact('productos')); // Pasa la variable productos a la vista
}



public function guardarProducto(Request $request)
{
    $producto = Producto::create($request->only(['nombre', 'descripcion_corta', 'categoria']));
    return response()->json(['id' => $producto->id]);
}

public function guardarDetalles(Request $request)
{
    $detalles = DetallesProducto::create([
        'producto_id' => $request->producto_id,
        'precio_unitario' => $request->precio_unitario,
        'unidades_stock' => $request->unidades_stock,
    ]);

    return response()->json(['success' => true]);
}

public function guardarColor(Request $request)
{
    $color = ColoresProducto::create($request->only(['producto_id', 'color_hex']));
    return response()->json(['success' => true]);
}

public function mostrarFormularioAgregar()
{
    return view('agregar');
}

public function obtenerDatosResumen($id)
{
    $producto = Producto::with(['detalles', 'colores'])->findOrFail($id);
    return response()->json($producto);
}

public function finalizarProceso(Request $request)
{
    // Aquí puedes realizar acciones adicionales, como enviar un correo, generar un PDF, etc.
    // Por ahora, simplemente devolvemos una respuesta de éxito.

    return response()->json(['success' => true]);
}
    
}
