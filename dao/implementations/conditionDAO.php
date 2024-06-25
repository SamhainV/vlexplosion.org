<?php

require_once(BASE_DIR . 'app/model/condition.php');
require_once(BASE_DIR . 'dao/interfaces/IConditionDAO.php');

// Implementación de la interfaz IConditionDAO
class conditionDAO implements IConditionDAO
{
    private $db; // PDO instance for database connection
    private $xmlImports = [
        [
            "xmlFilePath" => BASE_DIR . "app/data/xml/vinyl_condition.xml", // Ruta del archivo XML,
            "fatherTag" => "VinylConditions", // Etiqueta padre
            "childTag" => "VinylCondition", // Etiqueta hija
            "tableName" => "CONDITION_TBL", // Nombre de la tabla
            "columnName" => "Condition_Name" // Nombre de la columna
        ]
    ];

    // Constructor recibe la conexión a la base de datos
    public function __construct($db)
    {
        $this->db = $db;
        $this->importArrayLabels($this->xmlImports); // Importa los datos de los archivos XML
    }

    // Busca una condición por su ID
    public function findById($id): array
    {
        $sql = "SELECT * FROM CONDITION_TBL WHERE Id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'Condition');
        return $stmt->fetch(); // Devuelve un objeto Condition
    }

    //Busca una condición por su nombre
    public function findByName($name): int
    {
        $sql = "SELECT Id FROM CONDITION_TBL WHERE Condition_Name = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$name]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC); // Devuelve el resultado como un array asociativo
        return $result ? $result['Id'] : null; // Devuelve el ID de la condición si está disponible, o null si no se encontró
    }

    // Busca todas las condiciones
    public function findAll(): array
    {
        $sql = "SELECT * FROM CONDITION_TBL";  // Asegúrate de que el nombre de la tabla es correcto
        $stmt = $this->db->query($sql);
        $stmt->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Condition'); // Usar Condition como la clase destino
        $conditions = $stmt->fetchAll();
        if ($conditions) {
            return $conditions;  // Retorna los objetos Condition si la consulta fue exitosa
        } else {
            return null;  // Retorna null si no se encontraron condiciones
        }
    }

    // obtiene las condiciones de los vinilos insertados por un usuario específico.
    public function getConditionsByUserId($userId): array
    {
        // Consulta para obtener las condiciones de los vinilos insertados por un usuario específico
        $sql = "SELECT DISTINCT c.* FROM CONDITION_TBL c
                INNER JOIN VINYLS_TBL v ON c.Id = v.Condition_Id
                WHERE v.User_Id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$userId]);
        // Devuelve un array de objetos Condition
        return $stmt->fetchAll(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Condition');
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
