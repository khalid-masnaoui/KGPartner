<?php
require_once __DIR__ . "/../../../core/ini.php";

if (input::exists("post") && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' && isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] == "https://koreagaming.info/pages/member/accounts/mailbox.php") {

    if (token::check(input::get("token"), "mark_mails")) {


        $mailId = input::get("id");
        $mailId = trim($mailId);

        $response = 1;

        $MailBuilder = new Mails();

        $MailBuilder->marOneMailAsSeen($mailId);

        //check if there is still unSeen mail for the client
        $unSeenMails = $MailBuilder->checkUnseenMails();


        //check for unseen notifications -- for the account new badge
        $notificationBuilder = new Notifications();

        $unSeenNotifications = $notificationBuilder->checkUnseenNotifications();


        $token = token::generate("mark_mails");
        echo $response . "###" . $token . "###" . $unSeenMails . "###" . $unSeenNotifications;
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
