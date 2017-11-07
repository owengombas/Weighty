<?php
    session_start();
    require_once('php/class/Autoloader.php');
    Autoloader::Register();

    if(!Toolbox::IsConnected()) {
        Toolbox::Redirect('sign_up.php');
    }

    $message = new Message();
    $db = new Database();
    $res = $db->Execute('SELECT users.password, weights.weight FROM users LEFT OUTER JOIN weights ON weights.id_users = :id AND weights.day = CURDATE() WHERE users.id = :id', array(':id' => Toolbox::GetUser()->ID));
    $res = $res->fetch(PDO::FETCH_OBJ);

    if(isset($_POST['changeWeight'])) {
        if($res) {
            $message = $db->InsertUpdateWeight($_POST['valueWeight'], true);
        } else {
            $message->SetError('Enter a value for today before');
        }
    } 

    if(isset($_POST['changePassword'])) {
        if(password_verify($_POST['oldPassword'], $res->password)) {
            if(!empty($_POST['newPassword']) && !empty($_POST['confirmPassword'])) {
                if($_POST['newPassword'] == $_POST['confirmPassword']) {
                    $db->Execute('UPDATE users SET password = ? WHERE id = ?', array(password_hash($_POST['newPassword'], PASSWORD_BCRYPT), Toolbox::GetUser()->ID));
                } else {
                    $message->SetError('Password doesn\'t match');
                }
            } else {
                $message->SetError('Enter values');
            }
        } else {
            $message->SetError('Your old password doesn\'t match');
        }
    }

    require_once('php/inc/header.inc.php');
    
    if(isset($message)) {
        $message->Show();
    }
?>
    <h1 class="text-center">Setting</h1>

    <div class="container-fluid weighty-form">
        <div class="row justify-content-md-center">
            <div class="col-md-4">
                <form action="<?= $_SERVER['PHP_SELF'] ?>" method="POST">
                    <h3>Change your weight of today (kg)</h3>
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="Enter your weight" name="valueWeight" value="<?= $res ? $res->weight : "" ?>">
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary form-group-center" name="changeWeight">Change</button>
                    </div>
                </form>

                <form action="<?= $_SERVER['PHP_SELF'] ?>" method="POST">
                    <h3>Change your password</h3>
                    <div class="form-group">
                        <input type="password" class="form-control" placeholder="Old password" name="oldPassword">
                    </div>

                    <div class="form-group">
                        <input type="password" class="form-control" placeholder="New password" name="newPassword">
                    </div>
                    
                    <div class="form-group">
                        <input type="password" class="form-control" placeholder="Confirm" name="confirmPassword">
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary form-group-center" name="changePassword">Change</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

<?php
    require_once('php/inc/end.inc.php');
?>