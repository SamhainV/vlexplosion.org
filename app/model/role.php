<?php

class Role
{
    public $id; // ID del rol, único
    public $rol_name; // Nombre del rol, por ejemplo, "Administrator", "User"
    private $rolesDao;
    private $db;

    // Constructor
    public function __construct($db, $id = null, $rol_name = null)
    {
        $this->id = $id;
        $this->rol_name = $rol_name;
        $this->db = $db;
        $this->rolesDao = new RolesDAO($this->db);
    }

    // Método para añadir un rol a un usuario
    public function assignRoleToUser($userId, $roleId): void
    {
        $this->rolesDao->addRoleToUser($userId, $roleId);
    }

    // Método para obtener el id del rol de un usuario
    public function getUserRol(string $username): int
    {
        return $this->rolesDao->getUserRoleByUsername($username);
    }

    // Método para eliminar un rol de un usuario
    public function delRolFromUser($userId, $roleId)
    {
        $this->rolesDao->deleteRoleFromUser($userId, $roleId);
    }

    // Método para actualizar el rol de un usuario
    public function updateUserRole(string $username, int $roleId, bool $addRole = true): void
    {
        $this->rolesDao->updateUserRole($username, $roleId, $addRole);
    }
}
