<?php
    define('APPLICATION', true);
    session_start();
    require_once('class/Autoloader.php');
    Autoloader::Register();

    if(Toolbox::IsConnected()) {
        $db = new Database();
        $res = $db->Execute('SELECT weights.weight, weights.day FROM weights WHERE weights.id_users = ? ORDER BY weights.day', array(Toolbox::GetUser()->ID));
        $res = $res->fetchAll(PDO::FETCH_OBJ);

        $return = array();
        $index = new stdClass();
        $index->index = 0;
        $index->day = null;
        foreach($res as $key => $value) {
            $day = date('Ym', strtotime($value->day));
            if($day == $index->day) {
                array_push($return[$index->index], $value);
            } else {
                if($key >= 1) {
                    $index->index++;
                }
                $return[$index->index] = array();
                array_push($return[$index->index], $value);
            }
            $index->day = $day;
        }

        echo json_encode($return);
    } else {
        echo null;
    }
    