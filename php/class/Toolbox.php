<?php
    class Toolbox {
        // Check if array hasn't a empty value
        public static function IsArrayNotEmpty($array) {
            foreach($array as $i){
                if(empty($i)){
                    return true;
                }
            }
            return false;
        }

        public static function RedirectToHome() {
            header('Location: index.php');
        }

        public function Redirect($url) {
            header('Location: '.$url);
        }

        // Redirect to the back page
        public static function RedirectToCurrentPage() {
            if(isset($_SERVER['HTTP_REFERER'])){
                header('location: '.$_SERVER['HTTP_REFERER']);
            } else {
                $this->RedirectToHome();
            }
        }

        public static function IsConnected() {
            return isset($_SESSION['user']);
        }

        public static function GetUser() {
            return unserialize($_SESSION['user']);
        }

        public static function Refresh() {
            header('Location: '.$_SERVER['PHP_SELF']);
        }
    }