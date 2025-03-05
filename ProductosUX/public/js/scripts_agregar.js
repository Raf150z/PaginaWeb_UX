let productoId = null;

function siguiente(formActual, formSiguiente) {
    document.getElementById(formActual).classList.add('d-none'); 
    document.getElementById(formSiguiente).classList.remove('d-none'); 
    actualizarBarraProgreso(formSiguiente); 
}

function actualizarBarraProgreso(paso) {
    const porcentaje = (paso / 4) * 100; 
    const progressBar = document.getElementById('progressBar');
    progressBar.style.width = `${porcentaje}%`;
    progressBar.textContent = `${porcentaje}%`;
}

function cargarResumen(productoId) {
    fetch(`/obtener-datos-resumen/${productoId}`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('resumenNombre').textContent = data.nombre;
            document.getElementById('resumenDescripcion').textContent = data.descripcion_corta;
            document.getElementById('resumenCategoria').textContent = data.categoria;
            document.getElementById('resumenPrecio').textContent = data.detalles.precio_unitario;
            document.getElementById('resumenStock').textContent = data.detalles.unidades_stock;

            const colorHex = data.colores.color_hex;
            document.getElementById('resumenColor').textContent = colorHex;
            document.getElementById('resumenColor').style.backgroundColor = colorHex;
            document.getElementById('resumenColor').style.padding = '5px';
            document.getElementById('resumenColor').style.borderRadius = '5px';
            document.getElementById('resumenColor').style.color = '#fff'; 
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Hubo un error al cargar el resumen.');
        });
}

document.getElementById('formProducto').addEventListener('submit', function (e) {
    e.preventDefault();

    const formData = new FormData(this);

    fetch(this.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        },
    })
    .then(response => response.json())
    .then(data => {
        if (data.id) {
            productoId = data.id;
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

document.getElementById('formDetalles').addEventListener('submit', function (e) {
    e.preventDefault(); 

    document.getElementById('producto_id').value = productoId;

    const formData = new FormData(this);

    fetch(this.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        },
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            siguiente('formDetalles', 'formColor');
        } else {
            alert('Ingrese los datos en el formato correcto.');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Ingrese los datos en el formato correcto.');
    });
});

document.getElementById('formColor').addEventListener('submit', function (e) {
    e.preventDefault(); 

    document.getElementById('producto_id_color').value = productoId;

    const formData = new FormData(this);

    fetch(this.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        },
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            cargarResumen(productoId); 
            siguiente('formColor', 'resumen');
        } else {
            alert('Hubo un error al guardar el color del producto.');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Ingrese los datos en formato correcto.');
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
            body: JSON.stringify({ producto_id: productoId }),
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



function mostrarFormulario(paso) {
    document.querySelectorAll('form, #resumen').forEach((form) => {
        form.classList.add('d-none');
    });

    if (paso === 1) {
        document.getElementById('formProducto').classList.remove('d-none');
    } else if (paso === 2) {
        document.getElementById('formDetalles').classList.remove('d-none');
    } else if (paso === 3) {
        document.getElementById('formColor').classList.remove('d-none');
    } else if (paso === 4) {
        document.getElementById('resumenNombre').textContent = document.getElementById('nombre').value;
        document.getElementById('resumenDescripcion').textContent = document.getElementById('descripcion_corta').value;
        document.getElementById('resumenCategoria').textContent = document.getElementById('categoria').value;
        document.getElementById('resumenPrecio').textContent = document.getElementById('precio_unitario').value;
        document.getElementById('resumenStock').textContent = document.getElementById('unidades_stock').value;
        document.getElementById('resumenColor').textContent = document.getElementById('color_hex_text').value;

        document.getElementById('resumen').classList.remove('d-none');
    }

    actualizarBarraProgreso(paso);
}

function anterior(pasoActual) {
    if (pasoActual > 0) {
        pasoActual = pasoActual + 1;
        mostrarFormulario(pasoActual - 1);
    }
}

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
