DROP DATABASE IF EXISTS VinylLibraryDB;

-- Nombre de la base de datos: VinylLibraryDB
CREATE DATABASE IF NOT EXISTS VinylLibraryDB;
USE VinylLibraryDB;

-- ----------------------------------------------------------------------------------------------------------------
-- ----------------------------------------------------------------------------------------------------------------
-- ----------------------------------------------------------------------------------------------------------------
-- ----------------------------------------------------------------------------------------------------------------
-- ----USUARIOS Y ROLES--------------------------------------------------------------------------------------------
-- ----------------------------------------------------------------------------------------------------------------
-- La relación entre las entidades Users_TBL y Roles_TBL es de N:M
-- Por lo tanto necesitamos una tabla intermedia (Users_Roles) para Users_TBL y Roles_TBL.
-- Tablas: Users_TBL, Roles_TBL y Users_Roles
-- ----------------------------------------------------------------------------------------------------------------
-- ----------------------------------------------------------------------------------------------------------------
-- ----------------------------------------------------------------------------------------------------------------
-- ----------------------------------------------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS Users_TBL ( -- Tabla de usuarios
  id INT AUTO_INCREMENT PRIMARY KEY, -- ID del usuario
  username VARCHAR(32) NOT NULL, -- Nombre de usuario
  password VARCHAR(255) NOT NULL, -- Contraseña
  email VARCHAR(254) NOT NULL, -- Email
  UNIQUE KEY unique_email (email), -- Valores únicos (no pueden existir dos emails iguales)
  UNIQUE KEY unique_username (username) -- Valores únicos (no pueden existir dos usernames iguales)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4; -- Codificación de caracteres

CREATE TABLE IF NOT EXISTS Roles_TBL ( -- Tabla de roles
  id INT AUTO_INCREMENT PRIMARY KEY, -- ID del rol
  rol_name VARCHAR(255) UNIQUE -- Nombre del rol
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4; -- Codificación de caracteres

CREATE TABLE IF NOT EXISTS Users_Roles ( -- Tabla intermedia para la relación N:M entre Users_TBL y Roles_TBL
  User_Id INT, -- ID del usuario
  Role_Id INT, -- ID del rol
  PRIMARY KEY (User_Id, Role_Id), -- Clave primaria
  CONSTRAINT fk_user_id FOREIGN KEY (User_Id) REFERENCES Users_TBL(id) ON DELETE CASCADE, 
  CONSTRAINT fk_role_id FOREIGN KEY (Role_Id) REFERENCES Roles_TBL(id) ON DELETE CASCADE 
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insertamos los datos del Administrador. Contraseña A29xxRamones
INSERT INTO Users_TBL (id, username, password, email) VALUES (1, "admin", "$2y$10$73G0DGZZFkHPUHMtL85VUOHLz3K7Fj9jrJrdH/XXd8epLW8ncpIVe", "admin@vlexplosion.org");
INSERT INTO Users_TBL (id, username, password, email) VALUES (2, "Antonio", "$2y$10$i3JX.uYgwqhTDPVfCsgSrehmJiWOcN0zH/Yyw/3gCjrSdyE0NxVWG", "antoniomartinezramirez@gmail.com");


-- Insertamos datos en la tabla Roles_TBL 
INSERT INTO Roles_TBL (rol_name) VALUES ('admin'); -- ID 1 Admin
INSERT INTO Roles_TBL (rol_name) VALUES ('user'); -- ID 2 User
-- ------------------------------------------------------------- 

-- Insertamos datos en la tabla Users_Roles

INSERT INTO Users_Roles (User_Id, Role_Id) VALUES (1, 1); -- 1, 1 (Usuario 1, Rol administrador) -- admin
INSERT INTO Users_Roles (User_Id, Role_Id) VALUES (2, 2); -- 2, 2 (Usuario 2, Rol User) -- Antonio

-- Añade rol administrador
-- INSERT INTO Users_Roles (User_Id, Role_Id)
-- SELECT u.id, r.id
-- FROM Users_TBL u
-- JOIN Roles_TBL r ON r.rol_name = 'admin'
-- WHERE u.username = 'Antonio'
-- ON DUPLICATE KEY UPDATE Role_Id = 1;

-- elimina rol,administrador
-- DELETE FROM Users_Roles
-- USING Users_Roles
-- JOIN Users_TBL ON Users_TBL.id = Users_Roles.User_Id
-- JOIN Roles_TBL ON Roles_TBL.id = Users_Roles.Role_Id


-- ----------------------------------------------------------------------------------------------------------------
-- ----------------------------------------------------------------------------------------------------------------
-- ----------------------------------------------------------------------------------------------------------------
-- ----------------------------------------------------------------------------------------------------------------
-- ----- FIN USUARIOS Y ROLES--------------------------------------------------------------------------------------
-- ----------------------------------------------------------------------------------------------------------------
-- ----------------------------------------------------------------------------------------------------------------
-- ----------------------------------------------------------------------------------------------------------------
-- ----------------------------------------------------------------------------------------------------------------


/*********************************************************/

-- Tabla Edición EDITION_TBL 

-- Un disco puede ser de una solo una edición.
-- Una edición puede tener muchos discos
-- Relación 1:N

CREATE TABLE IF NOT EXISTS EDITION_TBL ( -- Tabla de ediciones
  Id INT NOT NULL AUTO_INCREMENT, -- ID de la edición
  Edition_Name VARCHAR(30) NOT NULL, -- Nombre de la edición
  PRIMARY KEY (Id) -- Clave primaria
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4; -- Codificación de caracteres


/*********************************************************/

-- Tabla Discograficas RECORD_LABEL_TBL

-- Un disco puede ser de una sello discografico.
-- Una discografica puede tener muchos discos
-- Relación 1:N

CREATE TABLE IF NOT EXISTS RECORD_LABEL_TBL ( -- Tabla de discográficas
  Id INT NOT NULL AUTO_INCREMENT, -- ID de la discográfica
  Record_Label_Name VARCHAR(40) NOT NULL, -- Nombre de la discográfica
  PRIMARY KEY (Id) -- Clave primaria
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4; -- Codificación de caracteres


/*********************************************************/

-- Tabla Generos GENRES_TBL

-- Un disco puede ser de un genero.
-- Un genero puede tener muchos discos.
-- Relación 1:N


CREATE TABLE IF NOT EXISTS GENRES_TBL ( -- Tabla de géneros
  Id INT NOT NULL AUTO_INCREMENT, -- ID del género
  Genre_Name VARCHAR(30) NOT NULL, -- Nombre del género
  PRIMARY KEY (Id) -- Clave primaria
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4; -- Codificación de caracteres

/*********************************************************/

-- Tabla Formatos FORMAT_TBL

-- Un disco puede ser de un formato.
-- Un formato puede tener muchos discos.
-- Relación 1:N

CREATE TABLE IF NOT EXISTS FORMAT_TBL ( -- Tabla de formatos
  Id INT NOT NULL AUTO_INCREMENT, -- ID del formato
  Format_Name VARCHAR(30) NOT NULL, -- Nombre del formato
  PRIMARY KEY (Id) -- Clave primaria
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4; -- Codificación de caracteres


/*********************************************************/

-- Tabla Formatos CONDITION_TBL

-- Un disco puede estar en un estado de conservación X.
-- Un estado de conservación lo pueden tener muchos discos
-- Relación 1:N

CREATE TABLE IF NOT EXISTS CONDITION_TBL ( -- Tabla de estados de conservación
  Id INT NOT NULL AUTO_INCREMENT, -- ID del estado de conservación
  Condition_Name VARCHAR(30) NOT NULL, -- Nombre del estado de conservación
  PRIMARY KEY (Id) -- Clave primaria
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4; -- Codificación de caracteres


/*********************************************************/

-- Tabla Author AUTHORS_TBL ¡¡¡¡¡¡¡ENTIDAD AUTOR!!!!!!

-- Un autor puede tener muchos discos de vinilo
-- Un disco de vinilo pueder ser de muchos autores
-- Relación N:M

CREATE TABLE IF NOT EXISTS AUTHORS_TBL ( -- Tabla de autores
  Id INT NOT NULL AUTO_INCREMENT, -- ID del autor
  Author_Name VARCHAR(50) NOT NULL, -- Nombre del autor
  Debut_album_release YEAR, -- Año de lanzamiento del primer álbum
  Original_members INT, -- Número de miembros originales
  Breakup_date YEAR, -- Año de separación
  PRIMARY KEY (Id) -- Clave primaria
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4; -- Codificación de caracteres


/*********************************************************/

-- Tabla Vinilos VINYLS_TBL ¡¡¡¡¡¡¡ENTIDAD VINILOS!!!!!!
-- Relación 1:N
CREATE TABLE IF NOT EXISTS VINYLS_TBL (
    Id INT NOT NULL AUTO_INCREMENT,
    Title VARCHAR(65) NOT NULL,
    Genres_Id INT NOT NULL,
    Format_Id INT NOT NULL,
    Condition_Id INT NOT NULL,
    Record_Label_Id INT NOT NULL,
    Producer VARCHAR(50) NOT NULL,
    Release_date YEAR NOT NULL,
    Edition_Id INT NOT NULL,
    User_Id INT NOT NULL,
    Is_Favorite BOOLEAN DEFAULT FALSE,
    Is_Desired BOOLEAN DEFAULT FALSE,
    PRIMARY KEY (Id),
    CONSTRAINT fk_genero FOREIGN KEY (Genres_Id) REFERENCES GENRES_TBL(Id) ON DELETE CASCADE,
    CONSTRAINT fk_formato FOREIGN KEY (Format_Id) REFERENCES FORMAT_TBL(Id) ON DELETE CASCADE,
    CONSTRAINT fk_condition FOREIGN KEY (Condition_Id) REFERENCES CONDITION_TBL(Id) ON DELETE CASCADE,
    CONSTRAINT fk_record_label FOREIGN KEY (Record_Label_Id) REFERENCES RECORD_LABEL_TBL(Id) ON DELETE CASCADE,
    CONSTRAINT fk_edition FOREIGN KEY (Edition_Id) REFERENCES EDITION_TBL(Id) ON DELETE CASCADE,
    CONSTRAINT fk_user FOREIGN KEY (User_Id) REFERENCES Users_TBL(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

/***********************************************************************************************************************************************/

-- Tabla intermedia para la relación de N:M entre AUTHORS_TBL y VINYLS_TBL ¡¡¡¡¡¡¡RELACIÓN!!!!!!

CREATE TABLE IF NOT EXISTS AUTOR_VINYLS_TBL ( -- Tabla intermedia para la relación N:M entre AUTHORS_TBL
  Autor_Id INT NOT NULL, -- ID del autor
  Vinilo_Id INT NOT NULL, -- ID del vinilo
  PRIMARY KEY (Autor_Id, Vinilo_Id), -- Clave primaria
  CONSTRAINT fk_autor FOREIGN KEY (Autor_Id) REFERENCES AUTHORS_TBL(Id) ON DELETE CASCADE, -- Clave foranea
  CONSTRAINT fk_vinilo FOREIGN KEY (Vinilo_Id) REFERENCES VINYLS_TBL(Id) ON DELETE CASCADE -- Clave foranea
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4; -- Codificación de caracteres

/***********************************************************************************************************************************************/

