document.addEventListener('DOMContentLoaded', () => {
    cargarPersonas();
    
    // Al cambiar el origen, buscamos sus bienes
    document.getElementById('select_origen').addEventListener('change', (e) => {
        if(e.target.value) cargarBienesPorPersona(e.target.value);
    });

    document.getElementById('btn_transferir').addEventListener('click', ejecutarTransferencia);
});

async function cargarPersonas() {
    const resp = await fetch('/control-patrimonial/backend/routes/api.php?accion=listar_personas');
    const personas = await resp.json();
    const combos = [document.getElementById('select_origen'), document.getElementById('select_destino')];
    
    combos.forEach(combo => {
        personas.forEach(p => {
            combo.innerHTML += `<option value="${p.id}">${p.nombre} (${p.area})</option>`;
        });
    });
}

async function cargarBienesPorPersona(personaId) {
    const resp = await fetch(`/control-patrimonial/backend/routes/api.php?accion=listar_bienes_por_persona&id=${personaId}`);
    const bienes = await resp.json();
    const contenedor = document.getElementById('lista_bienes_origen');
    
    contenedor.innerHTML = bienes.length ? '' : '<p class="text-danger">Esta persona no tiene bienes asignados.</p>';

    bienes.forEach(b => {
        contenedor.innerHTML += `
            <div class="form-check">
                <input class="form-check-input check-bien" type="checkbox" value="${b.id}" id="bien_${b.id}">
                <label class="form-check-label" for="bien_${b.id}">
                    <strong>${b.codigo_patrimonial}</strong> - ${b.nombre}
                </label>
            </div>
        `;
    });
}

async function ejecutarTransferencia() {
    const bienesSeleccionados = Array.from(document.querySelectorAll('.check-bien:checked')).map(cb => cb.value);
    
    const data = {
        numero_desplazamiento: document.getElementById('nro_desplazamiento').value,
        fecha: document.getElementById('fecha_desplazamiento').value,
        persona_origen_id: document.getElementById('select_origen').value,
        persona_destino_id: document.getElementById('select_destino').value,
        motivo: document.getElementById('txt_motivo').value,
        bienes_ids: bienesSeleccionados
    };

    if (data.persona_origen_id === data.persona_destino_id) {
        alert("El destino no puede ser el mismo que el origen.");
        return;
    }

    if (bienesSeleccionados.length === 0) {
        alert("Seleccione al menos un bien.");
        return;
    }

    const resp = await fetch('/control-patrimonial/backend/routes/api.php?accion=registrar_desplazamiento', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify(data)
    });

    const res = await resp.json();
    if(res.status === 'success') {
        alert("Transferencia realizada con éxito.");
        location.reload();
    } else {
        alert("Error: " + res.message);
    }
}