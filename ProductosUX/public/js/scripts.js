let productoActualId = null;

function abrirModalEditar(id) {
    productoActualId = id;

    fetch(`/productos/${id}`)
        .then(response => response.json())
        .then(producto => {
            document.getElementById('nombre').value = producto.nombre;
            document.getElementById('descripcion').value = producto.descripcion_corta;
            document.getElementById('categoria').value = producto.categoria;
            document.getElementById('precio_unitario').value = producto.detalles.precio_unitario;
            document.getElementById('unidades_stock').value = producto.detalles.unidades_stock;

            document.getElementById('formEditar').action = `/productos/${id}`;

            const modalEditar = new bootstrap.Modal(document.getElementById('modalEditar'));
            modalEditar.show();
        })
        .catch(error => {
            console.error("Error al obtener los datos del producto:", error);
            alert("Hubo un error al cargar los datos del producto.");
        });
}

function guardarCambios() {
    const nombre = document.getElementById('nombre').value;
    const descripcion = document.getElementById('descripcion').value;
    const categoria = document.getElementById('categoria').value;
    const precio_unitario = document.getElementById('precio_unitario').value;
    const unidades_stock = document.getElementById('unidades_stock').value;

    const datosActualizados = {
        nombre,
        descripcion,
        categoria,
        precio_unitario,
        unidades_stock
    };

    fetch(`/productos/${productoActualId}`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') 
        },
        body: JSON.stringify(datosActualizados)
    })
    .then(response => response.json())
    .then(data => {
        console.log("Producto actualizado con éxito:", data);
        
        actualizarAcordion(productoActualId, datosActualizados);

        const modalEditar = bootstrap.Modal.getInstance(document.getElementById('modalEditar'));
        modalEditar.hide();
    })
    .catch(error => {
        console.error("Error al actualizar el producto:", error);
        alert("Hubo un error al actualizar el producto.");
    });
}

function actualizarAcordion(id, producto) {
    const boton = document.querySelector(`[data-bs-target="#collapse${id}"]`);
    const cuerpo = document.querySelector(`#collapse${id} .accordion-body`);

    if (boton && cuerpo) {
        boton.innerHTML = producto.nombre;

        cuerpo.innerHTML = `
            <strong>Nombre: </strong> ${producto.nombre} <br>
            <strong>Descripción: </strong> ${producto.descripcion} <br>
            <strong>Categoría: </strong> ${producto.categoria} <br>
            <br>
            <strong>Precio unitario: </strong> $${producto.precio_unitario} <br>
            <strong>Unidades en stock: </strong> ${producto.unidades_stock} <br>
        `;
    }
}

function eliminarProducto(id) {
    if (confirm('¿Estás seguro de eliminar este producto?')) {
        fetch(`/productos/${id}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload(); 
            } else {
                alert('Error al eliminar el producto');
            }
        })
        .catch(error => console.error('Error:', error));
    }
}

