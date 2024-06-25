<?php

// Interfaz DAO para ediciones
interface IEditionDAO
{
    public function findById($id): array;
    public function findByName($name): int;
    public function findAll(): array;
    public function getEditionsByUserId($userId):array;
    public function importArrayLabels($arrayData): void;
}
    
