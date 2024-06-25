<?php


require_once(BASE_DIR . 'core/database/database.php');

/* 
 * Clase modelo para gestionar las conexiones 
*/
class mdlConnect
{
    private $db;
    private ?PDO $pdo;

    public function __construct()
    {
        $this->db = new Database(); // no tiene constructor especificado.
    }
    public function connect()
    {
        $this->pdo = $this->db->connect();
        return $this->pdo;
    }

    public function close()
    {
        $this->db->close();
    }
}
