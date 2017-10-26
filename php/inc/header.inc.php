<?php
    require_once('php/class/Autoloader.php');
    Autoloader::Register();

    echo
    '<!DOCTYPE html>
    <html lang="en">
    
    <head>
        <title>Weighty</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css" integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb"
            crossorigin="anonymous">
        <link rel="stylesheet" href="css/style.css">
    </head>
    
    <body>
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <a class="navbar-brand" href="index.php">Weighty</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse justify-content-end" id="navbarSupportedContent">
                <ul class="nav justify-content-end navbar-nav">';
                
    if(Toolbox::IsConnected()) {
        echo '<li class="nav-item btn btn-light"><span class="username">', unserialize($_SESSION['user'])->Username, '</span></li>';
    }
                
    // Show all the links to naviguate on the website
    // The current page is not displayed in the menu
    // The diplayed name is based on the filename: a_b_c.php = A b c (No extension, no "_", Uppercase first letter)
    // Exeception for index.php file, it will appear like that: Home
    foreach(scandir('.') as $i) {
        if(pathinfo($i, PATHINFO_EXTENSION) == 'php') {
            if(is_file($i) && $i != pathinfo($_SERVER["PHP_SELF"])['basename']) {
                if(($i == 'sign_out.php' && Toolbox::IsConnected()) || (!Toolbox::IsConnected() && $i != 'sign_out.php')) {
                    echo 
                    '<li class="nav-item">
                        <a class="btn btn-primary" href="', $i, '">';

                    if($i == 'index.php') {
                        $i = 'home';
                    }
                    
                    echo
                        ucfirst(str_replace('_', ' ', basename($i, '.php'))), '</a>
                    </li>';
                }
            }
        }
    }

    echo
                '</ul>
            </div>
        </nav>';