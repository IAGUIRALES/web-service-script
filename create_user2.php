<?php
// Configuración del Web Service de Moodle
define('MOODLE_URL', 'http://localhost/moodle45/webservice/rest/server.php'); // URL de tu Moodle
define('MOODLE_TOKEN', '41749fd74ae7a24715630e21d6aaac7e'); //  de servicio web

function create_users($users) {
    // Construir los datos de la solicitud
    $postData = [
        'wstoken' => MOODLE_TOKEN,
        'wsfunction' => 'core_user_create_users',
        'moodlewsrestformat' => 'json',
        'users' => $users, // Lista de usuarios
    ];

// Configurar la solicitud cURL
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, MOODLE_URL);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData)); // Convertir datos a formato application/x-www-form-urlencoded

$response = curl_exec($ch);
if ($response === false) {
        echo 'Error en cURL: ' . curl_error($ch);
}        
    curl_close($ch);
    return json_decode($response, true);
} 

//  formulario 
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $firstname = trim($_POST['firstname']);
    $lastname = trim($_POST['lastname']);
    $email = trim($_POST['email']);
    $auth ='manual';

    if ($username && $password && $firstname && $lastname && $email) {
        $users = [
            [
                'username' => $username,
                'password' => $password,
                'firstname' => $firstname,
                'lastname' => $lastname,
                'email' => $email,
                'auth' => $auth,
                'lang' => 'es',
            ]
        ];

//  crear usuarios
        $response = create_users($users);

        if (isset($response['exception'])) {
            $message = "Error: " . $response['message'];
        } else {
            $message = "Usuario creado exitosamente.";
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
    <title>Crear Usuario en Moodle</title>
</head>
<body>
    <h1>Crear Usuario </h1>
    <form method="POST" action="">
    <label for="username">Usuario:</label>
        <input type="text" id="username" name="username" required><br><br>

        <label for="password">Contraseña:</label>
        <input type="password" id="password" name="password" required>
        
        debe tener 8 caracteres, 1 mayuscula, un numero y caracter (#,$,@)<br><br>

        <label for="firstname">Nombre:</label>
        <input type="text" id="firstname" name="firstname" required><br><br>

        <label for="lastname">Apellido:</label>
        <input type="text" id="lastname" name="lastname" required><br><br>

        <label for="email">Correo Electrónico:</label>
        <input type="email" id="email" name="email" required><br><br>

        <button type="submit">Crear Usuario</button>
    </form>

    <?php if ($message): ?>
        <p style="color: red;"><?php echo $message; ?></p>
    <?php endif; ?>
</body>
</html>
