<?php

require_once(BASE_DIR . 'dao/interfaces/IVinylAuthorDAO.php');

// Implementación de la interfaz IVinylAuthorDAO
class VinylAuthorDAO implements IVinylAuthorDAO
{
    private $db; // Conexión a la base de datos

    // Constructor recibe la conexión a la base de datos
    public function __construct($db)
    {
        $this->db = $db;
    }

    // Añade un autor a un vinilo
    public function addAuthorToVinyl($vinylId, $authorId)
    {
        $sql = "INSERT INTO AUTOR_VINYLS_TBL (Vinilo_Id, Autor_Id) VALUES (?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$vinylId, $authorId]);
    }

    // Elimina un autor de un vinilo
    public function removeAuthorFromVinyl($vinylId, $authorId)
    {
        $sql = "DELETE FROM AUTOR_VINYLS_TBL WHERE Vinilo_Id = ? AND Autor_Id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$vinylId, $authorId]);
    }

    // Obtiene todos los autores de un vinilo
    public function getAuthorsByVinylId($vinylId)
    {
        $sql = "SELECT a.* FROM AUTHORS_TBL a
                INNER JOIN AUTOR_VINYLS_TBL av ON a.Id = av.Autor_Id
                WHERE av.Vinilo_Id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$vinylId]);
        return $stmt->fetchAll(PDO::FETCH_CLASS, 'Author');
    }

    // Obtiene todos los vinilos de un autor
    public function getCompleteVinylDetailsByVinylIdAndUsername($vinylId, $username)
    {
        $sql = "SELECT v.*, 
                       a.Id AS Author_Id, a.Author_Name, a.Debut_album_release, a.Original_members, a.Breakup_date,
                       g.Genre_Name, 
                       f.Format_Name, 
                       c.Condition_Name, 
                       rl.Record_Label_Name, 
                       e.Edition_Name,
                       u.username AS User_Name
                FROM VINYLS_TBL v
                LEFT JOIN AUTOR_VINYLS_TBL av ON v.Id = av.Vinilo_Id
                LEFT JOIN AUTHORS_TBL a ON av.Autor_Id = a.Id
                INNER JOIN Users_TBL u ON v.User_Id = u.id
                LEFT JOIN GENRES_TBL g ON v.Genres_Id = g.Id
                LEFT JOIN FORMAT_TBL f ON v.Format_Id = f.Id
                LEFT JOIN CONDITION_TBL c ON v.Condition_Id = c.Id
                LEFT JOIN RECORD_LABEL_TBL rl ON v.Record_Label_Id = rl.Id
                LEFT JOIN EDITION_TBL e ON v.Edition_Id = e.Id
                WHERE v.Id = ? AND u.username = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$vinylId, $username]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC); // Devuelve un array asociativo con todos los datos combinados
    }
}

?>
