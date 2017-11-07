<?php
    session_start();
    require_once('php/class/Autoloader.php');
    Autoloader::Register();

    if(!Toolbox::IsConnected()) {
        Toolbox::Redirect('sign_up.php');
    }

    $db = new Database();
    $res = $db->Execute('SELECT weight FROM weights WHERE day = CURDATE() AND id_users = ?', array(Toolbox::GetUser()->ID));
    $res = $res->fetch(PDO::FETCH_OBJ);

    $message = new Message();

    if(isset($_POST['changeWeight'])) {
        if(isset($_POST['valueWeight'])) {
            if(is_numeric($_POST['valueWeight'])) {
                $db->Execute('UPDATE weights SET weight = ? WHERE day = CURDATE() AND id_users = ?', array((int)$_POST['valueWeight'], Toolbox::GetUser()->ID));
                Toolbox::RedirectToCurrentPage();
            } else {    
                $message->SetError('Enter a numeric value');
            }
        } else {
            $message->SetError('Enter a value');
        }
    } 

    require_once('php/inc/header.inc.php');
   
    echo 
    '<h1 class="text-center">Setting</h1>
    <div class="container-fluid weighty-form">
        <div class="row justify-content-md-center">
            <div class="col-md-4">
                <form action="', $_SERVER['PHP_SELF'], '" method="POST">
                    <h3>Change your weight of today</h3>
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="Enter your weight" name="valueWeight" value="', $res ? $res->weight : "" ,'">
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary form-group-center" name="changeWeight">Change</button>
                    </div>
                </form>

                <form action="', $_SERVER['PHP_SELF'], '" method="POST">
                    <h3>Change your password</h3>
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="Old password" name="oldPassword">
                    </div>

                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="New password" name="newPassword">
                    </div>
                    
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="Confirm" name="confirmPassword">
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary form-group-center" name="changePassword">Change</button>
                    </div>
                </form>
            </div>
        </div>
    </div>';

    require_once('php/inc/end.inc.php');
?>