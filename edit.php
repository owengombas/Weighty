<?php
    define('APPLICATION', true);
    session_start();
    require_once('php/class/Autoloader.php');
    Autoloader::Register();

    if(Toolbox::IsConnected()) {
        // Get the success message by $_GET
        $message = new Message();
        if(isset($_GET['message'])) {
            $message->SetSuccess($_GET['message']);
        }

        $db = new Database();
        $res = $db->Execute('SELECT users.password, weights.weight FROM users LEFT OUTER JOIN weights ON weights.id_users = :id AND weights.day = CURDATE() WHERE users.id = :id', array(':id' => Toolbox::GetUser()->ID));
        $res = $res->fetch(PDO::FETCH_OBJ);

        if(isset($_POST['changeWeight'])) {
            $message = $db->InsertUpdateWeight($_POST['valueWeight'], isset($res->weight));
            if($message->Status >= 1){
                Toolbox::Redirect('edit.php', array('message' => $message->Message));
            }
        } 

        // Change password tests
        if(isset($_POST['changePassword'])) {
            if(Toolbox::ArrayHasValue($_POST, ['oldPassword', 'newPassword', 'confirmPassword'])) {
                if(password_verify($_POST['oldPassword'], $res->password)) {
                    if($_POST['newPassword'] == $_POST['confirmPassword']) {
                        if(strlen($_POST['newPassword']) >= 4) {
                            $db->Execute('UPDATE users SET password = ? WHERE id = ?', array(password_hash($_POST['newPassword'], PASSWORD_BCRYPT), Toolbox::GetUser()->ID));
                            Toolbox::Redirect('edit.php', array('message' => 'Your passdword has been changed'));
                        } else {
                            $message->SetError('Your password is too short');
                        }
                    } else {
                        $message->SetError('Passwords do not match');
                    }
                } else {
                    $message->SetError('Your old password doesn\'t match');
                }
            } else {
                $message->SetError('Fill all fields');
            }
        }

        // Delete weight of today
        if(isset($_POST['deleteWeight'])) {
            $db->Execute('DELETE FROM weights WHERE id_users = ? AND day = CURDATE()', array(Toolbox::GetUser()->ID));
            Toolbox::Redirect('edit.php', array('message' => 'Your weight has been deleted'));
        }

        require_once('php/inc/header.inc.php');
        
        if(isset($message)) {
            $message->Show();
        }
?>
    <h1 class="text-center">Setting</h1>

    <div class="container-fluid weighty-form">
        <div class="row justify-content-md-center">
            <div class="col-md-6">
                <form action="<?= $_SERVER['PHP_SELF'] ?>" method="POST">
                    <h3>Change your weight of today (kg)</h3>
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="Enter your weight" name="valueWeight" value="<?= $res ? $res->weight : "" ?>">
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-2"></div>
                            <div class="col-md-4">
                                <button type="submit" class="btn btn-primary btn-block" name="changeWeight">Change</button>
                            </div>
                            <div class="col-md-4">
                                <button type="button" class="btn btn-secondary btn-block" data-toggle="modal" data-target="#deleteModal">Delete</button>
                            </div>
                            <div class="col-md-2"></div>
                        </div>
                    </div>

                    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="deleteModalLabel">Delete</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                You wan't to delete your weight of today ?
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
                                    <form action="<?= $_SERVER['PHP_SELF'] ?>" method="POST" class="no-margin-top">
                                        <button type="submit" class="btn btn-danger" name="deleteWeight">Yes, delete</button>
                                    </form>
                                </div>
                            </div>
                        </div>
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
    } else {
        Toolbox::Redirect('sign_up.php');
    }
?>