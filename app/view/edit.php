<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
//session_start();

if (empty($_SESSION)) {
    header('Location: index.php');
    exit();
}

require_once(BASE_DIR . 'app/controller/ctrlConnect.php'); // Controlador de la conexión
require_once(BASE_DIR . 'dao/implementations/GenresDAO.php'); // DAO de géneros
require_once(BASE_DIR . 'dao/implementations/formatDAO.php'); // DAO de formatos
require_once(BASE_DIR . 'dao/implementations/conditionDAO.php'); // DAO de condiciones
require_once(BASE_DIR . 'dao/implementations/recordLabelDAO.php'); // DAO de discográficas
require_once(BASE_DIR . 'dao/implementations/editionDAO.php'); // DAO de ediciones
require_once(BASE_DIR . 'dao/implementations/VinylDAO.php'); // DAO de vinilos
require_once(BASE_DIR . 'dao/implementations/VinylAuthorDAO.php'); // DAO de la relación N:M entre vinilos y autores
require_once(BASE_DIR . 'app/model/vinyl.php'); // Modelo de vinilo
require_once(BASE_DIR . 'app/model/author.php'); // Modelo de autor
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Formulario de entrada de datos</title>
    <link rel="stylesheet" type="text/css" href="css/new.css" />
    <!--<script src="View/js/javaScript.js"></script>-->
</head>

