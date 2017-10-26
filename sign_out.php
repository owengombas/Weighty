<?php
    session_start();
    session_destroy();
    
    require 'php/class/Autoloader.php';
    Autoloader::Register();

    Toolbox::RedirectToHome();