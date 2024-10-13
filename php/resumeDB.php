<?php
class resumeDB
{
    private $cn;

    function __construct($server_name, $username, $password, $db_name)
    {
        $config = include("./config.php");
        $this->cn = new mysqli(
            $config['server_name'],
            $config['server_username'],
            $config['server_password'],
            $config['db_name']
        );

        if ($this->cn->connect_error) {
            echo "Failed to connect to MySQL: " . $this->cn->connect_error;
            die();
        }
    }

    function __destruct()
    {
        $this->cn->close();
    }

    function getResponses()
    {

        $responses = array();
        $counter = 0;

        if ($result = $this->cn->query("SELECT * FROM contact_form_responses ORDER BY date_time DESC;")) {
            while ($row = $result->fetch_assoc()) {
                $responses[$counter++] = new Response(
                    $row['sr_no'],
                    $row['name'],
                    $row['email'],
                    $row['message'],
                    $row['date_time'],
                    $row['is_archive']
                );
            }
        } else {
            echo "Failed to execute Query.";
        }

        return $responses;
    }

    function setResponse(Response $obj)
    {

        $stmt = $this->cn->prepare("INSERT INTO  contact_form_responses (name, email, message, date_time) 
                        VALUES (?, ?, ?, ?)");

        $stmt->bind_param(
            "ssss",
            $obj->name,
            $obj->email,
            $obj->message,
            $obj->date_time
        );

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }

        $stmt->close();
    }

    function updateColumn($sr_no, $col_name, $value){
        $query = "UPDATE contact_form_responses
                  SET $col_name = $value 
                  WHERE sr_no = $sr_no ;";

        if ($this->cn->query($query)) {
            return true;
        }
            return false;
        }

    function getVal($sr_no, $col_name){
        $query = "SELECT $col_name FROM contact_form_responses WHERE sr_no = $sr_no";

        if ($result = $this->cn->query($query)) {
            return $result->fetch_assoc();
        }
            return -1;
    }

    function executeQuery($query){
        if ($this->cn->query($query)) {
            return true;
        }
            return false;
    }


}
