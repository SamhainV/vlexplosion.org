<?php

require_once(BASE_DIR . 'app/model/genres.php');
require_once(BASE_DIR . 'dao/interfaces/IGenreDAO.php');

// Implementación de la interfaz IGenreDAO
class genreDAO implements IGenreDAO
{
    private $db; // PDO instance for database connection
    private $xmlImports = [
        [
            "xmlFilePath" => BASE_DIR . "app/data/xml/generosMusicales.xml", // Ruta del archivo XML,
            "fatherTag" => "genres", // Etiqueta padre
            "childTag" => "genre", // Etiqueta hija
            "tableName" => "GENRES_TBL", // Nombre de la tabla
            "columnName" => "Genre_Name" // Nombre de la columna
        ]
    ];

    // Constructor recibe la conexión a la base de datos
    public function __construct($db)
    {
        $this->db = $db;
        $this->importArrayLabels($this->xmlImports); // Importa los datos de los archivos XML
    }

    // Busca un género por su ID
    public function findById($id): ?Genre
    {
        $sql = "SELECT * FROM GENRES_TBL WHERE Id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        $genreData = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($genreData) {
            return new Genre($this->db, $genreData['Id'], $genreData['Genre_Name']);
        } else {
            return null; // O lanza una excepción si prefieres manejar el error de esta manera
        }
    }

    // Busca un género por su nombre
    public function findByName($name): int
    {
        $sql = "SELECT Id FROM GENRES_TBL WHERE Genre_Name = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$name]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC); // Devuelve el resultado como un array asociativo
        return $result ? $result['Id'] : null; // Devuelve el ID del género si está disponible, o null si no se encontró
    }

    // Busca todos los géneros
    public function findAll(): array
    {
        $sql = "SELECT * FROM GENRES_TBL";
        $stmt = $this->db->query($sql);
        $genresData = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $genres = [];
        foreach ($genresData as $genreData) {
            $genres[] = new Genre($this->db, $genreData['Id'], $genreData['Genre_Name']);
        }

        return $genres;
    }

    // Busca los géneros de un ID de usuario
    public function getGenresByUserId($userId): array
    {
        $sql = "SELECT DISTINCT g.* FROM GENRES_TBL g
                INNER JOIN VINYLS_TBL v ON g.Id = v.Genres_Id
                WHERE v.User_Id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$userId]);
        $genresData = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $genres = [];
        foreach ($genresData as $genreData) {
            $genres[] = new Genre($this->db, $genreData['Id'], $genreData['Genre_Name']);
        }
        return $genres;
    }

    // Importa las etiquetas de los archivos XML a la base de datos.
    public function importArrayLabels($arrayData): void
    {
        foreach ($arrayData as $import) { // Iterar sobre cada elemento en $arrayData
            try {
                // Cargar el archivo XML
                $xml = simplexml_load_file($import['xmlFilePath']);
                if ($xml === false) {
                    throw new Exception("No se pudo cargar el archivo XML.");
                }

                // Verificar si la tabla está vacía antes de insertar los datos
                $checkStmt = $this->db->query("SELECT COUNT(*) FROM {$import['tableName']}");
                $count = $checkStmt->fetchColumn();

                if ($count > 0) {
                    /*echo "La tabla '{$import['tableName']}' ya contiene datos. No se realizarán inserciones.<br/>";*/
                    continue;  // Salta al siguiente elemento en $arrayData si la tabla no está vacía
                }

                // Preparar la sentencia de inserción
                $stmt = $this->db->prepare("INSERT INTO {$import['tableName']} ({$import['columnName']}) VALUES (:name)");

                // Insertar cada etiqueta del XML en la tabla
                foreach ($xml->table->{$import['fatherTag']}->{$import['childTag']} as $label) {
                    $stmt->execute([':name' => (string)$label]);
                    //echo "Insertado: " . $label . "<br/>";
                }

                //echo "Todos los datos han sido insertados correctamente en la tabla '{$import['tableName']}'.<br/>";
            } catch (Exception $e) {
                echo "Error al importar etiquetas del XML {$import['xmlFilePath']}: " . $e->getMessage() . "<br/>";
            }
        }
    }
}
