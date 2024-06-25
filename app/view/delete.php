<?php

error_reporting(E_ALL);

// Mostrar errores en el navegador
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
session_start();

if (empty($_SESSION)) {
    // Redirigir al usuario al inicio de sesión
    header('Location: index.php');
    exit();
}
require_once(BASE_DIR . 'app/controller/ctrlConnect.php'); // Controlador de la conexión
require_once(BASE_DIR . 'dao/implementations/VinylAuthorDAO.php'); // DAO de la relación N:M entre vinilos y autores
require_once(BASE_DIR . 'app/model/vinyl.php'); // Modelo de vinilo
require_once(BASE_DIR . 'dao/implementations/GenresDAO.php'); // DAO de géneros

$db = new ctrlConnect();
$pdo = $db->connectDB(); // Conectar a la BBDD    

$vinylId = $_GET['IdAlbum']; // Dato obtenido del formulario
$authorId = $_GET['IdAutor']; // Dato obtenido del formulario
$userId = $_SESSION['userid']; // Dato obtenido de la sesión

$vinilo = new Vinyl($pdo, $userId); // Instancia del modelo de vinilo


$vinilo->vinylDAO->deleteVinylByUser($vinylId, $userId); // Borrar el vinilo de la BBDD

/*echo "delete ";*/

//print_r($_GET);
header('Location: index.php?method=userSpace');
exit();
