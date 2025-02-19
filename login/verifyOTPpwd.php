<?php
require_once('../api/dbConnection.php');
session_start();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require_once('../vendor/phpmailer/src/Exception.php');
require_once('../vendor/phpmailer/src/PHPMailer.php');
require_once('../vendor/phpmailer/src/SMTP.php');

function createOTP()
{
    return rand(10000, 100000);
}
if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    // passing true in constructor enables exceptions in PHPMailer
    $mail = new PHPMailer(true);
    $noticeSignUp;
    try {
        // Server settings
        // $mail->SMTPDebug = SMTP::DEBUG_SERVER; // for detailed debug output
        $mail->SMTPDebug = 0;
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->Username = 'kksoftwarelanguagecenter@gmail.com'; // YOUR gmail email
        $mail->Password = 'plffbgscyaylniio'; // YOUR gmail password

        $gmailTarget = $_SESSION['gmail'];
        // Sender and recipient settings
        $mail->setFrom('kksoftwarelanguagecenter@gmail.com', 'Web Film');
        $mail->addAddress($gmailTarget);
        $mail->addReplyTo('kksoftwarelanguagecenter@gmail.com', 'Web Film'); // to set the reply to

        $OTP = createOTP();

        $sql = 'UPDATE Users SET OTP=? WHERE Gmail=?';
        try {
            $stmt = $dbCon->prepare($sql);
            $stmt->execute(array($OTP, $gmailTarget));
        } catch (\PDOException $ex) {
            die(json_encode(array('status' => false, 'data' => $ex->getMessage())));
        }
        // Setting the email content
        $mail->IsHTML(true);
        $mail->Subject = "VERIFY OTP FOR ACCOUNT IN WEBFILM";
        $mail->Body = "<b>Your OTP: $OTP</b>";
        $mail->AltBody = "<b>Your OTP: $OTP</b>";

        $mail->send();
        $noticeSignUp = "Gửi OTP thành công, vui lòng kiểm tra!";
    } catch (Exception $e) {
        $noticeSignUp = "Đã xảy ra lỗi (OTP), vui lòng thử lại!";
    }
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['submitForgetOTP'])) {
        if (isset($_SESSION['gmail'])) {
            $gmail = $_SESSION['gmail'];
            $sql = 'SELECT OTP FROM Users WHERE Gmail=?';
            try {
                $stmt = $dbCon->prepare($sql);
                $stmt->execute(array($gmail));
            } catch (\PDOException $ex) {
                die(json_encode(array('status' => false, 'data' => $ex->getMessage())));
            }
            if ($stmt->rowCount() > 0) {
                if ($stmt->fetch(PDO::FETCH_ASSOC)['OTP'] == $_POST['OTP']) {
                    header('Location:changePwd.php');
                } else {
                    $noticeSignUp = "OTP không chính xác!";
                }
            }
        } else {
            $noticeSignUp = "Đã xảy ra lỗi (OTP), vui lòng thử lại!";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Đăng ký tài khoản | Website Film</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="../vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="../css/login.css">
    <link rel="stylesheet" href="https://unpkg.com/boxicons@latest/css/boxicons.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
</head>

<body>
    <div class="limiter">
        <div class="container-login100">
            <div class="wrap-login100">
                <div class="login100-pic js-tilt" data-tilt>
                <a href="../index.php"><img src="../images/team.png" alt="IMG"></a>
                </div>
                <!--=====TIÊU ĐỀ======-->
                <div class="login100-form-register validate-form">
                    <span class="login100-form-title-register">
                        <b>ĐĂNG KÝ</b>
                    </span>
                    <!--=====FORM INPUT TAOJ TÀI KHOẢN VÀ PASSWORD======-->
                    <form action="" method="POST">
                        <div class="wrap-input100 validate-input">
                            <input class="input100" type="text" placeholder="Nhập OTP" name="OTP" id="OTP">
                            <span class="focus-input100"></span>
                            <span class="symbol-input100">
                                <i class='bx bx-mail-send'></i>
                            </span>
                        </div>
                        <?php if (isset($noticeSignUp)) echo $noticeSignUp; ?>
                        <!--=====ĐĂNG Ký======-->
                        <div class="container-login100-form-btn">
                            <input type="submit" value="XÁC NHẬN" id="submitForgetOTP" name="submitForgetOTP" />
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>