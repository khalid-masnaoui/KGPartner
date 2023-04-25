<?php
require_once __DIR__ . "/../../../core/ini.php";
require_once __DIR__ . "/../../../functions/sanitize.php";
require_once __DIR__ . "/../../../functions/encryptDecrypt.php";
require_once __DIR__ . "/../../../vendor/autoload.php";


if (input::exists("post") && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' && isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] == "https://koreagaming.info/pages/member/accounts/mailbox.php") {

    if (token::check(input::get("token"), "display_reply_mails")) {

        $mailId = input::get("id");

        $partner = new user();
        $partnerId = $partner->data()["id"];


        $mailsBuilder = new Mails();

        $repliesData = $mailsBuilder->getPreviousReplies($mailId, $partnerId);


        $tableBody = '';
        $i = 0;

        $purifier = new HTMLPurifier();

        foreach ($repliesData as $key => $value) {
            $i++;

            //purify the message --> we need to display the html
            $value["message"] = $purifier->purify($value["message"]);

            if ($i != 1) { // not the first iteration
                $tableBody .= '<hr>';
            }

            $tableBody .= '<div class="card-body bg-light">
                                <div style="margin-left:20px">';

            $tableBody .= '<div class="replyMessages">' . $value["message"] . '</div>';
            $tableBody .= '</div> <p class="card-subtitle"> Date : <small class="replyDates" style="color: black;font-weight: bold">' . $value["created_at"] . '</small>';
            $tableBody .= '</div>';

        }


        if ($tableBody == '') {
            $tableBody = '<div> No Previous Replies </div>';
        } else {
            $tableBody = '<div class="mb-2">Previous Replies</div>' . $tableBody;
        }

        $tableBody .= '<hr>';

        $token = token::generate("display_reply_mails");
        print_r(json_encode([$tableBody, $token]));

    } else {
        $token = token::generate("display_reply_mails");
        print_r(json_encode([$token]));
    }

} else {
    echo "unauthorized";
    // header('Location: /pages/errors/403.php');      
}
