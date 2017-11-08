<?php
    session_start();
    require_once('php/class/Autoloader.php');
    Autoloader::Register();
    
    if(!Toolbox::IsConnected()) {
        if(isset($_POST['submit'])) {
            $message = new Message();
            if(Toolbox::ArrayHasValue($_POST, ['username', 'email', 'password', 'confirm'])) {
                $password = $_POST['password'];
                $confirm = $_POST['confirm'];
                if($password == $confirm) {
                    if(strlen($password) >= 4) {
                        $username = strip_tags($_POST['username']);
                        $email = strip_tags($_POST['email']);
                        $password = password_hash($password, PASSWORD_BCRYPT);
                        $db = new Database();
                        $res = $db->Execute('SELECT * FROM users WHERE username=? OR email=?', array($username, $email));
                        if($res->rowCount() <= 0) {
                            $db->Execute('INSERT INTO users (username, email, password) VALUES (?, ?, ?)', array($username, $email, $password));
                            Toolbox::Redirect('sign_in.php');
                        } else {
                            $message->SetError('This username or e-mail exists, choose another one');
                        }
                    } else {
                        $message->SetError('Your password is too short');
                    }
                } else {
                    $message->SetError('Passwords do not match');
                }
            } else {
                $message->SetError('Fill all fields');
            }
        }
    } else {
        Toolbox::RedirectToHome();
    }
    
    require_once('php/inc/header.inc.php'); 
    
    if(isset($message)) {
        $message->Show();
    }
?>

<div class="container-fluid weighty-form">
    <div class="row justify-content-md-center">
        <div class="col-md-6">
            <h1 class="text-center">Sign up</h1>
            <form action="" method="POST">
                <div class="form-group">
                    <input type="text" class="form-control" placeholder="Username" name="username" value="<?php if(isset($_POST['username'])) echo $_POST['username']; ?>">
                </div>

                <div class="form-group">
                    <input type="email" id="inputSUEmail" class="form-control" placeholder="E-mail" name="email" value="<?php if(isset($_POST['email'])) echo $_POST['email']; ?>">
                </div>

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <input type="password" class="form-control" placeholder="Password" name="password">
                    </div>

                    <div class="form-group col-md-6">
                        <input type="password" id="inputSUConfirm" class="form-control" placeholder="Confirm" name="confirm">
                    </div>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-secondary form-group-center" name="submit">Sign up</button>
                </div>
            </form>
            <div class="text-center">
                <a href="sign_in.php">Already registered ?</a>
            </div>
        </div>
    </div>
</div>

<?php
    require_once('php/inc/end.inc.php');
?>
