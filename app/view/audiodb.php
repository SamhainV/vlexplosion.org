<?php
error_reporting(E_ALL);

// Mostrar errores en el navegador
ini_set('display_errors', 1);

ini_set('display_startup_errors', 1);

if (empty($_SESSION)) {
    header('Location: index.php');
    exit();
}

require_once(BASE_DIR . 'core/database/database.php'); // Controlador de la conexión
require_once(BASE_DIR . 'app/controller/ctrlConnect.php');
require_once(BASE_DIR . 'app/model/vinyl.php'); // Controlador de la conexión
require_once(BASE_DIR . 'dao/implementations/GenresDAO.php'); // DAO de vinilos


var_dump($_GET);
echo "<br>";
var_dump($_SESSION);
$usuario = $_SESSION['username'];
$vinylId = $_GET['IdAutor'];

/*********** CONEXION A LA BBDD ************************/
$db = new ctrlConnect();
$pdo = $db->connectDB(); // Conectar a la BBDD    
/*********** FIN DE CONEXION A LA BBDD *****************/


$vinilo = new Vinyl($pdo, $vinylId);

$dato = $vinilo->vinylAuthorDAO->getCompleteVinylDetailsByVinylIdAndUsername($vinylId, $usuario);

var_dump($dato);

echo "<br>";
echo "<br>";

echo "buscar datos del autor " . $dato[0]['Author_Name'] . " disco " . $dato[0]['Title'];
//$vinylDetails = $vinylAuthorDAO->getCompleteVinylDetailsByVinylIdAndUsername($vinylId, $username);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Consulta de Artistas y Álbumes de vlexplosion.org</title>
    <script>
        function buscarInformacion() {
            var artista = document.getElementById('artista').value;
            var album = document.getElementById('album').value;

            var xhr = new XMLHttpRequest();
            xhr.open('GET', 'consulta.php?artista=' + encodeURIComponent(artista) + '&album=' + encodeURIComponent(album), true);
            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    document.getElementById('resultado').innerHTML = xhr.responseText;
                    setupPagination();
                }
            };
            xhr.send();
        }

        function setupPagination() {
            let currentPage = 0;
            const imagesPerPage = 2;
            const images = document.querySelectorAll('.artist-image');

            function showPage(page) {
                images.forEach((img, index) => {
                    img.style.display = (index >= page * imagesPerPage && index < (page + 1) * imagesPerPage) ? 'block' : 'none';
                });
            }

            function createPagination() {
                const container = document.getElementById('paginationContainer');
                container.innerHTML = '';  // Clear existing pagination buttons
                const totalPages = Math.ceil(images.length / imagesPerPage);

                for (let i = 0; i < totalPages; i++) {
                    const button = document.createElement('button');
                    button.innerText = i + 1;
                    button.addEventListener('click', () => {
                        currentPage = i;
                        showPage(currentPage);
                    });
                    container.appendChild(button);
                }
            }

            showPage(currentPage);
            createPagination();
        }
    </script>
</head>


<body>

<?php

//header('Location: index.php?method=consulta&artista=$dato[0]['Author_Name']');
header('Location: index.php?method=consulta&artista=' . $dato[0]['Author_Name']);


?>


    <h1>Consulta de Artistas y Álbumes</h1>
    <form action="index.php" method="get">
        <input type="hidden" name="method" value="consulta">
        <label for="artista">Nombre del Artista:</label>
        <input type="text" id="artista" name="artista" required>
        <br><br>
        <label for="album">Nombre del Álbum:</label>
        <input type="text" id="album" name="album" required>
        <br><br>
        <input type="submit" value="Buscar">
    </form>
    <div id="resultado"></div>
    <div id="paginationContainer"></div>
    


</body>

</html>
