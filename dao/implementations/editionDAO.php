<?php

require_once(BASE_DIR . 'app/model/edition.php');
require_once(BASE_DIR . 'dao/interfaces/IEditionDAO.php');

// Implementación de la interfaz IEditionDAO
class editionDAO implements IEditionDAO
{
    private $db; // Instancia PDO para la conexión a la base de datos.
    private $xmlImports = [
        [
            "xmlFilePath" => BASE_DIR . "app/data/xml/edition.xml", // Ruta del archivo XML,
            "fatherTag" => "Editions", // Etiqueta padre
            "childTag" => "Edition", // Etiqueta hija
            "tableName" => "EDITION_TBL", // Nombre de la tabla
            "columnName" => "Edition_Name" // Nombre de la columna
        ]
    ];

    // Constructor recibe la conexión a la base de datos
    public function __construct($db)
    {
        $this->db = $db;
        $this->importArrayLabels($this->xmlImports); // Importa los datos de los archivos XML
    }

    // busca una edición por su ID
    public function findById($id): array
    {
        $sql = "SELECT * FROM EDITION_TBL WHERE Id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'Edition');
        return $stmt->fetch(); // Returns the genre object
    }

    // busca una edición por su nombre
    public function findByName($name): int
    {
        $sql = "SELECT Id FROM EDITION_TBL WHERE Edition_Name = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$name]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC); // Devuelve el resultado como un array asociativo
        return $result ? $result['Id'] : null; // Devuelve el ID de la edicion si está disponible, o null si no se encontró
    }

    // busca todas las ediciones
    public function findAll(): array
    {
        $sql = "SELECT * FROM EDITION_TBL";  // Asegúrate de que el nombre de la tabla es correcto
        $stmt = $this->db->query($sql);
        $stmt->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Edition'); // Usar Edition como la clase destino
        $editions = $stmt->fetchAll();
        if ($editions) {
            return $editions;  // Retorna los objetos Edition si la consulta fue exitosa
        } else {
            return null;  // Retorna null si no se encontraron ediciones
        }
    }

    // obtiene las ediciones de un vinilo por su ID
    public function getEditionsByUserId($userId): array
    {
        // Consulta para obtener las ediciones de los vinilos insertados por un usuario específico
        $sql = "SELECT DISTINCT e.* FROM EDITION_TBL e
                INNER JOIN VINYLS_TBL v ON e.Id = v.Edition_Id
                WHERE v.User_Id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$userId]);
        // Devuelve un array de objetos Edition
        return $stmt->fetchAll(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Edition');
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
                }

                //echo "Todos los datos han sido insertados correctamente en la tabla '{$import['tableName']}'.<br/>";
            } catch (Exception $e) {
                echo "Error al importar etiquetas del XML {$import['xmlFilePath']}: " . $e->getMessage() . "<br/>";
                exit();
            }
        }
    }
}
