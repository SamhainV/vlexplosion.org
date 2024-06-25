<?php


class Format {
    public $Id; // ID del formato del vinilo, único
    public $Format_Name; // Nombre del formato, por ejemplo, "LP", "Single"

    public function __construct($Id = null, $Format_Name = null) {
        $this->Id = $Id;
        $this->Format_Name = $Format_Name;
    }
}
