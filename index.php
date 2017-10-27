<?php
    session_start();
    require_once('php/class/Autoloader.php');
    Autoloader::Register();

    if(isset($_POST['submit'])) {
        $message = new Message();
        if(Toolbox::IsConnected()) {
            $value = $_POST['value'];
            if(is_numeric($value)) {
                $db = new Database();
                $db->Execute('INSERT INTO weights (id_users, weight) VALUES (?, ?)', array(unserialize($_SESSION['user'])->ID, $value));
            } else {
                $message->SetError('Enter a numeric value');
            }
        } else {
            Toolbox::Redirect('sign_in.php');
        }
    }

    if(isset($message)) {
        $message->Show();
    }
    require_once('php/inc/header.inc.php');
?>

<div class="container-fluid weighty-form">
    <div class="row justify-content-md-center">
        <div class="col-md-4">
            <form action="" method="POST">
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
</div>

<?php
    require_once('php/inc/end.inc.php');
?>