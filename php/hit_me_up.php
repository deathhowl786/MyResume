<?php
    
    use PHPMailer\PHPMailer\Exception;
    use PHPMailer\PHPMailer\PHPMailer;

    require "../dependencies/vendor/phpmailer/phpmailer/src/Exception.php";
    require "../dependencies/vendor/phpmailer/phpmailer/src/PHPMailer.php";
    require "../dependencies/vendor/phpmailer/phpmailer/src/SMTP.php";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Get form input
        $name = $_POST['name'];
        $email = $_POST['email'];
        $message = $_POST['message'];
    
        // Check if email is valid
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            // Create a new PHPMailer instance
            $mail = new PHPMailer(true);  // Enable exceptions for error handling
            
            try {
                // Server settings
                $mail->isSMTP();  // Send using SMTP
                $mail->Host       = 'smtp.gmail.com';  // SMTP server (Gmail in this case)
                $mail->SMTPAuth   = true;  // Enable SMTP authentication
                $mail->Username   = 'web.srajan@gmail.com';  // Your Gmail address
                $mail->Password   = 'rxvr qlji ctwa sqds';  // Your Gmail password or App password
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;  // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` for SSL
                $mail->Port       = 587;  // TCP port to connect to
    
                // Recipients
                $mail->setFrom($email, $name);  // From email and name
                $mail->addAddress('web.srajan@gmail.com');  // Your email where the message will be sent
                
                // Content
                $mail->isHTML(true);  // Set email format to HTML
                $mail->Subject = 'Resume Pge Contact Form Submission from ' . $name;
                $mail->Body    = "You have received a new message from your contact form.<br><br>";
                $mail->Body   .= "<strong>Name:</strong> $name<br>";
                $mail->Body   .= "<strong>Email:</strong> $email<br>";
                $mail->Body   .= "<strong>Message:</strong><br>$message";
    
                // Send the email
                $mail->send();
                echo 'Thank you for contacting me! Your message has been recording Successfully!';
            } catch (Exception $e) {
                echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            }
        } else {
            echo 'Invalid email address.';
        }
    }
    

?>