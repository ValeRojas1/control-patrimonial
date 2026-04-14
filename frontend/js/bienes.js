// frontend/js/bienes.js

document.addEventListener('DOMContentLoaded', () => {
    cargarBienes();

    // Evento para el botón guardar
    const btnGuardar = document.getElementById('btn_guardar');
    if (btnGuardar) {
        btnGuardar.addEventListener('click', guardarBien);
    }
});

// Función para obtener y mostrar los bienes
async function cargarBienes() {
    try {
        const resp = await fetch('/control-patrimonial/backend/routes/api.php?accion=listar_bienes');
        const bienes = await resp.json();
        
        const tbody = document.getElementById('tabla_bienes');
        tbody.innerHTML = '';

        bienes.forEach(b => {
            // Lógica simple para el color del badge
            const badgeClass = b.estado === 'Activo' ? 'bg-success' : 'bg-secondary';
            
            tbody.innerHTML += `
                <tr>
                    <td>${b.codigo_patrimonial}</td>
                    <td>${b.nombre}</td>
                    <td><span class="badge ${badgeClass}">${b.estado}</span></td>
                    <td>${b.asignado ? b.asignado : '<em class="text-muted">No asignado</em>'}</td>
                </tr>
            `;
        });
    } catch (error) {
        console.error("Error al cargar bienes:", error);
    }
}

// Función para enviar un nuevo bien al backend
async function guardarBien() {
    const data = {
        codigo: document.getElementById('txt_codigo').value,
        nombre: document.getElementById('txt_nombre').value,
        estado: document.getElementById('txt_estado').value,
        descripcion: document.getElementById('txt_descripcion').value
    };

    // Validación básica
    if (!data.codigo || !data.nombre || !data.estado) {
        alert("Por favor completa los campos obligatorios.");
        return;
    }

    try {
        const resp = await fetch('/control-patrimonial/backend/routes/api.php?accion=guardar_bien', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(data)
        });

        const res = await resp.json();
        if (res.status === 'success') {
            alert("Bien registrado correctamente");
            // Limpiar formulario
            document.getElementById('txt_codigo').value = '';
            document.getElementById('txt_nombre').value = '';
            document.getElementById('txt_estado').value = '';
            document.getElementById('txt_descripcion').value = '';
            // Recargar tabla
            cargarBienes();
        } else {
            alert("Error al guardar: " + res.message);
        }
    } catch (error) {
        console.error("Error al guardar bien:", error);
    }
    document.addEventListener('DOMContentLoaded', () => {
    cargarBienes(); // Carga los datos apenas abre la página
    });
    async function cargarBienes() {
    try {
        const resp = await fetch('/control-patrimonial/backend/routes/api.php?accion=listar_bienes');
        const bienes = await resp.json();
        
        const tbody = document.getElementById('tabla_bienes');
        tbody.innerHTML = ''; // Limpiamos la tabla antes de llenar

        bienes.forEach(bien => {
            // Lógica para el color del estado
            const badgeClass = bien.estado === 'Activo' ? 'bg-success' : 'bg-secondary';
            const nombreAsignado = bien.asignado ? bien.asignado : '<span class="text-muted">Sin asignar</span>';

            tbody.innerHTML += `
                <tr>
                    <td>${bien.codigo_patrimonial}</td>
                    <td>${bien.nombre}</td>
                    <td><span class="badge ${badgeClass}">${bien.estado}</span></td>
                    <td>${nombreAsignado}</td>
                </tr>
            `;
        });
    } catch (error) {
        console.error("Error al cargar la tabla de bienes:", error);
    }
    }
}