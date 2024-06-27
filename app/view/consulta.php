<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);



$apiKey = '523532';
$artista = urlencode($_GET['artista']);
$_GET['album'] = 'Memento Mori';


$album = urlencode($_GET['album']);


function removePrefix($url, $prefix) {
    if (substr($url, 0, strlen($prefix)) === $prefix) {
        return substr($url, strlen($prefix));
    }
    return $url;
}

// Obtén información del artista
$urlArtist = "https://theaudiodb.com/api/v1/json/$apiKey/search.php?s=$artista";
$responseArtist = file_get_contents($urlArtist);
if ($responseArtist === FALSE) {
    die("Error al obtener la información del artista.");
}
$dataArtist = json_decode($responseArtist, true);

if (isset($dataArtist['artists'][0])) {
    $artist = $dataArtist['artists'][0];
    $strArtist = $artist['strArtist'];
    $intFormedYear = $artist['intFormedYear'];
    $strWebsite = removePrefix($artist['strWebsite'], 'http://www.theaudiodb.org/');
    $strBiographyEN = $artist['strBiographyEN'];
    $strCountry = $artist['strCountry'];
    $images = array_filter([
        $artist['strArtistThumb'],
        $artist['strArtistCutout'],
        $artist['strArtistClearart'],
        $artist['strArtistWideThumb'],
        $artist['strArtistFanart'],
        $artist['strArtistFanart2'],
        $artist['strArtistFanart3'],
        $artist['strArtistFanart4']
    ]);
    //$strWikipedia = $artist['strWikipediaID'] ? "https://en.wikipedia.org/wiki/" . $artist['strWikipediaID'] : "";
} else {
    echo "No se encontró información del artista.";
    exit;
}

// Obtén información del álbum
$urlAlbum = "https://theaudiodb.com/api/v1/json/$apiKey/searchalbum.php?s=$artista&a=$album";
$responseAlbum = file_get_contents($urlAlbum);
if ($responseAlbum === FALSE) {
    die("Error al obtener la información del álbum.");
}
$dataAlbum = json_decode($responseAlbum, true);

if ($dataAlbum['album']) {
    // Filtrar el álbum específico
    $filteredAlbum = null;
    foreach ($dataAlbum['album'] as $alb) {
        if (strtolower($alb['strAlbum']) === strtolower($_GET['album'])) {
            $filteredAlbum = $alb;
            break;
        }
    }

    if ($filteredAlbum) {
        echo "<h2>" . $filteredAlbum['strAlbum'] . "</h2>";
        echo "<img src='" . $filteredAlbum['strAlbumThumb'] . "' alt='" . $filteredAlbum['strAlbum'] . "' style='width:200px;'><br>";
        echo "<p><strong>Año de Lanzamiento:</strong> " . $filteredAlbum['intYearReleased'] . "</p>";
        echo "<p><strong>Estilo:</strong> " . $filteredAlbum['strStyle'] . "</p>";
        echo "<p><strong>Género:</strong> " . $filteredAlbum['strGenre'] . "</p>";
        echo "<p><strong>Etiqueta:</strong> " . $filteredAlbum['strLabel'] . "</p>";
        echo "<p><strong>Formato de Lanzamiento:</strong> " . $filteredAlbum['strReleaseFormat'] . "</p>";
        echo "<p><strong>Descripción:</strong> " . $filteredAlbum['strDescriptionEN'] . "</p>";

        // Obtén información de las canciones del álbum
        $albumId = $filteredAlbum['idAlbum'];
        $urlTracks = "https://theaudiodb.com/api/v1/json/$apiKey/track.php?m=$albumId";
        $responseTracks = file_get_contents($urlTracks);
        if ($responseTracks === FALSE) {
            die("Error al obtener la información de las canciones.");
        }
        $dataTracks = json_decode($responseTracks, true);

        if ($dataTracks['track']) {
            echo "<h3>Lista de Canciones:</h3><ul>";
            foreach ($dataTracks['track'] as $track) {
                echo "<li>" . $track['strTrack'] . "</li>";
            }
            echo "</ul>";
        } else {
            echo "<p>No se encontraron canciones para este álbum.</p>";
        }
    } else {
        echo "No se encontró el álbum específico.";
    }
} else {
    echo "No se encontraron resultados.";
}

// Información del Artista
echo "<h2>Información del Artista</h2>";
echo "<p><strong>Nombre:</strong> $strArtist</p>";
echo "<p><strong>Año de Formación:</strong> $intFormedYear</p>";
echo "<p><strong>Sitio Web:</strong> <a href='http://$strWebsite' target='_blank'>$strWebsite</a></p>";
echo "<p><strong>Biografía:</strong> $strBiographyEN</p>";
echo "<p><strong>País:</strong> $strCountry</p>";

echo "<div id='imageContainer'>";
foreach ($images as $index => $image) {
    echo "<img src='$image' alt='Artist Image' class='artist-image' style='display: none; width: 200px;' data-index='$index'><br>";
}
echo "</div>";

/*if ($strWikipedia) {
    echo "<p><strong>Más información en Wikipedia:</strong> <a href='$strWikipedia' target='_blank'>Wikipedia</a></p>";
}*/
?>
<script>
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
            container.innerHTML = '';
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

    document.addEventListener('DOMContentLoaded', setupPagination);
</script>
