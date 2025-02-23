@extends('plantillas.app')
@section('title', 'Agregar productos')
@section('contenido')
<h1>Agregar productos</h1>

<div class="container mt-5">

    <!-- Formulario 1: Datos del producto -->
    <form id="formProducto" action="{{ route('guardar.producto') }}" method="POST" class="needs-validation" novalidate>
        @csrf
        <h4>Parte 1 de 4: Información básica</h4>
        <div class="progress mb-4">
            <div id="progressBar" class="progress-bar" role="progressbar" style="width: 25%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
        </div>

        <div class="container-frm">
            <div class="contenedor-input mb-3">
                <div class="contenedor-label">
                    <label for="nombre" class="form-label">Nombre</label>
                </div>
                <div class="contenedor-campo">
                    <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Ej: Silla ergonómica" required>
                    <div class="campo-obligatorio">*</div>
                </div>
            </div>

            <div class="contenedor-input mb-3">
                <div class="contenedor-label">
                    <label for="descripcion_corta" class="form-label">Descripción</label>
                </div>
                <div class="contenedor-campo">
                    <textarea class="form-control" id="descripcion_corta" name="descripcion_corta" placeholder="Ej: Silla cómoda para oficina" required></textarea>
                </div>
            </div>

            <div class="contenedor-input mb-3">
                <div class="contenedor-label">
                    <label for="categoria" class="form-label">Categoría</label>
                </div>
                <div class="contenedor-campo">
                    <select class="form-select" id="categoria" name="categoria" required>
                        <option value="Electrónica">Electrónica</option>
                        <option value="Hogar">Hogar</option>
                        <option value="Ropa y calzado">Ropa y calzado</option>
                        <option value="Juguetes">Juguetes</option>
                        <option value="Otros">Otros</option>
                    </select>
                    <div class="campo-obligatorio">*</div>
                </div>
            </div>
            <p>NOTA: Los campos obligatorios llevan un asterisco *</p>
            <div class="contenedor-botonesSyA">
                <button type="button" class="btn btn-danger" onclick="cancelar()">Cancelar</button>
                <button type="submit" class="btn btn-success">Siguiente</button>
            </div>
        </div>
    </form>

    <!-- Formulario 2: Detalles del producto -->
    <form id="formDetalles" action="{{ route('guardar.detalles') }}" method="POST" class="needs-validation d-none" novalidate>
        @csrf
        <input type="hidden" name="producto_id" id="producto_id"> <!-- Campo oculto para el producto_id -->
        <h4>Parte 2 de 4: Detalles del Producto</h4>
        <div class="progress mb-4">
            <div id="progressBar" class="progress-bar" role="progressbar" style="width: 50%;" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
        </div>

        <div class="container-frm">
            <div class="contenedor-input mb-3">
                <div class="contenedor-label">
                    <label for="precio_unitario" class="form-label">Precio Unitario</label>
                </div>
                <div class="contenedor-campo">
                    <input type="number" step="0.01" class="form-control" id="precio_unitario" name="precio_unitario" placeholder="Ej: 150.00" required>
                </div>
            </div>

            <div class="contenedor-input mb-3">
                <div class="contenedor-label">
                    <label for="unidades_stock" class="form-label">Unidades en Stock</label>
                </div>
                <div class="contenedor-campo">
                    <input type="number" class="form-control" id="unidades_stock" name="unidades_stock" placeholder="Ej: 10" required>
                </div>
            </div>
            <p>NOTA: Los campos obligatorios llevan un asterisco *</p>

            <div class="contenedor-botonesSyA">
                <button type="button" class="btn btn-secondary" onclick="anterior(1)">Atrás</button>
                <button type="button" class="btn btn-danger" onclick="cancelar()">Cancelar</button>
                <button type="submit" class="btn btn-success">Siguiente</button>

            </div>
        </div>
    </form>

<!-- Formulario 3: Color del producto -->
<form id="formColor" action="{{ route('guardar.color') }}" method="POST" class="needs-validation d-none" novalidate>
    @csrf
    <input type="hidden" name="producto_id" id="producto_id_color"> <!-- Campo oculto para el producto_id -->
    <h4>Parte 3 de 4: Color del Producto</h4>
    <div class="progress mb-4">
        <div id="progressBar" class="progress-bar" role="progressbar" style="width: 75%;" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"></div>
    </div>

    <div class="container-frm">
        <div class="contenedor-input mb-3">
            <div class="contenedor-label">
                <label for="color_hex_text" class="form-label">Elegir un color</label>
            </div>
            <div class="contenedor-campo">
                <!-- Input de tipo texto para el valor hexadecimal -->
                <input type="text" class="form-control" id="color_hex_text" name="color_hex_text" placeholder="Ej: #FF0000" required>
            </div>
        </div>

        <div class="contenedor-input_color mb-3">

            <div class="contenedor-campo_color">
                <input type="color" class="form-control-color" id="color_hex" name="color_hex" required>
            </div>
        </div>

        <p>NOTA: Los campos obligatorios llevan un asterisco *</p>

        <div class="contenedor-botonesSyA">
            <button type="button" class="btn btn-secondary" onclick="anterior(2)">Atrás</button>
            <button type="submit" class="btn btn-success">Siguiente</button>
            <button type="button" class="btn btn-danger" onclick="cancelar()">Cancelar</button>
        </div>
    </div>
</form>

    <!-- Formulario 4: Resumen y confirmación -->
    <div id="resumen" class="d-none">
    <h4>Parte 4 de 4: Resumen</h4>
    <div class="progress mb-4">
        <div id="progressBar" class="progress-bar" role="progressbar" style="width: 100%;" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
    </div>

    <div class="container-frm">
        <p><strong>Nombre:</strong> <span id="resumenNombre"></span></p>
        <p><strong>Descripción:</strong> <span id="resumenDescripcion"></span></p>
        <p><strong>Categoría:</strong> <span id="resumenCategoria"></span></p>
        <p><strong>Precio Unitario:</strong> <span id="resumenPrecio"></span></p>
        <p><strong>Unidades en Stock:</strong> <span id="resumenStock"></span></p>
        <p><strong>Color:</strong> <span id="resumenColor"></span></p>

        <div class="contenedor-botonesSyA-f">
            <button type="button" class="btn btn-secondary" onclick="anterior(3)">Atrás</button>
            <button type="button" class="btn btn-success" onclick="confirmar()">Guardar y Confirmar</button>
            <button type="button" class="btn btn-danger" onclick="cancelar()">Cancelar</button>
        </div>
    </div>
</div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="js/scripts.js"></script>
<script src="js/scripts_agregar.js"></script>
@endsection