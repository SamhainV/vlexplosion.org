<?php

//session_start();

if (empty($_SESSION)) {
    // Redirigir al usuario al inicio de sesión
    header('Location: index.php');
    exit();
}

/*
Hay que instanciar un usuario.
*/

// Para hacer la conexión
require_once(BASE_DIR . 'app/controller/ctrlConnect.php');
require_once(BASE_DIR . 'app/model/user.php');

/*********** CONEXION A LA BBDD ************************/
$db = new ctrlConnect();
$pdo = $db->connectDB(); // Conectar a la BBDD    
/*********** FIN DE CONEXION A LA BBDD *****************/

$usuario = new User($pdo); // Instanciamos el usuario
$usuario->setUserName($_SESSION['username']); // Seteamos el nombre de usuario

//echo "la acción es " . $_SESSION['action'];exit;
$condiciones = [];

switch ($_SESSION['action']) { // Validamos la acción
    case 'genre': // Si la acción es género
        $condiciones = [
            'Genre_Name' => $_SESSION['genre']
        ];
        break;
    case 'autor': // Si la acción es autor
        $condiciones = [
            'Author_Name' => $_SESSION['autor']
        ];
        break;
    case 'label': // Si la acción es label
        $condiciones = [
            'Record_Label_Name' => $_SESSION['label']
        ];
        break;
    default:
        break;
}


$discos = $usuario->vinyl->getRecords($usuario->getUserName(), $condiciones); // Obtenemos los discos del usuario
//var_dump($_SESSION);
//var_dump($discos);exit;
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" type="text/css" href="css/listitems.css" />
</head>

<body>
    <div class="container">
        <form class="entradaDatosForm" action="#" method="GET">
            <input onclick="window.location.href='index.php?method=userSpace';" type="button" value="&#11013;" name="back" title="Volver atrás sin salvar cambios">

            <div class="table-container">
                <div class="table"> <!-- Tabla de discos -->
                    <div class="table-header">
                        <div class="table-cell">Autor</div>
                        <div class="table-cell">Título</div>
                        <div class="table-cell">Formato</div>
                        <div class="table-cell"></div>
                        <div class="table-cell table-cell-condition">
                            <?php
                            // Mostrar el contenido de $condiciones una vez
                            foreach ($condiciones as $key => $value) {
                                echo htmlspecialchars("$value"); // Muestra el valor de la condición
                            }
                            ?>
                        </div>

                    </div>

                    <?php if (!empty($discos)) : ?>
                        <?php foreach ($discos as $disco) : ?>
                            <div class="table-row">
                                <div class="table-cell"><?= htmlspecialchars($disco['Author_Name']) ?></div>
                                <div class="table-cell"><?= htmlspecialchars($disco['Title']) ?></div>
                                <div class="table-cell"><?= htmlspecialchars($disco['Format_Name']) ?></div>
                                <div class="table-cell"></div>

                                <div class="table-cell">
                                    <a href="index.php?method=edit&IdAutor=<?php echo $disco['AutorId']; ?>" onclick="window.scroll({top: 0, left: 0, behavior: 'smooth'});">Editar</a>

                                    <!--<a href="#" onclick="editarAutor('<?= htmlspecialchars($disco['Author_Name']) ?>')">Editar</a>-->
                                </div>
                                <div class="table-cell">
                                    <!--                                    <a href="#" onclick="eliminarAutor('<?= htmlspecialchars($disco['Author_Name']) ?>')">Eliminar</a>-->
                                    <a class="btn" href="index.php?method=delete&IdAutor=<?php echo $disco['AutorId']; ?>&IdAlbum=<?php echo $disco['Vinilo_Id']; ?>" onclick="return confirm('Está seguro S/N');">Eliminar</a>

                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <div class="table-row">
                            <div class="table-cell" colspan="5">Sin datos</div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </form>
    </div>
</body>

</html>