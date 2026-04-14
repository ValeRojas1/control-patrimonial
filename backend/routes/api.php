<?php
// backend/routes/api.php
header("Content-Type: application/json");

// Reporte de errores para debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../config/conexion.php';

// Importación de controladores
require_once __DIR__ . '/../controllers/DesplazamientoController.php';
require_once __DIR__ . '/../controllers/ReporteController.php';
require_once __DIR__ . '/../controllers/UsuarioController.php';
require_once __DIR__ . '/../controllers/BienController.php';
require_once __DIR__ . '/../controllers/AsignacionController.php';

$metodo = $_SERVER['REQUEST_METHOD'];
$accion = $_GET['accion'] ?? '';

// --- PETICIONES POST ---
if ($metodo === 'POST') {
    $json = file_get_contents('php://input');
    $data = json_decode(file_get_contents('php://input'), true);

    switch ($accion) {
       case 'login':
            // Asegúrate de que el require esté al inicio del api.php o aquí mismo
            require_once __DIR__ . '/../controllers/UsuarioController.php';
            $controller = new UsuarioController($pdo);
            echo json_encode($controller->login($data['email'], $data['password']));
            break;

        case 'registrar_desplazamiento':
            $controller = new DesplazamientoController($pdo);
            echo json_encode($controller->registrar($data));
            break;

        case 'guardar_bien':
            $controller = new BienController($pdo);
            echo json_encode($controller->registrar($data));
            break;

        case 'asignar_bien':
            $controller = new AsignacionController($pdo);
            echo json_encode($controller->asignar($data));
            break;
            
        default:
            echo json_encode(["status" => "error", "message" => "Accion POST no encontrada"]);
            break;
    }
    exit; // Termina aquí para peticiones POST
}

// --- PETICIONES GET ---
if ($metodo === 'GET') {
    switch ($accion) {
        case 'listar_personas':
            $stmt = $pdo->query("SELECT id, nombre, area FROM personas ORDER BY nombre ASC");
            echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
            break;

        case 'listar_bienes':
            $controller = new BienController($pdo);
            echo json_encode($controller->listar());
            break;

        case 'listar_bienes_por_persona':
            $personaId = $_GET['id'] ?? null;
            $stmt = $pdo->prepare("SELECT id, codigo_patrimonial, nombre FROM bienes WHERE persona_id = ?");
            $stmt->execute([$personaId]);
            echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
            break;

        case 'reporte_asignacion':
            $personaId = $_GET['id'] ?? null;
            if ($personaId) {
                $controller = new ReporteController($pdo);
                $controller->generarAsignacionPersona($personaId);
            } else {
                echo json_encode(["status" => "error", "message" => "ID no proporcionado"]);
            }
            break;

        case 'reporte_general_desplazamientos':
            $controller = new ReporteController($pdo);
            $controller->generarReporteGeneralDesplazamientos();
            break;

        default:
            echo json_encode(["status" => "error", "message" => "Accion GET no encontrada"]);
            break;
    }
    exit; // Termina aquí para peticiones GET
}