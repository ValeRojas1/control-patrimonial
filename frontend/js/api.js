const DB = {
    bienes: [
        { codigo: "001", nombre: "Laptop", estado: "Activo", persona: "Juan" },
        { codigo: "002", nombre: "Monitor", estado: "Activo", persona: "Maria" }
    ],
    desplazamientos: []
};

export function obtenerBienes() {
    return DB.bienes;
}

export function agregarBien(bien) {
    DB.bienes.push(bien);
}

export function registrarDesplazamiento(data) {
    DB.desplazamientos.push(data);

    DB.bienes.forEach(b => {
        if (data.bienes.includes(b.codigo)) {
            b.persona = data.destino;
        }
    });
}