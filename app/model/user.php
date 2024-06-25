<?php

/* 

    La lógica es la siguiente:

    1.- Tenemos las entidades User y Roles, que tienen una relación N:M entre sí.
    2.- Tenemos también una relación 1:N entre User y Vinyl.

    Por lo tanto en la clase User, tenemos que instanciar las clases Roles y Vinyl, 
    para poder acceder a sus métodos y propiedades.

        - public $vinyl; // class Vinyl
        - public $role; // class Role
        - $this->vinyl = new Vinyl($this->db, $this->id);    
        - $this->role = new Role($this->db);

    La clase User al igual que todas las demás clases individuales, solo tiene acceso 
    a sus propios metodos y atributos.

    Para poder hacer uso de otros metodos tiene que ser a través de instancias.

    Por ejemplo si queremos sabe el genero de un disco el procedimiento es el siguiente:

    - Instanciamos la clase Vinyl en la clase User
    - Llamamos al metodo imaginario getGenero($idVinilo) de la clase Vinyl con el id del vinilo a consultar.

    El metodo de la clase Vinyl hace uso de VinylDAO para interactuar con la BBDD y devolver el resultado.
*/

require_once(BASE_DIR . 'dao/implementations/UserDAO.php');
require_once(BASE_DIR . 'dao/implementations/GenresDAO.php');
require_once(BASE_DIR . 'app/model/role.php');
require_once(BASE_DIR . 'app/model/vinyl.php');

// Clase User
class User
{
    private $id; // ID del usuario, único para cada usuario
    private $username;  // Nombre de usuario, único
    private $password; // Contraseña del usuario, almacenada de preferencia como hash
    private $email; // Correo electrónico del usuario, único
    private $roles = []; // Relación n:m con Role. Almacena objetos Role
    private $vinyls = []; // Relación 1:n con Vinyl
    private $db;
    public $usuarioDao;

    // Instancia clases vinyl y roles para la relacion N:M con roles y 1:n con vinyl
    public $vinyl; // class Vinyl
    public $role; // class Role

    public function __construct($db, $id = null, $username = null, $password = null, $email = null)
    {
        $this->id = $id;
        $this->username = $username;
        $this->password = $password;
        $this->email = $email;
        $this->db = $db;
        $this->usuarioDao = new UserDAO($this->db, null, null, null);
        $this->vinyl = new Vinyl($this->db, $this->id);

        $this->role = new Role($this->db);
    }

    // Método para añadir un nuevo usuario
    public function addNew()
    {
        $this->id = $this->usuarioDao->insertUser($this);
        // Por defecto añadimos el rol 2 de usuario
        $this->role->assignRoleToUser($this->id, 2); // 2 es el id del rol "usuario"
        $defaultRole = new Role($this->db, 2, 'User');

        //$this->roles[] = $defaultRole; // Por defecto roles 2 User;
        $this->setRoles($defaultRole);
    }

    // Método para obtener todos los usuarios
    public function findAllusers()
    {
        return $this->usuarioDao->findAll();
    }

    // Método para obtener todos los usuarios y sus roles, excluyendo al usuario Admin
    public function getUsersAndRolesExcludingAdmin(): array
    {
        $allUsers = $this->findAllusers();
        $usersAndRoles = [];

        foreach ($allUsers as $user) {
            $username = $user->getUsername();

            // Excluir al usuario Admin
            if (strcasecmp($username, 'Admin') !== 0) {
                $role = $this->role->getUserRol($username);
                $usersAndRoles[] = [
                    'username' => $username,
                    'role' => $role
                ];
            }
        }

        return $usersAndRoles;
    }

    // Método para obtener un usuario por ID
    public function findId()
    {
        return $this->usuarioDao->findById($this->id);
    }

    // actualiza un usuario
    public function update(User $user)
    {
        return $this->usuarioDao->updateUser($user);
    }

    // Método para eliminar un usuario por ID
    public function deleteId()
    {
        return $this->usuarioDao->deleteById($this->id);
    }

    // Método para eliminar un usuario por nombre de usuario
    public function deleteUser()
    {
        return $this->usuarioDao->deleteByUsername($this->username);
    }

    /************************************/
    /* Metodos para leer de la BBDD */

    // Devuelve 0 si no existe el usuario
    public function isUserExisting()
    {
        return $this->usuarioDao->checkUserExists($this->username);
    }

    // Devuelve el email del usuario
    public function getUserEmail()
    {
        return $this->usuarioDao->getEmailByUsername($this->username);
    }

    // Devuelve true si la contraseña del usuario es correcta.
    public function isPasswordExisting()
    {
        return $this->usuarioDao->checkPasswordExists($this->username, $this->password);
    }

    // Devuelve el email del usuario
    public function findEmail($email)
    {
        return $this->usuarioDao->findOneEmail($email);
    }

    /************************************/

    /* Getters */
    public function getId()
    {
        return $this->id;
    }
    // obtener el nombre de usuario
    public function getUserName()
    {
        return $this->username;
    }

    // obtener la contraseña
    public function getPassword()
    {
        return $this->password;
    }

    // obtener el email
    public function getEmail()
    {
        return $this->email;
    }

    // obtener los roles
    public function getRoles()
    {
        return $this->roles;
    }

    // obtener los vinilos
    public function getVinyls()
    {
        return $this->vinyls;
    }

    /*************/
    /* Setters   */
    /*************/

    // establecer el id
    public function setId($id)
    {
        $this->id = $id;
    }

    // establecer el nombre de usuario
    public function setUserName($username)
    {
        $this->username = $username;
    }

    // establecer la contraseña
    public function setPassword($password)
    {
        $this->password = $password;
    }

    // establecer el email
    public function setEmail($email)
    {
        $this->email = $email;
    }

    // establecer los roles
    public function setRoles(Role $rol)
    {
        $this->roles[] = $rol;
    }

    // establecer los vinilos
    public function setVinyls(Vinyl $vinyl) // El argumento tiene que ser class Vinyl
    {
        $this->vinyls = $vinyl;
    }
    /***********/

    // Método para añadir un rol a un usuario
    public function addRole($role, $user) // 1 admin 2 user
    {
        $defaultRole = new Role($this->db, $role, $user);
        $this->role->assignRoleToUser($this->id, $role);
        $this->roles[] = $defaultRole;
    }

    // Método para eliminar un rol de un usuario
    public function delRole($role)
    {
        // Eliminar el rol del usuario en la base de datos
        $this->role->delRolFromUser($this->id, $role);

        // Eliminar el rol del array $this->roles
        foreach ($this->roles as $key => $r) {
            if ($r->id == $role) {
                unset($this->roles[$key]);
            }
        }
        // Reindexar el array para mantener los índices consistentes
        $this->roles = array_values($this->roles);
    }

    // Método para añadir un vinilo a un usuario
    public function addVinyl(Vinyl $vinyl)
    {
        $this->vinyls[] = $vinyl;
    }
}
