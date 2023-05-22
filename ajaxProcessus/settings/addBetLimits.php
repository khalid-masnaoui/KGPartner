<?php
require_once __DIR__ . "/../../core/ini.php";
require_once __DIR__ . "/../../functions/randomString.php";
require_once __DIR__ . "/../../functions/encryptDecrypt.php";


if (input::exists("post") && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' && isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] == "https://koreagaming.info/pages/settings/bet_limits.php") {

    if (isset($_GLOBALS["ERR"])) {
        unset($_GLOBALS["ERR"]);
    }

    if (token::check(input::get(config::get("session/token_name")), "add_limits")) {

        $clientId = input::get("client");
        $providerId = input::get("provider");
        $gameId = input::get("gameId");
        $limitAmount = input::get("limitAmount");

        $validate = new validate();
        $validation = $validate->check(
            $_POST,
            array(
                "client" => [
                    "exclusion" => [
                        "list" => ["all"],
                        "msg" => "Please Select a valid client"
                    ]
                ],

                "provider" => [
                    "exclusion" => [
                        "list" => ["all"],
                        "msg" => "Please Select a valid product"
                    ]
                ],

                // "gameId" => [
                //     "exclusion" => [
                //         "list" => ["all"],
                //         "msg" => "Please Select a valid gameId"
                //     ]
                // ],

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

            //sql data
            $dataArray = array();
            $dataArray["client_id"] = $clientId;
            $dataArray["product_id"] = $providerId;
            $dataArray["game_code"] = $gameId;
            $dataArray["max_amount"] = $limitAmount;
            $dataArray["operator"] = "p:$partnerId";

            // $inserted = $db->insert('bet_limits', $dataArray);
            $inserted = $db->query("INSERT INTO bet_limits (client_id, product_id, game_code, max_amount, operator) VALUES (?,?,?,?,?) ON DUPLICATE KEY UPDATE max_amount = VALUES(max_amount), operator = VALUES(operator)", [$clientId, $providerId, $gameId, $limitAmount, "p:$partnerId"]);
            if ($inserted->error()) {
                $data = json_encode(["response" => 4, "errors" => [], "token" => token::generate("add_limits")]);
                print_r($data);
                exit();
            }

            //log the action
            $log = new ActivityLogger();

            $action = "Bet-Limit Added";
            $description = "A Bet-Limit has been added";
            $logged = $log->addLog($action, $description, $clientId);


            //call function for showing the data on table without refreshing
            $data = json_encode(["response" => 1, "errors" => [], "token" => token::generate("add_limits")]);
            print_r($data);


        } else {
            //output errors & and inputs /use sessions or globals if in the same page
            $_GLOBALS["ERR"] = $validation->errors();
            $data = json_encode(["response" => 0, "errors" => $_GLOBALS["ERR"], "token" => token::generate("add_limits")]);
            print_r($data);
            exit();


        }
    } else {
        $data = json_encode(["response" => 2, "errors" => [], "token" => token::generate("add_limits")]);
        print_r($data);
        exit();
    }
} else {
    echo "unauthorized";
    // header('Location: /pages/errors/403.php');      
}


















?>
