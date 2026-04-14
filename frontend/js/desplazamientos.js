import { obtenerBienes, registrarDesplazamiento } from './api.js';

const form = document.querySelector("form");

form.addEventListener("submit", e => {
    e.preventDefault();

    const inputs = form.querySelectorAll("input, select, textarea");

    const data = {
        numero: inputs[0].value,
        fecha: inputs[1].value,
        origen: inputs[2].value,
        destino: inputs[3].value,
        motivo: inputs[4].value,
        bienes: ["001"] // simulación
    };

    registrarDesplazamiento(data);

    alert("Desplazamiento registrado 🚀");
    form.reset();
});