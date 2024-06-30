<?php

/**
 * Controlador de la aplicación
 */
class vinylCtrl
{
    // Constructor
    function __construct()
    {
    }

    // Metodo vista login
    public function login() {
        require_once(BASE_DIR . "app/view/login.php");
        exit();
    }

    // Metodo vista createAcount
    public function createAcount() {
        require_once(BASE_DIR . "app/view/createacount.php");
    }

    // Metodo vista loginSuccess
    public function loginSuccess() {
        require_once(BASE_DIR . "app/view/loginsuccess.php");
        exit();
    }

    // Metodo vista userSpace
    public function userSpace() {
        require_once(BASE_DIR . "app/view/userspace.php");
        exit();
    }

    // Metodo vista addNew
    public function addNew()
    {
        require_once(BASE_DIR . "app/view/addnew.php");
        exit();
    }

    // Metodo vista manageUsers
    public function manageUsers()
    {
        require_once(BASE_DIR . "app/view/manageusers.php");        
        exit();
    }

    // Metodo vista edit registro
    public function edit()
    {
        require_once(BASE_DIR . "app/view/edit.php");
        exit();
    }

    // Metodo vista logout
    public function logout()
    {
        echo "metodo logout";
        require_once(BASE_DIR . "app/view/logout.php");
        exit();
    }

    // Metodo vista listItems
    public function listItems()
    {       
        require_once(BASE_DIR . "app/view/listitems.php");
        exit();
    }

    // Metodo vista delete
    public function delete()
    {       
        require_once(BASE_DIR . "app/view/delete.php");
        exit();
    }

    // Metodo vista favorites
    public function favorites()
    {       
        require_once(BASE_DIR . "app/view/favorites.php");
        exit();
    }

    // Metodo vista desired
    public function desired()
    {       
        require_once(BASE_DIR . "app/view/desired.php");
        exit();
    }    

    // Metodo vista modifyuser
    public function modifyuser()
    {       
        require_once(BASE_DIR . "app/view/modifyuser.php");
        exit();
    }

    public function audiodb()
    {       
        require_once(BASE_DIR . "app/view/audiodb.php");
        exit();
    }

    /*public function consulta()
    {       
        require_once(BASE_DIR . "app/view/consulta.php");
        exit();
    }*/
}
