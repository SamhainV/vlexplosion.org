<?php

require_once(BASE_DIR . 'dao/interfaces/IRolesDAO.php');

// Implementación de la interfaz IRolesDAO
class RolesDAO implements IRolesDAO
{
    private $db; // PDO instance

    // Constructor recibe la conexión a la base de datos
    public function __construct($db)
    {
        $this->db = $db;
    }

    // Método para añadir un rol a un usuario
    public function addRoleToUser($userId, $roleId): void
    {
        $sql = "INSERT INTO Users_Roles (User_Id, Role_Id) VALUES (?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$userId, $roleId]);
    }

    // Método para eliminar un rol de un usuario
    public function deleteRoleFromUser($userId, $roleId): void
    {
        $sql = "DELETE FROM Users_Roles WHERE User_Id = ? AND Role_Id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$userId, $roleId]);
    }

    // Método para obtener el id del rol de un usuario
    public function getUserRoleByUsername(string $username): int
    {
        // SQL para obtener el Role_Id basado en el username
        $sql = "SELECT r.id
                FROM Users_TBL u
                JOIN Users_Roles ur ON u.id = ur.User_Id
                JOIN Roles_TBL r ON ur.Role_Id = r.id
                WHERE u.username = :username";

        // Preparar la consulta
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->execute();

        // Obtener el resultado
        $role = $stmt->fetch(PDO::FETCH_ASSOC);

        // Si no hay un rol, podemos decidir qué hacer, aquí lanzo una excepción
        if (!$role) {
            throw new Exception("No se encontró el rol para el usuario: " . $username);
        }

        // Devolver el id del rol como entero
        return (int) $role['id'];
    }

    // Método para actualizar el rol de un usuario
    public function updateUserRole(string $username, int $roleId, bool $addRole = true): void
    {
        // Obtener el userId basado en el username
        $sql = "SELECT id FROM Users_TBL WHERE username = :username";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            throw new Exception("No se encontró el usuario con el username: " . $username);
        }

        $userId = $user['id'];

        if ($addRole) {
            // Añadir el nuevo rol al usuario si no existe ya
            $sql = "SELECT COUNT(*) FROM Users_Roles WHERE User_Id = ? AND Role_Id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$userId, $roleId]);
            $count = $stmt->fetchColumn();

            if ($count == 0) {
                $this->addRoleToUser($userId, $roleId);
            }
        } else {
            // Eliminar el rol del usuario si existe
            $this->deleteRoleFromUser($userId, $roleId);
        }
    }
}
