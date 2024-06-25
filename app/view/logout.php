// logout.php
<?php
ob_start(); // Iniciar el buffer de salida
error_reporting(E_ALL); // Mostrar errores en el navegador
ini_set('display_errors', 1); // Mostrar errores en el navegador
session_start(); // Iniciar la sesión
session_unset(); // Eliminar todas las variables de sesión
session_destroy(); // Destruir la sesión

ob_end_clean();  // Limpiar el buffer y descartar la salida
// Ahora que todo está procesado, se envía la redirección.
header('Location: index.php');
exit();

?>
