<?php
require_once __DIR__ . "/../../core/ini.php";



if (input::exists("post") && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' && isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] == "https://koreagaming.info/pages/infos/deposit_withdraw/deposit_withdraw_transaction.php") {

    if (token::check(input::get("token"), "delete_deposit")) {

        $depositId = input::get("id");


        //prepare log data
        $action = "Deposit Removed";
        $description = "A Deposit [$depositId] has been Removed";


        $depositBuilder = new Deposits();

        $removedDeposit = $depositBuilder->deleteDeposit($depositId, $action, $description);

        $response = 1;
        if (!$removedDeposit) {
            $response = 0;
        }

        $token = token::generate("delete_deposit");

        echo $response . "###" . $token;
        exit;

    } else {
        $token = token::generate("delete_deposit");
        echo "0###" . $token;
        exit;

    }

} else {
    echo "unauthorized";
    // header('Location: /pages/errors/403.php');      
}

















?>
