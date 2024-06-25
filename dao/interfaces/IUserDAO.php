<?php

// Interfaz DAO para usuarios
interface IUserDAO
{
    public function insertUser(User $user): int; // Devuelve el ID del usuario insertado
    public function findAll(): array; // Devuelve una array de objetos User
    public function findById(int $id): ?User; // Devuelve un objeto User o null si no se encuentra
    public function updateUser(User $user): int; // Devuelve el número de filas afectadas
    public function deleteById($id): int; // Borra un usuario segun si id
    public function deleteByUsername($username): int; // Borra un usuario segun su username
    public function checkUserExists(string $username): int; // Devuelve 0 si no existe el usuario, o el ID del usuario
    public function checkEmailExists(string $email): int; // Devuelve 0 si no existe el email, o el ID del usuario
    public function checkPasswordExists(string $username, string $password); // Devuelve true si la contraseña del usuario es correcta
    public function getEmailByUsername($username); // Devuelve el email del usuario
    public function findOneEmail($email): int; // Devuelve 1 o 0 en función de si el email existe o no.
}
