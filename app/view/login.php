<?php
// Para hacer la conexión
require_once(BASE_DIR . 'app/controller/ctrlConnect.php'); // Controlador de la conexión
require_once(BASE_DIR . 'app/model/user.php'); // Modelo de usuario

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    /*********** CONEXION A LA BBDD ************************/
    $db = new ctrlConnect();
    $pdo = $db->connectDB(); // Conectar a la BBDD
    /*********** FIN DE CONEXION A LA BBDD *****************/

    $usuario = new User($pdo); // Instanciamos el usuario  
    $usuario->setUsername($username); // Tomado del $_POST
    $usuario->setPassword($password); // Tomado del $_POST
    $userId = $usuario->isUserExisting(); // Devuelve el id, 0 si no existe en la bbdd
    $userpasswd = $usuario->isPasswordExisting(); // Devuelve 0 si no existe en la bbdd. Comrpueba que el usuario y la contraseña son correctos

    if ($userId && $userpasswd) {
        // Si el usuario y la contraseña son correctos
        $usuario->setEmail($usuario->getUserEmail());
        $usuario->setId($userId);

        $userRol = $usuario->role->getUserRol($usuario->getUserName());

        $db->closeDB(); // Cerrar la conexión

        $_SESSION["email"] = $usuario->getEmail(); // Email del usuario
        $_SESSION["userid"] = $userId; // Id del usuario
        $_SESSION["userrol"] = $userRol; // Rol del usuario
        $_SESSION['username'] = $usuario->getUserName(); // Nombre de usuario

        header('Location: index.php?method=loginSuccess');
        exit();
    } else {
        if (!$userId) {
            echo "<script>alert('El usuario no existe.'); window.location.href='index.php?method=createAcount';</script>";
            $_SESSION['error_message'] = "El nombre de usuario no existe.";
            $_SESSION['form_data'] = ['username' => $username, 'password' => $password];
            exit();
        }
        if (!$userpasswd) {
            echo "<script>alert('Contraseña erronea.'); window.location.href='index.php?method=login';</script>";
            $_SESSION['error_message'] = "Error en la contraseña.";
            $_SESSION['form_data'] = ['username' => $username, 'password' => $password];
            exit();
        }
        echo "usuario o contraseña incorrectos";
    }
    
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>VinylExplosion!</title>
    <link rel="stylesheet" type="text/css" href="css/login.css" />
    <link rel="stylesheet" href="css/styles.css" />
    <script src="js/script.js"></script>
</head>

<body>
    <div id="login-form">
        <h1>Login</h1>
        <form method="POST" action="#">
            <div class="user-form">
                <input type="text" name="username" placeholder="Insertar usuario*" required value="<?php echo isset($_SESSION['form_data']['username']) ? $_SESSION['form_data']['username'] : ''; ?>" />
            </div>
            <div class="password-form">
                <input type="password" name="password" placeholder="Insertar contraseña*" pattern="^(?=\w*\d)(?=\w*[A-Z])(?=\w*[a-z])\S{8,16}$" required value="<?php echo isset($_SESSION['form_data']['password']) ? $_SESSION['form_data']['password'] : ''; ?>" />
            </div>
            <div class="submit-form">
                <input type="submit" name="btnlogin" value="Iniciar sesión" />
            </div>
            <div class="notmember-div">
                <label>No es miembro?</label>
                <a href="index.php?method=createAcount">Crear una cuenta</a>
            </div>
        </form>
    </div>
</body>

</html>