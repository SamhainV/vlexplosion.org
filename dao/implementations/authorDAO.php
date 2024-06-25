<?php
require_once(BASE_DIR . 'dao/interfaces/IAuthorDAO.php');

// Implementación de la interfaz IAuthorDAO
class AuthorDAO implements IAuthorDAO
{
    private $db; // Conexión a la base de datos

    // Constructor recibe la conexión a la base de datos
    public function __construct($db)
    {
        $this->db = $db; // Guarda la conexión a la base de datos
    }

    // Implementación de los métodos de la interfaz
    public function insert(Author $author): int
    {
        $sql = "INSERT INTO AUTHORS_TBL (Author_Name, Debut_album_release, Original_members, Breakup_date) VALUES (?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$author->Author_Name, $author->Debut_album_release, $author->Original_members, $author->Breakup_date]);
        $author->Id = $this->db->lastInsertId();
        return $author->Id; // Devuelve el ID del autor insertado
    }

    // Implementación de los métodos de la interfaz
    public function findAll(): array
    {
        $sql = "SELECT * FROM AUTHORS_TBL";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_CLASS, 'Author'); // Devuelve un array de objetos Author
    }

    // Implementación de los métodos de la interfaz
    public function findById($id): ?Author
    {
        $sql = "SELECT * FROM AUTHORS_TBL WHERE Id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'Author');
        return $stmt->fetch(); // Devuelve un objeto Author
    }

    // Implementación de los métodos de la interfaz
    public function update(Author $author): int
    {
        if (empty($author->Id)) {
            throw new InvalidArgumentException("El ID del autor es necesario para actualizar.");
        }

        $sql = "UPDATE AUTHORS_TBL SET 
                Author_Name = ?, 
                Debut_album_release = ?, 
                Original_members = ?, 
                Breakup_date = ?
                WHERE Id = ?";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            $author->Author_Name,
            $author->Debut_album_release,
            $author->Original_members,
            $author->Breakup_date,
            $author->Id
        ]);

        // Devuelve el número de filas afectadas
        return $stmt->rowCount();
    }
}
