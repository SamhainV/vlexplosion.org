<?php

require_once(BASE_DIR . 'app/model/recordLabel.php');
require_once(BASE_DIR . 'dao/interfaces/IRecordLabelDAO.php');

// Implementación de la interfaz IRecordLabelDAO
class recordLabelDAO implements IRecordLabelDAO
{
    private $db; // PDO instance for database connection
    private $xmlImports = [
        [
            "xmlFilePath" => BASE_DIR . "app/data/xml/discograficas.xml", // Ruta del archivo XML,
            "fatherTag" => "record_labels", // Etiqueta padre
            "childTag" => "record_label", // Etiqueta hija
            "tableName" => "RECORD_LABEL_TBL", // Nombre de la tabla
            "columnName" => "Record_Label_Name" // Nombre de la columna
        ]
    ];

    // Constructor recibe la conexión a la base de datos
    public function __construct($db)
    {
        $this->db = $db;
        $this->importArrayLabels($this->xmlImports); // Importa los datos de los archivos XML
    }

    // Busca una discográfica por su ID
    public function findRecordLabelById($id): array
    {
        $sql = "SELECT * FROM RECORD_LABEL_TBL WHERE Id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'RecordLabel');
        return $stmt->fetch(); // Devuelve un objeto RecordLabel
    }

    // Busca una discográfica por su nombre
    public function findRecordLabelByName($name): int
    {
        $sql = "SELECT Id FROM RECORD_LABEL_TBL WHERE Record_Label_Name = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$name]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC); // Devuelve el resultado como un array asociativo
        return $result ? $result['Id'] : null; // Devuelve el ID del género si está disponible, o null si no se encontró
    }

    // Busca todas las discográficas
    public function findAllRecordLabel(): array
    {
        $sql = "SELECT * FROM RECORD_LABEL_TBL";  // Asegúrate de que el nombre de la tabla es correcto
        $stmt = $this->db->query($sql);
        $stmt->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'RecordLabel'); // Usar RecordLabel como la clase destino
        $recordLabels = $stmt->fetchAll();
        if ($recordLabels) {
            return $recordLabels;  // Retorna los objetos RecordLabel si la consulta fue exitosa
        } else {
            return null;  // Retorna null si no se encontraron discográficas
        }
    }
    // Busca las discográficas de los vinilos insertados por un usuario específico
    public function getRecordLabelsByUserId($userId): array
    {
        // Consulta para obtener las discográficas de los vinilos insertados por un usuario específico
        $sql = "SELECT DISTINCT rl.* FROM RECORD_LABEL_TBL rl
                INNER JOIN VINYLS_TBL v ON rl.Id = v.Record_Label_Id
                WHERE v.User_Id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$userId]);
        // Devuelve un array de objetos RecordLabel
        return $stmt->fetchAll(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'RecordLabel');
    }

    // Importa las etiquetas de los archivos XML a la base de datos
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
