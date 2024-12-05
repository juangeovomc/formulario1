<?php
require_once 'config/database.php';

// Establecer el encabezado para devolver JSON
header('Content-Type: application/json');

$response = [];

if (isset($_POST['id'])) {
    $id = intval($_POST['id']); // Asegúrate de que sea un número entero
    $sql = "DELETE FROM registros WHERE id = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            $response['success'] = true;
        } else {
            $response['success'] = false;
            $response['error'] = $stmt->error; // Error específico de la ejecución
        }
        $stmt->close();
    } else {
        $response['success'] = false;
        $response['error'] = $conn->error; // Error específico al preparar la consulta
    }
} else {
    $response['success'] = false;
    $response['error'] = 'ID no proporcionado';
}

// Devolver respuesta en formato JSON
echo json_encode($response);

$conn->close();
?>
