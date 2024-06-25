<?php
// Interfaz DAO para vinilos
interface IVinylDAO
{
    public function insert(Vinyl $vinyl): int;
    public function update(Vinyl $vinyl): int;
    public function findByUserId($userId): array;
    public function getRecordDetails($userLogged, $condiciones = null):array;
    public function getFavoriteRecords($userId);

}
