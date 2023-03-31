<?php
require_once __DIR__ . "/../../../core/ini.php";
require_once __DIR__ . "/../../../core/constants.php";
require_once __DIR__ . "/../../../functions/randomString.php";


if (input::exists("post") && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' && isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] == "https://koreagaming.live/pages/member/member_management/partners_list.php") {

    if (isset($_GLOBALS["ERR"])) {
        unset($_GLOBALS["ERR"]);
    }

    if (token::check(input::get(config::get("session/token_name")), "edit_partner")) {

        $partner = new user();
        $parentPtId = $partner->data()["pt_id"];
        $parentRate = $partner->data()["rate"];

        $id = input::get("id");

        $db = DB::getInstance();
        $originalData = $db->get("pt_id, rate,status", "partner_users", array(["id", "=", $id]))->first();

        if (!count($originalData)) {
            $data = json_encode(["response" => 4, "errors" => [], "token" => token::generate("edit_partner")]);
            print_r($data);
            exit();
        }
        $partnerId = $originalData["pt_id"];

        $validate = new validate();
        $validation = $validate->check(
            $_POST,
            array(

                "partnerRate" => [
                    "pattern" => ["rule" => '/^[0-9]{1,2}\.[0-9]{2}$/', "msg" => 'Not a valid Rate number. MUST be a DECIMAL number from 0-99. Example : 45.30.'],
                    "notEmpty" => true,
                    "biggerThan" => 0,

                    //check that the partner rate is bigger than that of the parent
                    "RateBiggerOfPartner" => [
                        "parentRate" => $parentRate,
                    ],
                    //check that the partner rate is less than that of the child
                    "RateLesserOfChild" => [
                        "child" => $partnerId,
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

            //edit partner in db;

            $array = ["rate" => input::get("partnerRate"), "status" => input::get("status")];

            $updated = $db->update('partner_users', [["id", "=", $id]], $array);
            if ($updated->error()) {
                $data = json_encode(["response" => 4, "errors" => [], "token" => token::generate("edit_partner")]);
                print_r($data);
                exit();
            }


            //update records to rate table if rate is updated
            if (input::get("partnerRate") != $originalData["rate"]) {
                $ratesBuilder = new Rates();
                $ratesBuilder->upsertCommissionsRatesPartner($id);
            }

            //log the action
            $log = new ActivityLogger();

            $action = "Partner Edited";

            $diffArray = array_diff_assoc($array, $originalData);

            $details = "";
            foreach ($diffArray as $key => $value) {

                $originalValue = $originalData[$key];
                $details .= "--$key" . "[" . $originalValue . "]->[" . $value . "]";
            }

            if ($details != "") {
                $description = "The Partner [" . $id . "] has been edited. <<$details>>";
                $logged = $log->addLog($action, $description);
            }



            //call function for showing the data on table without refreshing
            $data = json_encode(["response" => 1, "errors" => [], "token" => token::generate("edit_partner")]);
            print_r($data);
            exit();


        } else {
            //output errors & and inputs /use sessions or globals if in the same page
            $_GLOBALS["ERR"] = $validation->errors();
            $data = json_encode(["response" => 0, "errors" => $_GLOBALS["ERR"], "token" => token::generate("edit_partner")]);
            print_r($data);
            exit();

        }
    } else {
        $data = json_encode(["response" => 2, "errors" => [], "token" => token::generate("edit_partner")]);
        print_r($data);
        exit();
    }
} else {
    echo "unauthorized";
    // header('Location: /pages/errors/403.php');      
}
?>
