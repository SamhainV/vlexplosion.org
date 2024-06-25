<?php


class Edition {
    public $Id; // ID de la edición del vinilo, único
    public $Edition_Name; // Nombre de la edición

    public function __construct($Id = null, $Edition_Name = null) {
        $this->Id = $Id;
        $this->Edition_Name = $Edition_Name;
    }
}
