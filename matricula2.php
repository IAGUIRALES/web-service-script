<?php
// Configuración del Web Service de Moodle
define('MOODLE_URL', 'http://localhost/moodle45/webservice/rest/server.php'); // URL de tu Moodle
define('MOODLE_TOKEN', '41749fd74ae7a24715630e21d6aaac7e'); // Token generado en Moodle

/**
 * Función para crear usuarios en Moodle usando el servicio core_user_create_users
 *
 * @param array $users Lista de usuarios a crear
 * @return mixed Respuesta del servicio web o mensaje de error
 */

// Función para matricular usuarios en Moodle
function enrol_users($enrolments) {
    $postData = [
        'wstoken' => MOODLE_TOKEN,
        'wsfunction' => 'enrol_manual_enrol_users',
        'moodlewsrestformat' => 'json',
        'enrolments' => $enrolments,
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, MOODLE_URL);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));

    $response = curl_exec($ch);
    if ($response === false) {
        echo 'Error en cURL: ' . curl_error($ch);
        curl_close($ch);
        return null;
    }

    curl_close($ch);
    return json_decode($response, true);
}

//  formulario
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userid = intval($_POST['userid']);
    $courseid = intval($_POST['courseid']);
    $roleid = intval($_POST['roleid']);

    if ($userid > 0 && $courseid > 0 && $roleid > 0) {
        $enrolments = [
            [
                'roleid' => $roleid,
                'userid' => $userid,
                'courseid' => $courseid,
            ]
        ];
        

// Llamar a la función para matricular usuarios

        $response = enrol_users($enrolments);

        // Verificar la respuesta

        if (isset($response['exception'])) {
            $message = "Error: " . $response['message'];
        } else {
            $message = "Usuario matriculado exitosamente.";
        }
    } else {
        $message = "Todos los campos son obligatorios.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Matricular Usuario en Moodle</title>
</head>
<body>
    <h1>Matricular Usuario en un Curso</h1>
    <form method="POST" action="">
        <label for="userid">ID del Usuario:</label>
        <input type="number" id="userid" name="userid" required><br><br>

        <label for="courseid">ID del Curso:</label>
        <input type="number" id="courseid" name="courseid" required><br><br>

        <label for="roleid">ID del Rol:</label>
        <select id="roleid" name="roleid" required>
            <option value="5">Estudiante</option>
            <option value="3">Profesor sin edición</option>
            <option value="4">Profesor</option>
        </select><br><br>

        <button type="submit">Matricular Usuario</button>
    </form>

    <?php if ($message): ?>
        <p style="color: red;"><?php echo $message; ?></p>
    <?php endif; ?>
</body>
</html>
