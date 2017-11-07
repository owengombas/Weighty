<?php
    class User {
        public function __construct($id, $username, $email, $admin) {
            $this->ID = $id;
            $this->Username = $username;
            $this->Email = $email;
            $this->Admin = $admin;
        }

        public static function SignIn($id, $username, $email, $admin) {
            $_SESSION['user'] = serialize(new User($id, $username, $email, $admin));
        }
    }