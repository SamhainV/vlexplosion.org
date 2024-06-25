<?php

// Interfaz DAO para sellos discográficos
interface IRecordLabelDAO
{
    public function findRecordLabelById($id): array;
    public function findRecordLabelByName($name): int;
    public function findAllRecordLabel(): array;
    public function getRecordLabelsByUserId($userId):array;
    public function importArrayLabels($arrayData): void;
}