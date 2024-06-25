<?php


class Genre
{
    public $Id; // ID del género musical, único
    public $Genre_Name; // Nombre del género musical, por ejemplo, "Rock", "Jazz"
    private $genreDAO;
    private $db;

    public function __construct($db, $Id, $Genre_Name = null)
    {
        $this->Id = $Id;
        $this->Genre_Name = $Genre_Name;
        $this->db = $db;
        $this->genreDAO = new genreDAO($this->db);
    }

    // Obtiene los géneros musicales de un usuario
    public function getUserGenres()
    {
        return $this->genreDAO->getGenresByUserId($this->Id);
    }
}
