<?php
require_once __DIR__ . "/../../core/ini.php";
require_once __DIR__ . "/../../core/constants.php";
require_once __DIR__ . "/../../functions/randomString.php";
require_once __DIR__ . "/../../functions/encryptDecrypt.php";


if (input::exists("post") && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' && isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] == "https://koreagaming.live//pages/authentication/change_password.php") {

    if (isset($_GLOBALS["ERR"])) {
        unset($_GLOBALS["ERR"]);
    }

    if (token::check(input::get(config::get("session/token_name")), "edit_password")) {

        $admin = new user();
        $admin_id = $admin->data()["id"];
        $adminUserName = $admin->data()["username"];

        $_POST["username"] = $adminUserName;

        $validate = new validate();

        $validation = $validate->check(
            $_POST,
            array(
                "oldPassword" => [
                    "pass_matches" => "admin_users",
                ],
                "newPassword" => [
                    "pattern" => ["rule" => '/^.{8,30}$/', "msg" => 'Field is required and should be {8 to 30} characters long.']
                ],
                "confirmPassword" => [
                    "matches" => "newPassword"
                ],
            )
        );

        if ($validation->passed()) {
            //log the user

            // print_r($login);exit;
            $db = DB::getInstance();

            // $password = encrypt(input::get("newPassword"));
            $hashed_password = password_hash(input::get("newPassword"), PASSWORD_DEFAULT);


            // $array = ["password" => $hashed_password, "raw_ps" => $password];
            $array = ["password" => $hashed_password];


            $inserted = $db->update('admin_users', [["id", "=", $admin_id]], $array);
            if ($inserted->error()) {
                $data = json_encode(["response" => 4, "errors" => [], "token" => token::generate("edit_password")]);
                print_r($data);
                exit();
            }


            //log the action
            $log = new ActivityLogger();

            $action = "Password changed";
            $description = "Admin's Password changed";
            $logged = $log->addLog($action, $description);

            //call function for showing the data on table without refreshing
            $data = json_encode(["response" => 1, "errors" => [], "token" => token::generate("edit_password")]);
            print_r($data);

        } else {
            //output errors & and inputs /use sessions or globals if in the same page
            $_GLOBALS["ERR"] = $validation->errors();
            $data = json_encode(["response" => 0, "errors" => $_GLOBALS["ERR"], "token" => token::generate("edit_password")]);
            print_r($data);
            exit();

        }
    } else {
        $data = json_encode(["response" => 2, "errors" => [], "token" => token::generate("edit_password")]);
        print_r($data);
        exit();



    }
} else {
    echo "unauthorized";
    // header('Location: /pages/errors/403.php');      
}

















?>
