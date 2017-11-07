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
            $conn = new PDO('mysql:host=localhost;dbname=weighty', 'root', '');
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $conn;
        }

        public function InsertUpdateWeight($value, $update) {
            $message = new Message();

            if(is_numeric($value)) {
                $value = (int)$value;
                if($value > 0) {
                    $this->Execute($update ? 
                                            'UPDATE weights SET weight = :value WHERE day = CURDATE() AND id_users = :id' : 
                                            'INSERT INTO weights (id_users, weight, day) VALUES (:id, :value, CURDATE())', 
                                    array(':value' => $value, ':id' => Toolbox::GetUser()->ID));
                    Toolbox::Refresh();
                } else {
                    $message->SetError('The value must be greater than 0');
                }
            } else {
                $message->SetError('Enter a numeric value');
            }

            return $message;
        }
    }