<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
   
    $nombre = trim($_POST['nombre']);
    $edad = trim($_POST['edad']);
    $correo = trim($_POST['correo']);
    $curso = trim($_POST['curso']);
    $genero = trim($_POST['genero']);
    $intereses = isset($_POST['intereses']) ? $_POST['intereses'] : [];

   
    if (empty($nombre) || empty($edad) || empty($correo) || empty($curso) || empty($genero)) {
        echo "Por favor, complete todos los campos requeridos.";
        exit;
    }


    echo "<h1>Datos del Estudiante Registrado</h1>";
    echo "<p><strong>Nombre Completo:</strong> " . htmlspecialchars($nombre) . "</p>";
    echo "<p><strong>Edad:</strong> " . htmlspecialchars($edad) . "</p>";
    echo "<p><strong>Correo Electrónico:</strong> " . htmlspecialchars($correo) . "</p>";
    echo "<p><strong>Curso:</strong> " . htmlspecialchars($curso) . "</p>";
    echo "<p><strong>Género:</strong> " . htmlspecialchars($genero) . "</p>";

    
    if (!empty($intereses)) {
        echo "<p><strong>Áreas de Interés:</strong> " . implode(", ", array_map('htmlspecialchars', $intereses)) . "</p>";
    } else {
        echo "<p><strong>Áreas de Interés:</strong> Ninguna seleccionada</p>";
    }
} else {
    echo "No se recibieron datos del formulario.";
}
?>