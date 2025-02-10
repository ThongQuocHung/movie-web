<?php
require('../api/dbConnection.php');
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $err;
    if (isset($_POST['confirmPWDbtn'])) {
        if (!isset($_POST['user']) || empty($_POST['user'])) {
            $err = 'Vui lòng nhập Tên đăng nhập';
        }
        elseif(!isset($_POST['currentPWD']) || empty($_POST['currentPWD'])){
            $usernames = $_POST['user'];
            $err = 'Vui lòng nhập mật khẩu hiện tại';
        }
        elseif(!isset($_POST['newPWD']) || empty($_POST['newPWD'])){
            $usernames = $_POST['user'];
            $currPWD = $_POST['currentPWD'];
            $err = 'Vui lòng nhập mật khẩu mới';
        }
        elseif(strlen($_POST['newPWD']) < 8 ){
            $usernames = $_POST['user'];
            $currPWD = $_POST['currentPWD'];
            $err = 'Vui lòng nhập mật khẩu trên 8 ký tự';
        }
        elseif(!isset($_POST['confirmPWD']) || empty($_POST['confirmPWD'])){
            $usernames = $_POST['user'];
            $currPWD = $_POST['currentPWD'];
            $newPWD = $_POST['newPWD'];
            $err = 'Vui lòng xác nhận mật khẩu mới';
        }
        elseif($_POST['confirmPWD'] != $_POST['newPWD']){
            $usernames = $_POST['user'];
            $currPWD = $_POST['currentPWD'];
            $newPWD = $_POST['newPWD'];
            $err = 'Mật khẩu không khớp';
        }else{
            $usernames = $_POST['user'];
            $newPWD = $_POST['newPWD'];
            $currPWD = $_POST['currentPWD'];
            $sql = 'SELECT * FROM Users WHERE Username=?';
            try{
                $stmt = $dbCon->prepare($sql);
                $stmt->execute(array($usernames));
            }catch(PDOException $ex){
                die(json_encode(array('status' => false, 'data' => $ex->getMessage())));
            }
            if($stmt->rowCount() > 0 && $stmt->fetch(PDO::FETCH_ASSOC)['PWD'] == $currPWD){
                $sql1 = 'UPDATE Users SET PWD=? WHERE Username=?';
                try{
                    $stmt1 = $dbCon->prepare($sql1);
                    $stmt1->execute(array($newPWD, $usernames));
                }catch(PDOException $ex){
                    die(json_encode(array('status' => false, 'data' => $ex->getMessage())));
                }
                if($stmt1->rowCount() > 0){
                    header('Location:login.php?msg=2');
                }
                else{
                    $err = 'Đã xảy ra lỗi';
                }
            }
            else{
                $err = 'Tài khoản không tồn tại hoặc mật khẩu không chính xác!';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Đổi Mật Khẩu | Website Film</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="../vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/login.css">
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
                <div class="login100-form validate-form">
                    <span class="login100-form-title">
                        <b>ĐỔI MẬT KHẨU</b>
                    </span>
                    <!--=====FORM INPUT TÀI KHOẢN VÀ PASSWORD======-->
                    <form action="" method="POST">
                        <div class="wrap-input100 validate-input">
                            <input autocomplete="off" class="input100" type="text" placeholder="Tên đăng nhập" name="user" id="password-field" value=<?php if (isset($usernames))
                                echo $usernames;?>>
                            <span class="symbol-input100">
                                <i class='bx bx-user'></i>
                            </span>
                        </div>
                        <div class="wrap-input100 validate-input">
                            <input autocomplete="off" class="input100" type="password" placeholder="Mật khẩu hiện tại" name="currentPWD" id="password-field" value=<?php if (isset($currPWD))
                                echo $currPWD;?>>
                            <span class="symbol-input100">
                                <i class='bx bx-key' id="showPassword"></i>
                            </span>
                        </div>
                        <div class="wrap-input100 validate-input">
                            <input autocomplete="off" class="input100" type="password" placeholder="Mật khẩu mới" name="newPWD" id="password-field" value=<?php if (isset($newPWD))
                                echo $newPWD;?>>
                            <span class="symbol-input100">
                                <i class='bx bx-key' id="showPassword"></i>
                            </span>
                        </div>
                        <div class="wrap-input100 validate-input">
                            <input autocomplete="off" class="input100" type="password" placeholder="Xác nhận mật khẩu" name="confirmPWD" id="password-field" value=<?php if (isset($currPWD))
                                echo $currPWD;?>>
                            <span class="symbol-input100">
                                <i class='bx bx-key' id="showPassword"></i>
                            </span>
                        </div>
                        <?php if (isset($err)) echo $err; ?>
                        <!--=====ĐĂNG NHẬP======-->
                        <div class="container-login100-form-btn">
                            <input type="submit" value="Xác Nhập" name="confirmPWDbtn" />
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- PHP -->
</body>

</html>