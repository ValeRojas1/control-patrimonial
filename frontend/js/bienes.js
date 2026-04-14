import { obtenerBienes, agregarBien } from './api.js';

const tabla = document.querySelector("tbody");
const form = document.querySelector("form");

function render() {
    tabla.innerHTML = "";
    const bienes = obtenerBienes();

    bienes.forEach(b => {
        tabla.innerHTML += `
            <tr>
                <td>${b.codigo}</td>
                <td>${b.nombre}</td>
                <td><span class="badge bg-success">${b.estado}</span></td>
                <td>${b.persona}</td>
            </tr>
        `;
    });
}

form.addEventListener("submit", e => {
    e.preventDefault();

    const inputs = form.querySelectorAll("input, textarea, select");

    const nuevo = {
        codigo: inputs[0].value,
        nombre: inputs[1].value,
        estado: inputs[2].value || "Activo",
        persona: "Sin asignar"
    };

    agregarBien(nuevo);
    render();
    form.reset();
});

render();