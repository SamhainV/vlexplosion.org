<?php

// Para hacer la conexión
require_once(BASE_DIR . 'app/controller/ctrlConnect.php');
// Para trabajar con el usuario
require_once(BASE_DIR . 'app/controller/ctrlUserLogin.php');
// Interfaz DAO
//require_once(BASE_DIR . 'dao/implementations/UsuarioDAO.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") { // Si se ha pulsado el botón de confirmar datos
  $username = $_POST["username"]; // Username del formulario
  $email = $_POST["email"]; // Email del formulario
  $password = $_POST["password"]; // Password del formulario
  $passwordConfirm = $_POST["passwordConfirm"]; // Password de confirmación del formulario

  /*********** CONEXION A LA BBDD ************************/
  $db = new ctrlConnect();
  $pdo = $db->connectDB(); // Conectar a la BBDD
  /*********** FIN DE CONEXION A LA BBDD *****************/

  /*

    Pasos a seguir:
      1. Crear instancia de Usuario.
      1. Comprobar que el usuario no existe en la BBDD
      2. Comprobar que el email no existe en la BBDD
      3. Si no existe ni el usuario ni el mail, añadimos el usuario/mail
      4. Si el usuario ya existe, mostramos un mensaje de error
      5. Si el email ya existe, mostramos un mensaje de error  
  */

  $usuario = new User($pdo);
  $usuario->setId(0); // Ponemos un id por defecto(luego cambiara al que le asigne MySQL)
  $usuario->setUsername($username); // Tomado del $_POST
  $usuario->setPassword(password_hash($password, PASSWORD_DEFAULT)); // Tomado del $_POST
  $usuario->setEmail($email); // Tomado del $_POST
  $userId = $usuario->isUserExisting();
  $userMail = $usuario->findEmail($email);

  /*
  echo $usuario->getUserName() . "<br>";
  echo $usuario->getEmail() . "<br>";

  echo $userId . " " . $userMail . "<br>"; //exit;
*/


  /* Si no existe ni el usuario ni el mail, añadimos el usuario/mail */
  if (!$userId && !$userMail) {
    $usuario->addNew($usuario);
    //echo $userId . " " . $userMail . "<br>";exit;
    $_SESSION['username'] = $username;
    $_SESSION['userrol'] = 2; // Por defecto el usuario creado tiene rol: User.
    $_SESSION['userid'] = $usuario->getId();
    $_SESSION['email'] = $email;
    //$db->closeDB(); // Cerrar la conexión
    /*var_dump($_SESSION);*/
    /*exit();*/
    /*echo "el id del usuario es: " . $usuario->getId();*/
    $usuario->addRole(1, "Admin"); // 1 admin 2 user
    $usuario->delRole(1); // 1 admin 2 user

    /*echo "Los roles del usuario son :";*/
    $misRoles = $usuario->getRoles();

    /*foreach ($misRoles as $rol) {
      echo $rol->rol_name . " ";
      echo $rol->id . "<br>";
    }*/

    //exit;
    header('Location: index.php?method=loginSuccess');
    exit();
  } else if ($userId) { // SI es 1 es por que el usuario ya existe */
    /*echo "usuario ya existe<br>";*/
    $_SESSION['error_message'] = "El nombre de usuario ya está en uso. Por favor, elige uno diferente.";
    $_SESSION['form_data'] = ['username' => $username, 'password' => $password, 'passwordConfirm' => $passwordConfirm, 'email' => $email];
  } else if ($userMail) { // SI es 1 es por que el email ya existe */
    /*echo "el email ya existe<br>";*/
    $_SESSION['error_message'] = "El correo electrónico ya está registrado. Por favor, intenta con otro.";
    $_SESSION['form_data'] = ['username' => $username, 'password' => $password, 'passwordConfirm' => $passwordConfirm, 'email' => $email];
  }

  if (isset($_SESSION['error_message'])) {
    /*echo "<script>alert('" . $_SESSION['error_message'] . "');</script>";*/
    unset($_SESSION['error_message']);
  }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" type="text/css" href="css/ccuenta.css" />
  <title>Crear Cuenta</title>
</head>

<body>
  <div id="create-account">
    <h1>Registro de usuarios</h1>

    <!-- Formulario de registro de usuarios -->
    <form method="POST" action="#">
      <input type="text" placeholder="username*" name="username" required value="<?php echo isset($_SESSION['form_data']['username']) ? htmlspecialchars($_SESSION['form_data']['username']) : ''; ?>" />
      <input type="password" class="form-Password" placeholder="Password*" value="<?php echo isset($_SESSION['form_data']['password']) ? htmlspecialchars($_SESSION['form_data']['password']) : ''; ?>" name="password" pattern="^(?=\w*\d)(?=\w*[A-Z])(?=\w*[a-z])\S{8,16}$" required />
      <input type="password" class="form-Password" placeholder="Password*" value="<?php echo isset($_SESSION['form_data']['passwordConfirm']) ? htmlspecialchars($_SESSION['form_data']['passwordConfirm']) : ''; ?>" name="passwordConfirm" pattern="^(?=\w*\d)(?=\w*[A-Z])(?=\w*[a-z])\S{8,16}$" required />
      <input type="email" placeholder="Email*" name="email" required value="<?php echo isset($_SESSION['form_data']['email']) ? htmlspecialchars($_SESSION['form_data']['email']) : ''; ?>" />
      <input type="submit" name="confirmardatos" value="Confirmar datos" />
    </form>
    <?php if (isset($_SESSION['error_message'])) : ?>
      <p><?php echo $_SESSION['error_message']; ?></p>
      <?php unset($_SESSION['error_message']); // Limpia el mensaje de error después de mostrarlo 
      ?>
    <?php endif; ?>
  </div>
  <script>
    document.addEventListener("DOMContentLoaded", () => {
      const passwordInputs = document.getElementsByClassName("form-Password");

      const validatePasswords = () => { // Valida que las contraseñas coincidan
        if (passwordInputs[0].value !== passwordInputs[1].value) {
          passwordInputs[1].setCustomValidity("Las contraseñas no coinciden.");
        } else {
          passwordInputs[1].setCustomValidity("");
        }
      };

      Array.from(passwordInputs).forEach((input) => { // Valida las contraseñas al escribir
        input.oninput = () => {
          // Limpia cualquier mensaje de validación personalizado para reevaluar
          input.setCustomValidity("");
          validatePasswords();
        };
      });

      // Establece el mensaje de error personalizado solo cuando el input es inválido.
      passwordInputs[0].oninvalid = (e) => 
        e.target.setCustomValidity(
          "Password no válida.\nLa contraseña debe tener al entre 8 y 16 caracteres,\n" +
          "al menos un dígito, al menos una minúscula y al menos una mayúscula.\n" +
          "Puede tener otros símbolos."
        );
    });
  </script>
</body>

</html>