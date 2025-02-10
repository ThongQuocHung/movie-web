<?php
    require_once('../api/dbConnection.php');
    session_start();
    // biến kiểm tra Admin có đăng nhập chưa
    $_SESSION['checkAdmin'] = False;

    if (isset($_POST['submitSignIn'])) {
        $errSignIn = 'Tên đăng nhập hoặc mật khẩu không đúng';
        //kiểm tra có nhập đầy đủ cả tên đăng nhập và mật khẩu
        if(isset($_POST['adminName']) && isset($_POST['passSignIn'])){
            //truy suất database
            $sql = 'SELECT * FROM admin Where Usernames=?';
            try {
                $stmt = $dbCon->prepare($sql);
                $stmt->execute(array($_POST['adminName']));
            } catch (PDOException $ex) {
                die(json_encode(array('status' => false, 'data' => $ex->getMessage())));
            }
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            //nếu tên đăng nhập và mật khẩu đúng thì checkAdmin True và điều hướng trang sang home.php
            if ($_POST['adminName'] == $row['Usernames'] && $_POST['passSignIn'] == $row['PWD']) {
                $errSignIn = 'Đăng nhập thành công';
                $_SESSION['checkAdmin'] = True;
                header('Location:home.php');
            }
        }
    }
    
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Đăng nhập quản trị | Website Film</title>
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
                        <b>ĐĂNG NHẬP ADMIN QUẢN LÝ WEB MOVIE</b>
                    </span>
                    <!--=====FORM INPUT TÀI KHOẢN VÀ PASSWORD======-->
                    <form action="" method="POST">
                        <div class="wrap-input100 validate-input">
                            <input class="input100" type="text" placeholder="Tên tài khoản Admin" name="adminName" id="username">
                            <!-- <span class="focus-input100"></span> -->
                            <span class="symbol-input100">
                                <i class='bx bx-user'></i>
                            </span>
                        </div>
                        <div class="wrap-input100 validate-input">
                            <input autocomplete="off" class="input100" type="password" placeholder="Mật khẩu" name="passSignIn" id="password-field">
                            <!-- <span toggle="#password-field"></span>
                            <span class="focus-input100"></span> -->
                            <span class="symbol-input100">
                                <i class='bx bx-key' id="showPassword"></i>
                            </span>
                        </div>
                        <!--Nơi hiện kết quả khi đăng nhập-->
                        <?php if (isset($errSignIn)) echo $errSignIn; ?>
                        <!--=====ĐĂNG NHẬP======-->
                        <div class="container-login100-form-btn">
                            <input type="submit" value="Đăng nhập" id="submitSignIn" name="submitSignIn" />
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- PHP -->
</body>

</html>