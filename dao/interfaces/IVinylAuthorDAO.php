<?php

// Interfaz DAO para autores de vinilos
interface IVinylAuthorDAO {
    public function addAuthorToVinyl($vinylId, $authorId);
    public function removeAuthorFromVinyl($vinylId, $authorId);
    public function getAuthorsByVinylId($vinylId);
}