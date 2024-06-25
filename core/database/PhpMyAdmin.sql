DROP DATABASE IF EXISTS VinylLibraryDB;

-- Nombre de la base de datos: VinylLibraryDB
CREATE DATABASE IF NOT EXISTS VinylLibraryDB;

USE VinylLibraryDB;



-- phpMyAdmin SQL Dump
-- version 5.2.1deb1
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Tiempo de generación: 14-06-2024 a las 17:46:03
-- Versión del servidor: 8.0.36
-- Versión de PHP: 8.2.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `VinylLibraryDB`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `AUTHORS_TBL`
--

CREATE TABLE `AUTHORS_TBL` (
  `Id` int NOT NULL,
  `Author_Name` varchar(50) NOT NULL,
  `Debut_album_release` year DEFAULT NULL,
  `Original_members` int DEFAULT NULL,
  `Breakup_date` year DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `AUTHORS_TBL`
--

INSERT INTO `AUTHORS_TBL` (`Id`, `Author_Name`, `Debut_album_release`, `Original_members`, `Breakup_date`) VALUES
(6, 'Depeche mode', NULL, NULL, NULL),
(7, 'Depeche mode', NULL, NULL, NULL),
(8, 'Depeche mode', NULL, NULL, NULL),
(9, 'Pixies', NULL, NULL, NULL),
(10, 'Depeche mode', NULL, NULL, NULL),
(11, 'Depeche mode', NULL, NULL, NULL),
(12, 'The Cure', NULL, NULL, NULL),
(13, 'Alien sex fiend', NULL, NULL, NULL),
(14, 'Ramones', NULL, NULL, NULL),
(15, 'Ramones', NULL, NULL, NULL),
(16, 'Ramones', NULL, NULL, NULL),
(17, 'Ramones', NULL, NULL, NULL),
(18, 'Ramones', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `AUTOR_VINYLS_TBL`
--

CREATE TABLE `AUTOR_VINYLS_TBL` (
  `Autor_Id` int NOT NULL,
  `Vinilo_Id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `AUTOR_VINYLS_TBL`
--

INSERT INTO `AUTOR_VINYLS_TBL` (`Autor_Id`, `Vinilo_Id`) VALUES
(6, 6),
(7, 7),
(8, 8),
(9, 9),
(10, 10),
(11, 11),
(12, 12),
(13, 13),
(14, 14),
(15, 15),
(16, 16),
(17, 17),
(18, 18);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `CONDITION_TBL`
--

CREATE TABLE `CONDITION_TBL` (
  `Id` int NOT NULL,
  `Condition_Name` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `CONDITION_TBL`
--

INSERT INTO `CONDITION_TBL` (`Id`, `Condition_Name`) VALUES
(1, 'Mint'),
(2, 'Near Mint'),
(3, 'Excelent'),
(4, 'Very good Plus'),
(5, 'Very Good'),
(6, 'Good'),
(7, 'Poor');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `EDITION_TBL`
--

CREATE TABLE `EDITION_TBL` (
  `Id` int NOT NULL,
  `Edition_Name` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `EDITION_TBL`
--

INSERT INTO `EDITION_TBL` (`Id`, `Edition_Name`) VALUES
(1, 'Original'),
(2, 'Reedición'),
(3, 'Edición Remasterizada'),
(4, 'Edición Deluxe'),
(5, 'Edición Limitada'),
(6, 'Edición Expandida'),
(7, 'Box Set / Caja Recopilatoria'),
(8, 'Picture Disc'),
(9, 'Edición en color'),
(10, 'Edición Acustica');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `FORMAT_TBL`
--

CREATE TABLE `FORMAT_TBL` (
  `Id` int NOT NULL,
  `Format_Name` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `FORMAT_TBL`
--

INSERT INTO `FORMAT_TBL` (`Id`, `Format_Name`) VALUES
(1, 'LP'),
(2, 'Single'),
(3, '2 LP'),
(4, 'Maxi Single'),
(5, '4 LP');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `GENRES_TBL`
--

CREATE TABLE `GENRES_TBL` (
  `Id` int NOT NULL,
  `Genre_Name` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `GENRES_TBL`
--

INSERT INTO `GENRES_TBL` (`Id`, `Genre_Name`) VALUES
(1, 'American Rock Band'),
(2, 'American Singer'),
(3, 'Black Metal'),
(4, 'British rock'),
(5, 'Cantautor Español'),
(6, 'Compositor Britanico'),
(7, 'Doo wop'),
(8, 'Garage Rock'),
(9, 'Ghotic Rock'),
(10, 'Hard Rock'),
(11, 'Heavy Metal'),
(12, 'Indie'),
(13, 'Jazz Rock'),
(14, 'Metal Alternativo'),
(15, 'Musica Clasica'),
(16, 'Música Electrónica'),
(17, 'New Wave'),
(18, 'Oi!'),
(19, 'Pop'),
(20, 'Pop Punk'),
(21, 'Pop/Rock Español'),
(22, 'Post Punk'),
(23, 'Power Pop'),
(24, 'Psychedelic Rock'),
(25, 'Psychobilly'),
(26, 'Punk'),
(27, 'Punk Rock'),
(28, 'Rhythm and Blues'),
(29, 'Rock'),
(30, 'Rock Alternativo'),
(31, 'Rock Progresivo'),
(32, 'Rock sinfónico'),
(33, 'Rock&Roll 50\'s'),
(34, 'Rockabilly'),
(35, 'Ska'),
(36, 'Soft Rock'),
(37, 'Soul'),
(38, 'Surf'),
(39, 'Synth Pop / New Wave');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `RECORD_LABEL_TBL`
--

CREATE TABLE `RECORD_LABEL_TBL` (
  `Id` int NOT NULL,
  `Record_Label_Name` varchar(40) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `RECORD_LABEL_TBL`
--

INSERT INTO `RECORD_LABEL_TBL` (`Id`, `Record_Label_Name`) VALUES
(1, '4AD'),
(2, 'Ace Records'),
(3, 'Acme Records'),
(4, 'Arista'),
(5, 'Astan music AG/Luzern'),
(6, 'Atlantic'),
(7, 'Avispa Records'),
(8, 'BBC'),
(9, 'Beggars Banquet Records LTD'),
(10, 'Beserkley Records'),
(11, 'Big Beat'),
(12, 'Black tulip'),
(13, 'Bomp Records'),
(14, 'Buto Eskor'),
(15, 'Capitol Records'),
(16, 'Cascade Records'),
(17, 'Castle Communications'),
(18, 'CBS'),
(19, 'Chiswick Records'),
(20, 'Chrysalis'),
(21, 'Columbia'),
(22, 'Combat Records'),
(23, 'Demon Records'),
(24, 'DRO'),
(25, 'E.G. Records Ltd'),
(26, 'Electrobird'),
(27, 'EMI'),
(28, 'Enigma Record'),
(29, 'Epic'),
(30, 'EVA'),
(31, 'Factory Records'),
(32, 'Fiction Records'),
(33, 'Fonomusic'),
(34, 'Food Records'),
(35, 'Fontana'),
(36, 'Frontier Records'),
(37, 'G.G.G.B.H.'),
(38, 'Geffen Records'),
(39, 'GM Gramusic'),
(40, 'GMG Records'),
(41, 'Grabaciones Accidentales'),
(42, 'Grabaciones Interferencias'),
(43, 'Harvest'),
(44, 'Hi-fi Discos Electronica'),
(45, 'Hispavox'),
(46, 'Impulse'),
(47, 'Iberofon'),
(48, 'Island'),
(49, 'Jazz Life'),
(50, 'Jet Records'),
(51, 'Junk Records'),
(52, 'La Fabrica Magnetica'),
(53, 'La Rosa Records'),
(54, 'Lazy Records'),
(55, 'Liberty'),
(56, 'London Records'),
(57, 'Magnum House'),
(58, 'Metronome Musik GMBH'),
(59, 'Misfits Records'),
(60, 'Movieplay'),
(61, 'MCA Records'),
(62, 'Munster Records'),
(63, 'Music Manic Records'),
(64, 'Mute Records Ltd'),
(65, 'Nems'),
(66, 'No lo pone'),
(67, 'No Tomorrow Record'),
(68, 'Norton Records'),
(69, 'Philips'),
(70, 'Plan 9 Records'),
(71, 'Plangent Visions'),
(72, 'Polydor LTD'),
(73, 'Polygram Iberica'),
(74, 'Ppfront'),
(75, 'RCA'),
(76, 'Red Star Music Inc'),
(77, 'Rhino Records'),
(78, 'RSO Records'),
(79, 'Roadrunner Records'),
(80, 'Rockstar Records'),
(81, 'Rounder Records / DRO'),
(82, 'Rumble Records'),
(83, 'Running circle'),
(84, 'Sanctuary Records'),
(85, 'See for miles records ltd'),
(86, 'Serdisco'),
(87, 'Sirex'),
(88, 'Slash Records'),
(89, 'Speakers Corner Records'),
(90, 'spew noise'),
(91, 'SST Records'),
(92, 'Stiff Records'),
(93, 'Sun Records'),
(94, 'Taco Tunes ASCAP / Westminster Music LTD'),
(95, 'Tres Cipreses'),
(96, 'United Artist Records'),
(97, 'Vemsa'),
(98, 'Virgin'),
(99, 'Voxx Records'),
(100, 'White Label'),
(101, 'Wild Punk Records'),
(102, 'Zafiro'),
(103, 'Zyx Music'),
(104, 'Zoo Entertainment');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Roles_TBL`
--

CREATE TABLE `Roles_TBL` (
  `id` int NOT NULL,
  `rol_name` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `Roles_TBL`
--

INSERT INTO `Roles_TBL` (`id`, `rol_name`) VALUES
(1, 'admin'),
(2, 'user');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Users_Roles`
--

CREATE TABLE `Users_Roles` (
  `User_Id` int NOT NULL,
  `Role_Id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `Users_Roles`
--

INSERT INTO `Users_Roles` (`User_Id`, `Role_Id`) VALUES
(1, 1),
(2, 1),
(2, 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Users_TBL`
--

CREATE TABLE `Users_TBL` (
  `id` int NOT NULL,
  `username` varchar(32) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(254) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `Users_TBL`
--

INSERT INTO `Users_TBL` (`id`, `username`, `password`, `email`) VALUES
(1, 'admin', '$2y$10$73G0DGZZFkHPUHMtL85VUOHLz3K7Fj9jrJrdH/XXd8epLW8ncpIVe', 'admin@vlexplosion.org'),
(2, 'Antonio', '$2y$10$8pqx85SuTdARWkWQnhO.3OW6ohR3n...ObB/TSc8NKPdtkvY0e0s2', 'antoniomartinezramirez@gmail.com');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `VINYLS_TBL`
--

CREATE TABLE `VINYLS_TBL` (
  `Id` int NOT NULL,
  `Title` varchar(65) NOT NULL,
  `Genres_Id` int NOT NULL,
  `Format_Id` int NOT NULL,
  `Condition_Id` int NOT NULL,
  `Record_Label_Id` int NOT NULL,
  `Producer` varchar(50) NOT NULL,
  `Release_date` year NOT NULL,
  `Edition_Id` int NOT NULL,
  `User_Id` int NOT NULL,
  `Is_Favorite` tinyint(1) DEFAULT '0',
  `Is_Desired` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `VINYLS_TBL`
--

INSERT INTO `VINYLS_TBL` (`Id`, `Title`, `Genres_Id`, `Format_Id`, `Condition_Id`, `Record_Label_Id`, `Producer`, `Release_date`, `Edition_Id`, `User_Id`, `Is_Favorite`, `Is_Desired`) VALUES
(6, 'Construction time again', 39, 1, 4, 64, 'Depeche Mode y Daniel Miller', '1983', 1, 2, 0, 1),
(7, 'Violator', 39, 1, 3, 64, 'Depeche Mode y Flood', '1990', 1, 2, 0, 1),
(8, 'Black Celebration', 39, 1, 2, 64, 'Daniel Miller y Gareth Jones', '1986', 2, 2, 1, 0),
(9, 'Doolittle', 30, 1, 3, 1, 'Gil Norton', '1989', 1, 2, 1, 0),
(10, 'Memento mori', 16, 3, 2, 21, 'James Ford', '2023', 1, 2, 0, 0),
(11, 'Playing the Angel', 39, 3, 2, 64, 'Ben Hillier', '2005', 2, 2, 1, 0),
(12, 'Seventeen Seconds', 9, 1, 3, 32, 'Mike Hedges, Robert Smith', '1979', 2, 2, 0, 0),
(13, 'All our yesterdays', 9, 1, 4, 28, 'Yoof', '1988', 1, 2, 1, 0),
(14, 'Road to ruin', 27, 1, 5, 87, 'Tommy Ramone, Ed Stasium', '1978', 1, 2, 0, 0),
(15, 'End of the century', 27, 1, 2, 87, 'Phil Spector', '1980', 2, 2, 0, 0),
(16, 'I Wanna Live', 27, 4, 5, 9, 'Daniel rey', '1987', 1, 2, 0, 0),
(17, 'Subterranean Jungle', 27, 1, 3, 87, 'Glen Kolotkin, Ritchie Cordell', '1983', 2, 2, 0, 0),
(18, 'Howling At The Moon (Sha-La-La) / Chasing The Night', 27, 4, 5, 87, 'David A. Stewart, Ed Stasium, T. Erdelyi', '1984', 1, 2, 0, 0);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `AUTHORS_TBL`
--
ALTER TABLE `AUTHORS_TBL`
  ADD PRIMARY KEY (`Id`);

--
-- Indices de la tabla `AUTOR_VINYLS_TBL`
--
ALTER TABLE `AUTOR_VINYLS_TBL`
  ADD PRIMARY KEY (`Autor_Id`,`Vinilo_Id`),
  ADD KEY `fk_vinilo` (`Vinilo_Id`);

--
-- Indices de la tabla `CONDITION_TBL`
--
ALTER TABLE `CONDITION_TBL`
  ADD PRIMARY KEY (`Id`);

--
-- Indices de la tabla `EDITION_TBL`
--
ALTER TABLE `EDITION_TBL`
  ADD PRIMARY KEY (`Id`);

--
-- Indices de la tabla `FORMAT_TBL`
--
ALTER TABLE `FORMAT_TBL`
  ADD PRIMARY KEY (`Id`);

--
-- Indices de la tabla `GENRES_TBL`
--
ALTER TABLE `GENRES_TBL`
  ADD PRIMARY KEY (`Id`);

--
-- Indices de la tabla `RECORD_LABEL_TBL`
--
ALTER TABLE `RECORD_LABEL_TBL`
  ADD PRIMARY KEY (`Id`);

--
-- Indices de la tabla `Roles_TBL`
--
ALTER TABLE `Roles_TBL`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `rol_name` (`rol_name`);

--
-- Indices de la tabla `Users_Roles`
--
ALTER TABLE `Users_Roles`
  ADD PRIMARY KEY (`User_Id`,`Role_Id`),
  ADD KEY `fk_role_id` (`Role_Id`);

--
-- Indices de la tabla `Users_TBL`
--
ALTER TABLE `Users_TBL`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_email` (`email`),
  ADD UNIQUE KEY `unique_username` (`username`);

--
-- Indices de la tabla `VINYLS_TBL`
--
ALTER TABLE `VINYLS_TBL`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `fk_genero` (`Genres_Id`),
  ADD KEY `fk_formato` (`Format_Id`),
  ADD KEY `fk_condition` (`Condition_Id`),
  ADD KEY `fk_record_label` (`Record_Label_Id`),
  ADD KEY `fk_edition` (`Edition_Id`),
  ADD KEY `fk_user` (`User_Id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `AUTHORS_TBL`
--
ALTER TABLE `AUTHORS_TBL`
  MODIFY `Id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT de la tabla `CONDITION_TBL`
--
ALTER TABLE `CONDITION_TBL`
  MODIFY `Id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `EDITION_TBL`
--
ALTER TABLE `EDITION_TBL`
  MODIFY `Id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `FORMAT_TBL`
--
ALTER TABLE `FORMAT_TBL`
  MODIFY `Id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `GENRES_TBL`
--
ALTER TABLE `GENRES_TBL`
  MODIFY `Id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT de la tabla `RECORD_LABEL_TBL`
--
ALTER TABLE `RECORD_LABEL_TBL`
  MODIFY `Id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=105;

--
-- AUTO_INCREMENT de la tabla `Roles_TBL`
--
ALTER TABLE `Roles_TBL`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `Users_TBL`
--
ALTER TABLE `Users_TBL`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `VINYLS_TBL`
--
ALTER TABLE `VINYLS_TBL`
  MODIFY `Id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `AUTOR_VINYLS_TBL`
--
ALTER TABLE `AUTOR_VINYLS_TBL`
  ADD CONSTRAINT `fk_autor` FOREIGN KEY (`Autor_Id`) REFERENCES `AUTHORS_TBL` (`Id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_vinilo` FOREIGN KEY (`Vinilo_Id`) REFERENCES `VINYLS_TBL` (`Id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `Users_Roles`
--
ALTER TABLE `Users_Roles`
  ADD CONSTRAINT `fk_role_id` FOREIGN KEY (`Role_Id`) REFERENCES `Roles_TBL` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_user_id` FOREIGN KEY (`User_Id`) REFERENCES `Users_TBL` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `VINYLS_TBL`
--
ALTER TABLE `VINYLS_TBL`
  ADD CONSTRAINT `fk_condition` FOREIGN KEY (`Condition_Id`) REFERENCES `CONDITION_TBL` (`Id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_edition` FOREIGN KEY (`Edition_Id`) REFERENCES `EDITION_TBL` (`Id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_formato` FOREIGN KEY (`Format_Id`) REFERENCES `FORMAT_TBL` (`Id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_genero` FOREIGN KEY (`Genres_Id`) REFERENCES `GENRES_TBL` (`Id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_record_label` FOREIGN KEY (`Record_Label_Id`) REFERENCES `RECORD_LABEL_TBL` (`Id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_user` FOREIGN KEY (`User_Id`) REFERENCES `Users_TBL` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
