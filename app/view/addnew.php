<?php

error_reporting(E_ALL);

// Mostrar errores en el navegador
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
//session_start();

if (empty($_SESSION)) {
  // Redirigir al usuario al inicio de sesión
  header('Location: index.php');
  exit();
}


require_once(BASE_DIR . 'app/controller/ctrlConnect.php'); // Controlador de la conexión
require_once(BASE_DIR . 'dao/implementations/GenresDAO.php'); // DAO de géneros
require_once(BASE_DIR . 'dao/implementations/formatDAO.php'); // DAO de formatos
require_once(BASE_DIR . 'dao/implementations/conditionDAO.php'); // DAO de condiciones
require_once(BASE_DIR . 'dao/implementations/recordLabelDAO.php'); // DAO de discográficas
require_once(BASE_DIR . 'dao/implementations/editionDAO.php'); // DAO de ediciones
require_once(BASE_DIR . 'dao/implementations/authorDAO.php'); // DAO de autores
require_once(BASE_DIR . 'dao/implementations/VinylDAO.php'); // DAO de vinilos
require_once(BASE_DIR . 'dao/implementations/VinylAuthorDAO.php'); // DAO de la relación N:M entre vinilos y autores
require_once(BASE_DIR . 'app/model/vinyl.php'); // Modelo de vinilo
require_once(BASE_DIR . 'app/model/author.php'); // Modelo de autor
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Formulario de entrada de datos</title>
  <link rel="stylesheet" type="text/css" href="css/new.css" />
</head>

<body>
  <?php
  $db = new ctrlConnect(); // Controlador de la conexión
  $pdo = $db->connectDB(); // Conectar a la BBDD    

  $generosDao = new genreDAO($pdo); // Instancia del DAO de géneros
  $formatosDao = new formatDAO($pdo); // Instancia del DAO de formatos
  $conditionDao = new conditionDAO($pdo); // Instancia del DAO de condiciones
  $recordLabelDao = new recordLabelDAO($pdo); // Instancia del DAO de discográficas
  $editionDao = new editionDAO($pdo); // Instancia del DAO de ediciones
  $authorDAO = new AuthorDAO($pdo); // Instancia del DAO de autores
  $vinylDAO = new VinylDAO($pdo); // Instancia del DAO de vinilos
  $vinylAuthorDAO = new VinylAuthorDAO($pdo); // Instancia del DAO de la relación N:M entre vinilos y autores

  if (isset($_GET['addReg'])) { // Si se ha pulsado el botón de añadir registro
    /* Inserta el autor */
    $author = new Author($pdo, null, null, null, null, null); // Instancia del modelo de autor
    $author->Author_Name = $_GET['Author_Name']; // Nombre del autor obtenido del formulario
    $authorId = $authorDAO->insert($author); // Insertar el autor en la BBDD

    /* Inserta el vinilo */
    $vinilo = new Vinyl($pdo, $_SESSION['userid']);
    $vinilo->Title = $_GET['TitleOfRecord'];
    $vinilo->Genres_Id = $_GET['Genres_Id'];
    $vinilo->Format_Id = $_GET['Vinyl_Format'];
    $vinilo->Condition_Id = $_GET['Vinyl_Condition'];
    $vinilo->Record_Label_Id = $_GET['Record_Label'];
    $vinilo->Producer = $_GET['Producer'];
    $vinilo->Release_date = $_GET['Release_date'];
    $vinilo->Edition_Id = $_GET['Edition'];
    $vinilo->User_Id = $_SESSION['userid'];
    $vinilo->Is_Favorite = isset($_GET['Is_Favorite']) ? 1 : 0;
    $vinilo->Is_Desired = isset($_GET['Is_Desired']) ? 1 : 0;
    $vinylId = $vinylDAO->insert($vinilo);

    /* Inserta en la tabla intermedia de la relación N:M */
    $vinylAuthorDAO->addAuthorToVinyl($vinylId, $authorId);

    header('Location: index.php?method=userSpace'); // Redirigir al usuario a su espacio
  }
  ?>

  <section> <!-- Formulario de entrada de datos -->
    <form class="entradaDatosForm" action="#" method="GET">
      <input type="hidden" name="method" value="addNew">
      <input onclick="window.location.href='index.php?method=userSpace';" type="button" value="&#11013;" name="back" title="Volver atrás sin salvar cambios">
      <input type="text" placeholder="Titulo" name="TitleOfRecord" title="Titulo del albúm" required />

      <select name="Genres_Id" title="Género musical" required>
        <option value="" disabled selected hidden>Genero Musical</option>
        <?php
        $arrayGeneros = $generosDao->findAll();
        foreach ($arrayGeneros as $genre) {
          echo "<option value=\"" . htmlspecialchars($genre->Id) . "\">" . htmlspecialchars($genre->Genre_Name) . "</option>";
        }
        ?>
      </select>

      <input type="text" placeholder="Autor" name="Author_Name" title="Autor / Banda" required />
      
      <select name="Vinyl_Format" required>
        <option value="" disabled selected hidden>Formato</option>
        <?php
        $arrayFormatos = $formatosDao->findAll();
        foreach ($arrayFormatos as $formato) {
          echo "<option value=\"" . htmlspecialchars($formato->Id) . "\">" . htmlspecialchars($formato->Format_Name) . "</option>";
        }
        ?>
      </select>

      <select name="Vinyl_Condition" required>
        <option value="" disabled selected hidden>Estado de conservación</option>
        <?php
        $arrayCondition = $conditionDao->findAll();
        foreach ($arrayCondition as $condition) {
          echo "<option value=\"" . htmlspecialchars($condition->Id) . "\">" . htmlspecialchars($condition->Condition_Name) . "</option>";
        }
        ?>
      </select>

      <select name="Record_Label" required>
        <option value="" disabled selected hidden>Discográfica</option>
        <?php
        $arrayRecordLabel = $recordLabelDao->findAllRecordLabel();
        foreach ($arrayRecordLabel as $recordLabels) {
          echo "<option value=\"" . htmlspecialchars($recordLabels->Id) . "\">" . htmlspecialchars($recordLabels->Record_Label_Name) . "</option>";
        }
        ?>
      </select>

      <input type="text" placeholder="Producer" name="Producer" required/>

      <select name="Release_date" id="yearSelect" required>
        <option value="" disabled hidden>Año de lanzamiento</option>
        <?php
        $currentYear = date("Y");
        $startYear = 1948;
        for ($year = $startYear; $year <= $currentYear; $year++) {
          $selected = ($year == $currentYear) ? 'selected' : '';
          echo "<option value='$year' $selected>$year</option>";
        }
        ?>
      </select>

      <select name="Edition" required>
        <option value="" disabled selected hidden>Edición</option>
        <?php
        $arrayEdition = $editionDao->findAll();
        foreach ($arrayEdition as $edition) {
          echo "<option value=\"" . htmlspecialchars($edition->Id) . "\">" . htmlspecialchars($edition->Edition_Name) . "</option>";
        }
        ?>
      </select>

      <label>
        <input type="checkbox" name="Is_Favorite"> Favorito
      </label>
      <label>
        <input type="checkbox" name="Is_Desired"> Deseado
      </label>

      <input type="submit" name="addReg" value="Añadir Registro">
    </form>
  </section>
</body>
</html>
