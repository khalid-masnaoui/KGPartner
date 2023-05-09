<?php
require_once __DIR__ . "/../../core/ini.php";
require_once __DIR__ . "/../../core/constants.php";
require_once __DIR__ . "/../../functions/randomString.php";
require_once __DIR__ . "/../../core/mysqli_conn.php";


if (input::exists("post") && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' && isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] == "https://koreagaming.info/pages/member/member_management/clients_list.php") {

    if (isset($_GLOBALS["ERR"])) {
        unset($_GLOBALS["ERR"]);
    }

    if (token::check(input::get(config::get("session/token_name")))) {

        $partner = new user();
        $parentPtId = $partner->data()["pt_id"];
        $parentRate = $partner->data()["rate"];

        $id = input::get("id");

        $db = DB::getInstance();

        $originalData = $db->get("rate, status, spadeEvoSkin, prefix, DgSkin, DwSkin, WmSkin, OrSkin, AgSkin, BgSkin", "clients", array(["id", "=", $id]))->first();

        if (!count($originalData)) {
            $data = json_encode(["response" => 4, "errors" => [], "token" => token::generate()]);
            print_r($data);
            exit();
        }


        $validate = new validate();
        if (!isset($_POST["activatedProducts"]) || $_POST["activatedProducts"] == null) {
            $_POST["activatedProducts"] = array();
        }



        $validation = $validate->check(
            $_POST,
            array(
                "partnerRate" => [
                    "pattern" => ["rule" => '/^[0-9]{1,2}\.[0-9]{2}$/', "msg" => '본인의 요율보다 같거나 높게 입력해 주세요.'],
                    "notEmpty" => true,
                    "biggerThan" => 0,

                    //check that the partner rate is bigger than that of the parent
                    "RateBiggerOfPartner" => [
                        "parentRate" => $parentRate,
                    ],
                ],
                "status" => [
                    "inclusion" => [
                        "list" => ["1", "0", "3"],
                        "msg" => "please select valid option from the purposed option!"
                    ]
                ],
                "skinSelect" => [
                    "inclusion" => [
                        "list" => ["1", "2", "3", "4", "5"],
                        "msg" => "please select valid option from the purposed options list!"
                    ]
                ],
                "skinSelectDG" => [
                    "inclusion" => [
                        "list" => ["1", "2", "3", "4", "5"],
                        "msg" => "please select valid option from the purposed options list!"
                    ]
                ],
                "skinSelectDW" => [
                    "inclusion" => [
                        "list" => ["1", "2", "3", "4", "5", "6", "7"],
                        "msg" => "please select valid option from the purposed options list!"
                    ]
                ],
                "skinSelectWM" => [
                    "inclusion" => [
                        "list" => ["1", "2", "3", "4", "5", "6", "7"],
                        "msg" => "please select valid option from the purposed options list!"
                    ]
                ],
                "skinSelectOR" => [
                    "inclusion" => [
                        "list" => ["1", "2", "3", "4", "5", "6", "7"],
                        "msg" => "please select valid option from the purposed options list!"
                    ]
                ],
                "skinSelectAG" => [
                    "inclusion" => [
                        "list" => ["1", "2"],
                        "msg" => "please select valid option from the purposed options list!"
                    ]
                ],
                "skinSelectBG" => [
                    "inclusion" => [
                        "list" => ["1", "2", "3", "4", "5", "6"],
                        "msg" => "please select valid option from the purposed options list!"
                    ]
                ],
                "activatedProducts" => [
                    "arrayIncludes" => [
                        "list" => array_values(config::get("providersProductIdMappings")),
                        "msg" => "Some ACtivated products are not valid!."
                    ]
                ],

            )
        );
        if ($validation->passed()) {

            //edit client in db;

            $array = ["rate" => input::get("partnerRate"), "status" => input::get("status"), "spadeEvoSkin" => input::get("skinSelect"), "DgSkin" => input::get("skinSelectDG"), "DwSkin" => input::get("skinSelectDW"), "WmSkin" => input::get("skinSelectWM"), "OrSkin" => input::get("skinSelectOR"), "AgSkin" => input::get("skinSelectAG"), "BgSkin" => input::get("skinSelectBG")];

            $updated = $db->update('clients', [["id", "=", $id]], $array);
            if ($updated->error()) {
                $data = json_encode(["response" => 4, "errors" => [], "token" => token::generate()]);
                print_r($data);
                exit();
            }

            //update client in resource server
            $stmt = $conn2->prepare("UPDATE clients set status = ? WHERE prefix = ?");
            $stmt->bind_param("is", $array["status"], $originalData["prefix"]);
            $stmt->execute();
            $stmt->close();
            $conn2->close();

            //update records to rate table if rate if updated
            if (input::get("partnerRate") != $originalData["rate"]) {
                $ratesBuilder = new Rates();
                $rates = $ratesBuilder->upsertCommissionsRates($parentPtId, $id, input::get("partnerRate"));
            }


            //insert activated products
            //-->first we delete client existed activated products
            $activatedProductsOriginalValues = $db->get("product_id", "client_products", [["client_id", "=", "$id"]])->results();
            $db->delete("client_products", [["client_id", "=", "$id"]]);

            $activatedProducts = input::get("activatedProducts");
            $sql = "INSERT INTO client_products VALUES ";
            $queryParameters = [];

            foreach ($activatedProducts as $key => $value) {
                $sql .= " (?,?),";

                $queryParameters[] = $id;
                $queryParameters[] = $value;
            }
            // last commas deletion
            $sql = substr($sql, 0, -1);

            if ($activatedProducts != []) {
                $insertActivatedProducts = $db->query($sql, $queryParameters);
            }

            //log the action
            $log = new ActivityLogger();

            $action = "Client Edited";

            unset($originalData["prefix"]);
            $diffArray = array_diff_assoc($array, $originalData);

            $details = "";
            foreach ($diffArray as $key => $value) {

                $originalValue = $originalData[$key];
                $details .= "--$key" . "[" . $originalValue . "]->[" . $value . "]";
            }

            //activation of products
            $activatedProductsOriginalValues = array_map(function ($el) {
                return $el["product_id"];
            }, $activatedProductsOriginalValues);

            if (!compareIsEqualArray($activatedProductsOriginalValues, $activatedProducts)) {
                $details .= "--activatedProducts" . "[" . json_encode($activatedProductsOriginalValues) . "]->[" . json_encode($activatedProducts) . "]";
            }

            if ($details != "") {
                $description = "The client has been edited. <<$details>>";
                $logged = $log->addLog($action, $description, $id);
            }



            //call function for showing the data on table without refreshing
            $data = json_encode(["response" => 1, "errors" => [], "token" => token::generate()]);
            print_r($data);
            exit();


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

function compareIsEqualArray(array $array1, array $array2): bool
{
    return (array_diff($array1, $array2) == [] && array_diff($array2, $array1) == []);
}
?>
