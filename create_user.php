<?php
// Configuración del Web Service de Moodle
define('MOODLE_URL', 'http://localhost/moodle45/webservice/rest/server.php'); // URL de tu Moodle
define('MOODLE_TOKEN', '41749fd74ae7a24715630e21d6aaac7e'); //  de servicio web

/**
 * Función para crear usuarios en Moodle usando el servicio core_user_create_users
 *
 * @param array $users Lista de usuarios a crear
 * @return mixed Respuesta del servicio web o mensaje de error
 */

function create_users($users) {
    // Construir los datos de la solicitud
    $postData = [
        'wstoken' => MOODLE_TOKEN,
        'wsfunction' => 'core_user_create_users',
        'moodlewsrestformat' => 'json',
        'users' => $users, // Lista de usuarios
    ];

    // Iniciar la sesión cURL
    $ch = curl_init();
//print_r($postData); 
    // Configurar la solicitud cURL
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

    // Decodificar la respuesta JSON
    return json_decode($response, true);
}

// Ejemplo de usuarios a crear
$users = [
    [
        'username' => 'usuario3',
        'password' => 'Contraseña#Segura123', // Contraseña segura requerida por Moodle
        'firstname' => 'Nombre3',
        'lastname' => 'Apellido3',
        'email' => 'usuario3@example.com',
        'auth' => 'manual', // Método de autenticación
        'lang' => 'es', // Idioma por defecto
    ],
    [
        'username' => 'usuario4',
        'password' => 'OtraContras#eñaSegura123',
        'firstname' => 'Nombre4',
        'lastname' => 'Apellido2',
        'email' => 'usuario4@example.com',
        'auth' => 'manual',
        'lang' => 'es',
    ],
];

// Llamar a la función para crear usuarios
$response = create_users($users);

// Verificar la respuesta
if ($response) {
    if (isset($response['exception'])) {
        echo "Error: " . $response['exception'] . " - " . $response['message'];
    } else {
        echo "Usuarios creados exitosamente:\n";
        print_r($response);
    }
} else {
    echo "Error al comunicarse con el servicio web.";
}
?>
