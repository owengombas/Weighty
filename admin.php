<?php
    define('APPLICATION', true);
    session_start();
    require_once('php/class/Autoloader.php');
    Autoloader::Register();

    if(Toolbox::GetAdmin() >= 1) {
        $db = new Database();
        $message = new Message();
        if(isset($_POST['update'])) {
            if(Toolbox::ArrayHasValue($_POST, ['username', 'email'])) {
                if($_GET['id'] == Toolbox::GetUser()->ID) {
                    $_POST['admin'] = Toolbox::GetAdmin();
                }
                $res = $db->Execute('UPDATE users SET username = ?, email = ?, admin = ? WHERE id = ?', array($_POST['username'], $_POST['email'], $_POST['admin'], $_GET['id']));
                if(isset($_GET['page']) && isset($_GET['id'])) {
                    Toolbox::Redirect('admin.php', array('page' => $_GET['page'], 'id' => $_GET['id']));
                }
            } else {
                $message->SetError('Values can\'t be empty');
            }
        }

        if(isset($_POST['delete'])) {
            $res = $db->Execute('DELETE FROM users WHERE id = ?', array($_POST['delete']));
            if(isset($_GET['page'])) {
                Toolbox::Redirect('admin.php', array('page' => $_GET['page']));
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
            <?php
                if(isset($_GET['id']) && !isset($_POST['delete'])) {
                    $res = $db->Execute('SELECT * FROM users WHERE id = ?', array($_GET['id']));
                    $res = $res->fetch(PDO::FETCH_OBJ);
                    if($res) {
                        echo '<h1 class="text-center">', $res->username, '</h1>'
            ?>
                <form action="" method="POST">
                    <div class="form-group">
                        <label for="inputUsername">Username</label>
                        <input type="text" id="inputUsername" class="form-control" placeholder="Username" name="username" value="<?= $res->username ?>">
                    </div>

                    <div class="form-group">
                        <label for="inputEmail">E-mail</label>
                        <input type="email" id="inputEmail" class="form-control" placeholder="E-mail" name="email" value="<?= $res->email ?>">
                    </div>
                    
                    <?php if($res->id != Toolbox::GetUser()->ID) {?>
                        <div class="form-check">
                            <label class="form-check-label">
                                <input class="form-check-input" type="radio" name="admin" id="radioAdmin" value="1" <?= boolval($res->admin) ? 'checked' : '' ?>>
                                Admin
                            </label>
                        </div>
                        <div class="form-check">
                            <label class="form-check-label">
                                <input class="form-check-input" type="radio" name="admin" id="radioUser" value="0" <?= boolval($res->admin) ? '' : 'checked' ?>>
                                User
                            </label>
                        </div>
                    <?php } ?>

                    <div class="form-group">
                        <button type="submit" class="btn btn-secondary form-group-center" name="update">Change</button>
                    </div>

                    <?php if($res->id != Toolbox::GetUser()->ID) {?>
                        <div class="form-group">
                            <button type="button" class="btn btn-danger form-group-center" data-toggle="modal" data-target="#deleteModal">Delete</button>
                        </div>
                    <?php } ?>
                    
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
                                You wan't to delete <strong><?= $res->username ?></strong> ?
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
                                    <form action="<?= $_SERVER['PHP_SELF'] ?>" method="POST" class="no-margin-top">
                                        <button type="submit" class="btn btn-danger" name="delete" value="<?= $res->id ?>">Yes, delete</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <a href="admin.php?page=<?= $_GET['page'] ?>" class="text-center col-md-12 form-group-center">Back</a>
                    </div>
                </form>
            <?php
                    } else {
                        Toolbox::Redirect('admin.php');
                    } 
                } else {
                    $limit = 15;
                    $page = isset($_GET['page']) ? $_GET['page'] : 1;
                    $res = $db->Execute('SELECT COUNT(*) as count FROM users');
                    $count = $res->fetch(PDO::FETCH_OBJ)->count;  
                    $total = ceil($count / $limit);
                    
                    if($page > $total){
                        Toolbox::Redirect('admin.php?page='.$total);
                    } else if($page < 1) {
                        Toolbox::Redirect('admin.php');
                    }

                    if(isset($_POST['search']) && !empty($_POST['valueSearch'])) {
                        $res = $db->Execute('SELECT * FROM users WHERE username LIKE "%'.$_POST['valueSearch'].'%" OR email LIKE "%'.$_POST['valueSearch'].'%" ORDER BY username');
                        $count = $res->rowCount();
                    } else {
                        $res = $db->Execute('SELECT * FROM users ORDER BY username LIMIT '.$limit.' OFFSET '.($page - 1) * $limit);
                    }
                ?>    

                    <h1 class="text-center"><?= $count > 0 ? $count.' user'.($count > 1 ? 's' : '') : 'No results' ?></h1>
                    <form action="<?= $_SERVER['PHP_SELF'] ?>" method="POST">
                        <div class="row">
                            <div class="col-md-8">
                                <input type="text" name="valueSearch" class="form-control" placeholder="Search by username" value="<?= isset($_POST['valueSearch']) ? $_POST['valueSearch'] : '' ?>">
                            </div>
                            <div class="col-md-4">
                                <button type="submit" class="no-margin-top btn btn-primary btn-block" name="search">Search</button>
                            </div>
                        </div>
                <?php
                    if(isset($_POST['search'])) {
                        echo
                        '<div class="row">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-secondary btn-block margin-top-25" name="all">Show all users</button>
                            </div>
                        </div>';
                    }
                ?>
                    </form>
                    <div class="list-group margin-top-75">

                <?php
                    while($i = $res->fetch(PDO::FETCH_OBJ)) {
                        echo 
                        '<a href="admin.php?page=', $page,'&id=', $i->id,'" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <div ', $i->id == Toolbox::GetUser()->ID ? 'class="bold"' : '','>
                                <div>', $i->username,'</div>
                                <div class="text-primary">', $i->email,'</div>
                            </div>',
                            boolval($i->admin) ? '<span class="badge badge-primary badge-pill">Admin</span>' : '',
                        '</a>';
                    }
                    echo '</div>';
                    if(!isset($_POST['search']) || empty($_POST['valueSearch'])) {
                ?>
                        <div class="row margin-top-25">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-2">
                                    </div>
                                    <div class="col-md-4">
                                        <a href="admin.php?page=<?= $page - 1 < 1 ? $page : $page - 1 ?>" class="btn btn-secondary btn-block <?= $page - 1 < 1 ? 'disabled' : '' ?>">Previous</a>
                                    </div>
                                    <div class="col-md-4">
                                        <a href="admin.php?page=<?= $page + 1 > $total ? $page : $page + 1 ?>" class="btn btn-secondary btn-block <?= $page + 1 > $total ? 'disabled' : '' ?>">Next</a>
                                    </div>
                                    <div class="col-md-2">
                                    </div>
                                </div>
                            </div>
                        </div>
                <?php
                    }
                }
            ?>
        </div>
    </div>
</div>

<?php
    require_once('php/inc/end.inc.php');
?>
