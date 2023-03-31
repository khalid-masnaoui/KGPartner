<?php
require_once __DIR__ . "/../../../core/ini.php";
require_once __DIR__ . "/../../../functions/randomString.php";
require_once __DIR__ . "/../../../functions/encryptDecrypt.php";


if (input::exists("post") && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' && isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] == "https://koreagaming.live/pages/member/accounts/mailbox.php") {

    if (isset($_GLOBALS["ERR"])) {
        unset($_GLOBALS["ERR"]);
    }

    if (token::check(input::get("token"), "reply_mails")) {

        $mail = input::get("mail");
        $mail = trim($mail);

        $mailPlain = input::get("mailPlain");
        $mailPlain = trim($mailPlain);

        $mailId = input::get("mailId");

        $dataWrapper = array(
            "replyText" => $mailPlain,
        );

        $validationRules = array(
            "replyText" => [
                "notEmpty" => true
            ],
        );


        $validate = new validate();
        $validation = $validate->check($dataWrapper, $validationRules);

        if ($validation->passed()) {


            //prepare log data
            $details = "--replyId[$mailId]";
            $action = "Mail Reply Sent";
            $description = "A Reply-Mail has been sent. <<$details>>";


            $mailsBuilder = new Mails();

            $replyMail = $mailsBuilder->replyMail($mailId, $mail, $action, $description);

            if (!$replyMail) {
                $data = json_encode(["response" => 4, "errors" => [], "token" => token::generate("reply_mails")]);
                print_r($data);
                exit();
            }

            $data = json_encode(["response" => 1, "errors" => [], "token" => token::generate("reply_mails")]);
            print_r($data);

        } else {
            $_GLOBALS["ERR"] = $validation->errors();
            $data = json_encode(["response" => 0, "errors" => $_GLOBALS["ERR"], "token" => token::generate("reply_mails")]);
            print_r($data);
            exit();
        }
    } else {
        $data = json_encode(["response" => 2, "errors" => [], "token" => token::generate("reply_mails")]);
        print_r($data);
        exit();



    }
} else {
    echo "unauthorized";
}

















?>
