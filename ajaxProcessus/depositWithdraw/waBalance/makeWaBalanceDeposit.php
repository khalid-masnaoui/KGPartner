<?php
require_once __DIR__ . "/../../../core/ini.php";
require_once __DIR__ . "/../../../functions/sanitize.php";
require_once __DIR__ . "/../../../functions/encryptDecrypt.php";

// require_once __DIR__ . "/../../../includes/partials/_super_admin_exclusive_access_ajax.php";


if (input::exists("post") && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' && isset($_SERVER['HTTP_REFERER']) && ($_SERVER['HTTP_REFERER'] == "https://koreagaming.live/pages/infos/deposit_withdraw/wa_balance_transaction.php" || $_SERVER['HTTP_REFERER'] == "https://koreagaming.live/pages/member/member_management/partners_list.php")) {

    if (isset($_GLOBALS["ERR"])) {
        unset($_GLOBALS["ERR"]);
    }

    if (token::check(input::get(config::get("session/token_name")), "make_waBalance_transaction")) {

        $partner = new user();
        $partnerId = $partner->data()["id"];

        $depositor = input::get("client");
        $depositAmount = input::get("depositAmount");

        $validate = new validate();
        $validation = $validate->check(
            $_POST,
            array(

                "depositAmount" => [
                    "pattern" => ["rule" => '/^[0-9]+$/', "msg" => 'Field is required and should have correct format!.'],
                    "biggerThan" => 0,
                    "lessThanOrEqual" => $partner->data()["wa_balance"],
                ]

            )
        );
        if ($validation->passed()) {

            //sql data
            $dataArray = array();
            $dataArray["amount"] = $depositAmount;
            $dataArray["partner_id"] = $depositor;

            //logs data
            $action = "WaBalance Deposit Added";
            $description = "A WaBalance Deposit has been added";


            $depositBuilder = new Deposits();

            $makeDeposit = $depositBuilder->makeWaBalanceDeposit($dataArray, $action, $description, $depositor);

            if ($makeDeposit === false) {
                $data = json_encode(["response" => 4, "errors" => [], "token" => token::generate("make_waBalance_transaction")]);
                print_r($data);
                exit();
            }


            //call function for showing the data on table without refreshing
            $data = json_encode(["response" => 1, "errors" => [], "token" => token::generate("make_waBalance_transaction")]);
            print_r($data);


        } else {
            //output errors & and inputs /use sessions or globals if in the same page
            $_GLOBALS["ERR"] = $validation->errors();
            $data = json_encode(["response" => 0, "errors" => $_GLOBALS["ERR"], "token" => token::generate("make_waBalance_transaction")]);
            print_r($data);
            exit();


        }
    } else {
        $data = json_encode(["response" => 2, "errors" => [], "token" => token::generate("make_waBalance_transaction")]);
        print_r($data);
        exit();
    }
} else {
    echo "unauthorized";
    // header('Location: /pages/errors/403.php');      
}


















?>
