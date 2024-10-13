<?php
    class Response{
        public $sr_no;
        public $name;
        public $email;
        public $message;
        public $date_time;
        public $is_archive;

        function __construct($sr_no, $name, $email, $message, $date_time, $is_archive){
            $this->sr_no = $sr_no;
            $this->name = $name;
            $this->email = $email;
            $this->message = $message;
            $this->date_time = $date_time;
            $this->is_archive = $is_archive;;
        }                
    }
?>