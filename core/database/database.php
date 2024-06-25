<?php

// Clase controladora de la base de datos
class Database
{
    private ?PDO $pdo;
    private string $dbHost = 'localhost';
    private string $dbName = 'VinylLibraryDB';
    private string $dbUser = 'root';
    private string $dbPass = 'A29xxRamones';

    // Conexión a la base de datos
    public function connect()
    {
        $this->pdo = null;
        try {
            $dsn = "mysql:host=" . $this->dbHost . ";dbname=" . $this->dbName;
            $this->pdo = new PDO($dsn, $this->dbUser, $this->dbPass);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Connection error: " . $e->getMessage();
        }

        return $this->pdo;
    }
    // Cierra la conexión a la base de datos
    public function close()
    {
        $this->pdo = null;
    }
}
