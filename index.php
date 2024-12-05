<?php

require_once 'config\database.php';

$sql = "SELECT * FROM registros ORDER BY fecha_registro DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario de Registro y Datos</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- SweetAlert2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css"> <!-- Asegúrate de enlazar tu CSS -->
    <nav> <li><a href="registros.php">Registros</a>

    </li>

    </nav>

    
</head>
<body class="bg-light">
    <div class="container-fluid mt-5">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header bg-success text-white">
                        <h1 class="h3 mb-0 text-center">Formulario de Registro</h1>
                    </div>
                    <div class="card-body">
                        <form id="registroForm" action="procesar_formulario.php" method="POST">
                            <div class="mb-3">
                                <label for="nombre" class="form-label">Nombre completo:</label>
                                <input type="text" class="form-control" id="nombre" name="nombre" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="edad" class="form-label">Edad:</label>
                                <input type="number" class="form-control" id="edad" name="edad" min="1" max="120" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="email" class="form-label">Correo electrónico:</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="curso" class="form-label">Programa de interes:</label>
                                <select class="form-select" id="curso" name="curso" required>
                                    <option value="">Seleccione un curso</option>
                                    <option value="Desarrollo Web">Desarrollo Web</option>
                                    <option value="Administración de empresas">Administracion de empresas</option>
                                    <option value="Matematicas">Matematicas</option>
                                    < <option value="Diseño">Diseño</option>
                                </select>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label d-block">Género:</label>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" id="masculino" name="genero" value="masculino" required>
                                    <label class="form-check-label" for="masculino">Masculino</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" id="femenino" name="genero" value="femenino">
                                    <label class="form-check-label" for="femenino">Femenino</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" id="otro" name="genero" value="otro">
                                    <label class="form-check-label" for="otro">Otro</label>
                                </div>
                                
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label d-block">Áreas de interés:</label>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" id="Ingenieria" name="intereses[]" value="Ingenieria">
                                    <label class="form-check-label" for="Ingenieria">Ingenieria</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" id="Administración" name="intereses[]" value="Administración">
                                    <label class="form-check-label" for="Administracion">Administracion</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" id="Ciencias Exactas" name="intereses[]" value="Ciencias Exactas">
                                    <label class="form-check-label" for="Ciencias Exactas">Ciencias Exactas</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" id="Artes" name="intereses[]" value="Artes">
                                    <label class="form-check-label" for="Artes">Artes</label>
                                </div>
                            </div>
                            
                            <div class="text-center">
                                <button type="submit" class="btn btn-success">Enviar</button>
                            </div>
                        </form>
                    </div>
                </div>

              
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https .jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('registroForm');
        
        form.addEventListener('submit', function(event) {
            event.preventDefault(); 

            const formData = new FormData(form);
            
            fetch('procesar_formulario.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire(
                        'Registro exitoso',
                        'El registro se ha guardado correctamente.',
                        'success'
                    ).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire(
                        'Error',
                        'No se pudo registrar: ' + (data.error || 'Error desconocido'),
                        'error'
                    );
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire(
                    'Error',
                    'Ocurrió un error al procesar la solicitud: ' + error.message,
                    'error'
                );
            });
        });

        const deleteButtons = document.querySelectorAll('.delete-btn');
        deleteButtons.forEach(button => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                Swal.fire({
                    title: '¿Estás seguro?',
                    text: "No podrás revertir esta acción",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch('delete.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded',
                            },
                            body: 'id=' + id
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire(
                                    'Eliminado',
                                    'El registro ha sido eliminado.',
                                    'success'
                                ).then(() => {
                                    location.reload();
                                });
                            } else {
                                Swal.fire(
                                    'Error',
                                    'No se pudo eliminar el registro: ' + (data.error || 'Error desconocido'),
                                    'error'
                                );
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            Swal.fire(
                                'Error',
                                'Ocurrió un error al procesar la solicitud: ' + error.message,
                                'error'
                            );
                        });
                    }
                });
            });
        });
    });
    </script>
</body>
</html>

<?php

$conn->close();
?>