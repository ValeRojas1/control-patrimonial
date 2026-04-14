document.addEventListener('DOMContentLoaded', async () => {
    const selectPersona = document.getElementById('select_persona');

    // 1. Cargar personas para el reporte individual
    const resp = await fetch('/control-patrimonial/backend/routes/api.php?accion=listar_personas');
    const personas = await resp.json();
    personas.forEach(p => {
        const opt = document.createElement('option');
        opt.value = p.id;
        opt.textContent = `${p.nombre} (${p.area})`;
        selectPersona.appendChild(opt);
    });

    // 2. Botón Acta de Asignación
    document.getElementById('btn_repo_asignacion').addEventListener('click', () => {
        const id = selectPersona.value;
        if (!id) return alert("Por favor seleccione una persona");
        window.open(`/control-patrimonial/backend/routes/api.php?accion=reporte_asignacion&id=${id}`, '_blank');
    });

    // 3. Botón Historial General
    document.getElementById('btn_repo_historial').addEventListener('click', () => {
        window.open(`/control-patrimonial/backend/routes/api.php?accion=reporte_general_desplazamientos`, '_blank');
    });
});