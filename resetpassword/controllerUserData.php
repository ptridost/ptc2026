<?php 
session_start();
require "connection.php";
require_once "../vendor/autoload.php";
$host = getenv('PTC_SMTP_HOST') ?: 'smtp.gmail.com';
$port = (int) (getenv('PTC_SMTP_PORT') ?: 587);
$tls = getenv('PTC_SMTP_ENCRYPTION') ?: 'tls';
$username = getenv('PTC_SMTP_USERNAME') ?: 'eventsmanagerauthenticate@gmail.com';
$password = getenv('PTC_SMTP_PASSWORD') ?: 'lwvf dxem ayyb qjnp';
$subject = "Password Reset Code";
$email = "";
$name = "";
$errors = array();
date_default_timezone_set("Asia/Manila");


// require "connection.php";
// require_once "../vendor/autoload.php";
// $host = "smtp.gmail.com";
// $port = "587";
// $tls = "tls";
// $username = "authenticate.ptri@gmail.com";
// $password = "fkzr oyxr lwho chjh";
// $subject = "Password Reset Code";

    if(isset($_POST['check-email'])){
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $check_email = "SELECT * FROM participant WHERE username ='$email'";
    $run_sql = mysqli_query($con, $check_email);
    if(mysqli_num_rows($run_sql) > 0){
        $code = rand(999999, 111111);
        $expiry = date("Y-m-d H:i:s", time() + 60 * 3);
        $insert_code = "UPDATE participant
            SET code = '$code',
            code_expires_at = '$expiry'
         WHERE username = '$email'";
        $run_query =  mysqli_query($con, $insert_code);
        if($run_query){
            $bodyContent = 'This is your password reset code: '.$code;
            if ($username === '' || $password === '') {
                $errors['mail-error'] = "Password reset email is not configured. Please contact the administrator.";
            } else {
                try {
                    $transport = (new Swift_SmtpTransport($host, $port, $tls))
                        ->setUsername($username)
                        ->setPassword($password);
                    $mailer = new Swift_Mailer($transport);

                    $message = (new Swift_Message($subject))
                        ->setFrom([$username => 'Philippine Textile Congress'])
                        ->setTo([$email])
                        ->setBody($bodyContent, 'text/html');

                    $sent = $mailer->send($message);
                    if ($sent < 1) {
                        throw new RuntimeException('SMTP server accepted no recipients.');
                    }

                    $_SESSION['info'] = "We've sent a password reset OTP to your email - $email <br><br>Please enter it before it expires after <strong>3 minutes</strong>";
                    $_SESSION['email'] = $email;
                    header('location: reset-code.php');
                    exit();
                } catch (Throwable $exception) {
                    error_log('Password reset email failed: ' . $exception->getMessage());
                    $errors['mail-error'] = "We couldn't send the reset email right now. Please try again later or contact the administrator.";
                }
            }
            }else{
                $errors['db-error'] = "Something went wrong!";
            }
        }else{
            $errors['email'] = "This email address does not exist!";
        }
    }

    //if user click check reset otp button
   if (isset($_POST['check-reset-otp'])) {
       $_SESSION['info'] = "";
       $otp_code = mysqli_real_escape_string($con, $_POST['otp']);
       
       // Prepare the SQL statements
       $check_code = "SELECT * FROM participant WHERE code = '$otp_code'";
       $code_res = mysqli_query($con, $check_code);
       
       if (mysqli_num_rows($code_res) > 0) {
           $fetch_data = mysqli_fetch_assoc($code_res);
           $email = $fetch_data['username'];
           $code_expires_at = $fetch_data['code_expires_at'];

           $current_time = date("Y-m-d H:i:s");

           // Check if the OTP has expired
           if ($code_expires_at > $current_time) {
               $_SESSION['email'] = $email;
               $_SESSION['info'] = "Please create a new password that you don't use on any other site.";
               header('location: new-password.php');
               exit();
           } else {
               // OTP expired
               $errors['otp-error'] = "OTP expired, please generate a new one by clicking <a href='forgot-password.php'>forgot password</a>!";
               $remove_otp = "UPDATE participant SET code = 0 WHERE username = '$email'";
               mysqli_query($con, $remove_otp);
               // header('location: forgot-password.php');
           }
       } else {
           // Incorrect OTP
           $errors['otp-error'] = "You've entered an incorrect code!";
       }
   }


    //if user click change password button
    if(isset($_POST['change-password'])){
        $_SESSION['info'] = "";
        $password = mysqli_real_escape_string($con, $_POST['password']);
        $cpassword = mysqli_real_escape_string($con, $_POST['cpassword']);
        if($password !== $cpassword){
            $errors['password'] = "Confirm password not matched!";
        }else{
            $code = 0;
            $email = $_SESSION['email']; //getting this email using session
            $encpass = md5($password);
            $update_pass = "UPDATE participant SET code = $code, password = '$encpass' WHERE username = '$email'";
            $run_query = mysqli_query($con, $update_pass);
            if($run_query){
                $info = "Your password changed. Now you can login with your new password.";
                $_SESSION['info'] = $info;
                header('Location: password-changed.php');
            }else{
                $errors['db-error'] = "Failed to change your password!";
            }
        }
    }
    
   //if login now button click
    if(isset($_POST['login-now'])){
        header('Location: ../Loginn.php');
    }
?>
