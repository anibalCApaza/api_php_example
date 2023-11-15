<?php
$host = "localhost";
$usuario = "root";
$password = "";
$base_de_datos = "api";

$conexion = new mysqli($host, $usuario, $password, $base_de_datos);

if ($conexion->connect_error) {
    die("Conexión no establecida " . $conexion->connect_error);
}

header("Content-Type: application/json");
$metodo = $_SERVER['REQUEST_METHOD'];

switch ($metodo) {
    case 'GET':
        // CONSULTA DE TIPO SELECT
        consulta($conexion);
        break;
    case 'POST':
        // CONSULTA DE TIPO INSERT
        insertar($conexion);
        break;
    case "PUT":
        // CONSULTA DE TIPO UPDATE
        echo "Reemplazo de registros - PUT";
        break;
    case "PATCH":
        echo "Edición de registros - PATCH";
        break;
    case "DELETE":
        echo "Borrado de registros - DELETE";
        break;
    default:
        "Metodo no permitido";
        break;
}

function consulta($conexion)
{
    $sql = "SELECT * FROM usuarios";
    $result = $conexion->query($sql);

    if ($result->num_rows > 0) {
        $datos = array();
        while ($row = $result->fetch_assoc()) {
            $datos[] = $row;
        }
        echo json_encode($datos);
    }
}

function insertar($conexion)
{
    $dato = json_decode(file_get_contents('php://input'), true);
    $nombre = $dato['nombre'];

    $sql = "INSERT INTO usuarios(nombre) VALUES ('$nombre')";

    $result = $conexion->query($sql);

    if ($result) {
        $dato['id'] = $conexion->insert_id;
        echo json_encode($dato);
    } else {
        echo json_encode(array('error' => 'Error al crear usuario.'));
    }

}

?>