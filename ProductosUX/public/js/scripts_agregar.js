let productoId = null; // Variable global para almacenar el ID del producto

// Función para avanzar al siguiente formulario
function siguiente(formActual, formSiguiente) {
    document.getElementById(formActual).classList.add('d-none'); // Oculta el formulario actual
    document.getElementById(formSiguiente).classList.remove('d-none'); // Muestra el siguiente formulario
    actualizarBarraProgreso(formSiguiente); // Actualiza la barra de progreso
}

// Función para actualizar la barra de progreso
function actualizarBarraProgreso(paso) {
    const porcentaje = (paso / 4) * 100; // Calcula el porcentaje de progreso
    const progressBar = document.getElementById('progressBar');
    progressBar.style.width = `${porcentaje}%`;
    progressBar.textContent = `${porcentaje}%`;
}

// Función para cargar los datos del resumen
function cargarResumen(productoId) {
    fetch(`/obtener-datos-resumen/${productoId}`)
        .then(response => response.json())
        .then(data => {
            // Actualiza el contenido del resumen con los datos obtenidos
            document.getElementById('resumenNombre').textContent = data.nombre;
            document.getElementById('resumenDescripcion').textContent = data.descripcion_corta;
            document.getElementById('resumenCategoria').textContent = data.categoria;
            document.getElementById('resumenPrecio').textContent = data.detalles.precio_unitario;
            document.getElementById('resumenStock').textContent = data.detalles.unidades_stock;

            // Mostrar el color en el resumen
            const colorHex = data.colores.color_hex;
            document.getElementById('resumenColor').textContent = colorHex;
            document.getElementById('resumenColor').style.backgroundColor = colorHex;
            document.getElementById('resumenColor').style.padding = '5px';
            document.getElementById('resumenColor').style.borderRadius = '5px';
            document.getElementById('resumenColor').style.color = '#fff'; // Color del texto para mejor contraste
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Hubo un error al cargar el resumen.');
        });
}

// Manejo del formulario 1: Datos del producto
document.getElementById('formProducto').addEventListener('submit', function (e) {
    e.preventDefault(); // Evita que el formulario se envíe de forma tradicional

    const formData = new FormData(this); // Obtiene los datos del formulario

    fetch(this.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'), // Token CSRF
        },
    })
    .then(response => response.json()) // Convierte la respuesta a JSON
    .then(data => {
        if (data.id) {
            productoId = data.id; // Guarda el ID del producto
            siguiente('formProducto', 'formDetalles');
        } else {
            alert('Hubo un error al guardar el producto.');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Por favor llene los campos obligatorios.');
    });
});

// Manejo del formulario 2: Detalles del producto
document.getElementById('formDetalles').addEventListener('submit', function (e) {
    e.preventDefault(); // Evita que el formulario se envíe de forma tradicional

    // Asigna el producto_id al campo oculto antes de enviar el formulario
    document.getElementById('producto_id').value = productoId;

    const formData = new FormData(this); // Obtiene los datos del formulario

    fetch(this.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'), // Token CSRF
        },
    })
    .then(response => response.json()) // Convierte la respuesta a JSON
    .then(data => {
        if (data.success) {
            // Si se guardaron los detalles, avanza al siguiente formulario
            siguiente('formDetalles', 'formColor');
        } else {
            alert('Hubo un error al guardar los detalles del producto.');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Hubo un error al enviar el formulario.');
    });
});

// Manejo del formulario 3: Color del producto
document.getElementById('formColor').addEventListener('submit', function (e) {
    e.preventDefault(); // Evita que el formulario se envíe de forma tradicional

    // Asigna el producto_id al campo oculto antes de enviar el formulario
    document.getElementById('producto_id_color').value = productoId;

    const formData = new FormData(this); // Obtiene los datos del formulario

    fetch(this.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'), // Token CSRF
        },
    })
    .then(response => response.json()) // Convierte la respuesta a JSON
    .then(data => {
        if (data.success) {
            // Si se guardó el color, avanza al resumen
            cargarResumen(productoId); // Carga los datos del resumen
            siguiente('formColor', 'resumen');
        } else {
            alert('Hubo un error al guardar el color del producto.');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Hubo un error al enviar el formulario.');
    });
});


function confirmar() {
    if (confirm('¿Estás seguro de confirmar y guardar los datos?')) {
        fetch('/finalizar-proceso', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            },
            body: JSON.stringify({ producto_id: productoId }), // Envía el producto_id
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Datos guardados correctamente.');
                window.location.href = '/';
            } else {
                alert('Hubo un error al guardar los datos.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Hubo un error al enviar la solicitud.');
        });
    }
}



document.getElementById('color_hex').addEventListener('input', function () {
    document.getElementById('color_hex_text').value = this.value;
});

document.getElementById('color_hex_text').addEventListener('input', function () {
    const colorInput = document.getElementById('color_hex');
    const colorText = this.value;

    if (/^#[0-9A-F]{6}$/i.test(colorText)) {
        colorInput.value = colorText;
    }

});



// Función para mostrar un formulario específico
function mostrarFormulario(paso) {
    // Oculta todos los formularios
    document.querySelectorAll('form, #resumen').forEach((form) => {
        form.classList.add('d-none');
    });

    // Muestra el formulario correspondiente al paso
    if (paso === 1) {
        document.getElementById('formProducto').classList.remove('d-none');
    } else if (paso === 2) {
        document.getElementById('formDetalles').classList.remove('d-none');
    } else if (paso === 3) {
        document.getElementById('formColor').classList.remove('d-none');
    } else if (paso === 4) {
        // Llena el resumen con los datos ingresados
        document.getElementById('resumenNombre').textContent = document.getElementById('nombre').value;
        document.getElementById('resumenDescripcion').textContent = document.getElementById('descripcion_corta').value;
        document.getElementById('resumenCategoria').textContent = document.getElementById('categoria').value;
        document.getElementById('resumenPrecio').textContent = document.getElementById('precio_unitario').value;
        document.getElementById('resumenStock').textContent = document.getElementById('unidades_stock').value;
        document.getElementById('resumenColor').textContent = document.getElementById('color_hex_text').value;

        // Muestra el resumen
        document.getElementById('resumen').classList.remove('d-none');
    }

    // Actualiza la barra de progreso
    actualizarBarraProgreso(paso);
}

// Función para retroceder
function anterior(pasoActual) {
    if (pasoActual > 0) {
        pasoActual = pasoActual + 1;
        mostrarFormulario(pasoActual - 1);
    }
}

// Función para cancelar
function cancelar(productoId) {
    if (confirm('¿Estás seguro de que deseas cancelar? Todos los datos se perderán.')) {
        fetch(`/productos/${productoId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            },
        })
    }
    window.location.href = '/';
}
