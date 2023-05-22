<?php
require_once __DIR__ . "/../../core/ini.php";


if (input::exists("post") && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' && isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] == "https://koreagaming.info/pages/settings/bet_limits.php") {

    if (token::check(input::get("token"), "delete_limits")) {

        $limitId = input::get("id");

        $db = DB::getInstance();

        //delete client
        $action = $db->delete("bet_limits", [["id", "=", $limitId]]);

        $response = 1;
        if ($action->error()) {
            $response = 0;
        }

        //log the action
        $log = new ActivityLogger();

        $action = "Bet-Limit Removed";
        $description = "The Bet-Limit has been removed";
        $logged = $log->addLog($action, $description, $limitId);

        $token = token::generate("delete_limits");

        echo $response . "###" . $token;
        exit;

    } else {
        $token = token::generate("delete_limits");
        echo "0###" . $token;
        exit;

    }

} else {
    echo "unauthorized";
    // header('Location: /pages/errors/403.php');      
}

















?>
