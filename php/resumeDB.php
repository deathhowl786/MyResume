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

        if ($result = $this->cn->query("SELECT * FROM contact_form_responses;")) {
            while ($row = $result->fetch_assoc()) {
                $responses[$counter++] = new Response(
                    $row['name'],
                    $row['email'],
                    $row['message'],
                    $row['date_time']
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
}
