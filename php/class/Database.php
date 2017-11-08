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

            if(isset($value)) {
                if(is_numeric($value)) {
                    $value = (int)$value;
                    if($value <= 9999) {
                        if($value > 0) {
                            $this->Execute($update ? 
                                                    'UPDATE weights SET weight = :value WHERE day = CURDATE() AND id_users = :id' : 
                                                    'INSERT INTO weights (id_users, weight, day) VALUES (:id, :value, CURDATE())', 
                                            array(':value' => $value, ':id' => Toolbox::GetUser()->ID));
                            $message->SetSuccess('Your weight has been submitted');
                        } else {
                            $message->SetError('The value must be greater than 0');
                        }
                    } else {
                        $message->SetError('The value is too big');
                    }
                } else {
                    $message->SetError('Enter a numeric value');
                }
            } else {
                $message->SetError('Fill all fields');
            }

            return $message;
        }
    }