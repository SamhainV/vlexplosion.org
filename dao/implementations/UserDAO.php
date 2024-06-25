<?php

require_once(BASE_DIR . 'dao/interfaces/IUserDAO.php');
require_once(BASE_DIR . 'dao/implementations/rolesDAO.php');

// Implementación de la interfaz IUserDAO
class UserDAO implements IUserDAO
{
    private $db; // PDO instance
    private $roles;

    // Constructor recibe la conexión a la base de datos
    public function __construct($db)
    {
        $this->db = $db;
        $this->roles = new RolesDAO($this->db);
    }

    // Implementación de los métodos de la interfaz
    public function insertUser(User $user): int
    {
        // Para hacer el insert no hace falta el id, este lo genera MySQL
        // int $id; El id es local en este método.
        $sql = "INSERT INTO Users_TBL (username, password, email) VALUES (?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$user->getUsername(), $user->getPassword(), $user->getEmail()]);
        $id = $this->db->lastInsertId();
        return $id;
    }

    // buscar un usuario por su ID
    public function findAll(): array
    {
        $sql = "SELECT * FROM Users_TBL";
        $stmt = $this->db->query($sql);
        $usersData = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $users = [];
        foreach ($usersData as $userData) {
            $users[] = new User($this->db, $userData['id'], $userData['username'], $userData['password'], $userData['email']);
        }

        return $users; // Devuelve un array de objetos User
    }

    // buscar un usuario por su ID
    public function findById($id): ?User
    {
        $sql = "SELECT * FROM Users_TBL WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        $userData = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($userData) {
            // Devuelve un objeto User si se encuentra el usuario
            return new User($this->db, $userData['id'], $userData['username'], $userData['password'], $userData['email']);
        }

        return null;
    }

    // Actualizar los datos de un usuario
    public function updateUser(User $user): int
    {
        $sql = "UPDATE Users_TBL SET username = ?, password = ?, email = ? WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$user->getUsername(), $user->getPassword(), $user->getEmail(), $user->getId()]);
        return $stmt->rowCount();
    }

    // Eliminar un usuario por su ID
    public function deleteById($id): int
    {
        $sql = "DELETE FROM Users_TBL WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->rowCount(); // Retorna el número de filas afectadas
    }

    // Eliminar un usuario por su nombre de usuario
    public function deleteByUsername($username): int
    {
        // Obtener el ID del usuario a partir del nombre de usuario
        $sql = "SELECT id FROM Users_TBL WHERE username = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            throw new Exception("Usuario no encontrado: " . $username);
        }

        $userId = $user['id'];

        // Usar el método deleteById para eliminar el usuario
        return $this->deleteById($userId);
    }


    // Devuelve 0 si no existe el usuario
    // O el ID del usuario
    public function checkUserExists(string $username): int
    {
        $sql = "SELECT id FROM Users_TBL WHERE username = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$username]);
        $id = $stmt->fetchColumn();

        return $id ? (int) $id : 0;
    }

    // Devuelve 0 si no existe el email
    // O el ID del usuario
    public function checkEmailExists(string $email): int
    {
        $sql = "SELECT id FROM Users_TBL WHERE email = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$email]);
        $id = $stmt->fetchColumn();

        return $id ? (int) $id : 0;
    }

    // Verifica si la contraseña coincide con la de la BBDD
    public function checkPasswordExists(string $username, string $password)
    {
        try {
            $sql = "SELECT password FROM Users_TBL WHERE username = :username";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':username', $username);
            $stmt->execute();
            $storedPassword = $stmt->fetchColumn();

            if ($storedPassword) {
                return (int) password_verify($password, $storedPassword);
            } else {
                // Si no se encuentra el usuario o no tiene contraseña almacenada
                return 0;
            }
        } catch (PDOException $e) {
            die("Error al verificar la contraseña: " . $e->getMessage());
        }
    }

    // Devuelve el email de un usuario por su nombre de usuario
    public function getEmailByUsername($username)
    {
        $sql = "SELECT email FROM Users_TBL WHERE username = :username";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->execute();

        $email = $stmt->fetchColumn();
        return $email ? $email : 0; // Devuelve el email o null si el usuario no existe
    }

    // Devuelve el nombre de usuario por su email
    public function findOneEmail($email): int
    {
        $sql = "SELECT * FROM Users_TBL WHERE email = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            return 1; // Devuelve 1 si se encuentra el correo electrónico
        } else {
            return 0; // Devuelve 0 si no se encuentra el correo electrónico
        }
    }
}
