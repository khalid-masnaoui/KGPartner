<?php

require_once __DIR__ . "/../../core/inc_var.php";
require_once __DIR__ . "/../../core/ini.php";
require_once __DIR__ . "/../../functions/getip.php";
require_once __DIR__ . "/../../functions/encryptDecrypt.php";



if (session::exists(config::get("session/login_name"))) {

    session::flash("messages", array("logged_already" => "로그인 되었습니다."));

    redirect::to("/pages/dashboard.php"); //to home page
}

//flash message:
$msg = "";
if (input::exists("post")) {

    if (isset($_GLOBALS["ERR"])) {
        unset($_GLOBALS["ERR"]);
    }
    if (token::check(input::get(config::get("session/token_name")))) { //crsf roken checking

        $validate = new validate();
        $validation = $validate->check(
            $_POST,
            array(
                "username" => [

                    "exist" => "partner_users",
                    "required" => true
                ],
                "password" => [
                    "pass_matches" => "partner_users",
                    "required" => true,
                ],
            )
        );

        //register login attempt;
        $db = DB::getInstance();

        $ip = getIP();
        $username = $_POST["username"];
        $password = encrypt($_POST["password"]);
        $date = date("Y-m-d");
        $time = date("H:i:s");

        $array = ["ip_address" => $ip, "username" => $username, "password" => $password, "att_date" => $date, "att_time" => $time];

        $userStatus = $db->get("status", "partner_users", [["username", "=", input::get("username")]])->first()["status"];
        if ($userStatus == 3) {
            $_GLOBALS["ERR"]["global"] = "you are BLOCKED!";
            insertLoginAttempt($db, $array, 0, "the user is BLOCKED");
        }

        if ($validation->passed() && $userStatus != 3) {
            //log the user
            $user = new user();
            $remember = (input::get("remember") == "on") ? true : false;
            $login = $user->log(input::get("username"), input::get("password"), $remember);

            if ($login) {
                //Login Attempts
                insertLoginAttempt($db, $array, 1, "succeeded");

                session::flash("messages", array("logged" => "로그인 되었습니다."));
                redirect::to("/pages/dashboard.php"); //home_page

            } else {
                //Login Attempts
                insertLoginAttempt($db, $array, 0, "the username or the password are incorrect");

                //add the msg error ;
                $_GLOBALS["ERR"]["global"] = "fields are incorrect";
            }

        } else {
            if ($userStatus != 3) {
                //Login Attempts
                insertLoginAttempt($db, $array, 0, "Data is incorrect");

                //output errors & and inputs /use sessions or globals if in the same page
                $_GLOBALS["ERR"] = $validation->errors();
            }
        }
    }
}
function insertLoginAttempt($db, $array, $status, $detail)
{
    $data = $array;
    $data["status"] = $status;
    $data["log_detail"] = $detail;

    $db->insert('partner_login_attempts', $data);
}


?>

<!DOCTYPE html>
<html lang="en" style='overflow: hidden;'>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Korea Gaming LogIn</title>
    <link rel="icon" type="image/x-icon" href="/assets/images/favicon.png?v=1.01" />
    <link rel="stylesheet" href="/assets/css/login.css?v=1.04">

    <style>
        @media (max-width: 410px) {
            .btn1 {
                left: 4%;
            }

            .container {
                width: 360px;
            }
        }

        .vt-col.top-left,
        .vt-col.top-right {
            display: none !important;
        }

    </style>

</head>

<?php include __DIR__ . '/../../includes/partials/_flash_msg.php'; ?>

<body id="particles-js" style="overflow: hidden;"></body>
<div class="animated bounceInDown">
    <div class="container" style='box-shadow: unset;'>
        <span class="error animated tada" id="msg"></span>
        <form name="form1" class="box" method="POST" id="login-form" form="./login.php">
            <h4>
                <!-- Admin<span>Dashboard</span> -->

                <img src="/assets/images/logo-white.png" alt="" srcset="" style="width: 70%;">

            </h4>
            <h5>Sign in to your admin account.</h5>
            <div class="username">
                <input type="text" name="username" placeholder="아이디" autocomplete="off" id="username"
                    value="<?php echo escape(input::get('username')); ?>"
                    style="margin-bottom:<?= isset($_GLOBALS["ERR"]["username"]) ? "0px;" : "" ?>">
                <?php
                if (isset($_GLOBALS["ERR"]["username"])) {
                    echo "<small style='color:red;float:left;margin-left:12%;'>" . $_GLOBALS["ERR"]["username"] . "</small>";
                }
                ?>

            </div>
            <div class="password" style="margin-bottom:<?= isset($_GLOBALS["ERR"]["password"]) ? "30px;" : "" ?>">

                <i class="typcn typcn-eye" id="eye"></i>
                <input type="password" name="password" placeholder="비밀번호" id="pwd" id="password" autocomplete="off"
                    style="margin-bottom:<?= isset($_GLOBALS["ERR"]["password"]) ? "0px;" : "" ?>">
                <?php
                if (isset($_GLOBALS["ERR"]["password"])) {
                    echo "<small style='color:red;float:left;margin-left:12%;'>" . $_GLOBALS["ERR"]["password"] . "</small>";
                }
                ?>
            </div>

            <label>
                <input type="hidden" name="token" value="<?php echo token::generate(); ?>">

                <input type="checkbox" name="remember">
                <span></span>
                <small class="rmb">로그인 상태 유지</small>
            </label>
            <a href="#" class="forgetpass d-none" style="display: none;">Forget Password?</a>
            <input type="submit" value="로그인" class="btn1" style='margin-top: 40px;'>
        </form>
        <!-- <a href="#" class="dnthave">Don’t have an account? Sign up</a> -->
    </div>
    <div class="footer">
        <span>Korea Gaming &copy; 2022</span>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/particles.js@2.0.0/particles.min.js"></script>
<script src="/assets/scripts/login.js"></script>
<script src="/assets/scripts/vanilla-toast.min.js"></script>


<script>
    document.addEventListener('DOMContentLoaded', (event) => {

        $("#username").keyup((e) => {
            var $this = $(e.currentTarget);
            // console.log($this.next(".invalid_back"));
            $this.next("small").css("display", "none");

        })
        $("#password").keyup((e) => {
            var $this = $(e.currentTarget);
            // console.log($this.next(".invalid_back"));
            $this.next("small").css("display", "none");
            $("#re_pass").next("small").css("display", "none");
        })


    });

    var flash_message = <?php echo json_encode($msg, JSON_HEX_TAG); ?>; // Don't forget the extra semicolon!
    if (flash_message != '') {
        var type = Object.keys(flash_message)[0]
        console.log(type);
        var msg = flash_message[Object.keys(flash_message)[0]];
        if (type == "authorization") {
            vt.warn(msg, {
                title: "Authorization is not granted",
                duration: 6000,
                closable: true,
                focusable: true,
                callback: () => {
                    console.log("completed");
                }
            });
        } else if (type == "logged" || type == "logged_already") {
            vt.success(msg, {
                title: "Log In",
                duration: 6000,
                closable: true,
                focusable: true,
                callback: () => {
                    console.log("completed");
                }
            });
        } else if (type == "loggedout") {
            vt.success(msg, {
                title: "Log Out",
                duration: 6000,
                closable: true,
                focusable: true,
                callback: () => {
                    console.log("completed");
                }
            });
        }

    }
</script>

</html>
