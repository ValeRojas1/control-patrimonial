const API = "http://localhost/control-patrimonial/backend/";

async function obtenerBienes() {
    const res = await fetch(API + "bienes.php");
    const data = await res.json();

    console.log(data);
}