<?php
    class Toolbox {
        // Check if array hasn't a empty value
        public static function ArrayHasValue($array, $keys) {
            foreach($keys as $key) {
                if(empty($array[$key])) {
                    return false;  
                }
            }
            return true;
        }

        public static function RedirectToHome() {
            header('Location: index.php');
        }

        public static function Redirect($url) {
            header('Location: '.$url);
        }

        // Redirect to the back page
        public static function RedirectToCurrentPage() {
            if(isset($_SERVER['HTTP_REFERER'])){
                header('location: '.$_SERVER['HTTP_REFERER']);
            } else {
                self::RedirectToHome();
            }
        }

        public static function IsConnected() {
            return isset($_SESSION['user']);
        }
        
        public static function GetAdmin() {
            return self::IsConnected() ? self::GetUser()->Admin : 0;
        } 

        public static function IsAdmin() {
            return boolval(self::GetAdmin());
        }

        public static function GetUser() {
            return unserialize($_SESSION['user']);
        }

        public static function Refresh() {
            header('Refresh: 0');
        }
    }