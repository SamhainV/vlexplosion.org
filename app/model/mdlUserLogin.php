<?php

require_once(BASE_DIR . 'dao/implementations/UserDAO.php');

class mdlUserLogin
{
    private $usuarioDao;
    private $rolesDao;
    public function __construct($pdo)
    {
        $this->usuarioDao = new UserDAO($pdo);
        $this->rolesDao = new RolesDAO($pdo);
    }

    // Devuelve 0 si no existe el usuario
    public function isUserExisting($username)
    {
        return $this->usuarioDao->checkUserExists($username);
    }

    // Devuelve 0 si no existe el email
    public function isMailExisting($username)
    {
        return $this->usuarioDao->checkEmailExists($username);
    }

    // Devuelve true si la contraseña del usuario es correcta.
    public function isPasswordExisting($username, $password)
    {
        return $this->usuarioDao->checkPasswordExists($username, $password);
    }

    // Devuelve 0 si no existe
    /*
    public function getRole($username)
    {
        return $this->rolesDao->getUserRol($username);
    }
    
    public function createUser(User $user)
    {
        return $this->usuarioDao->insert($user);
    }*/
}
