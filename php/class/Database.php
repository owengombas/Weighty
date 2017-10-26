<?php
    class Database {
        public function __construct() {
            $this->Connection = self::GetConnection();
        }

        // Execute a SQL Request to the server
        public function Execute($req, $values = null) {
            $res = $this->Connection->prepare($req);
            $res->execute($values);
            return $res;
        }
        
        // Informations for the connection to MySQL server
        public static function GetConnection() {
            return new PDO('mysql:host=localhost;dbname=weighty', 'root', '');
        }
    }