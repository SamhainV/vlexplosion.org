<?php

//session_start();

if (empty($_SESSION)) {
    // Redirigir al usuario al inicio de sesión
    header('Location: index.php');
    exit();
}

require_once(BASE_DIR . 'core/database/database.php'); // Controlador de la conexión
require_once(BASE_DIR . 'dao/implementations/GenresDAO.php'); // DAO de vinilos
require_once(BASE_DIR . 'dao/implementations/recordLabelDAO.php'); // DAO de la relación N:M entre vinilos y autores

// Para hacer la conexión
require_once(BASE_DIR . 'app/controller/ctrlConnect.php');

// Implementación del DAO
require_once(BASE_DIR . 'dao/implementations/UserDAO.php');
//require_once(BASE_DIR . 'app/controller/ctrlUserSpace.php');
require_once(BASE_DIR . 'dao/implementations/VinylDAO.php');

require_once(BASE_DIR . 'app/model/user.php');

if (isset($_SESSION['userid']) && isset($_SESSION['username'])) {
    /* Toma de datos del usuario */
    $userId = $_SESSION['userid'];
    $userName = $_SESSION['username'];
    $userEmail = $_SESSION['email'];

    /*********** CONEXION A LA BBDD ************************/
    $db = new ctrlConnect();
    $pdo = $db->connectDB(); // Conectar a la BBDD    
    /*********** FIN DE CONEXION A LA BBDD *****************/
    $usuario = new User($pdo, $userId, $userName, null, $userEmail); // Instanciamos el usuario

    $usuario->setUserName($userName); // Establecemos el nombre de usuario

} else {
    echo "error";
    exit;
}

// Verifica si el formulario fue enviado
// Este codigo se ejecuta cuando pulsamos el boton Confirmar Datos en la vista modifyuser.php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verifica si los campos específicos están establecidos y no están vacíos
    if (isset($_POST['password'], $_POST['passwordConfirm'], $_POST['email'])) {
        // Almacena los valores de los campos
        $password = $_POST['password'];
        $passwordConfirm = $_POST['passwordConfirm'];
        $email = $_POST['email'];
        $usuario->setPassword(password_hash($password, PASSWORD_DEFAULT)); // Tomado del $_POST
        $usuario->setEmail($email); // Tomado del $_POST 
        $usuario->update($usuario); // Actualiza los datos del usuario
    } else {
        echo 'Todos los campos son requeridos.';
    }
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listados colecciones</title>
    <link rel="stylesheet" type="text/css" href="css/table.css">
    <link rel="stylesheet" type="text/css" href="css/userspace.css">
    <link rel="stylesheet" type="text/css" href="css/menu.css">
    <link rel="stylesheet" href="css/styles.css" />
</head>

