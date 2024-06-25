<?php

class Condition
{
    public $Id; // ID de la condición del vinilo, único
    public $Condition_Name; // Nombre de la condición, por ejemplo, "Nuevo", "Usado"

    public function __construct($Id = null, $Condition_Name = null)
    {
        $this->Id = $Id;
        $this->Condition_Name = $Condition_Name;
    }
}
