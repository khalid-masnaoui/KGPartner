<?php
require_once __DIR__ . "/../../core/ini.php";
require_once __DIR__ . "/../../functions/randomString.php";
require_once __DIR__ . "/../../functions/encryptDecrypt.php";


if (input::exists("post") && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' && isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] == "https://koreagaming.info/pages/settings/bet_limits.php") {

    if (isset($_GLOBALS["ERR"])) {
        unset($_GLOBALS["ERR"]);
    }

    if (token::check(input::get(config::get("session/token_name")), "edit_limits")) {

        $limitAmount = input::get("limitAmount");
        $limitId = input::get("limitId");

        $validate = new validate();
        $validation = $validate->check(
            $_POST,
            array(
                "limitAmount" => [
                    "pattern" => ["rule" => '/^[0-9]+\.[0-9]{2}$/', "msg" => 'Field is required and should have correct format!.'],
                    "biggerThan" => 0
                ]
            )
        );
        if ($validation->passed()) {

            $partner = new user();
            $partnerId = $partner->data()["id"];

            $db = DB::getInstance();

            $originalData = $db->get("max_amount, operator", "bet_limits", array(["id", "=", $limitId]))->first();

            if (!count($originalData)) {
                $data = json_encode(["response" => 4, "errors" => [], "token" => token::generate("edit_limits")]);
                print_r($data);
                exit();
            }

            //sql data
            $dataArray = array();
            $dataArray["max_amount"] = $limitAmount;
            $dataArray["operator"] = "p:$partnerId";

            $updated = $db->update('bet_limits', [["id", "=", $limitId]], $dataArray);
            if ($updated->error()) {
                $data = json_encode(["response" => 4, "errors" => [], "token" => token::generate("edit_limits")]);
                print_r($data);
                exit();
            }


            //log the action
            $log = new ActivityLogger();

            $diffArray = array_diff_assoc($dataArray, $originalData);

            $details = "";
            foreach ($diffArray as $key => $value) {

                $originalValue = $originalData[$key];
                $details .= "--$key" . "[" . $originalValue . "]->[" . $value . "]";
            }

            if ($details != "") {
                $action = "Bet-Limit Updated";
                $description = "The Bet-Limit has been updated. <<$details>>";
                $logged = $log->addLog($action, $description, $limitId);
            }


            //call function for showing the data on table without refreshing
            $data = json_encode(["response" => 1, "errors" => [], "token" => token::generate("edit_limits")]);
            print_r($data);


        } else {
            //output errors & and inputs /use sessions or globals if in the same page
            $_GLOBALS["ERR"] = $validation->errors();
            $data = json_encode(["response" => 0, "errors" => $_GLOBALS["ERR"], "token" => token::generate("edit_limits")]);
            print_r($data);
            exit();


        }
    } else {
        $data = json_encode(["response" => 2, "errors" => [], "token" => token::generate("edit_limits")]);
        print_r($data);
        exit();
    }
} else {
    echo "unauthorized";
    // header('Location: /pages/errors/403.php');      
}


















?>
