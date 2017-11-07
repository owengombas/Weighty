<?php
    session_start();
    require_once('php/class/Autoloader.php');
    Autoloader::Register();

    if(Toolbox::IsConnected()) {
        $db = new Database();    
        $message = new Message();
        $res = $db->Execute('SELECT COUNT(*) AS count FROM weights WHERE day = CURDATE() AND id_users = ?', array(Toolbox::GetUser()->ID));
        $count = $res->fetch(PDO::FETCH_OBJ)->count;

        if(isset($_POST['submit'])) {
            $value = $_POST['value'];
            if(is_numeric($value)) {
                $db->Execute('INSERT INTO weights (id_users, weight, day) VALUES (?, ?, CURDATE())', array(Toolbox::GetUser()->ID, $value));
                $message->SetSuccess('You saved your weight for today');
                Toolbox::Refresh();
            } else {
                $message->SetError('Enter a numeric value');
            }
        }
    }

    if(isset($message)) {
        $message->Show();
    }
    require_once('php/inc/header.inc.php');
   
    
    if(isset($count) && Toolbox::IsConnected() && $count < 1) {
        echo
        '<div class="container-fluid weighty-form">
            <div class="row justify-content-md-center">
                <div class="col-md-4">
                    <form action="', $_SERVER['PHP_SELF'], '" method="POST">
                        <div class="form-group">
                            <label for="inputWeight">Enter your weight</label>
                            <input type="text" id="inputWeight" class="form-control" placeholder="Enter your weight" name="value">
                        </div>
        
                        <div class="form-group">
                            <button type="submit" class="btn btn-secondary form-group-center" name="submit">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>';
    } else if(isset($count) && $count >= 1) {
        require_once('view/chartWeight.html');
    } else if(!Toolbox::IsConnected()) {
        Toolbox::Redirect('sign_up.php');
    }

    require_once('php/inc/end.inc.php');
?>