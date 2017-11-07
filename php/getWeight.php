<?php
    session_start();
    require_once('/class/Autoloader.php');
    Autoloader::Register();

    if(Toolbox::IsConnected()) {
        $db = new Database();
        $res = $db->Execute('SELECT weights.weight, weights.day FROM weights WHERE weights.id_users = ?', array(Toolbox::GetUser()->ID));
        echo json_encode($res->fetchAll(PDO::FETCH_OBJ));
    } else {
        echo null;
    }
    