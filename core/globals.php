<?php

$GLOBALS["config"] = array(
    "mysql" => array(
        "host" => "localhost",
        "username" => "wauser",
        "password" => "k1sol#U24u43wa",
        "db" => "k1api",
        "charset" => 'utf8'

    ),

    "remember" => array(

        "cookie_name" => "hash",
        "cookie_exp" => 604800 //a month
    ),

    "session" => array(
        "login_name" => "partner",
        "token_name" => "token",
        //crsf
        "role_name" => "role"

    ),
    "ptSettings" => array(
        "givenPtValue" => 87
    ),
    "display" => array(
        "activeNumber" => 20,
        "casinos" => array(
            "evo" => "",
            "pp" => "",
            "vivo" => "",
            "cq9" => "",
            "popok" => "",
            "mg" => "",
            "og" => "",
            "ag" => "",
            "bg" => "",
            "dg" => "",
            "ez" => "",
            "bota" => "",
            "dw" => "",
            "ts" => "",
            "wm" => "",
        ),
        "slots" => array(
            "cq9 slot" => "",
            "ps" => "",
            "netent" => "",
            "redtiger" => "",
            "nlc" => "",
            "btg" => "",
            "pp slot" => "",
            "ygg" => "",
            "popok slot" => "",
            "rk" => "",
            "bp" => "",
            "dr" => "",
            "els" => "",
            "mg slot" => "",
            "qs" => "",
            "rx" => "",
            "rr" => "",
            "sh" => "",
            "ns" => "",
            "ng" => "",
            "ga" => "",
            "ftg" => "",
            "ap" => "",
            "bs" => "",
            "png" => "",
            "hs" => "",
            "aux" => "",
            "bog" => "",
            "psn" => "",
            "pgs" => "",
            "hb" => "",
            "gmw" => "",
            "ag slot" => "",
            "sw" => "",
            "upg" => ""
        ),
        "activeProviders" => array()
    ),
    "providersNameMappings" => array(
        "evo" => "Evolution",
        "pp" => "Pragmatic Play",
        "vivo" => "VIVO Gaming",
        "cq9" => "CQ9",
        "popok" => "POPOK",
        "mg" => "Micro Gaming",
        "og" => "Oriental Game",
        "ag" => "Asia Gaming",
        "bg" => "Big Gaming",
        "dg" => "Dream Gaming",
        "ez" => "Ezugi Gaming",
        "bota" => "Bota Gaming",
        "dw" => "Dowinn Gaming",
        "ts" => "Taishan Gaming",
        "wm" => "World Match",
        "cq9 slot" => "CQ9 Slot",
        "ps" => "PlayStar",
        "netent" => "NetEnt",
        "redtiger" => "RedTiger",
        "nlc" => "No Limit City",
        "btg" => "Big Time Gaming",
        "pp slot" => "Pragmatic Play Slot",
        "ygg" => "Yggdrasil",
        "popok slot" => "POPOK Slot",
        "rk" => "Reel Kingdom Slot",
        "bp" => "Blue Print Slot",
        "dr" => "Dragoon Slot",
        "els" => "Elysium Slot",
        "mg slot" => "Micro Gaming Slot",
        "qs" => "Quick Spin Slot",
        "rx" => "Relax Slot",
        "rr" => "Red Rake Slot",
        "sh" => "Spear Head Slot",
        "ns" => "Next Spin Slot",
        "ng" => "Net Gaming Slot",
        "ga" => "Game Art Slot",
        "ftg" => "FunTa Gaming Slot",
        "ap" => "Aspect Slot",
        "bs" => "Bet Soft Slot",
        "png" => "Png Slot",
        "hs" => "Hs Slot",
        "aux" => "Aux Slot",
        "bog" => "Boongo Slot",
        "psn" => "PlaySon Slot",
        "pgs" => "Pg Soft Slot",
        "hb" => "Habanero Slot",
        "gmw" => "Game Media Works Slot",
        "ag slot" => "Asian Gaming Slot",
        "sw" => "SkyWind Slot",
        "upg" => "Ultimate Play Gaming Slot"

    ),
    "providersProductIdMappings" => array(
        "evo" => 1,
        "pp" => 2,
        "vivo" => 3,
        "cq9" => 4,
        "popok" => 6,
        "mg" => 7,
        "og" => 8,
        "ag" => 9,
        "bg" => 10,
        "dg" => 11,
        "ez" => 12,
        "bota" => 13,
        "dw" => 14,
        "ts" => 15,
        "wm" => 16,
        "cq9 slot" => 100,
        "ps" => 101,
        "netent" => 102,
        "redtiger" => 103,
        "nlc" => 104,
        "btg" => 105,
        "pp slot" => 106,
        "ygg" => 107,
        "popok slot" => 108,
        "rk" => 109,
        "bp" => 110,
        "dr" => 111,
        "els" => 112,
        "mg slot" => 113,
        "qs" => 114,
        "rx" => 115,
        "rr" => 116,
        "sh" => 117,
        "ns" => 118,
        "ng" => 119,
        "ga" => 120,
        "ftg" => 121,
        "ap" => 122,
        "bs" => 123,
        "png" => 124,
        "hs" => 125,
        "aux" => 126,
        "bog" => 127,
        "psn" => 128,
        "pgs" => 129,
        "hb" => 130,
        "gmw" => 131,
        "ag slot" => 132,
        "sw" => 133,
        "upg" => 134


    ),

    "serviceConfig" => array(
        "companyWinPercentage" => 0.15,
    ),

    "logs" => array(
        "partners" => array(
            //password
            1 => array("display" => "Change Password", "query" => "Password Edited"),

            //alias
            2 => array("display" => "Create Alias", "query" => "Alias Added"),
            3 => array("display" => "Update Alias", "query" => "Alias Edited"),
            4 => array("display" => "Delete Alias", "query" => "Alias Removed"),

            //logging
            5 => array("display" => "Log In", "query" => "Login"),
            6 => array("display" => "Log Out", "query" => "Logout"),
            7 => array("display" => "Failed Log In", "query" => "Login Failed"),

            //client
            8 => array("display" => "Add Client", "query" => "Client Added"),
            9 => array("display" => "Update Client", "query" => "Client Edited"),
            10 => array("display" => "Delete Client", "query" => "Client Removed"),

            //partner
            11 => array("display" => "Add Partner", "query" => "Partner Added"),
            12 => array("display" => "Update Partner", "query" => "Partner Edited"),
            13 => array("display" => "Delete Partner", "query" => "Partner Removed"),

            //mail reply
            14 => array("display" => "Send Reply Mail", "query" => "Mail Reply Sent"),

            //excel exported
            15 => array("display" => "Export Excel", "query" => "Excel Exported"),
        )
    )
);

//add genetics activeProviders config
$activeProviders = [];
foreach ($GLOBALS["config"]["display"]["casinos"] as $key => $value) {
    $value === "" ? array_push($activeProviders, $key) : '';
}
foreach ($GLOBALS["config"]["display"]["slots"] as $key => $value) {
    $value === "" ? array_push($activeProviders, $key) : '';
}
$GLOBALS["config"]["display"]["activeProviders"] = $activeProviders;
