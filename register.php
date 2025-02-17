<?php
	require 'vendor/autoload.php';
	use PHPMailer\PHPMailer\PHPMailer;
	use PHPMailer\PHPMailer\Exception;


include 'includes/session.php';

if(isset($_POST['signup'])){
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $repassword = $_POST['repassword'];

    $_SESSION['firstname'] = $firstname;
    $_SESSION['lastname'] = $lastname;
    $_SESSION['email'] = $email;

    if($password != $repassword){
        $_SESSION['error'] = 'Passwords did not match';
        header('location: signup.php');
        exit();
    }

    $conn = $pdo->open();

    $stmt = $conn->prepare("SELECT COUNT(*) AS numrows FROM users WHERE email=:email");
    $stmt->execute(['email'=>$email]);
    $row = $stmt->fetch();

    if($row['numrows'] > 0){
        $_SESSION['error'] = 'Email already taken';
        header('location: signup.php');
        exit();
    }

    $now = date('Y-m-d');
    $password = password_hash($password, PASSWORD_DEFAULT);

    // Generate activation code
    $set = '123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $code = substr(str_shuffle($set), 0, 12);

    try {
        $stmt = $conn->prepare("INSERT INTO users (email, password, firstname, lastname, activate_code, created_on) 
            VALUES (:email, :password, :firstname, :lastname, :code, :now)");
        $stmt->execute([
            'email' => $email, 
            'password' => $password, 
            'firstname' => $firstname, 
            'lastname' => $lastname, 
            'code' => $code, 
            'now' => $now
        ]);
        $userid = $conn->lastInsertId();

        $message = "
            <h2>Thank you for Registering.</h2>
            <p>Your Account:</p>
            <p>Email: ".$email."</p>
            <p>Password: ".$_POST['password']."</p>
            <p>Please click the link below to activate your account.</p>
            <a href='http://localhost/CheeseStore/activate.php?code=".$code."&user=".$userid."'>Activate Account</a>
        ";

        // Load PHPMailer
        $mail = new PHPMailer(true);                             
        try {
            // SMTP settings
            $mail->isSMTP();
            $mail->Host = 'smtp.mailersend.net';  
            $mail->SMTPAuth = true;
            $mail->Username = 'MS_n1Elf5@trial-jpzkmgqweq1g059v.mlsender.net'; // Your Gmail
            $mail->Password = 'mssp.KXrfkTC.351ndgwqvkdgzqx8.2NpyiRW'; // **Use App Password**
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Use 'PHPMailer::ENCRYPTION_STARTTLS' if using TLS
            $mail->Port = 587; // **Use 465 for SSL, 587 for TLS**

            $mail->setFrom('MS_qH6QZS@trial-jpzkmgqweq1g059v.mlsender.net', 'Cheese Store');
            $mail->addAddress($email);
            $mail->addReplyTo('MS_qH6QZS@trial-jpzkmgqweq1g059v.mlsender.net', 'Cheese Store');

            // Email content
            $mail->isHTML(true);
            $mail->Subject = 'Cheese Store Sign Up';
            $mail->Body    = $message;

            $mail->send();

            // Clear session data
            unset($_SESSION['firstname']);
            unset($_SESSION['lastname']);
            unset($_SESSION['email']);

            $_SESSION['success'] = 'Account created. Check your email to activate.';
            header('location: signup.php');
            exit();

        } catch (Exception $e) {
            $_SESSION['error'] = 'Message could not be sent. Mailer Error: '.$mail->ErrorInfo;
            header('location: signup.php');
            exit();
        }

    } catch (PDOException $e) {
        $_SESSION['error'] = $e->getMessage();
        header('location: register.php');
        exit();
    }

    $pdo->close();
} else {
    $_SESSION['error'] = 'Fill up signup form first';
    header('location: signup.php');
    exit();
}

?>
