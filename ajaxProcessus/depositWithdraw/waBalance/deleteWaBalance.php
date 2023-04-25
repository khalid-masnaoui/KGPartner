<?php
require_once __DIR__ . "/../../../core/ini.php";


// require_once __DIR__ . "/../../../includes/partials/_super_admin_exclusive_access_ajax.php";


if (input::exists("post") && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' && isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] == "https://koreagaming.info/pages/infos/deposit_withdraw/wa_balance_transaction.php") {

    if (token::check(input::get("token"), "delete_waBalance")) {

        $depositId = input::get("id");


        //prepare log data
        $action = "Wa Balance Deposit Removed";
        $description = "A Wa Balance Deposit [$depositId] has been Removed";


        $depositBuilder = new Deposits();

        $removedDeposit = $depositBuilder->deleteWaBalanceDeposit($depositId, $action, $description);

        $response = 1;
        if (!$removedDeposit) {
            $response = 0;
        }

        $token = token::generate("delete_waBalance");

        echo $response . "###" . $token;
        exit;

    } else {
        $token = token::generate("delete_waBalance");
        echo "0###" . $token;
        exit;

    }

} else {
    echo "unauthorized";
    // header('Location: /pages/errors/403.php');      
}

















?>
