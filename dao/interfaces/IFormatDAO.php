<?php

// Interfaz DAO para formatos
interface IFormatDAO
{
    public function findById($id): array;
    public function findByName($name): int;
    public function findAll(): array;
    public function getFormatsByUserId($userId):array;
    public function importArrayLabels($arrayData): void;
}