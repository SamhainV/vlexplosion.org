<?php

require_once(BASE_DIR . 'app/model/mdlConnect.php');

/* 
 * Clase controladora para gestionar las conexiones 
*/
class ctrlConnect
{

    private $model;
    private ?PDO $pdo;

    public function __construct()
    {
        $this->model = new mdlConnect();
    }

    public function connectDB()
    {
        $this->pdo = $this->model->connect();
        return $this->pdo;
    }

    public function closeDB()
    {
        $this->model->close();
    }
}
