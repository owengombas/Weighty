<?php
    define('APPLICATION', true);
    session_start();
    require_once('php/class/Autoloader.php');
    Autoloader::Register();

    if(!Toolbox::IsConnected()) {
        // Sign in tests
        if(isset($_POST['submit'])) {
            $message = new Message();
            if(Toolbox::ArrayHasValue($_POST, ['username', 'password'])) {
                $username = strip_tags($_POST['username']);
                $password = $_POST['password'];
                $db = new Database();
                $res = $db->Execute('SELECT * FROM users WHERE LOWER(username)=LOWER(?)', array($username));
                if($res->rowCount() == 1) {
                    $res = $res->fetch(PDO::FETCH_OBJ);
                    if(password_verify($password, $res->password)) {
                        var_dump($res);
                        User::SignIn($res->id, $res->username, $res->email, (int)$res->admin);
                        Toolbox::Redirect('index.php');
                    } else {
                        $message->SetError('Wrong username or password');
                    }
                } else {
                    $message->SetError('Wrong username or password');
                }
            } else {
                $message->SetError('Fill all fields');
            }  
        }

        require_once('php/inc/header.inc.php'); 
        
        if(isset($message)) {
            $message->Show();
        }
?>

<div class="container-fluid weighty-form">
    <div class="row justify-content-md-center">
        <div class="col-md-3">
            <h1 class="text-center">Sign in</h1>
            <form action="" method="POST">
                <div class="form-group">
                    <input type="text" id="inputSIEU" class="form-control" placeholder="Username" maxlength="25" name="username" value="<?php if(isset($_POST['username'])) echo $_POST['username']; ?>">
                </div>

                <div class="form-group">
                    <input type="password" id="inputSIPassword" class="form-control" placeholder="Password" name="password">
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary form-group-center" name="submit">Sign in</button>
                </div>
            </form>
            <div class="text-center">
                <a href="sign_up.php">You don't have an account ?</a>
            </div>
        </div>
    </div>
</div>

<?php
        require_once('php/inc/end.inc.php');
    } else {
        Toolbox::RedirectToHome();
    }
?>
