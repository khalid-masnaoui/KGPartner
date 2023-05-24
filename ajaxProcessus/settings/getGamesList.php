<?php
require_once __DIR__ . "/../../core/ini.php";
require_once __DIR__ . "/../../functions/sanitize.php";
require_once __DIR__ . "/../../functions/encryptDecrypt.php";



if (input::exists("post") && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' && isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] == "https://koreagaming.info/pages/settings/bet_limits.php") {

    if (token::check(input::get("token"), "get_games")) {

        $provider = input::get("provider");

        $db = DB::getInstance();

        $games = $db->get("game_code, game_name_kr,game_name_en", "games_list", [["product_id", "=", $provider]])->results();
        $gamesOptions = "";

        foreach ($games as $key => $value) {
            // $gamesOptions .= "<option value=" . $value['game_code'] . ">" . $value["game_name_kr"] . "(" . $value["game_code"] . ")" . "</option>";
            $name = $value["game_name_kr"];
            $name = $name == "" || $name == NULL ? $value["game_name_en"] : $name;
            $gamesOptions .= "<option value=" . $value['game_code'] . ">" . $name . "</option>";
        }

        $token = token::generate("get_games");
        print_r(json_encode([$gamesOptions, $token]));

    } else {
        $token = token::generate("get_games");
        print_r(json_encode([$token]));
    }


} else {
    echo "unauthorized";
    // header('Location: /pages/errors/403.php');      
}
















?>
