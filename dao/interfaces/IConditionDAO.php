<?php

// Interfaz DAO para condiciones
interface IConditionDAO
{
    public function findById($id): array;
    public function findByName($name): int;
    public function findAll(): array;
    public function getConditionsByUserId($userId):array;
    public function importArrayLabels($arrayData): void;
}
    
