<?php
    define('APPLICATION', true);
    session_start();
    session_destroy();
    
    require_once('php/class/Autoloader.php');
    Autoloader::Register();

    Toolbox::Redirect('sign_up.php');