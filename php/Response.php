<?php
    class Response{
        public $name;
        public $email;
        public $message;
        public $date_time;

        function __construct($name, $email, $message, $date_time){
            $this->name = $name;
            $this->email = $email;
            $this->message = $message;
            $this->date_time = $date_time;
        }                
    }
?>