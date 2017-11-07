<?php
    class File {
        public function __construct($fileName, $displayName, $displayLogged, $displayDisconnected, $admin = 0) {
            $this->fileName = $fileName;
            $this->displayName = $displayName;
            $this->displayLogged = $displayLogged;
            $this->displayDisconnected = $displayDisconnected;
            $this->admin = $admin;
        }
    }