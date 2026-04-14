async function cargarComponente(id, archivo) {
    const res = await fetch("../components/" + archivo);
    const html = await res.text();
    document.getElementById(id).innerHTML = html;
}