<?php
    defined('APPLICATION') OR exit('Accès interdit');
    // Auto load class when you call it in the project
    class Autoloader {
        public static function Autoload($className) {
            $fileName = $className.'.php';
            require($fileName);
        }

        public static function Register() {
            spl_autoload_register(array(__CLASS__, 'Autoload'));
        }
    }