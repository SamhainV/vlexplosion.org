<?php
require_once(BASE_DIR . 'dao/implementations/VinylDAO.php');
require_once(BASE_DIR . 'dao/implementations/recordLabelDAO.php');
require_once(BASE_DIR . 'dao/implementations/VinylAuthorDAO.php');

// Clase Vinyl
class Vinyl
{
    public $Id; // ID del vinilo, único
    public $Title; // Título del vinilo
    public $Genres_Id; // ID del género musical asociado
    public $GenresArray = []; // Array de géneros musicales del usuario
    public $Format_Id; // ID del formato del vinilo
    public $FormatArray = []; // Array del formato del vinilo del usuario
    public $Condition_Id; // ID de la condición del vinilo
    public $ConditionArray = []; // Array de la condición del vinilo
    public $Record_Label_Id; // ID del sello discográfico del vinilo
    public $Record_Label_Array = []; // Array del sello discográfico del vinilo
    public $Producer; // Nombre del productor del vinilo
    public $Release_date; // Año de lanzamiento del vinilo
    public $Edition_Id; // ID de la edición del vinilo
    public $EditionArray = []; // Array de la edición del vinilo
    public $User_Id; // ID del usuario que registró el vinilo
    public $authors = []; // Lista de autores asociados al vinilo
    public $vinylDAO; // Objeto de la clase VinylDAO
    private $genre; // Objeto de la clase Genre
    public $Is_Favorite; // Indica si el vinilo es favorito del usuario
    public $Is_Desired; // Indica si el vinilo es deseado por el usuario
    public $recordLabelDAO; // Objeto de la clase recordLabelDAO
    public $vinylAuthorDAO; // Objeto de la clase VinylAuthorDAO

    private $db; // Conexión a la base de datos

    public function __construct($db, $Id, $Title = null, $Release_date = null)
    {
        $this->Id = $Id;
        $this->Title = $Title;
        $this->Release_date = $Release_date;
        $this->db = $db;
        $this->genre = new Genre($this->db, $this->Id, null);
        $this->vinylDAO = new VinylDAO($this->db);
        $this->vinylAuthorDAO = new VinylAuthorDAO($this->db); // Tabla intermedia entre autores y vinilos

        $this->recordLabelDAO = new recordLabelDAO($db);

        //$this->genre = new Genre();
    }

    // Obtienen los géneros musicales del usuario
    public function getUserGenero()
    {
        return $this->genre->getUserGenres();
    }

    // Obtiene los discos de un usuario
    public function getRecords($userLogged, $condiciones = null)
    {
        return $this->vinylDAO->getRecordDetails($userLogged, $condiciones);
    }
    /***************************************************************************/

    // Añade un autor
    public function addAuthor(Author $author)
    {
        $this->authors[] = $author; // Agrega un autor a la lista de autores
    }

    // Establece un genero musical.
    public function setGenre(Genre $genero)
    {
        $this->GenresArray[] = $genero; // Agrega un genero a la lista de generos del autor
    }

    // Establece un formato.
    public function setFormat(Format $formato)
    {
        $this->FormatArray[] = $formato; // Agrega un genero a la lista de generos del autor
    }

    // Establece una condición.
    public function setCondition(Condition $condition)
    {
        $this->ConditionArray[] = $condition; // Agrega un genero a la lista de generos del autor
    }

    // Establece un sello discográfico.
    public function setRecordLabel(RecordLabel $recordLabel)
    {
        $this->Record_Label_Array[] = $recordLabel; // Agrega un genero a la lista de generos del autor
    }

    // Establece una edición.
    public function setEdition(Edition $edition)
    {
        $this->EditionArray[] = $edition; // Agrega un genero a la lista de generos del autor
    }

    // Obtiene la lista de autores.
    public function getAuthors()
    {
        return $this->authors; // Devuelve la lista de autores
    }
}
