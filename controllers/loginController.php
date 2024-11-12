<?php
session_start();// Inicia una nueva sesión o reanuda la sesión existente
require_once '../models/mtologin.php';// Incluye el archivo que contiene la definición de la clase 'Login'

header('Content-Type: application/json');// Establece el tipo de contenido de la respuesta como JSON

$response = array();//Inicializa un array para almacenar la respuesta

try {

    //Consultamos que tipo de metodo optiene el servidor
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        //asignamos a una variable el dato enviado y consultamos a la vez si esta vacio o no
        $mail = isset($_POST['mail']) ? $_POST['mail'] : '';
        $password = isset($_POST['password']) ? $_POST['password'] : '';

        $userModel = new mtologin();//Instanciamos el modelo del Login
        $user = $userModel->authenticate($mail, $password); //Mandamos los datos a la autenticación

        //Consultamos que tipo de respuesta se obtuvo del metodo
        if ($user) {
            if($user['estado_usuario']=="H"){
                $_SESSION['user'] = $user['mail'];//Asignamos el correo como usuario de session
                $_SESSION['user_id'] = $user['id_usuario'];//Asignamos el ID del usuario para poder ocuparla en el navegador para la gestión de pedidos
                $_SESSION['tpu'] = $user['id_tipo_usuario'];//Asignamos el id del tipo usuario a una variable de sesion
                if ($user['id_tipo_usuario'] == 1) {//Si es administrador
                    //Redirigimos al usuario a su vista
                     $response = ['status' => 'success', 'redirect' => '../comanda/views/admin.php'];
                } elseif ($user['id_tipo_usuario'] == 2) {//Si es mesero
                    //Redirigimos al usuario a su vista
                    $response = ['status' => 'success', 'redirect' => '../comanda/views/mesero.php'];
                } elseif ($user['id_tipo_usuario'] == 3) {//Si es cocinero
                    //Redirigimos al usuario a su vista
                    $response = ['status' => 'success', 'redirect' => '../comanda/views/cocinero.php'];
                }elseif ($user['id_tipo_usuario'] == 4) {//Si es cliente
                    //Redirigimos al usuario a su vista
                    $response = ['status' => 'success', 'redirect' => '../comanda/views/clinte.php'];
                }
                else {//Cualquier otro tipo de usuario redirige al login
                    $response = ['status' => 'success', 'redirect' => '../comanda/login.php?er=na'];
                }
            }
            else{
                $response = ['status' => 'error', 'message' => 'Usuario deshabilitado'];
            }
        } else {//Si el correo o la contraseña son incorrectos mostramos un mensaje de error
            throw new Exception('Correo o contraseña incorrectos ' .$user. " ERROR");
        }
    } else {//Si hay algun error en el servidore mostramos algun error
        throw new Exception('Método no permitido.');
    }
} catch (Exception $e) {//Captura de errores
    $response = ['status' => 'error', 'message' => $e->getMessage()];
}

echo json_encode($response);//Revolvemos la respuesta codificada como JSON
?>
