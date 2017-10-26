<?php
    session_start();
    require 'php/class/Autoloader.php';
    Autoloader::Register();
    
    if(!Toolbox::IsConnected()) {
        if(isset($_POST['submit'])) {
            $message = new Message();
            if(Toolbox::IsArrayNotEmpty($_POST)) {
                $password = $_POST['password'];
                $confirm = $_POST['confirm'];
                if($password == $confirm) {
                    $username = strip_tags($_POST['username']);
                    $email = strip_tags($_POST['email']);
                    $password = password_hash($password, PASSWORD_BCRYPT);
                    $db = new Database();
                    $res = $db->Execute('SELECT * FROM users WHERE username=? OR email=?', array($username, $email));
                    if($res->rowCount() <= 0) {
                        $db->Execute('INSERT INTO users (username, email, password) VALUES (?, ?, ?)', array($username, $email, $password));
                        $_SESSION['user'] = serialize(new User($username, $email));
                        Toolbox::RedirectToHome();
                    } else {
                        $message->SetError('This username or e-mail exist, please choose another one');
                    }
                } else {
                    $message->SetError('Password doesn\'t match');
                }
            } else {
                $message->SetError('Please enter values');
            }
        }
    } else {
        Toolbox::RedirectToHome();
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
                    <label for="inputSUUsername">Username</label>
                    <input type="text" class="form-control" placeholder="Username" name="username" value="<?php if(isset($_POST['username'])) echo $_POST['username']; ?>">
                </div>

                <div class="form-group">
                    <label for="inputSUEmail">E-mail</label>
                    <input type="email" id="inputSUEmail" class="form-control" placeholder="E-mail" name="email" value="<?php if(isset($_POST['email'])) echo $_POST['email']; ?>">
                </div>

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="inputSUPassword">Password</label>
                        <input type="password" class="form-control" placeholder="Password" name="password">
                    </div>

                    <div class="form-group col-md-6">
                        <label for="inputConfirm">Confirm</label>
                        <input type="password" id="inputSUConfirm" class="form-control" placeholder="Confirm" name="confirm">
                    </div>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-secondary form-group-center" name="submit">Sign up</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
    require_once('php/inc/footer.inc.php')
?>
