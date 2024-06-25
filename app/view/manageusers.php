<?php
// Iniciar la sesión.
//session_start();

if (empty($_SESSION)) {
    // Redirigir a la página de inicio.
    header('Location: index.php');
    exit();
}

require_once(BASE_DIR . 'app/controller/ctrlConnect.php'); // Controlador de la conexión
require_once(BASE_DIR . 'app/model/user.php'); // Modelo de usuario
require_once(BASE_DIR . 'app/model/role.php'); //   

/*********** CONEXION A LA BBDD ************************/
$db = new ctrlConnect();
$pdo = $db->connectDB(); // Conectar a la BBDD    
/*********** FIN DE CONEXION A LA BBDD *****************/

/*
 * En este contexto, la instancia User tiene dos cometidos.
 * 
 * Uno es llamar a métodos que devuelven los usuarios y roles, 
 * que posteriormente se van a listar en el formulario.
 * 
 * $allUsersAndRoles = $usuario->getUsersAndRolesExcludingAdmin();
 * 
 * 
 * Por otro lado se usa para completar los datos del usuario que vamos a borrar, ya que hay
 * que tener en cuenta que algunos metodos como los mencionados a continuacion, utilizan los 
 * atributos de clase User y deben de estar inicializados.
 * 
 * para ser ejecutados:
 * 
 *  - public function findId(); Require $this->id
 *  - public function deleteId(); Require $this->id
 *  - public function deleteUser(); Require $this->username
 *  - public function isUserExisting(); Require $this->username
 *  - public function getUserEmail(); Require $this->username
 *  - public function isPasswordExisting(); Require this->username, $this->password)
 * 
 * 
 * 
*/
$usuario = new User($pdo, null, null, null, null); // Instanciamos el usuario

/* 
 * Obtener el nombre del usuario para usarlo en los listados de usuarios/roles 
 * del formulario html 
*/

$loggedInUsername = $_SESSION['username'];
// Obtener todos los usuarios y roles excluyendo al administrador logueado
$allUsersAndRoles = $usuario->getUsersAndRolesExcludingAdmin();
$usersAndRoles = [];
foreach ($allUsersAndRoles as $user) {
    if ($user['username'] !== $loggedInUsername) {
        $usersAndRoles[] = $user;
    }
}

// Procesar si se ha enviado el formulario.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    /*var_dump($_POST);exit;*/
    // Verificar si se hizo clic en "Aplicar cambios"
    if (isset($_POST['apply_changes']) && $_POST['apply_changes'] == '1') {
        if (isset($_POST['admin_role'])) {
            foreach ($_POST['admin_role'] as $username => $value) {
                // Actualizar el rol del usuario para que sea admin
                $usuario->role->updateUserRole($username, 1, true);
            }
        }

        // Manejar usuarios que no tienen la casilla de administrador marcada
        foreach ($usersAndRoles as $user) {
            $username = $user['username'];
            if (!isset($_POST['admin_role'][$username])) {
                // Eliminar el rol de admin del usuario si no está marcado.
                $usuario->role->updateUserRole($username, 1, false);
            }
        }

        // Actualizar la lista de usuarios y roles después de aplicar cambios
        $allUsersAndRoles = $usuario->getUsersAndRolesExcludingAdmin();
        $usersAndRoles = [];
        foreach ($allUsersAndRoles as $user) {
            if ($user['username'] !== $loggedInUsername) {
                $usersAndRoles[] = $user;
            }
        }
    }

    // Verificar si se hizo clic en "Eliminar usuario"
    if (isset($_POST['delete_user'])) {
        $usernameToDelete = $_POST['delete_user'];
        $usuario->setUsername($usernameToDelete);
        $usuario->setEmail($_SESSION['email']);
        $usuario->setId($usuario->isUserExisting($usernameToDelete));
        // Eliminar el usuario de la base de datos
        $usuario->deleteUser();

        // Actualizar la lista de usuarios y roles después de aplicar cambios
        $allUsersAndRoles = $usuario->getUsersAndRolesExcludingAdmin();
        $usersAndRoles = [];
        foreach ($allUsersAndRoles as $user) {
            if ($user['username'] !== $loggedInUsername) {
                $usersAndRoles[] = $user;
            }
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/menu.css">
    <link rel="stylesheet" href="css/admin.css">
    <link rel="stylesheet" href="css/success.css">
    <link rel="stylesheet" href="css/table.css">
    <link rel="stylesheet" href="css/styles.css">

    <script src="js/script.js" defer></script>
    <title>Administrar Usuarios</title>
</head>

<body>
    <div class="dropdown">
        <button class="dropbtn">Menú</button>
        <div class="dropdown-content">
            <a href="#admin-usuarios">Administrar Usuarios</a>
            <a href="index.php?method=logout">Logout</a>
        </div>
    </div>

    <!-- Sección oculta que se mostrará al hacer clic -->
    <div id="admin-usuarios" class="hidden">
        <div id="admin-div">
            <form method="post" action="#">
                <?php
                if (isset($usersAndRoles) && is_array($usersAndRoles)) {
                    foreach ($usersAndRoles as $user) {
                        $username = $user['username'];
                        $role = $user['role'];
                        $isAdmin = $role === 1 ? 'checked' : '';

                        echo '<div class="user-row">';
                        echo '<div class="user-action">';
                        echo '<button type="submit" class="btn" name="delete_user" value="' . htmlspecialchars($username) . '" onclick="return confirm(\'¿Está seguro de que desea eliminar al usuario ' . htmlspecialchars($username) . '?\');">Eliminar usuario</button>';
                        echo '</div>';
                        echo '<div class="user-name">' . htmlspecialchars($username) . '</div>';
                        echo '<div class="user-role">Role ID: ' . htmlspecialchars($role) . '</div>';
                        echo '<div class="user-admin">';
                        echo '<label>';
                        echo '<input type="checkbox" name="admin_role[' . htmlspecialchars($username) . ']" value="1" ' . $isAdmin . '> Admin';
                        echo '</label>';
                        echo '</div>';
                        echo '</div>';
                    }
                } else {
                    echo '<p>No se encontraron usuarios.</p>';
                }
                ?>
                <div class="apply-changes">
                    <input type="hidden" name="apply_changes" value="1">
                    <button type="submit">Aplicar cambios / Aceptar</button>
                </div>
            </form>
        </div>
    </div>
</body>

</html>

