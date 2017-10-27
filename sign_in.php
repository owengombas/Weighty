<?php
    session_start();
    require_once('php/class/Autoloader.php');
    Autoloader::Register();

    if(!Toolbox::IsConnected()) {
        if(isset($_POST['submit'])) {
            $message = new Message();
            if(Toolbox::IsArrayNotEmpty($_POST)) {
                $username = strip_tags($_POST['username']);
                $password = $_POST['password'];
                $db = new Database();
                $res = $db->Execute('SELECT * FROM users WHERE LOWER(username)=LOWER(?)', array($username));
                if($res->rowCount() == 1) {
                    $res = $res->fetch();
                    if(password_verify($password, $res['password'])) {
                        User::SignIn($res['id'], $res['username'], $res['email'], $res['admin']);
                        Toolbox::RedirectToHome();
                    } else {
                        $message->SetError('Wrong username or password');
                    }
                } else {
                    $message->SetError('Wrong username or password');
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
                    <label for="inputSIUsername">Username</label>
                    <input type="text" id="inputSIEU" class="form-control" placeholder="Username" name="username" value="<?php if(isset($_POST['username'])) echo $_POST['username']; ?>">
                </div>

                <div class="form-group">
                    <label for="inputSIPassword">Password</label>
                    <input type="password" id="inputSIPassword" class="form-control" placeholder="Password" name="password">
                </div>

                <div class="form-group">
                    <label class="custom-control custom-checkbox mb-2 mr-sm-2 mb-sm-0">
                        <input type="checkbox" class="custom-control-input" name="remember">
                        <span class="custom-control-indicator"></span>
                        <span class="custom-control-description">Remember me</span>
                    </label>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-secondary form-group-center" name="submit">Sign in</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
    require_once('php/inc/end.inc.php');
?>
