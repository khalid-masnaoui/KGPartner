<?php
require_once __DIR__ . "/../../core/ini.php";
require_once __DIR__ . "/../../core/constants.php";
require_once __DIR__ . "/../../functions/randomString.php";
require_once __DIR__ . "/../../functions/encryptDecrypt.php";


if (input::exists("post") && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' && isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] == "https://koreagaming.info/pages/member/member_management/clients_list.php") {

    if (isset($_GLOBALS["ERR"])) {
        unset($_GLOBALS["ERR"]);
    }

    if (token::check(input::get(config::get("session/token_name")))) {

        $partner = new user();
        $parentPtId = $partner->data()["pt_id"];
        $parentRate = $partner->data()["rate"];

        $validate = new validate();
        $validation = $validate->check(
            $_POST,
            array(

                "username" => [
                    "unique" => "clients",
                    "pattern" => ["rule" => '/^.{1,30}$/', "msg" => '1~30자를 입력하세요.']
                ],
                "prefix" => [
                    "unique" => "clients",
                    "pattern" => ["rule" => '/^[A-Za-z][A-Za-z0-9]{1,8}$/', "msg" => '1~8자를 입력하세요. 영어 + 숫자를 조합할 수 있습니다.']
                ],
                "end_point" => [
                    "unique" => "clients",
                    "min" => 1,
                    "max" => 100,
                    "pattern" => ["rule" => '/^https?:\/\/(?:www\.)?[-a-zA-Z0-9@:%._\+~#=]{1,256}\.[a-zA-Z0-9()]{1,6}\b(?:[-a-zA-Z0-9()@:%_\+.~#?&\/=]*)$/', "msg" => ' "http(s)://" 형태로 입력해 주세요. ']
                ],

                "password" => [
                    "pattern" => ["rule" => '/^.{8,30}$/', "msg" => '8~30자를 입력하세요.']
                ],
                "name" => [
                    "pattern" => ["rule" => '/^.{1,20}$/', "msg" => ' 1~20자를 입력하세요.']
                ],
                "partnerRate" => [
                    "pattern" => ["rule" => '/^[0-9]{1,2}\.[0-9]{2}$/', "msg" => '본인의 요율보다 같거나 높게 입력해 주세요.'],
                    "notEmpty" => true,
                    "biggerThan" => 0,

                    //check that the partner rate is bigger than that of the parent
                    "RateBiggerOfPartner" => [
                        "parentRate" => $parentRate,
                    ],
                ],
                "skinSelect" => [
                    "inclusion" => [
                        "list" => ["1", "2", "3", "4", "5"],
                        "msg" => "please select valid option from the purposed options list!"
                    ]
                ],

            )
        );
        if ($validation->passed()) {
            //log the user

            $admin = new user();
            $admin_id = $admin->data()["id"];
            // print_r($login);exit;
            // 
            $db = DB::getInstance();

            $secret_key = '';
            $api_key = '';
            $visible_ak = '';

            //guarantee unicity of api_key
            $checking = true;
            while ($checking) {
                [$api_key, $secret_key, $visible_ak] = generateKeys();

                $data = $db->get("id", "clients", array(["api_key", "=", $api_key]));
                if (!$data->count()) {
                    break;
                }
            }

            $visible_ak = encrypt($visible_ak);
            $password = encrypt(input::get("password"));

            $hashed_password = password_hash(input::get("password"), PASSWORD_DEFAULT);


            $array = ["pt_id" => $parentPtId, "username" => input::get("username"), "password" => $hashed_password, "name" => input::get("name"), "end_point" => input::get("end_point"), "prefix" => input::get("prefix"), "status" => input::get("status"), "secret_key" => $secret_key, "api_key" => $api_key, 'raw_ak' => $visible_ak, "raw_ps" => $password, "rate" => input::get("partnerRate"), "spadeEvoSkin" => input::get("skinSelect")];

            $inserted = $db->insert('clients', $array);
            if ($inserted->error()) {
                $data = json_encode(["response" => 4, "errors" => [], "token" => token::generate()]);
                print_r($data);
                exit();
            }
            $clientId = $inserted->lastId();

            //insert records{0.00} into balance/deposit table 
            $inserted2 = $db->insert('clients_balance', ["client_id" => $clientId, "balance" => 0.00, "deposit" => 0.00]);

            //insert records to rate table
            $ratesBuilder = new Rates();
            $rates = $ratesBuilder->upsertCommissionsRates($parentPtId, $clientId, input::get("partnerRate"));

            //insert activated products
            $activatedProducts = config::get("providersProductIdMappings");
            $sql = "INSERT INTO client_products VALUES ";
            $queryParameters = [];

            foreach ($activatedProducts as $key => $value) {
                $sql .= " (?,?),";

                $queryParameters[] = $clientId;
                $queryParameters[] = $value;
            }
            // last commas deletion
            $sql = substr($sql, 0, -1);

            if ($activatedProducts != []) {
                $insertActivatedProducts = $db->query($sql, $queryParameters);
            }


            //generate tables with prefix name
            $prefix = input::get("prefix");
            shell_exec("sudo /var/www/html/admin/core/sql/automaticTableInsertion.sh $prefix");

            //log the action
            $log = new ActivityLogger();

            $action = "Client Added";
            $description = "A Client has been added";
            $logged = $log->addLog($action, $description, $clientId);

            //call function for showing the data on table without refreshing
            $data = json_encode(["response" => 1, "errors" => [], "token" => token::generate()]);
            print_r($data);

        } else {
            //output errors & and inputs /use sessions or globals if in the same page
            $_GLOBALS["ERR"] = $validation->errors();
            $data = json_encode(["response" => 0, "errors" => $_GLOBALS["ERR"], "token" => token::generate()]);
            print_r($data);
            exit();
        }
    } else {
        $data = json_encode(["response" => 2, "errors" => [], "token" => token::generate()]);
        print_r($data);
        exit();



    }
} else {
    echo "unauthorized";
    // header('Location: /pages/errors/403.php');      
}

function generateKeys()
{
    // $status=input::get("status");
    $visible_sk = generateRandomString(20);
    $visible_ak = generateRandomString(20);

    $secret_key = hash::make($visible_sk, API_WA_SECRET_KEY_SALT);
    $api_key = hash::make($visible_ak, API_WA_KEY_SALT);

    return [$api_key, $secret_key, $visible_ak];
}

















?>
