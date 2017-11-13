<?php
    define('APPLICATION', true);
    session_start();
    require_once('php/class/Autoloader.php');
    Autoloader::Register();

    if(Toolbox::IsConnected()) {
        $db = new Database();    
        $message = new Message();
        $valueEntered = false;

        // If he hasn't enter a weight today == 0 else == 1
        $res = $db->Execute('SELECT COUNT(*) AS count FROM weights WHERE day = CURDATE() AND id_users = ?', array(Toolbox::GetUser()->ID));
        $count = $res->fetch(PDO::FETCH_OBJ)->count;

        if(isset($_POST['submit'])) {
            $message = $db->InsertUpdateWeight($_POST['valueWeight'], false);
            if($message->Status >= 1){
                Toolbox::RedirectToCurrentPage();
            }
        }
    } else {
        Toolbox::Redirect('sign_up.php');
    }

    require_once('php/inc/header.inc.php');
   
    if(isset($message)) {
        $message->Show();
    }

    echo $valueEntered;
    if(isset($count) && $count < 1) {
?>
        <div class="container-fluid weighty-form">
            <div class="row justify-content-md-center">
                <div class="col-md-6">
                    <form action="<?= $_SERVER['PHP_SELF'] ?>" method="POST">
                        <h3>Enter a weight for today (kg)</h3>
                        <div class="form-group">
                            <input type="text" id="inputWeight" class="form-control" placeholder="Weight" name="valueWeight">
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary form-group-center" name="submit">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
<?php
    } else {
        echo '<h1 class="text-center">You have enter a weight today</h1>';
    }
    require_once('view/chartWeight.html');

    require_once('php/inc/end.inc.php');
?>