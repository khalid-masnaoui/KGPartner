<?php
require_once __DIR__ . "/../../core/ini.php";
require_once __DIR__ . "/../../functions/sanitize.php";
require_once __DIR__ . "/../../functions/encryptDecrypt.php";



if (input::exists("post") && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' && isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] == "https://koreagaming.info/pages/settings/bet_limits.php") {

    if (token::check(input::get("token"), "display_limits")) {

        $partner = new user();
        $partnerPtId = $partner->data()["pt_id"];

        $activePage = input::get("page");
        $activeNumber = input::get("number");
        $activePage = ($activePage == 0 || $activePage == '') ? 1 : $activePage;

        $limit = $activeNumber;
        $offset = ($activePage - 1) * $activeNumber;

        $productsIds = config::get("providersProductIdMappings");
        $productsNames = config::get("providersNameMappings");

        //we will construct the logic based on the provider --> which table , naming conventions..
        $provider = input::get("provider");
        $category = input::get("category");
        $gameId = input::get("gameId");
        $clientId = input::get("client");


        $filerQuery = "";
        $queryParameters = [];


        $filerQuery = " AND  (cl.pt_id = ? OR cl.pt_id REGEXP ?)";
        $queryParameters[] = "$partnerPtId";
        $queryParameters[] = "^$partnerPtId/[0-9/]*$";

        if ($clientId != "all") {
            $filerQuery .= " AND bl.client_id = ? ";
            $queryParameters[] = $clientId;
        }

        if ($provider != "all") {
            $filerQuery .= " AND bl.product_id = ? ";
            $queryParameters[] = $provider;
        }

        if ($gameId != "all") {
            $filerQuery .= " AND bl.game_code = ? ";
            $queryParameters[] = $gameId;
        }

        $db = DB::getInstance();

        $games = $db->query("SELECT bl.*, gl.game_name_en, gl.game_name_kr, cl.username FROM bet_limits bl LEFT JOIN clients cl ON bl.client_id = cl.id LEFT JOIN games_list gl ON bl.game_code = gl.game_code AND bl.product_id = gl.product_id WHERE 1=1 $filerQuery ORDER BY id DESC LIMIT $limit OFFSET $offset", $queryParameters)->results();


        $tableBody = '';

        $i = ($activePage - 1) * $activeNumber;
        foreach ($games as $key => $value) {
            $i++;

            //page amount
            $value["max_amount"] = $fmt->format($value["max_amount"]);

            $jsonValues = str_replace(" ", "&&xx&&", json_encode($value));
            $jsonValues = escape($jsonValues);

            $operator = $value["operator"];
            if (strpos($operator, 'a:') !== false) {
                $operator = "Site";
            } else {
                if (strpos($operator, 'p:') !== false) {
                    $operator = explode("p:", $operator)[1];
                    $issuerSql = $db->get("username", "partner_users", [["id", "=", $operator]]);
                    if ($issuerSql->count()) {
                        $operator = "P:" . $issuerSql->first()["username"];
                    }
                }

                if (strpos($operator, 'c:') !== false) {
                    $operator = explode("c:", $operator)[1];
                    $issuerSql = $db->get("username", "clients", [["id", "=", $operator]]);
                    if ($issuerSql->count()) {
                        $operator = "C:" . $issuerSql->first()["username"];
                    }
                }

            }

            $providerShortName = array_search($value["product_id"], $productsIds);

            $providerName = $productsNames[$providerShortName];

            // $gameNameId = escape($value["game_name_en"]) . '(' . escape($value["game_code"]) . ')';
            $gameNameId = escape($value["game_name_kr"]);

            if (is_null($value["game_name_en"])) {
                $gameNameId = "ALL";
            }

            $tableBody .= '<tr>';

            $tableBody .= '<td class="text-center text-muted">' . $i . '</td>';
            $tableBody .= '<td class="text-center">' . escape($value["username"]) . '</td>';
            $tableBody .= '<td class="text-center">' . escape($providerName) . '</td>';
            $tableBody .= '<td class="text-center">' . $gameNameId . '</td>';

            $tableBody .= '<td class="text-center" style="font-weight: bold;">' . escape($value["max_amount"]) . ' 원</td>';
            $tableBody .= '<td class="text-center">' . escape($operator) . '</td>';
            $tableBody .= '<td class="text-center">' . escape($value["timestamp"]) . '</td>';

            $tableBody .= '<td class="text-center">
                    <button class="mb-2 mr-2 btn-transition  btn btn-outline-primary btn_action_edit" data-toggle="modal"
                    data-target=".add_limit" data-values=' . $jsonValues . ' onclick=showBetLimitsData(event)><i class="pe-7s-edit"> </i></button>
                    <button class="mb-2 mr-2 btn btn-danger active btn_action_delete"  data-id=' . $value['id'] . '  onclick=confirmDeleteBetLimit(event)>
                    <i class="pe-7s-trash"> 
                    </td>';


            $tableBody .= '</tr>';

        }

        //navigation
        $pagination = '';

        //count

        $gamesCount = $db->query("SELECT count(*) as gamesTotal FROM bet_limits bl LEFT JOIN clients cl ON bl.client_id = cl.id LEFT JOIN games_list gl ON bl.game_code = gl.game_code AND bl.product_id = gl.product_id WHERE 1=1 $filerQuery", $queryParameters)->first();

        $gamesTotal = $gamesCount["gamesTotal"];

        $pages = ceil($gamesTotal / $activeNumber);

        $current_page = $activePage == 0 ? 1 : $activePage;
        $total_records = $gamesTotal;
        $total_pages = $pages;

        if ($total_pages > 0 && $total_pages != 1 && $current_page <= $total_pages && $tableBody != '') { //verify total pages and current page number
            $pagination .= '<ul class="pagination">';
            $right_links = $current_page + 3;
            $previous = $current_page - 3; //previous link 
            $next = $current_page + 1; //next link
            $first_link = true; //boolean var to decide our first link

            //left-hand side links
            if ($current_page > 1) {
                $previous_link = ($previous == 0) ? 1 : $previous;
                $first = '1';

                $pagination .= '<li class="page-item"  onclick=displayLimits(1)><a href="javascript:void(0);" class="page-link" aria-label="First"><span aria-hidden="true">«</span><span class="sr-only">Last</span></a></li>';
                for ($i = ($current_page - 2); $i < $current_page; $i++) { //Create left-hand side links
                    if ($i > 0) {
                        $pagination .= '<li class="page-item"  onclick=displayLimits(' . $i . ')><a href="javascript:void(0);"class="page-link">' . $i . '</a></li>';

                    }
                }
                $first_link = false; //set first link to false $dateFrom, $dateTo
            }

            //active link
            $pagination .= '<li class="page-item active"><a href="javascript:void(0);"class="page-link">' . $current_page . '</a></li>';

            //create right-hand side links
            for ($i = $current_page + 1; $i < $right_links; $i++) {
                if ($i <= $total_pages) {
                    $pagination .= '<li class="page-item"  onclick=displayLimits(' . $i . ') ><a href="javascript:void(0);"class="page-link">' . $i . '</a></li>';

                }
            }
            if ($current_page < $total_pages) {
                $pagination .= '<li class="page-item"  onclick=displayLimits(' . $total_pages . ')><a href="javascript:void(0);" class="page-link" aria-label="Last"><span aria-hidden="true">»</span><span class="sr-only">Last</span></a></li>'; //last
            }

            $pagination .= '</ul>';
        }

        if ($tableBody == '') {
            $j = 8;
            $tableBody = '<tr> <td class="text-center" colspan="' . $j . '">No Data Available!</td> </tr>';
        }

        $token = token::generate("display_limits");
        print_r(json_encode([$tableBody, $pagination, $gamesTotal, $token]));

    } else {
        $token = token::generate("display_limits");
        print_r(json_encode([$token]));
    }


} else {
    echo "unauthorized";
    // header('Location: /pages/errors/403.php');      
}
















?>
