<?php
require_once 'config/database.php';

$sql = "SELECT * FROM registros ORDER BY fecha_registro DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registros y Formularios</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <nav> <li><a href="index.php">Inicio</a>

</li>

</nav>
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center mb-4">Registros Almacenados</h1>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Edad</th>
                    <th>Email</th>
                    <th>Curso</th>
                    <th>Género</th>
                    <th>Intereses</th>
                    <th>Fecha de Registro</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['nombre']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['edad']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['curso']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['genero']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['intereses']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['fecha_registro']) . "</td>";
                        echo "<td><button class='btn btn-danger btn-sm delete-btn' data-id='" . $row['id'] . "'>Eliminar</button></td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='8' class='text-center'>No hay registros</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const deleteButtons = document.querySelectorAll('.delete-btn');

            deleteButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const id = this.getAttribute('data-id');
                    Swal.fire({
                        title: '¿Estás seguro?',
                        text: "Esta acción no se puede revertir.",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Sí, eliminar',
                        cancelButtonText: 'Cancelar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            fetch('delete.php', {
                                method: 'POST',
                                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                                body: 'id=' + id
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    Swal.fire('Eliminado', 'El registro ha sido eliminado.', 'success')
                                    .then(() => location.reload());
                                } else {
                                    Swal.fire('Error', 'No se pudo eliminar: ' + (data.error || 'Error desconocido'), 'error');
                                }
                            })
                            .catch(error => Swal.fire('Error', 'Error: ' + error.message, 'error'));
                        }
                    });
                });
            });
        });
    </script>
</body>
</html>

<?php $conn->close(); ?>
