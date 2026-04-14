document.addEventListener('DOMContentLoaded', async () => {
    // 1. Cargar Personas
    const respP = await fetch('/control-patrimonial/backend/routes/api.php?accion=listar_personas');
    const personas = await respP.json();
    const selP = document.getElementById('select_persona');
    personas.forEach(p => {
        selP.innerHTML += `<option value="${p.id}">${p.nombre} (${p.area})</option>`;
    });

    // 2. Cargar Bienes
    const respB = await fetch('/control-patrimonial/backend/routes/api.php?accion=listar_bienes');
    const bienes = await respB.json();
    const selB = document.getElementById('select_bien');
    bienes.forEach(b => {
        selB.innerHTML += `<option value="${b.id}">${b.codigo_patrimonial} - ${b.nombre}</option>`;
    });

    // 3. Evento Guardar
    document.getElementById('btn_asignar').addEventListener('click', async () => {
        const data = {
            bien_id: document.getElementById('select_bien').value,
            persona_id: document.getElementById('select_persona').value
        };

        if(!data.bien_id || !data.persona_id) return alert("Seleccione ambos campos");

        const res = await fetch('/control-patrimonial/backend/routes/api.php?accion=asignar_bien', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify(data)
        });

        const text = await res.text(); 
        console.log("Respuesta bruta del servidor:", text);
        const result = JSON.parse(text);
            });
});