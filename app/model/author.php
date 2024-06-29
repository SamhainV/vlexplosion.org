<?php

//require_once(BASE_DIR . 'dao/implementations/authorDAO.php');

class Author
{
    public $Id; // ID del autor, único
    public $Author_Name; // Nombre del autor
    public $Debut_album_release; // Año de lanzamiento del primer álbum
    public $Original_members; // Número de miembros originales de la banda
    public $Breakup_date; // Año de separación de la banda, si aplica
    public $db;
    //public $authorDAO;

    // Constructor
    public function __construct(
        $db,
        $Id = null,
        $Author_Name = null,
        $Debut_album_release = null,
        $Original_members = null,
        $Breakup_date = null,
    ) {
        $this->db = $db;
        $this->Id = $Id;
        $this->Author_Name = $Author_Name;
        $this->Debut_album_release = $Debut_album_release;
        $this->Original_members = $Original_members;
        $this->Breakup_date = $Breakup_date;
        //$this->authorDAO = new AuthorDAO($db);
    }

    // Añade un autor
    public function addAuthor(Author $author)
    {
        $sql = "INSERT INTO AUTHORS_TBL (Author_Name, Debut_album_release, Original_members, Breakup_date) VALUES (?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$author->Author_Name, $author->Debut_album_release, $author->Original_members, $author->Breakup_date]);
        $author->Id = $this->db->lastInsertId();
        return $author->Id; // Devuelve el ID del autor insertado
    }

    // Obtiene todos los autores
    public function getAllAuthors()
    {
        $sql = "SELECT * FROM AUTHORS_TBL";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_CLASS, 'Author'); // Devuelve un array de objetos Author
//        return $this->authorDAO->findAll();
    }

    // Obtiene un autor por ID
    public function getAuthorById($id)
    {
        $sql = "SELECT * FROM AUTHORS_TBL WHERE Id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'Author');
        return $stmt->fetch(); // Devuelve un objeto Author
        //return $this->authorDAO->findById($id);
    }

    // actualiza un autor
    public function updateAuthor(Author $author)
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
        //return $this->authorDAO->update($author);
    }
}
