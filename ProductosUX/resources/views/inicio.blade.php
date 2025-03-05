@extends('plantillas.app')

@section('title', 'Gestión de productos')

@section('contenido')

<h1>Gestion de productos</h1>
<div class="contenedorAcordion">
    <div class="accordion" id="accordionExample">
        @if ($productos->isEmpty())
            <p>No hay productos registrados</p>
        @else            
            @foreach ($productos as $producto)
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <div class="d-flex align-items-center w-100" style="background-color: {{ $producto->colores->color_hex ?? '#FFFFFF' }};">
                            <button class="accordion-button flex-grow-1 text-start" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $producto->id }}" aria-expanded="true" aria-controls="collapse{{ $producto->id }}" style="background-color: transparent; color: white; width: 80%;">
                                {{ $producto->nombre }}
                            </button>
                            <div class="btn-group" style="width: 20%;">
                                <button class="btn btn-warning btn-sm me-4 border border-white rounded" style="border-width: 2px !important;" onclick="abrirModalEditar({{ $producto->id }})">
                                    Editar
                                </button>
                                
                                <button class="btn btn-danger btn-sm me-4 border border-white rounded" style="border-width: 2px !important;" onclick="eliminarProducto({{ $producto->id }}, '{{ $producto->nombre }}')">
                                    Eliminar
                                </button>
                            </div>
                        </div>
                    </h2>
                    <div id="collapse{{ $producto->id }}" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                    <div class="accordion-body">
    <strong>Nombre: </strong> {{ $producto->nombre }} <br>
    <strong>Descripción: </strong> {{ $producto->descripcion_corta }} <br>
    <strong>Categoría: </strong> {{ $producto->categoria }} <br>
    <br>
    @if ($producto->detalles)
        <strong>Precio unitario: </strong> ${{ $producto->detalles->precio_unitario }} <br>
        <strong>Unidades en stock: </strong> {{ $producto->detalles->unidades_stock }} <br>
        <strong>Última modificación: </strong> {{ $producto->detalles->fecha_ultima_actualizacion }}
    @else
        <p>No hay detalles disponibles para este producto.</p>
    @endif
</div>
                    </div>
                </div>

                
<!-- Modal de edición -->
<div class="modal fade" id="modalEditar" tabindex="-1" aria-labelledby="modalEditarLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalEditarLabel">Editar Producto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formEditar" method="POST" action="{{ route('productos.update', $producto->id) }}">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" value="{{ old('nombre', $producto->nombre) }}" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="descripcion" class="form-label">Descripción</label>
                        <textarea class="form-control" id="descripcion" name="descripcion" rows="3" required>{{ old('descripcion', $producto->descripcion_corta) }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label for="categoria" class="form-label">Categoría</label>
                        <select class="form-select" id="categoria" name="categoria" required>
                            <option value="Electrónica" {{ $producto->categoria == 'Electrónica' ? 'selected' : '' }}>Electrónica</option>
                            <option value="Ropa y calzado" {{ $producto->categoria == 'Ropa y calzado' ? 'selected' : '' }}>Ropa y calzado</option>
                            <option value="Hogar" {{ $producto->categoria == 'Hogar' ? 'selected' : '' }}>Hogar</option>
                            <option value="Juguetes" {{ $producto->categoria == 'Juguetes' ? 'selected' : '' }}>Juguetes</option>
                            <option value="Otros" {{ $producto->categoria == 'Otros' ? 'selected' : '' }}>Otros</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="precio_unitario" class="form-label">Precio unitario</label>
                        <input type="number" step="0.01" class="form-control" id="precio_unitario" name="precio_unitario" value="{{ old('precio_unitario', $producto->detalles ? $producto->detalles->precio_unitario : '') }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="unidades_stock" class="form-label">Unidades en stock</label>
                        <input type="number" class="form-control" id="unidades_stock" name="unidades_stock" value="{{ old('unidades_stock', $producto->detalles ? $producto->detalles->unidades_stock : '') }}" required>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
            @endforeach
        @endif
    </div>
</div>

<script src="js/scripts.js"></script>
@endsection
