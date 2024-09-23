<style>
@import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&family=Raleway:wght@400;600&display=swap');

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Raleway', sans-serif;
    background: linear-gradient(to bottom right, #4a90e2, #d4a5a5);
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    color: #fff;
}

.message-box {
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 20px;
    border-radius: 20px;
    backdrop-filter: blur(10px);
    box-shadow: 0 4px 30px rgba(0, 0, 0, 0.5);
}

.message {
    background-color: rgba(255, 255, 255, 0.9);
    border-radius: 20px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 40px;
    max-width: 400px;
    width: 100%;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    position: relative;
}

.message:before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    border-radius: 20px;
    background: linear-gradient(to bottom right, #4a90e2, #d4a5a5);
    z-index: -1;
    filter: blur(15px);
}

.message:hover {
    transform: translateY(-5px);
    box-shadow: 0 6px 30px rgba(0, 0, 0, 0.3);
}

.message.success {
    background-color: rgba(76, 175, 80, 0.9);
    color: #fff;
}

.message.error {
    background-color: rgba(244, 67, 54, 0.9);
    color: #fff;
}

.message h1 {
    font-size: 1.6rem;
    margin-bottom: 10px;
    text-align: center;
    text-shadow: 1px 1px 5px rgba(0, 0, 0, 0.4);
}

.message h2 {
    font-size: 1.2rem;
    margin-bottom: 20px;
    text-align: center;
    text-shadow: 1px 1px 5px rgba(0, 0, 0, 0.4);
}

.message a {
    font-weight: 600;
    color: #fff;
    text-decoration: none;
    margin-top: 15px;
    padding: 10px 20px;
    border: 2px solid #fff;
    border-radius: 5px;
    transition: background-color 0.3s ease, color 0.3s ease;
}

.message a:hover {
    background-color: #fff;
    color: #4a90e2;
}

/* Responsive Styling */
@media screen and (max-width: 768px) {
    .message {
        padding: 30px;
    }

    .message h1 {
        font-size: 1.4rem;
    }

    .message h2 {
        font-size: 1rem;
    }

    .message a {
        font-size: 1rem;
    }
}

@media screen and (max-width: 480px) {
    .message {
        padding: 20px;
    }

    .message h1 {
        font-size: 1.2rem;
    }

    .message h2 {
        font-size: 0.9rem;
    }

    .message a {
        font-size: 0.9rem;
    }
}
</style>



<div class="message-box">
<?php
    
    use PHPMailer\PHPMailer\Exception;
    use PHPMailer\PHPMailer\PHPMailer;
    
    require "../dependencies/vendor/phpmailer/phpmailer/src/Exception.php";
    require "../dependencies/vendor/phpmailer/phpmailer/src/PHPMailer.php";
    require "../dependencies/vendor/phpmailer/phpmailer/src/SMTP.php";
    require "./Response.php";
    require "./resumeDB.php";
    require "./EmailValidator.php";

    $config = include("./config.php");


    function printMessage($mssg, $type){
        echo "<div class=\"message ".$type."\" >"
                    .$mssg
                    ."<a href=\"../index.html  \">Back To Resume</a>"

              ."</div>";
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Get form input
        $name = $_POST['name'];
        $email = trim($_POST['email']);
        $message = $_POST['message'];
        date_default_timezone_set('Asia/Kolkata');
        $date_time = date("Y-m-d H:i:s");
        $EMAIL = new Email($email);
        


        if ($EMAIL->checkSyntax() || $EMAIL->checkDNS()) {

            if($EMAIL->isDeliverable()){
                $mail = new PHPMailer(true);
                try {
                    $mail->isSMTP();  // Send using SMTP
                    $mail->Host       = 'smtp.gmail.com';  // SMTP server (Gmail in this case)
                    $mail->SMTPAuth   = true;  // Enable SMTP authentication
                    $mail->Username   = $config['email'];  // Your Gmail address
                    $mail->Password   = $config['app_password'];  // Your Gmail password or App password
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;  // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` for SSL
                    $mail->Port       = 587;  // TCP port to connect to
                
                    // Recipients
                    $mail->setFrom($mail->Username, $config['site_name']);  // From email and name
                    $mail->addAddress($email);  // Your email where the message will be sent

                    // Content
                    $mail->isHTML(true);  // Set email format to HTML
                    $mail->Subject = 'Thank You for your Reponse ' . $name;
                    $mail->Body    = "I have received your message and will get back to you soon!<br><br>";
                
                    // Send the email
                    // $mail->send();
                    $my_db = new resumeDB($config['server_name'], 
                                          $config['server_username'], 
                                          $config['server_password'], 
                                          $config['db_name']);
                    $my_db->setResponse(new Response($name, $email, $message, $date_time));
                    printMessage("<h1>Your message has been recorded!</h1><h2>Thank You for contacting me !!</h2>", "success");
                } catch (Exception $e) {
                    // echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
                    printMessage("<h1>Message could not be sent.</h1><h2>Please Try again Later!</h2>", "error");
                }
            }else{
                printMessage("<h1>Email Address not Deliverable!</h1><h2>Please use a different email!</h2>", "error");
            }
        } else {
            printMessage("<h1>Invalid email address!</h1><h2>Please use a different email!</h2>", "error");
        }
    }

?>
</div>