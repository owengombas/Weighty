<?php
    defined('APPLICATION') OR exit('Accès interdit');
    require_once('php/class/Autoloader.php');
    Autoloader::Register();
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Weighty</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css" integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb" crossorigin="anonymous">
        <link rel="stylesheet" href="css/style.css">
        <script src="js/lib/jquery-3.2.1.min.js"></script>
        <script src="js/lib/moment.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js" integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh" crossorigin="anonymous"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js" integrity="sha384-alpBpkh1PFOepccYVYDB4do5UnbKysX5WZXm3XxPqe5iKTfUKjNkCk9SaVuEZflJ" crossorigin="anonymous"></script>
        <script src="js/request.js"></script>
    </head>

    <body>
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <a class="navbar-brand" href="index.php">Weighty</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse justify-content-end" id="navbarSupportedContent">
                <ul class="nav justify-content-end navbar-nav">

<?php
    if(Toolbox::IsConnected()) {
        echo '<li class="nav-item"><span class="text-dark username btn btn-light">', unserialize($_SESSION['user'])->Username, '</span></li>';
    }
    
    $conf = array(
        new File('index.php', 'Home', true, false),
        new File('edit.php', 'Settings', true, false),
        new File('admin.php', 'Admin', true, false, 1),
        new File('sign_out.php', 'Sign out', true, false),
        new File('sign_in.php', 'Sign in', false, true),
        new File('sign_up.php', 'Sign up', false, true),
    );

    foreach($conf as $i) {
        if(Toolbox::IsConnected() == $i->displayLogged && $i->fileName != pathinfo($_SERVER["PHP_SELF"])['basename'] && Toolbox::GetAdmin() >= $i->admin) {
            echo 
            '<li class="nav-item">
                <a class="btn btn-secondary" href="', $i->fileName, '">', $i->displayName, '</a>
            </li>';
        }
    }
?>
                </ul>
            </div>
        </nav>