<?php

require_once(BASE_DIR . 'app/model/format.php');
require_once(BASE_DIR . 'dao/interfaces/IFormatDAO.php');

// Implementación de la interfaz IFormatDAO
class formatDAO implements IFormatDAO
{
    private $db; // PDO instance for database connection
    private $xmlImports = [
        [
            "xmlFilePath" => BASE_DIR . "app/data/xml/formatos.xml", // Ruta del archivo XML,
            "fatherTag" => "formats", // Etiqueta padre
            "childTag" => "format", // Etiqueta hija
            "tableName" => "FORMAT_TBL", // Nombre de la tabla
            "columnName" => "Format_Name" // Nombre de la columna
        ]
    ];

    // Constructor recibe la conexión a la base de datos
    public function __construct($db)
    {
        $this->db = $db;
        $this->importArrayLabels($this->xmlImports); // Importa los datos de los archivos XML
    }

    // Busca un formato por su ID
    public function findById($id): array
    {
        $sql = "SELECT * FROM FORMAT_TBL WHERE Id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'Format');
        return $stmt->fetch(); // Devuelve un objeto Format
    }

    // Busca un formato por su nombre
    public function findByName($name): int
    {
        $sql = "SELECT Id FROM FORMAT_TBL WHERE Format_Name = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$name]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC); // Devuelve el resultado como un array asociativo
        return $result ? $result['Id'] : null; // Devuelve el ID del formato si está disponible, o null si no se encontró
    }

    // Busca todos los formatos
    public function findAll(): array
    {
        $sql = "SELECT * FROM FORMAT_TBL";  // Asegúrate de que el nombre de la tabla es correcto
        $stmt = $this->db->query($sql);
        $stmt->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Format'); // Usar Format como la clase destino
        $formats = $stmt->fetchAll();
        if ($formats) {
            return $formats;  // Retorna los objetos Format si la consulta fue exitosa
        } else {
            return null;  // Retorna null si no se encontraron formatos
        }
    }

    // Busca los formatos de vinilos insertados por un usuario específico.
    public function getFormatsByUserId($userId): array
    {
        // Consulta para obtener los formatos de los vinilos insertados por un usuario específico
        $sql = "SELECT DISTINCT f.* FROM FORMAT_TBL f
                INNER JOIN VINYLS_TBL v ON f.Id = v.Format_Id
                WHERE v.User_Id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$userId]);
        // Devuelve un array de objetos Format
        return $stmt->fetchAll(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Format');
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
