<?php
// Configuración del Web Service de Moodle
define('MOODLE_URL', 'http://localhost/moodle45/webservice/rest/server.php'); // URL de tu Moodle
define('MOODLE_TOKEN', '41749fd74ae7a24715630e21d6aaac7e'); //token de servicio web

/**
 * Función para matricular usuarios en un curso de Moodle
 *
 * @param array $enrolments Lista de matriculaciones a realizar
 * @return mixed Respuesta del servicio web o mensaje de error
 */
// Construir los datos de la solicitud 

function enrol_users($enrolments) {
    $postData = [
        'wstoken' => MOODLE_TOKEN,
        'wsfunction' => 'enrol_manual_enrol_users',
        'moodlewsrestformat' => 'json',
        'enrolments' => $enrolments, 
        ];

    // Iniciar la sesión cURL
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, MOODLE_URL);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData)); // Convertir datos a formato application/x-www-form-urlencoded

    // Ejecutar la solicitud
    $response = curl_exec($ch);

    // Manejar errores de cURL
    if ($response === false) {
        echo 'Error en cURL: ' . curl_error($ch);
        curl_close($ch);
        return null;
    }

    // Cerrar la sesión cURL
    curl_close($ch);
    return json_decode($response, true);
}

// Ejemplo de matriculaciones
$enrolments = [
    [
        'roleid' => 5, // ID del rol (5 es el ID predeterminado para "Estudiante")
        'userid' => 14, // ID del usuario en Moodle
        'courseid' => 3, // ID del curso en Moodle
    ],
    [
        'roleid' => 3, // ID del rol (3 es el ID predeterminado para "Profesor sin permiso de edición")
        'userid' => 15,
        'courseid' => 3,
    ],
];

// Llamar a la función para matricular usuarios
$response = enrol_users($enrolments);

// Verificar la respuesta
if ($response) {
    if (isset($response['exception'])) {
        echo "Error: " . $response['exception'] . " - " . $response['message'];
    } else {
        echo "Usuarios matriculados exitosamente:\n";
        print_r($response);
    }
} else {
    echo "Error al comunicarse con el servicio web.";
}
?>
