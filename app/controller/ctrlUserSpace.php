<?php
require_once(BASE_DIR . 'app/model/mdlUserSpace.php');

/**
 * Controlador de la clase mdlUserSpace
 */
class ctrlUserSpace
{
    private $mdlUserSpace;
    public function __construct($pdo)
    {
        $this->mdlUserSpace = new mdlUserSpace($pdo);
    }
}
