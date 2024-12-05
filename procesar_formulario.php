<?php
require_once 'config\database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $nombre = $_POST['nombre'];
    $edad = $_POST['edad'];
    $email = $_POST['email'];
    $curso = $_POST['curso'];
    $genero = $_POST['genero'];
    $intereses = implode(", ", $_POST['intereses']); 

    
    $sql = "INSERT INTO registros (nombre, edad, email, curso, genero, intereses, fecha_registro) VALUES (?, ?, ?, ?, ?, ?, NOW())";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("sissss", $nombre, $edad, $email, $curso, $genero, $intereses);
        
        if ($stmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => $stmt->error]);
        }

        $stmt->close();
    } else {
        echo json_encode(['success' => false, 'error' => $conn->error]);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Acceso no autorizado']);
}

$conn->close();
?>

