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
    // Variable global para almacenar la respuesta JSON
    var albumDetails = null;

    // Llamar a audiodb.php mediante AJAX internamente
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function() {
        if (this.readyState === 4 && this.status === 200) {
            var response = JSON.parse(this.responseText); // Parsear la respuesta JSON
            console.log(response); // Mostrar la respuesta JSON en consola (para propósitos de debug)
            
            // Almacenar la respuesta en la variable global albumDetails
            albumDetails = response.album && response.album.length > 0 ? response.album[0] : null;

            // Llamar a una función para mostrar los detalles del álbum
            mostrarDetallesAlbum();
        }
    };
    xhr.open('GET', '{$url}', true);
    xhr.send();

    // Función para mostrar los detalles del álbum en el DOM
    function mostrarDetallesAlbum() {
        var resultadoDiv = document.getElementById('resultado');
        resultadoDiv.innerHTML = ''; // Limpiar el contenido previo, si lo hubiera

        if (albumDetails) {
            var html = '<h2>' + albumDetails.strAlbum + '</h2>';
            html += '<p><strong>Artista:</strong> ' + albumDetails.strArtist + '</p>';
            html += '<p><strong>Género:</strong> ' + albumDetails.strGenre + '</p>';
            
            // Ejemplo de mostrar la descripción basado en una condición
            var mostrarDescripcion = true;
            if (mostrarDescripcion && albumDetails.strDescriptionEN) {
                html += '<p><strong>Descripción:</strong> ' + albumDetails.strDescriptionEN + '</p>';
            }
            
            html += '<img src="' + albumDetails.strAlbumThumb + '" alt="' + albumDetails.strAlbum + '">'; // Mostrar la imagen del álbum

            // Mostrar el HTML generado en el div resultado
            resultadoDiv.innerHTML = html;
        } else {
            resultadoDiv.innerHTML = '<p>No se encontraron álbumes.</p>';
        }
    }
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
