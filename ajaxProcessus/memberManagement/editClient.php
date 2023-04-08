<?php
require_once __DIR__ . "/../../core/ini.php";
require_once __DIR__ . "/../../core/constants.php";
require_once __DIR__ . "/../../functions/randomString.php";


if (input::exists("post") && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' && isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] == "https://koreagaming.live/pages/member/member_management/clients_list.php") {

    if (isset($_GLOBALS["ERR"])) {
        unset($_GLOBALS["ERR"]);
    }

    if (token::check(input::get(config::get("session/token_name")))) {

        $partner = new user();
        $parentPtId = $partner->data()["pt_id"];
        $parentRate = $partner->data()["rate"];

        $id = input::get("id");

        $db = DB::getInstance();

        $originalData = $db->get("rate, status, spadeEvoSkin", "clients", array(["id", "=", $id]))->first();

        if (!count($originalData)) {
            $data = json_encode(["response" => 4, "errors" => [], "token" => token::generate()]);
            print_r($data);
            exit();
        }


        $validate = new validate();
        $validation = $validate->check(
            $_POST,
            array(
                "partnerRate" => [
                    "pattern" => ["rule" => '/^[0-9]{1,2}$/', "msg" => 'Not a valid Rate number. MUST be a DECIMAL number from 0-99. Example : 45.30.'],
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
                "skinSelect" => [
                    "inclusion" => [
                        "list" => ["1", "2", "3", "4", "5"],
                        "msg" => "please select valid option from the purposed options list!"
                    ]
                ],

            )
        );
        if ($validation->passed()) {

            //edit client in db;

            $array = ["rate" => input::get("partnerRate"), "status" => input::get("status"), "spadeEvoSkin" => input::get("skinSelect")];

            $updated = $db->update('clients', [["id", "=", $id]], $array);
            if ($updated->error()) {
                $data = json_encode(["response" => 4, "errors" => [], "token" => token::generate()]);
                print_r($data);
                exit();
            }
            //update records to rate table if rate if updated
            if (input::get("partnerRate") != $originalData["rate"]) {
                $ratesBuilder = new Rates();
                $rates = $ratesBuilder->upsertCommissionsRates($parentPtId, $id, input::get("partnerRate"));
            }


            //log the action
            $log = new ActivityLogger();

            $action = "Client Edited";

            $diffArray = array_diff_assoc($array, $originalData);

            $details = "";
            foreach ($diffArray as $key => $value) {

                $originalValue = $originalData[$key];
                $details .= "--$key" . "[" . $originalValue . "]->[" . $value . "]";
            }

            if ($details != "") {
                $description = "The client has been edited. <<$details>>";
                $logged = $log->addLog($action, $description, $id);
            }



            //call function for showing the data on table without refreshing
            $data = json_encode(["response" => 1, "errors" => [], "token" => token::generate()]);
            print_r($data);
            exit();


        } else {
            //output errors & and inputs /use sessions or globals if in the same page
            $_GLOBALS["ERR"] = $validation->errors();
            $data = json_encode(["response" => 0, "errors" => $_GLOBALS["ERR"], "token" => token::generate()]);
            print_r($data);
            exit();

        }
    } else {
        $data = json_encode(["response" => 2, "errors" => [], "token" => token::generate()]);
        print_r($data);
        exit();
    }
} else {
    echo "unauthorized";
    // header('Location: /pages/errors/403.php');      
}
?>
