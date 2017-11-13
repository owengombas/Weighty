<?php
    defined('APPLICATION') OR exit('Accès interdit');
    class Weight {
        public function __construct($value, $date) {
            $this->Value = $value;
            $this->Date = $date;
        }
    }