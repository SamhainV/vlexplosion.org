<?php

// Interfaz DAO para roles
interface IRolesDAO
{    
    public function addRoleToUser(int $userId, int $roleId): void; // Asigna un rol a un usuario
    public function deleteRoleFromUser($userId, $roleId): void;
    public function getUserRoleByUsername(string $username): int; // Devuelve el ID del rol de un usuario
    public function updateUserRole(string $username, int $roleId, bool $addRole = true): void;
}
