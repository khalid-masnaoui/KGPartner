<?php
require_once __DIR__ . "/../../../core/ini.php";
require_once __DIR__ . "/../../../core/constants.php";
require_once __DIR__ . "/../../../functions/randomString.php";
require_once __DIR__ . "/../../../functions/encryptDecrypt.php";


if (input::exists("post") && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' && isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] == "https://koreagaming.info/pages/member/member_management/partners_list.php") {

    if (isset($_GLOBALS["ERR"])) {
        unset($_GLOBALS["ERR"]);
    }

    if (token::check(input::get("token"), "add_partner")) {

        $partner = new user();
        $parentPtId = $partner->data()["pt_id"];
        $parentRate = $partner->data()["rate"];

        $validate = new validate();
        $validation = $validate->check(
            $_POST,
            array(

                "username" => [
                    "unique" => "partner_users",
                    "pattern" => ["rule" => '/^.{1,30}$/', "msg" => '1~30자를 입력하세요.']
                ],
                "password" => [
                    "pattern" => ["rule" => '/^.{8,30}$/', "msg" => ' 8~30자를 입력하세요.']
                ],
                "partnerRate" => [
                    "pattern" => ["rule" => '/^[0-9]{1,2}\.[0-9]{2}$/', "msg" => '본인의 요율보다 같거나 높게 입력해 주세요.'],
                    "notEmpty" => true,
                    "biggerThan" => 0,

                    //check that the partner rate is bigger than that of the parent
                    "RateBiggerOfPartner" => [
                        "parentRate" => $parentRate,
                    ],
                ],
                "status" => [
                    "inclusion" => [
                        "list" => ["1", "0", "3"],
                        "msg" => "please select valid option from the purposed option!"
                    ]
                ],

            )
        );
        if ($validation->passed()) {
            //log the user

            $db = DB::getInstance();

            //construct password
            $password = encrypt(input::get("password"));
            $hashed_password = password_hash(input::get("password"), PASSWORD_DEFAULT);

            //construct pt_id
            $partnerId = $parentPtId . "/";



            $array = ["username" => input::get("username"), "password" => $hashed_password, "rate" => input::get("partnerRate"), "status" => input::get("status"), "raw_ps" => $password];

            $inserted = $db->insert('partner_users', $array);
            if ($inserted->error()) {
                $data = json_encode(["response" => 4, "errors" => [], "token" => token::generate("add_partner")]);
                print_r($data);
                exit();
            }
            //update the pt_id
            $partnerAutoId = $inserted->lastId();

            $partnerId .= $partnerAutoId;

            $updated = $db->update('partner_users', [["id", "=", $partnerAutoId]], ["pt_id" => $partnerId]);


            //log the action
            $log = new ActivityLogger();

            $action = "Partner Added";
            $description = "A Partner [" . $partnerAutoId . "] has been added";
            $logged = $log->addLog($action, $description);

            //call function for showing the data on table without refreshing
            $data = json_encode(["response" => 1, "errors" => [], "token" => token::generate("add_partner")]);
            print_r($data);

        } else {
            //output errors & and inputs /use sessions or globals if in the same page
            $_GLOBALS["ERR"] = $validation->errors();
            $data = json_encode(["response" => 0, "errors" => $_GLOBALS["ERR"], "token" => token::generate("add_partner")]);
            print_r($data);
            exit();


        }
    } else {
        $data = json_encode(["response" => 2, "errors" => [], "token" => token::generate("add_partner")]);
        print_r($data);
        exit();

    }
} else {
    echo "unauthorized";
    // header('Location: /pages/errors/403.php');      
}

















?>
