<?php

class RecordLabel {
    public $Id; // ID del sello discográfico, único
    public $Record_Label_Name; // Nombre del sello discográfico

    public function __construct($Id = null, $Record_Label_Name = null) {
        $this->Id = $Id;
        $this->Record_Label_Name = $Record_Label_Name;
    }
}
