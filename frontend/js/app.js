const API = "http://localhost/control-patrimonial/backend/";

async function obtenerBienes() {
    const res = await fetch(API + "bienes.php");
    const data = await res.json();

    console.log(data);
}

// Ejemplo de función para enviar el desplazamiento desde desplazamientos.html
async function enviarDesplazamiento() {
    const datos = {
        numero_desplazamiento: document.getElementById('nro_exp').value,
        persona_origen_id: document.getElementById('select_origen').value,
        persona_destino_id: document.getElementById('select_destino').value,
        motivo: document.getElementById('txt_motivo').value,
        bienes_ids: Array.from(document.querySelectorAll('.bien-checkbox:checked')).map(cb => cb.value)
    };

    const response = await fetch('../../backend/routes/api.php?accion=registrar_desplazamiento', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(datos)
    });

    const resultado = await response.json();
    if (resultado.status === 'success') {
        alert("¡Éxito! Bienes movidos y trazabilidad registrada.");
        location.reload(); 
    } else {
        alert("Error: " + resultado.message);
    }
}