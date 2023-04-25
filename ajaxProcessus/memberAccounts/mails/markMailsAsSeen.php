<?php
require_once __DIR__ . "/../../../core/ini.php";

if (input::exists("post") && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' && isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] == "https://koreagaming.info/pages/member/accounts/mailbox.php") {

    if (token::check(input::get("token"), "mark_mails")) {


        $data = input::get("checks");
        $data = array_map("trim", $data);

        $count = count($data);

        $holders = array_fill(0, $count, '?');
        $holders = implode(",", $holders);

        $response = 1;

        $db = DB::getInstance();

        $table = "partners_mailbox";

        $sql = "UPDATE {$table} SET status = 1 where id IN ($holders)";

        $action = $db->query($sql, $data);
        if ($action->error()) {
            $response = 3;
        }


        $token = token::generate("mark_mails");
        echo $response . "###" . $token;
        exit;
    } else {
        $token = token::generate("mark_mails");
        echo "0###" . $token;
        exit;
    }

} else {
    echo "unauthorized";
    // header('Location: /pages/errors/403.php');      
}

















?>
