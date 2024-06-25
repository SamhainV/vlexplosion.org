<?php

// Interfaz DAO para autores
interface IAuthorDAO
{
    public function insert(Author $author): int;
    public function findAll(): array;
    public function findById($id): ?Author;
    public function update(Author $author): int;
}
