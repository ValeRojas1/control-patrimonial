import { obtenerBienes } from './api.js';

const total = document.querySelectorAll("h2");

function cargarDatos() {
    const bienes = obtenerBienes();

    total[0].innerText = bienes.length;
    total[1].innerText = bienes.filter(b => b.persona !== "Sin asignar").length;
    total[2].innerText = 5; // simulado
}

cargarDatos();