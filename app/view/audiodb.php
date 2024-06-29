<?php
// audiodb.php

// Activar reporte de errores para debug (puedes comentar estas líneas en producción)
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

// Incluir archivos necesarios y obtener datos del vinilo
require_once(BASE_DIR . 'core/database/database.php'); // Controlador de la conexión
require_once(BASE_DIR . 'app/controller/ctrlConnect.php');
require_once(BASE_DIR . 'app/model/vinyl.php'); // Controlador de la conexión
require_once(BASE_DIR . 'dao/implementations/GenresDAO.php'); // DAO de vinilos

// Obtener el nombre de usuario de la sesión
//session_start();
$usuario = $_SESSION['username'] ?? '';

// Obtener el ID del vinilo desde GET
$vinylId = $_GET['IdAutor'] ?? '';

// Conectar a la base de datos
$db = new ctrlConnect();
$pdo = $db->connectDB();

// Obtener detalles completos del vinilo
$vinilo = new Vinyl($pdo, $vinylId);
$dato = $vinilo->vinylAuthorDAO->getCompleteVinylDetailsByVinylIdAndUsername($vinylId, $usuario);

// Obtener autor y título del vinilo
$autor = $dato[0]['Author_Name'];
$titulo = $dato[0]['Title'];

// Construir la URL de la API de TheAudioDB con tu clave API y los datos del vinilo
$apiKey = '523532'; // Tu clave API de TheAudioDB
$url = "https://www.theaudiodb.com/api/v1/json/{$apiKey}/searchalbum.php?s={$autor}&a={$titulo}";

// Generar código JavaScript para realizar la llamada AJAX internamente y procesar la respuesta
echo <<<HTML
<script>
    // Llamar a audiodb.php mediante AJAX internamente
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function() {
        if (this.readyState === 4 && this.status === 200) {
            var response = JSON.parse(this.responseText); // Parsear la respuesta JSON
            console.log(response); // Mostrar la respuesta JSON en consola (para propósitos de debug)
            
            // Mostrar los datos en la página HTML
            var resultadoDiv = document.getElementById('resultado');
            resultadoDiv.innerHTML = ''; // Limpiar el contenido previo, si lo hubiera
            
            // Verificar si se encontraron álbumes
            if (response && response.album && response.album.length > 0) {
                var album = response.album[0]; // Tomar el primer álbum encontrado (puedes iterar si hay más)
                
                // Construir el HTML para mostrar los datos
                var html = '<h2>' + album.strAlbum + '</h2>';
                html += '<p><strong>Artista:</strong> ' + album.strArtist + '</p>';
                html += '<p><strong>Género:</strong> ' + album.strGenre + '</p>';
                html += '<p><strong>Año de lanzamiento:</strong> ' + album.intYearReleased + '</p>';
                html += '<p><strong>Descripción:</strong> ' + album.strDescriptionEN + '</p>';
                html += '<img src="' + album.strAlbumThumb + '" alt="' + album.strAlbum + '">'; // Mostrar la imagen del álbum

                // Mostrar el HTML generado en el div resultado
                resultadoDiv.innerHTML = html;
            } else {
                resultadoDiv.innerHTML = '<p>No se encontraron álbumes.</p>';
            }
        }
    };
    xhr.open('GET', '{$url}', true);
    xhr.send();
</script>
HTML;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles del Vinilo</title>
</head>
<body>
    <div id="resultado">
        <!-- Aquí se mostrarán los detalles del álbum -->
    </div>
</body>
</html>