<body>
    <?php
    $db = new ctrlConnect(); // Controlador de la conexión
    $pdo = $db->connectDB(); // Conectar a la BBDD

    $generosDao = new genreDAO($pdo); // Instancia del DAO de géneros
    $formatosDao = new formatDAO($pdo); // Instancia del DAO de formatos
    $conditionsDao = new conditionDAO($pdo); // Instancia del DAO de condiciones
    $recordLabelDao = new recordLabelDAO($pdo); // Instancia del DAO de discográficas
    $editionsDao = new editionDAO($pdo); // Instancia del DAO de ediciones
    $vinylDAO = new VinylDAO($pdo); // Instancia del DAO de vinilos
    $vinylAuthorDAO = new VinylAuthorDAO($pdo); // Instancia del DAO de la relación N:M entre vinilos y autores

    $vinylId = $_GET['IdAutor']; // Dato obtenido del formulario
    $username = $_SESSION['username']; // Dato obtenido de la sesión
    $userId = $_SESSION['userid']; // Dato obtenido de la sesión
    // Obtener los detalles de toda la lista de vinilos del usuario
    $vinylDetails = $vinylAuthorDAO->getCompleteVinylDetailsByVinylIdAndUsername($vinylId, $username);

    if (!empty($vinylDetails)) {
        foreach ($vinylDetails as $detail) {
            // Detalles del vinilo
        }
    } else {
        echo "No se encontraron detalles para el vinilo con ID $vinylId y usuario $username.";
    }

    if (isset($_GET['updateReg'])) {
        $author = new Author($pdo, null, null, null, null, null); // Instancia del modelo de autor
        $author->Author_Name = $_GET['Author_Name']; // Nombre del autor obtenido del formulario
        $author->Id = $_GET['IdAutor']; // ID del autor obtenido del formulario
        $author->updateAuthor($author); // Actualizar el autor en la BBDD

        $vinilo = new Vinyl($pdo, $_GET['IdAutor']); // Instancia del modelo de vinilo
        $vinilo->Title = $_GET['TitleOfRecord']; // Título del vinilo obtenido del formulario
        $vinilo->Genres_Id = $_GET['Genres_Id']; // ID del género obtenido del formulario
        $vinilo->Format_Id = $_GET['Vinyl_Format']; // ID del formato obtenido del formulario
        $vinilo->Condition_Id = $_GET['Vinyl_Condition']; // ID del estado de conservación obtenido del formulario
        $vinilo->Record_Label_Id = $_GET['Record_Label']; // ID de la discográfica obtenido del formulario
        $vinilo->Producer = $_GET['Producer']; // Productor obtenido del formulario
        $vinilo->Release_date = $_GET['Release_date']; // Año de lanzamiento obtenido del formulario
        $vinilo->Edition_Id = $_GET['Edition']; // ID de la edición obtenido del formulario
        $vinilo->User_Id = $_SESSION['userid']; // ID del usuario obtenido de la sesión
        $vinilo->Is_Favorite = isset($_GET['Is_Favorite']) ? 1 : 0; // Favorito obtenido del formulario
        $vinilo->Is_Desired = isset($_GET['Is_Desired']) ? 1 : 0; // Deseado obtenido del formulario
        $vinylDAO->update($vinilo); // Actualizar el vinilo en la BBDD

        // Devuelve el control a la página de espacio de usuario
        header('Location: index.php?method=userSpace');
        exit();
    }
    ?>

    <section>
        <form class="entradaDatosForm" action="#" method="GET">
            <input type="hidden" name="method" value="edit">
            <input type="hidden" name="IdAutor" value="<?php echo $_GET['IdAutor']; ?>">

            <input onclick="window.location.href='index.php?method=userSpace';" type="button" value="&#11013;" name="back" title="Volver atrás sin salvar cambios">

            <input type="text" placeholder="Titulo" name="TitleOfRecord" title="Titulo del albúm" value="<?php echo htmlspecialchars($detail['Title']); ?>" required />

            <select name="Genres_Id" title="Género musical" required>
                <option value="" disabled selected hidden>Genero Musical</option>
                <?php
                $genreName = $detail['Genre_Name'];
                $currentGenreId = $generosDao->findByName($genreName);

                $arrayGeneros = $generosDao->findAll();
                foreach ($arrayGeneros as $genre) {
                    $selected = ($genre->Id == $currentGenreId) ? 'selected' : '';
                    echo "<option value=\"" . htmlspecialchars($genre->Id) . "\" $selected>" . htmlspecialchars($genre->Genre_Name) . "</option>";
                }
                ?>
            </select>

            <input type="text" placeholder="Autor" name="Author_Name" title="Autor / Banda" value="<?php echo htmlspecialchars($detail['Author_Name']); ?>" required />

            <select name="Vinyl_Format" required>
                <option value="" disabled selected hidden>Formato</option>
                <?php
                $formatName = $detail['Format_Name'];
                $currentFormatId = $formatosDao->findByName($formatName);

                $arrayFormatos = $formatosDao->findAll();
                foreach ($arrayFormatos as $formatos) {
                    $selected = ($formatos->Id == $currentFormatId) ? 'selected' : '';
                    echo "<option value=\"" . htmlspecialchars($formatos->Id) . "\" $selected>" . htmlspecialchars($formatos->Format_Name) . "</option>";
                }
                ?>
            </select>

            <select name="Vinyl_Condition" required>
                <option value="" disabled selected hidden>Estado de conservación</option>
                <?php
                $conditionName = $detail['Condition_Name'];
                $currentConditionId = $conditionsDao->findByName($conditionName);

                $arrayConditions = $conditionsDao->findAll();
                foreach ($arrayConditions as $conditions) {
                    $selected = ($conditions->Id == $currentConditionId) ? 'selected' : '';
                    echo "<option value=\"" . htmlspecialchars($conditions->Id) . "\" $selected>" . htmlspecialchars($conditions->Condition_Name) . "</option>";
                }
                ?>
            </select>

            <select name="Record_Label" required>
                <option value="" disabled selected hidden>Discográfica</option>
                <?php
                $recordLabelName = $detail['Record_Label_Name'];
                $currentRecordLabelId = $recordLabelDao->findRecordLabelByName($recordLabelName);

                $arrayRecordLabel = $recordLabelDao->findAllRecordLabel();
                foreach ($arrayRecordLabel as $recordLabels) {
                    $selected = ($recordLabels->Id == $currentRecordLabelId) ? 'selected' : '';
                    echo "<option value=\"" . htmlspecialchars($recordLabels->Id) . "\" $selected>" . htmlspecialchars($recordLabels->Record_Label_Name) . "</option>";
                }
                ?>
            </select>

            <input type="text" placeholder="Producer" name="Producer" required value="<?php echo htmlspecialchars($detail['Producer']); ?>" />

            <select name="Release_date" id="yearSelect" required>
                <option value="" disabled hidden>Año de lanzamiento</option>
                <?php
                $currentYear = date("Y");
                $startYear = 1948;
                $vinylReleaseYear = isset($detail['Release_date']) ? $detail['Release_date'] : null;

                for ($year = $startYear; $year <= $currentYear; $year++) {
                    $selected = ($year == $vinylReleaseYear) ? 'selected' : '';
                    echo "<option value='$year' $selected>$year</option>";
                }
                ?>
            </select>

            <select name="Edition" required>
                <option value="" disabled selected hidden>Edición</option>
                <?php
                $editionName = $detail['Edition_Name'];
                $currentEditionId = $editionsDao->findByName($editionName);

                $arrayEdition = $editionsDao->findAll();
                foreach ($arrayEdition as $edition) {
                    $selected = ($edition->Id == $currentEditionId) ? 'selected' : '';
                    echo "<option value=\"" . htmlspecialchars($edition->Id) . "\" $selected>" . htmlspecialchars($edition->Edition_Name) . "</option>";
                }
                ?>
            </select>

            <label>
                <input type="checkbox" name="Is_Favorite" <?php echo $detail['Is_Favorite'] ? 'checked' : ''; ?>> Favorito
            </label>
            <label>
                <input type="checkbox" name="Is_Desired" <?php echo $detail['Is_Desired'] ? 'checked' : ''; ?>> Deseado
            </label>

            <input type="submit" name="updateReg" value="Guardar cambios">
        </form>
    </section>
</body>

</html>

