<?php
    class File {
        public function __construct($fileName, $displayName, $displayLogged, $displayDisconnected, $class = '') {
            $this->fileName = $fileName;
            $this->displayName = $displayName;
            $this->displayLogged = $displayLogged;
            $this->displayDisconnected = $displayDisconnected;
        }
    }