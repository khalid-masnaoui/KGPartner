<?php
require_once __DIR__ . "/../../core/ini.php";
require_once __DIR__ . "/../../functions/sanitize.php";
require_once __DIR__ . "/../../functions/encryptDecrypt.php";

// require_once __DIR__ . "/../../../includes/partials/_super_admin_exclusive_access_ajax.php";



if (input::exists("post") && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' && isset($_SERVER['HTTP_REFERER']) && ($_SERVER['HTTP_REFERER'] == "https://koreagaming.info/pages/infos/deposit_withdraw/deposit_withdraw_transaction.php" || $_SERVER['HTTP_REFERER'] == "https://koreagaming.info/pages/member/member_management/clients_list.php")) {

    if (isset($_GLOBALS["ERR"])) {
        unset($_GLOBALS["ERR"]);
    }

    if (token::check(input::get(config::get("session/token_name")), "deduct")) {

        $depositor = input::get("client");
        $deductAmount = input::get("depositAmount");

        $db = DB::getInstance();

        //get deposit data
        $sql = "SELECT balance  FROM clients_balance WHERE client_id = ?";
        $depositData = $db->query($sql, [$depositor])->first();

        $totalPoints = $depositData["balance"];
        $deductPoints = $deductAmount;

        $enoughBalance = 1;
        if ($totalPoints < $deductPoints) {
            $enoughBalance = 0;
        }



        $validate = new validate();
        $validation = $validate->check(
            $_POST,
            array(

                "depositAmount" => [
                    "pattern" => ["rule" => '/^[0-9]+$/', "msg" => 'Field is required and should have correct format!.'],
                    "biggerThan" => 0
                ]

            )
        );
        if ($validation->passed() && $enoughBalance == 1) {

            //sql data
            $dataArray = array();
            $dataArray["amount"] = -$deductAmount;
            $dataArray["client_id"] = $depositor;

            //logs data
            $action = "Deducted";
            $description = "Deduction";


            $depositBuilder = new Deposits();

            $makeDeposit = $depositBuilder->makeDeposit($dataArray, $action, $description, $depositor, 5);

            if ($makeDeposit === false) {
                $data = json_encode(["response" => 4, "errors" => [], "token" => token::generate("deduct")]);
                print_r($data);
                exit();
            }


            //call function for showing the data on table without refreshing
            $data = json_encode(["response" => 1, "errors" => [], "token" => token::generate("deduct")]);
            print_r($data);


        } else {
            //output errors & and inputs /use sessions or globals if in the same page
            $_GLOBALS["ERR"] = $validation->errors();
            if ($_GLOBALS["ERR"] == []) {
                $_GLOBALS["ERR"] = ["depositAmount" => "deductAmount must be less than or equal than partner Game-Credit ( =" . $totalPoints . " ) value"];
            }
            $data = json_encode(["response" => 0, "errors" => $_GLOBALS["ERR"], "token" => token::generate("deduct")]);
            print_r($data);
            exit();


        }
    } else {
        $data = json_encode(["response" => 2, "errors" => [], "token" => token::generate("deduct")]);
        print_r($data);
        exit();
    }
} else {
    echo "unauthorized";
    // header('Location: /pages/errors/403.php');      
}


















?>
