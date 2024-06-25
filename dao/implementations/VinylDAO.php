<?php
require_once(BASE_DIR . 'dao/interfaces/IVinylDAO.php');

// Implementación de la interfaz IVinylDAO
class VinylDAO implements IVinylDAO
{
    private $db; // Conexión a la base de datos

    // Constructor recibe la conexión a la base de datos
    public function __construct($db)
    {
        $this->db = $db;
    }

    // inserta un vinilo en la base de datos
    public function insert(Vinyl $vinyl): int
    {
        $sql = "INSERT INTO VINYLS_TBL (Title, Release_date, Genres_Id, Format_Id, Condition_Id, Record_Label_Id, Producer, Edition_Id, User_Id, Is_Favorite, Is_Desired) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            $vinyl->Title,
            $vinyl->Release_date,
            $vinyl->Genres_Id,
            $vinyl->Format_Id,
            $vinyl->Condition_Id,
            $vinyl->Record_Label_Id,
            $vinyl->Producer,
            $vinyl->Edition_Id,
            $vinyl->User_Id,
            $vinyl->Is_Favorite,
            $vinyl->Is_Desired
        ]);
        return $this->db->lastInsertId();
    }
    
    // Elimina un vinilo por su ID
    public function deleteVinylByUser($vinylId, $userId)
    {
        // Primero, buscar los autores asociados a este vinilo antes de eliminar el vinilo
        $sql = "SELECT Autor_Id FROM AUTOR_VINYLS_TBL WHERE Vinilo_Id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$vinylId]);
        $authorIds = $stmt->fetchAll(PDO::FETCH_COLUMN, 0);

        // Eliminar el vinilo de la tabla VINYLS_TBL asegurando que pertenezca al usuario específico
        $sql = "DELETE FROM VINYLS_TBL WHERE Id = ? AND User_Id = ?";
        $stmt = $this->db->prepare($sql);
        $success = $stmt->execute([$vinylId, $userId]);

        if ($success) {
            // Eliminar las referencias del vinilo en la tabla AUTOR_VINYLS_TBL
            $sql = "DELETE FROM AUTOR_VINYLS_TBL WHERE Vinilo_Id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$vinylId]);

            // Verificar si los autores no están asociados con otros vinilos y eliminarlos si es el caso
            foreach ($authorIds as $authorId) {
                $sql = "SELECT COUNT(*) FROM AUTOR_VINYLS_TBL WHERE Autor_Id = ?";
                $stmt = $this->db->prepare($sql);
                $stmt->execute([$authorId]);
                $count = $stmt->fetchColumn();

                if ($count == 0) {
                    // Eliminar el autor de la tabla AUTHORS_TBL si no está asociado con otros vinilos
                    $sql = "DELETE FROM AUTHORS_TBL WHERE Id = ?";
                    $stmt = $this->db->prepare($sql);
                    $stmt->execute([$authorId]);
                }
            }

            return true; // Devuelve true si el vinilo se eliminó correctamente
        }

        return false; // Devuelve false si no se pudo eliminar el vinilo
    }


    // actualiza un ejemplar en la base de datos
    public function update(Vinyl $vinyl): int
    {
        if (empty($vinyl->Id)) {
            throw new InvalidArgumentException("El ID del vinilo es necesario para la actualización.");
        }

        $sql = "UPDATE VINYLS_TBL SET 
            Title = ?, 
            Release_date = ?, 
            Genres_Id = ?, 
            Format_Id = ?, 
            Condition_Id = ?, 
            Record_Label_Id = ?, 
            Producer = ?, 
            Edition_Id = ?, 
            User_Id = ?, 
            Is_Favorite = ?, 
            Is_Desired = ?
            WHERE Id = ?";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            $vinyl->Title,
            $vinyl->Release_date,
            $vinyl->Genres_Id,
            $vinyl->Format_Id,
            $vinyl->Condition_Id,
            $vinyl->Record_Label_Id,
            $vinyl->Producer,
            $vinyl->Edition_Id,
            $vinyl->User_Id,
            $vinyl->Is_Favorite,
            $vinyl->Is_Desired,
            $vinyl->Id
        ]);

        return $stmt->rowCount();
    }

    // Busca un discos segun el id del usuario
    public function findByUserId($userId): array
    {
        $sql = "SELECT * FROM VINYLS_TBL WHERE User_Id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_CLASS, 'Vinyl');
    }

    // Busca y devuelve todos los registros de un usuario dado.
    public function getRecordDetails($userLogged, $condiciones = null): array
    {
        try {
            // Iniciar la consulta con la parte base
            $query =
                "SELECT A.Id AS AutorId, AV.Autor_Id, AV.Vinilo_Id, A.Author_Name, A.Debut_album_release, A.Original_members, A.Breakup_date,
                        V.Title, G.Genre_Name, F.Format_Name, C.Condition_Name, R.Record_Label_Name, V.Producer,
                        V.Release_date, E.Edition_Name
                        FROM AUTOR_VINYLS_TBL AV
                        INNER JOIN AUTHORS_TBL A ON AV.Autor_Id = A.Id
                        INNER JOIN VINYLS_TBL V ON AV.Vinilo_Id = V.Id
                        INNER JOIN Users_TBL U ON V.User_Id = U.Id
                        INNER JOIN GENRES_TBL G ON V.Genres_Id = G.Id
                        INNER JOIN FORMAT_TBL F ON V.Format_Id = F.Id
                        INNER JOIN CONDITION_TBL C ON V.Condition_Id = C.Id
                        INNER JOIN RECORD_LABEL_TBL R ON V.Record_Label_Id = R.Id
                        INNER JOIN EDITION_TBL E ON V.Edition_Id = E.Id
                        WHERE U.username = :userLogged";

            // Preparar un array para los parámetros
            $params = [':userLogged' => $userLogged];

            // Añadir condiciones dinámicas si existen
            if ($condiciones !== null && is_array($condiciones)) {
                foreach ($condiciones as $clave => $valor) {
                    // Asegurarse de que el nombre de la columna sea específico para evitar ambigüedades
                    $prefijoTabla = "";
                    switch ($clave) {
                        case "Id":
                            $prefijoTabla = "A."; // Suponiendo que 'Id' se refiere a 'AUTHORS_TBL'
                            break;
                        case "Genre_Name":
                            $prefijoTabla = "G.";
                            break;
                        case "Format_Name":
                            $prefijoTabla = "F.";
                            break;
                            // Añade más casos según sea necesario
                    }
                    $query .= " AND " . $prefijoTabla . "$clave = :$clave";
                    $params[":$clave"] = $valor;
                }
            }

            // Preparar la consulta SQL
            $stmt = $this->db->prepare($query);

            // Ejecutar la consulta con los parámetros
            $stmt->execute($params);

            // Recuperar y devolver todos los resultados
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Error al realizar la consulta: " . $e->getMessage());
        }
    }

    // busca los vinilos marcados como favoritos.
    public function getFavoriteRecords($userId) {
        try {
            // preparar la consulta SQL con JOINs para incluir tablas relacionadas
            $stmt = $this->db->prepare("
                SELECT 
                    v.Id AS Vinilo_Id,
                    v.*,
                    g.Genre_Name,
                    f.Format_Name,
                    c.Condition_Name,
                    r.Record_Label_Name,
                    e.Edition_Name,
                    a.Author_Name,
                    av.Autor_Id AS Author_Id
                FROM VINYLS_TBL v
                LEFT JOIN GENRES_TBL g ON v.Genres_Id = g.Id
                LEFT JOIN FORMAT_TBL f ON v.Format_Id = f.Id
                LEFT JOIN CONDITION_TBL c ON v.Condition_Id = c.Id
                LEFT JOIN RECORD_LABEL_TBL r ON v.Record_Label_Id = r.Id
                LEFT JOIN EDITION_TBL e ON v.Edition_Id = e.Id
                LEFT JOIN AUTOR_VINYLS_TBL av ON v.Id = av.Vinilo_Id
                LEFT JOIN AUTHORS_TBL a ON av.Autor_Id = a.Id
                WHERE v.User_Id = :userId AND v.Is_Favorite = TRUE
            ");
            
            // Enlazar el parámetro de ID de usuario
            $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
            
            // ejecutar la consulta
            $stmt->execute();
            
            // Recuperar todos los resultados
            $favorites = $stmt->fetchAll(PDO::FETCH_ASSOC);            
            return $favorites;
        } catch (PDOException $e) {
            // Manejar cualquier error
            echo "Error: " . $e->getMessage();
            return [];
        }
    }
    
    // busca los vinilos marcados como deseados.
    public function getDesiredRecords($userId) {
        try {
            // Prepara la consulta SQL con JOINs para incluir tablas relacionadas
            $stmt = $this->db->prepare("
                SELECT 
                    v.Id AS Vinilo_Id,
                    v.*,
                    g.Genre_Name,
                    f.Format_Name,
                    c.Condition_Name,
                    r.Record_Label_Name,
                    e.Edition_Name,
                    a.Author_Name,
                    av.Autor_Id AS Author_Id
                FROM VINYLS_TBL v
                LEFT JOIN GENRES_TBL g ON v.Genres_Id = g.Id
                LEFT JOIN FORMAT_TBL f ON v.Format_Id = f.Id
                LEFT JOIN CONDITION_TBL c ON v.Condition_Id = c.Id
                LEFT JOIN RECORD_LABEL_TBL r ON v.Record_Label_Id = r.Id
                LEFT JOIN EDITION_TBL e ON v.Edition_Id = e.Id
                LEFT JOIN AUTOR_VINYLS_TBL av ON v.Id = av.Vinilo_Id
                LEFT JOIN AUTHORS_TBL a ON av.Autor_Id = a.Id
                WHERE v.User_Id = :userId AND v.Is_Desired = TRUE
            ");
            
            // Enlazar el parámetro de ID de usuario
            $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
            
            // Ejecutar la consulta
            $stmt->execute();
            
            // Fetch all results
            $desired = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            return $desired;
        } catch (PDOException $e) {            
            echo "Error: " . $e->getMessage();
            return [];
        }
    }
    


}
