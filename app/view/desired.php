<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" type="text/css" href="css/desiredfav.css">
</head>

<body>
    <?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    //session_start();

    if (empty($_SESSION)) { // Si no hay nadie logueado.
        header('Location: index.php');
        exit();
    }

    //var_dump($_SESSION);
    require_once(BASE_DIR . 'app/controller/ctrlConnect.php'); // Controlador de la conexión
    require_once(BASE_DIR . 'dao/implementations/VinylDAO.php'); // DAO de vinilos
    require_once(BASE_DIR . 'dao/implementations/VinylAuthorDAO.php'); // DAO de la relación N:M entre vinilos y autores
    require_once(BASE_DIR . 'app/model/vinyl.php'); // Modelo de vinilo
    require_once(BASE_DIR . 'app/model/author.php'); // Modelo de autor
    require_once(BASE_DIR . 'app/model/user.php'); // Modelo de usuario

    if (isset($_SESSION['userid']) && isset($_SESSION['username'])) {
        /* Toma de datos del usuario */
        $userId = $_SESSION['userid'];
        $userName = $_SESSION['username'];
        $userEmail = $_SESSION['email'];

        $db = new ctrlConnect();
        $pdo = $db->connectDB();

        $usuario = new User($pdo, $userId, $userName, null, $userEmail);

        $discosDeseados = $usuario->vinyl->vinylDAO->getDesiredRecords($userId);

        if (!empty($discosDeseados)) { 
            echo '<div class="table">'; // Tabla de discos deseados
            echo '<input class="back-button" onclick="window.location.href=\'index.php?method=userSpace\';" type="button" value="&#11013;" name="back" title="Volver atrás sin salvar cambios">';
            echo '<div class="table-header">';
            echo '<div class="table-cell">Titulo</div>';
            echo '<div class="table-cell">Autor</div>';            
            echo '</div>';

            foreach ($discosDeseados as $disco) {
                echo '<div class="table-row">'; // Fila de la tabla
                echo '<div class="table-cell">' . htmlspecialchars($disco['Title']) . '</div>';
                echo '<div class="table-cell">' . htmlspecialchars($disco['Author_Name']) . '</div>';
                // Botón de editar
                echo '<a class=btn editar" href="index.php?method=edit&IdAutor=' . $disco['Author_Id'] . 
                '" onclick="window.scroll({top: 0, left: 0, behavior: \'smooth\'});">Editar</a>';             
                
                // Botón de eliminar
                echo '<a class="btn" href="index.php?method=delete&IdAutor=' . $disco['Author_Id'] . 
                '&IdAlbum=' . $disco['Vinilo_Id'] . 
                '" onclick="return confirm(\'Está seguro S/N\');">Eliminar</a>';

                echo '</div>';
            }

            echo '</div>';
        } else {
            echo "sin datos";
        }
    }
    ?>

</body>