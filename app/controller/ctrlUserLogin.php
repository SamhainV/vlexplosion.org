<?php

require_once(BASE_DIR . 'app/model/mdlUserLogin.php');
require_once(BASE_DIR . 'app/model/user.php');
require_once(BASE_DIR . 'app/model/role.php');

class ctrlUserLogin
{
    private $modelUser;

    public function __construct($pdo)
    {
        $this->modelUser = new mdlUserLogin($pdo);
    }

    // Devuelve 0 si no existe el usuario
    public function checkUserExistence($username)
    {
        $userId = $this->modelUser->isUserExisting($username);
        return $userId;
    }

    // Devuelve 0 si no existe el email
    // O el ID del usuario
    public function checkMailExistence($username)
    {
        return $this->modelUser->isMailExisting($username);
    }

    // Devuelve true si la contraseña del usuario es correcta.
    public function checkPasswordExistence($username, $password)
    {
        $valor = $this->modelUser->isPasswordExisting($username, $password);
        return $valor;
    }

    // Devuelve 0 si no existe
    /*
    public function showUserRole($username)
    {
        $valor = $this->modelUser->getRole($username);
        return $valor;
    }

    public function addUser(string $nombre, string $password, string $email, int $rol)
    {
        return $this->modelUser->createUser($nombre, $password, $email, $rol);        
    }
        */
}
