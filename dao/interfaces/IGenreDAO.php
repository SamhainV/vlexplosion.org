<?php

// Interfaz DAO para géneros
interface IGenreDAO
{
    public function findById($id): ?Genre;
    public function findByName($name): int;
    public function findAll(): array;
    public function getGenresByUserId($userId):array;
    public function importArrayLabels($arrayData): void;
}
    