<body>
    <?php
    $dato = $usuario->vinyl->getRecords($userName,  null); // Obtenemos los discos del usuario
    ?>
    <!--
        Item Listar generos musicales del usuario, del submenu usuario logueado.
    -->
    <div class="dropdown">
        <button class="dropbtn">Usuario logueado <?php echo $userName; ?></button>
        <div class="dropdown-content">
            <div class="sub-dropdown">
                <a href="#">Generos musicales</a>
                <div class="sub-dropdown-content">
                    <?php
                    //$generos = $generoDao->getGenresByUserId($userId);
                    $generos = $usuario->vinyl->getUserGenero();
                    if (!empty($generos)) {
                        foreach ($generos as $value) {
                            echo '<a href="index.php?method=listItems&action=genre&genre=' .
                                urlencode($value->Genre_Name) .
                                '" class="ajax-link">' .
                                htmlspecialchars($value->Genre_Name) . '</a>';
                        }
                    } else {
                        echo "sin datos";
                    }
                    ?>
                </div>
            </div>
            <!-- Nuevo sub-menú para artistas -->
            <div class="sub-dropdown">
                <a href="#">Artistas / Bandas</a>

                <div class="sub-dropdown-content">
                    <?php
                    if (!empty($dato)) {
                        $autoresVistos = [];  // Array para guardar los autores ya mostrados
                        foreach ($dato as $artista) {
                            $nombreAutor = $artista['Author_Name'];
                            if (!in_array($nombreAutor, $autoresVistos)) {  // Verificar si el autor ya fue procesado
                                echo "<a href=\"index.php?method=listItems&action=autor&autor=$nombreAutor\" class=\"ajax-link\">$nombreAutor</a>";
                                $autoresVistos[] = $nombreAutor;  // Añadir el autor al array de control
                            }
                        }
                    } else {
                        echo "sin datos";
                    }
                    ?>
                </div>
            </div>
            <!--
                Item Listar discograficas del usuario, del submenu usuario logueado.
            -->
            <div class="sub-dropdown">
                <a href="#">Discográficas</a>
                <div class="sub-dropdown-content">
                    <?php
                    // $recordLabel = $recordLabelDao->getRecordLabelsByUserId($userId);
                    $recordLabel = $usuario->vinyl->recordLabelDAO->getRecordLabelsByUserId($userId);
                    if (!empty($recordLabel)) {
                        foreach ($recordLabel as $value) {
                            echo '<a href="index.php?method=listItems&action=label&label=' .
                                urlencode($value->Record_Label_Name) .
                                '" class="ajax-link">' .
                                htmlspecialchars($value->Record_Label_Name) . '</a>';
                        }
                    } else {
                        echo "sin datos";
                    }
                    ?>
                </div>
            </div>
            <!--
                Item Listar discos favoritos, del submenu usuario logueado.
            -->
            <a href="index.php?method=favorites">Favoritos</a>
            <a href="index.php?method=desired">Más buscados</a>

            <a href="index.php?method=modifyuser">Modificar usuario</a>
            <a href="index.php?method=logout">Cerrar sesión</a>
        
        </div>
    </div>

    <div class="panel">
        <h1 class="text-center">Listado completo. Discográfia</h1>
        <a href="index.php?method=addNew" class="btn nuevo">Nuevo</a>
        <div class="datos-container">
            <?php
            $records_per_page = 5;  // Registros por página 
            $total_records = count($dato);    // Calcula el total de registros
            $total_pages = ceil($total_records / $records_per_page); // Calcula el total de páginas que van a salir

            $page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Página actual
            $page = max($page, 1); // Si la página es menor que 1, la página actual será 1
            $page = min($page, $total_pages); // Si la página es mayor que el total de páginas, la página actual será la última

            $start = ($page - 1) * $records_per_page; // Calcula el inicio de la consulta
            $dato_page = array_slice($dato, $start, $records_per_page); // Extrae los registros de la página actual
            
            if (!empty($dato_page)) {
                foreach ($dato_page as $value) { ?>
                    <div class="fila">
                        <div class="celda autor" title="Nombre del autor"><?php echo $value['Author_Name']; ?></div>
                        <div class="celda titulo" title="Titulo del album"><?php echo $value['Title']; ?></div>
                        <div class="celda genero" title="Género musical"><?php echo $value['Genre_Name']; ?></div>
                        <div class="celda formato" title="Fomato del album"><?php echo $value['Format_Name']; ?></div>
                        <div class="celda estado" title="Estado de conservación"><?php echo $value['Condition_Name']; ?></div>
                        <div class="celda discografica" title="Nombre de la discográfica"><?php echo $value['Record_Label_Name']; ?></div>
                        <div class="celda productor" title="Nombre del productor"><?php echo $value['Producer']; ?></div>
                        <div class="celda lanzamiento" title="Fecha de lanzamiento"><?php echo $value['Release_date']; ?></div>
                        <div class="celda edicion" title="Edición"><?php echo $value['Edition_Name']; ?></div>
                        <div class="celda acciones">
                            <a class="btn editar" href="index.php?method=edit&IdAutor=<?php echo $value['AutorId']; ?>" onclick="window.scroll({top: 0, left: 0, behavior: 'smooth'});">Editar</a>
                            <a class="btn editar" href="index.php?method=audiodb&IdAutor=<?php echo $value['AutorId']; ?>" >Información extra (AudioDB)</a>
                            <a class="btn" href="index.php?method=delete&IdAutor=<?php echo $value['AutorId']; ?>&IdAlbum=<?php echo $value['Vinilo_Id']; ?>" onclick="return confirm('Está seguro S/N');">Eliminar</a>

                        </div>
                    </div>
                <?php }
            } else { ?>
                <div class="fila">
                    <div class="celda">No hay registros</div>
                </div>
            <?php } ?>
        </div>

        <!-- Lógica de paginación -->
        <div class="pagination">
            <?php if ($page > 1) :  // Si la página actual es mayor que 1, muestra el enlace a la página anterior 
            ?>
                <a href="index.php?method=userSpace&page=<?php echo $page - 1; ?>">&laquo; Anterior</a>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $total_pages; $i++) : // Muestra los enlaces a las páginas 
            ?>
                <a href="index.php?method=userSpace&page=<?php echo $i; ?>" <?php if ($page == $i) echo 'class="active"'; ?>><?php echo $i; ?></a>
            <?php endfor; ?>

            <?php if ($page < $total_pages) : // Si la página actual es menor que el total de páginas, muestra el enlace a la página siguiente 
            ?>
                <a href="index.php?method=userSpace&page=<?php echo $page + 1; ?>">Siguiente &raquo;</a>
            <?php endif; ?>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.body.addEventListener('click', function(event) {
                const target = event.target;

                // Comprobar si el elemento clicado es un enlace <a>
                if (target.tagName === 'A' && target.href) {
                    // Lista de patrones de enlaces a excluir
                    const excludePatterns = [
                        '#', 'logout.php',
                        'index.php?page=',
                        /*'index.php?method=delete',*/
                        'index.php?method=delete&IdAutor=',
                        'index.php?method=userSpace&page=',
                        'index.php?method=logout',
                        'index.php?method=audiodb&IdAutor=',
                    ];

                    // Verifica si el href del enlace contiene algún patrón de exclusión
                    const shouldExclude = excludePatterns.some(pattern => target.getAttribute('href').includes(pattern));

                    if (shouldExclude) {
                        return; // Retorna y permite la acción por defecto (importante para el enlace de eliminación)
                    }

                    // Previene la navegación tradicional para enlaces no excluidos
                    event.preventDefault();

                    const url = target.getAttribute('href'); // Obtiene la URL del enlace
                    const targetId = target.getAttribute('data-target') || 'contenido'; // Obtiene el ID del contenedor o default a 'contenido'

                    // Realizar la solicitud AJAX
                    const xhr = new XMLHttpRequest();
                    xhr.open('GET', url, true);
                    xhr.onload = function() {
                        if (xhr.status >= 200 && xhr.status < 300) {
                            const container = document.getElementById(targetId);
                            if (container) {
                                container.innerHTML = xhr.responseText; // Actualiza el contenido en el div especificado

                                // Ejecuta el script para modificar el usuario si el contenido cargado es el formulario de modificación
                                if (url.includes('index.php?method=modifyuser')) {
                                    initModifyUserScript();
                                }
                            } else {
                                console.error('Error: No se encontró el contenedor especificado');
                            }
                        } else {
                            console.error('Error al cargar el contenido:', xhr.statusText);
                        }
                    };
                    xhr.onerror = function() {
                        console.error('Error de red');
                    };
                    xhr.send();
                }
            });
        });

        function initModifyUserScript() {
            console.log('Script de modificación de usuario inicializado');

            const form = document.getElementById('modifyForm');
            if (form) {
                const password = document.getElementById('password');
                const passwordConfirm = document.getElementById('passwordConfirm');

                form.addEventListener('submit', function(event) {
                    console.log("Formulario enviado");
                    const passwordValue = password.value;
                    const passwordConfirmValue = passwordConfirm.value;
                    const passwordPattern = /^(?=.*\d)(?=.*[A-Z])(?=.*[a-z])\S{8,16}$/;

                    console.log("Password:", passwordValue);
                    console.log("Password Confirm:", passwordConfirmValue);

                    // Verifica si las contraseñas son iguales
                    if (passwordValue !== passwordConfirmValue) {
                        alert('Las contraseñas no coinciden.');
                        event.preventDefault();
                        return;
                    }

                    // Verifica si la contraseña cumple con el patrón
                    if (!passwordPattern.test(passwordValue)) {
                        alert('La contraseña debe tener entre 8 y 16 caracteres, al menos un número, una letra mayúscula y una letra minúscula.');
                        event.preventDefault();
                        return;
                    }
                });
            }
        }
    </script>

    <div id="contenido"> <!-- Contenedor de la respuesta AJAX -->
    </div>
</body>

</html>