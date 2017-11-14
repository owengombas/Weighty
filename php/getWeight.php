<?php
    define('APPLICATION', true);
    session_start();
    require_once('class/Autoloader.php');
    Autoloader::Register();

    if(Toolbox::IsConnected()) {
        $db = new Database();
        $res = $db->Execute('SELECT weights.weight, weights.day FROM weights WHERE weights.id_users = ? ORDER BY weights.day', array(Toolbox::GetUser()->ID));
        $res = $res->fetchAll(PDO::FETCH_OBJ);

        if(isset($_POST['unit'])) {
            $unit = $_POST['unit'];
        } else {
            $unit = 'month';
        }

        $unit = $unit == 'month' || $unit == 'week' ? $unit : 'month';

        // Sort the array by week or month
        $return = array();
        $index = new stdClass();
        $index->index = 0;
        $index->day = null;
        foreach($res as $key => $value) {
            switch($unit) {
                case 'month':
                    $day = date('Ym', strtotime($value->day));
                    break;
                case 'week':
                    $day = date('YW', strtotime($value->day));
                    break;
            }
            
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
    