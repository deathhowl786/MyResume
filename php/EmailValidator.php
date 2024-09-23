<?php

    class Email{
        private $email;

        function __construct($email)
        {   
            $this->email = $email;
        }

        function checkSyntax(){
            if (filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
                true;
            }
            return false;
        }

        function checkDNS(){
            // Extracting Domain
            $domain = substr(strrchr($this->email, "@"), 1);

            //Checking whether domain has valid MX Record
            if (checkdnsrr($domain, "MX")) {
                return true;
            }
            return false;
        }

        function testRegex(){
            //Random Regex
            $pattern = '/^(?!.*\.{2})(?!.*@-)(?!.*-@)(?!.*@\.)(?!.*\.$)(?!.*\.\.)(?!.*@.*\.{2})(?!^\d+$)[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+(\.[a-zA-Z]{2,5})+$/';
            if (preg_match($pattern, $this->email)) {
                return true;
            }
            return false;
        }


        function isDeliverable(){

            $config = include("./config.php");
            $domain = substr(strrchr($this->email, "@"), 1);
            $mx_records = $this->getMxRecords($domain);
            
            if (!$mx_records){
                echo "Mx records";
                return false;
            }
        
            $mail_server = $mx_records[0];  // Using the first MX record
            $connection = @fsockopen($mail_server, 25, $errno, $errstr, 15);  // Connect to SMTP port 25
        
            if ($connection) {
                $from = $config['email'];  // Use your own email here
                $helo = "HELO ".$config['email_domain']."\r\n";
                $mail_from = "MAIL FROM: <".$from.">\r\n";
                $rcpt_to = "RCPT TO: <".$this->email.">\r\n";
                

                // SMTP conversation
                fwrite($connection, $helo);
                $response = fgets($connection, 1024) . "\r\n";
                // echo "HELO Response: $response<br>";

                fwrite($connection, $mail_from);
                // echo "MAIL FROM Response: $response<br>";
                $response = fgets($connection, 1024) . "\r\n";

                fwrite($connection, $rcpt_to);
                $response = fgets($connection, 1024) . "\r\n";
                // echo "RCPT TO Response: $response<br>";

                fwrite($connection, "QUIT \r\n");
                $response = fgets($connection, 1024);
                // echo "QUIT Response: $response<br>";
                
                fclose($connection);

                // Get the server response
                if (strpos($response, '250') !== false) {
                    // echo "Email is deliverable.<br>";
                    return true;
                }else{
                    // echo "Email not deliverable.<br>";
                    return false;
                }
            } else {
                // echo "Connection Issue";
                return false;
            }
        }
        
        function getMxRecords($domain) {
            $mx_records = [];
            if (getmxrr($domain, $mx_records)) {
                return $mx_records;
            }
            return false;
        }

    }

?>