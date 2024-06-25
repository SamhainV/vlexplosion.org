<?php

require_once(BASE_DIR . 'dao/implementations/authorDAO.php');

class Author
{
    public $Id; // ID del autor, único
    public $Author_Name; // Nombre del autor
    public $Debut_album_release; // Año de lanzamiento del primer álbum
    public $Original_members; // Número de miembros originales de la banda
    public $Breakup_date; // Año de separación de la banda, si aplica
    public $db;
    public $authorDAO;

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
        $this->authorDAO = new AuthorDAO($db);
    }

    // Añade un autor
    public function addAuthor(Author $author)
    {
        return $this->authorDAO->insert($author);
    }

    // Obtiene todos los autores
    public function getAllAuthors() 
    {
        return $this->authorDAO->findAll();
    }
 
    // Obtiene un autor por ID
    public function getAuthorById($id)
    {
        return $this->authorDAO->findById($id);
    }

    // actualiza un autor
    public function updateAuthor(Author $author)
    {
        return $this->authorDAO->update($author);
    }
}
