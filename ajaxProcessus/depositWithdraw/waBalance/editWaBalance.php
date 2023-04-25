<?php
require_once __DIR__ . "/../../../core/ini.php";
require_once __DIR__ . "/../../../functions/sanitize.php";
require_once __DIR__ . "/../../../functions/encryptDecrypt.php";

// require_once __DIR__ . "/../../../includes/partials/_super_admin_exclusive_access_ajax.php";

exit;

if (input::exists("post") && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' && isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] == "https://koreagaming.info/pages/infos/deposit_withdraw/wa_balance_transaction.php") {

    if (isset($_GLOBALS["ERR"])) {
        unset($_GLOBALS["ERR"]);
    }

    if (token::check(input::get(config::get("session/token_name")), "edit_waBalance")) {

        $partner = new user();
        $partnerId = $partner->data()["id"];

        $depositId = input::get("depositId");
        $depositAmount = input::get("depositAmount");

        $db = DB::getInstance();

        //get deposit data
        $sql = "SELECT d.amount  FROM wa_balance_deposits d WHERE d.id = ?";
        $depositData = $db->query($sql, [$depositId])->first();

        $oldDeposit = $depositData["amount"];
        $newDeposit = $depositAmount;

        $diffToAdd = $newDeposit - $oldDeposit;

        $enoughBalance = 1;
        if ($partner->data()["wa_balance"] < $diffToAdd) {
            $enoughBalance = 0;
        }

        $validate = new validate();
        $validation = $validate->check(
            $_POST,
            array(

                "depositAmount" => [
                    "pattern" => ["rule" => '/^[0-9]+$/', "msg" => 'Field is required and should have correct format!.'],
                    "biggerThan" => 0,
                ]

            )
        );
        if ($validation->passed() && $enoughBalance == 1) {

            //logs data
            $action = "WaBalance Deposit Edited";
            $description = "A WaBalance Deposit [$depositId] has been edited";


            $depositBuilder = new Deposits();

            $updateDeposit = $depositBuilder->updateWaBalanceDeposit($depositId, $depositAmount, $action, $description);

            if ($updateDeposit === false) {
                $data = json_encode(["response" => 4, "errors" => [], "token" => token::generate("edit_waBalance")]);
                print_r($data);
                exit();
            }


            //call function for showing the data on table without refreshing
            $data = json_encode(["response" => 1, "errors" => [], "token" => token::generate("edit_waBalance")]);
            print_r($data);


        } else {
            //output errors & and inputs /use sessions or globals if in the same page
            $_GLOBALS["ERR"] = $validation->errors();
            if ($_GLOBALS["ERR"] == []) {
                $_GLOBALS["ERR"] = ["depositAmount" => "depositAmount must be less than or equal" . $partner->data()["wa_balance"] . "value"];
            }
            $data = json_encode(["response" => 0, "errors" => $_GLOBALS["ERR"], "token" => token::generate("edit_waBalance")]);
            print_r($data);
            exit();


        }
    } else {
        $data = json_encode(["response" => 2, "errors" => [], "token" => token::generate("edit_waBalance")]);
        print_r($data);
        exit();
    }
} else {
    echo "unauthorized";
    // header('Location: /pages/errors/403.php');      
}


















?>
