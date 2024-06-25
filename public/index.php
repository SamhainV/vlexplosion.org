<?php
session_start();

require_once('../core/config/config.php'); // Define BASE_DIR y cláusulas para mostrar errores en PHP.

require_once(BASE_DIR . 'app/controller/vinylCtrl.php');

$controller = new vinylCtrl(); // Crear un objeto del controlador vinylCtrl.

if (isset($_GET['method'])) {
  $method = $_GET['method'];

  if (method_exists($controller, $method)) {
    switch ($method) {
      case 'login':
        $controller->{$method}(); // Llamar al método del objeto si existe
        break;
      case 'createAcount':
        $controller->{$method}(); // Llamar al método del objeto si existe
        break;
      case 'loginSuccess':
        $controller->{$method}(); // Llamar al método del objeto si existe
        break;
      case 'userSpace':
        $controller->{$method}(); // Llamar al método del objeto si existe
        break;
      case 'delete':
        $controller->{$method}(); // Llamar al método del objeto si existe
        break;
      case 'favorites':
        $controller->{$method}(); // Llamar al método del objeto si existe
        break;
      case 'desired':
        $controller->{$method}(); // Llamar al método del objeto si existe
        break;
      case 'modifyuser':
        $controller->{$method}(); // Si existe, llamar al método del objeto.
        break;
      case 'audiodb':
        $controller->{$method}(); // Si existe, llamar al método del objeto.
        break;
      case 'consulta':
        $controller->{$method}(); // Si existe, llamar al método del objeto.
        break;
      case 'listItems':
        $action = isset($_GET['action']) ? $_GET['action'] : null; // recoge la acción.
        $_SESSION['action'] = $action; // Guardar la acción en la sesión.
        switch ($action) { // Evalua la acción.
          case 'genre': // Si es género.
            $_SESSION['genre'] = isset($_GET['genre']) ? $_GET['genre'] : null;;
            $controller->{$method}(); // Pasar el género al método
            break;
          case 'autor': // Si es autor.
            $_SESSION['autor'] = isset($_GET['autor']) ? $_GET['autor'] : null;;
            $controller->{$method}(); // Pasar el autor al método
            break;
          case 'label': // Si es label.
            $_SESSION['label'] = isset($_GET['label']) ? $_GET['label'] : null;;
            $controller->{$method}(); // Pasar el autor al método
            break;
          default:
            break;
        }
        break;
      case 'addNew':
        $controller->{$method}(); // Si existe, llamar al método del objeto.
        break;
      case 'edit':
        $controller->{$method}(); // Si existe, llamar al método del objeto.
        break;
      case 'manageUsers':
        $controller->{$method}(); // Si existe, llamar al método del objeto.
        break;
      case 'logout':
        echo "El metodo es logout<br>";
        $controller->{$method}(); // Si existe, llamar al método del objeto.
        break;
    }
  }
}
?>


<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Document</title>
  <link rel="stylesheet" href="css/styles.css" />
  <link rel="stylesheet" href="css/header.css" />
  <link rel="stylesheet" href="css/section.css" />
  <link rel="stylesheet" href="css/main.css" />
  <link rel="stylesheet" href="css/cards.css" />
  <script src="js/script.js"></script>
</head>

<body>
  <!-- Sección que pertenece al header --->
  <header id="headerId">
    <div class="h1_head">
      <!--<h1>Vinyl Explosion!!!</h1>-->
    </div>

    <h1>Vinyl Explosion!</h1>

    <div class="bloque">
      <ul class="header-img-input">
        <li class="link"><a href="index.php?method=login">Login</a></li>
      </ul>

  </header>

  <!--<main class="main_class"> -->
  <main>

    <div class="random-image-container">
      <img id="random-image" class="hover-zoom" src="images/DepecheMode.jpeg" alt="Imagen aleatoria" />
    </div>
  </main>

  <section class="ag-format-container">
    <div class="ag-courses_box">
      <div class="ag-courses_item">
        <a href="https://en.wikipedia.org/wiki/HTML5" class="ag-courses-item_link">
          <div class="ag-courses-item_bg"></div>
          <div class="ag-courses-item_title">
            <img src="images/html5-svgrepo-com.svg" />
            HTML5 (Hypertext Markup Language 5) is a markup language used for
            structuring and presenting content on the World Wide Web.
          </div>
          <div class="ag-courses-item_date-box">
            <span class="ag-courses-item_date">wikipedia.org/wiki/HTML5</span>
          </div>
        </a>
      </div>

      <div class="ag-courses_item">
        <a href="https://en.wikipedia.org/wiki/CSS" class="ag-courses-item_link">
          <div class="ag-courses-item_bg"></div>

          <div class="ag-courses-item_title">
            <img src="images/css3-svgrepo-com.svg" />
            Cascading Style Sheets (CSS) is a style sheet language used for
            specifying the presentation and styling of a document written in a
            markup language.
          </div>

          <div class="ag-courses-item_date-box">
            <!--Start:-->
            <span class="ag-courses-item_date">wikipedia.org/wiki/CSS</span>
          </div>
        </a>
      </div>

      <div class="ag-courses_item">
        <a href="https://en.wikipedia.org/wiki/JavaScript" class="ag-courses-item_link">
          <div class="ag-courses-item_bg"></div>

          <div class="ag-courses-item_title">
            <img src="images/javascript-svgrepo-com.svg" />
            JavaScript often abbreviated as JS, is a programming language and
            core technology of the World Wide Web, alongside HTML and CSS.
          </div>

          <div class="ag-courses-item_date-box">
            <span class="ag-courses-item_date">wikipedia.org/wiki/JavaScript</span>
          </div>
        </a>
      </div>

      <div class="ag-courses_item">
        <a href="https://en.wikipedia.org/wiki/PHP" class="ag-courses-item_link">
          <div class="ag-courses-item_bg"></div>

          <div class="ag-courses-item_title">
            <img src="images/php-svgrepo-com.svg" />
            PHP is a general purpose scripting language geared towards web
            development. It was originally created by Rasmus Lerdorf.
          </div>

          <div class="ag-courses-item_date-box">
            <span class="ag-courses-item_date">wikipedia.org/wiki/PHP</span>
          </div>
        </a>
      </div>

      <div class="ag-courses_item">
        <a href="https://en.wikipedia.org/wiki/Visual_Studio_Code" class="ag-courses-item_link">
          <div class="ag-courses-item_bg"></div>
          <div class="ag-courses-item_title">
            <img src="images/visual-studio-code-svgrepo-com.svg" />
            Visual Studio Code, also commonly referred to as VS Code, is a
            source-code editor developed by Microsoft for Windows, Linux and
            macOS.
          </div>
          <div class="ag-courses-item_date-box">
            <span class="ag-courses-item_date">wikipedia.org/wiki/Visual_Studio_Code</span>
          </div>
        </a>
      </div>

      <div class="ag-courses_item">
        <a href="https://en.wikipedia.org/wiki/MySQL" class="ag-courses-item_link">
          <div class="ag-courses-item_bg"></div>
          <div class="ag-courses-item_title">
            <img src="images/mysql-logo-svgrepo-com.svg" />
            MySQL is an open-source relational database management system
            (RDBMS).MySQL is free and open-source software. Written in C, C++.
          </div>
          <div class="ag-courses-item_date-box">
            <span class="ag-courses-item_date">wikipedia.org/wiki/MySQL</span>
          </div>
        </a>
      </div>
    </div>
  </section>
</body>

</html>