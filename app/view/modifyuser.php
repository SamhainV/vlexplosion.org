<?php

/* 
  * Este fichero es el encargado de mostrar el formulario de modificación de usuario
  * y de procesar los datos enviados por el usuario para modificar su cuenta.
*/

//var_dump($_SESSION);


if (empty($_SESSION)) {
    // Redirigir al usuario al inicio de sesión
    header('Location: index.php');
    exit();
}

//echo "email " . $_SESSION['email'];
/*echo "post es " . "<br>";
var_dump($_POST);*/

?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" type="text/css" href="css/modifyuser.css" />
  <title>Crear Cuenta</title>
</head>

<body>
  <div id="create-account">
    <h1>Modificar usuario</h1>

    <form id="modifyForm" method="POST" action="#">
      <input type="text" id="username" name="username" placeholder="Username*" style="display: none;" autocomplete="username" />
      <input type="password" id="password" placeholder="Password*" name="password" autocomplete="new-password" required />
      <input type="password" id="passwordConfirm" placeholder="Confirm Password*" name="passwordConfirm"  autocomplete="new-password" required />
      <input type="email" placeholder="Email*" name="email" required value="<?php echo isset($_SESSION['email']) ? htmlspecialchars($_SESSION['email']) : ''; ?>" />
      <input type="submit" name="confirmardatos" value="Confirmar datos" />
    </form>
    <?php if (isset($_SESSION['error_message'])) : ?> 
      <p><?php echo $_SESSION['error_message']; ?></p>
      <?php unset($_SESSION['error_message']); // Limpia el mensaje de error después de mostrarlo ?>
    <?php endif; ?>
  </div>

  
</body>

</html>
