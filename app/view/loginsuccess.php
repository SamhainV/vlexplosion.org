<?php
// Iniciar la sesión si aún no se ha iniciado
//session_start();


if (empty($_SESSION)) {
    // Redirigir al usuario al inicio de sesión
    header('Location: index.php');
    exit(); // Asegurarse de que el script se detenga después de redirigir
}

?>


<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Space</title>
    <link rel="stylesheet" type="text/css" href="css/success.css" />
    <link rel="stylesheet" href="css/styles.css" />
</head>

<body>
    <!-- Simula un disco de vinilo -->
    <div class="entry_box">
        <h1>Bienvenido</h1>
        <!--<br><br>
        <p>Hola, <?php echo $_SESSION['username']; ?>. </p>-->

        <p>Has iniciado sesión con éxito.</p>
        <!-- <p>Tu rol es, <?php echo ($_SESSION['userrol'] == 1) ? "admin" : (($_SESSION['userrol'] == 2) ? "user" : "Rol no definido"); ?></p> -->
        <div class="center_hole"></div>
        <?php
        $username = $_SESSION['username'];
        switch ($_SESSION['userrol']) {
            case 1:
                //echo "<a href='adminusers.php'>Administra usuarios</a>";
                //if ($_SESSION['username'] != 'Admin')
                if (isset($_SESSION['username']) && $username !== 'Admin')
                    echo "<a href='index.php?method=userSpace'>Pulsa aqui para comenzar</a>";
                echo "<a href='index.php?method=manageUsers'>Administra usuarios</a>";
                break;
            case 2:
                echo "<a href='index.php?method=userSpace'>Pulsa aqui para comenzar</a>";
                //                echo "<a href='../app/view/userspace.php'>Pulsa aqui para comenzar</a>";
                break;
            default:
                echo "Rol no definido";
                break;
        }
        ?>

        <!--<p><a href="logout.php">Cerrar sesión</a></p>-->
    </div>


</body>

</html>
