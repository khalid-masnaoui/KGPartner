<?php
require_once __DIR__ . "/../../core/ini.php";
require_once __DIR__ . "/../../functions/sanitize.php";
require_once __DIR__ . "/../../functions/encryptDecrypt.php";



if (input::exists("post") && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' && isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] == "https://koreagaming.info/pages/infos/reports/bonus_winning.php") {

    if (token::check(input::get("token"), "display_bonus")) {

        $activePage = input::get("page");
        $activeNumber = input::get("number");
        $activePage = ($activePage == 0 || $activePage == '') ? 1 : $activePage;

        $limit = $activeNumber;
        $offset = ($activePage - 1) * $activeNumber;

        $activeProviders = config::get("providersNameMappings");

        //we will construct the logic based on the provider --> which table , naming conventions..
        $provider = input::get("provider");

        $playerID = input::get("memberID");

        $BonusType = input::get("BonusType");
        $BonusType = trim($BonusType);

        $nameFilter = input::get("nameFilter");
        $nameFilter = escape($nameFilter);

        $status = input::get("status");

        $client = input::get("client");

        $from = input::get("startDate");
        $to = input::get("endDate");

        // $from .= ' 00:00:00';
        // $to .= ' 23:59:59';

        $db = DB::getInstance();

        $bonusHistory = [];


        $partner = new user();
        $partnerPtId = $partner->data()["pt_id"];

        $clientQuery = '';
        $parametersQuery = [];

        $partnerFilter = " AND pt_id = ?";
        $parametersQuery[] = "$partnerPtId";

        if ($client != 'all') {
            $clientQuery = ' AND id = ?';
            $parametersQuery[] = $client;
        }

        $clientSql = "SELECT id, pt_id, username, prefix FROM clients WHERE 1=1 $partnerFilter $clientQuery ";
        $prefixes = $db->query($clientSql, $parametersQuery)->results();


        foreach ($prefixes as $key => $value) {
            $clientId = $value["id"];
            $clientName = $value["username"];
            $prefix = $value["prefix"];
            $parentId = $value["pt_id"];


            $bonusTableName = $prefix . '_bonus';
            $bonusUsersId = $prefix . '_bonus.user_id';

            $usersTableName = $prefix . '_users';
            $columnUsersUsername = $prefix . '_users.username';
            $columnUsersId = $prefix . '_users.user_id';

            //filters
            $filterQuery = "";
            $queryParameters = [];

            // date filter
            $filterQuery .= " AND timestamp >= ? AND timestamp <= ?";
            $queryParameters = [$from, $to];


            // player filter
            if ($nameFilter != '') {
                $filterQuery .= " AND user_id IN (SELECT user_id FROM $usersTableName WHERE username LIKE ?)";
                array_push($queryParameters, "%$nameFilter%");
            }

            if ($playerID != '') {
                $filterQuery .= " AND user_id = ?";
                array_push($queryParameters, $playerID);

            }

            // win-loss-tie filter 
            if ($BonusType != 'all') {

                $likeString = '';
                if ($BonusType == "1") { //in game bonuses
                    $likeString = 'Reward';
                } else if ($BonusType == "2") { //promotions
                    $likeString = 'Promo';
                } else if ($BonusType == "3") { //jackBots
                    $likeString = 'Jackpot';
                }

                $filterQuery .= " AND promo_type LIKE ?";
                array_push($queryParameters, "%$likeString%");
            }

            if ($status != 'all') {
                $accepted = 0;
                if ($status == "accepted") {
                    $accepted = 1;
                } else if ($status == "error") {
                    $accepted = 0;
                }
                $filterQuery .= " AND acceptance_status = ?";
                array_push($queryParameters, $accepted);

            }

            $bonusHistoryData = $db->query("SELECT *, '$clientName' as clientName, '$parentId' AS parentId ,
            CASE
                WHEN promo_type LIKE '%Reward%' THEN 'In Game Bonus'
                WHEN promo_type LIKE '%Promo%' THEN 'Promotion'
                WHEN promo_type LIKE '%Jackpot%' THEN 'JackPot'
            END as bonusType  , '$prefix' AS prefix, $columnUsersUsername FROM $bonusTableName JOIN $usersTableName ON $bonusUsersId = $columnUsersId  WHERE 1=1 $filterQuery", $queryParameters)->results();

            $bonusHistory = array_merge($bonusHistory, $bonusHistoryData);
        }

        //order them by timestamps

        function date_compare($a, $b)
        {
            $t1 = strtotime($a['timestamp']);
            $t2 = strtotime($b['timestamp']);
            return $t2 - $t1; //desc
        }
        usort($bonusHistory, 'date_compare');

        $totalAmount = array_sum(array_column($bonusHistory, 'amount'));

        $bonusHistoryPartial = array_slice($bonusHistory, $offset, $limit);



        $tableBody = '';
        $i = 0;
        $i = ($activePage - 1) * $activeNumber;

        foreach ($bonusHistoryPartial as $key => $value) {
            $i++;

            $status = escape($value["acceptance_status"]);

            $statusHtml = '';
            if ($status == 1) {
                $statusHtml = '<div class="mb-2 mr-2 badge badge-pill badge-success">Accepted</div>';
            } else if ($status == 0) {
                $statusHtml = '<div class="mb-2 mr-2 badge badge-pill badge-danger" style="color:white !important;">Error</div>';
            }

            if ($value["amount"] == '') {
                $value["amount"] = '-';
            } else {
                $value["amount"] = $fmt->format($value["amount"]);
            }
            $parentName = $db->get("username", "partner_users", array(["id", "=", $value["parentId"]]))->first()["username"];


            $tableBody .= '<tr>';

            $tableBody .= '<td class="text-center text-muted">' . $i . '</td>';
            $tableBody .= '<td class="text-center">' . escape($value["txn_id"]) . '</td>';
            $tableBody .= '<td class="text-center">' . escape($value["clientName"]) . '</td>';
            $tableBody .= '<td class="text-center">' . escape($parentName) . '</td>';
            $tableBody .= '<td class="text-center">' . escape($value["user_id"]) . '</td>';
            $tableBody .= '<td class="text-center">' . escape($value["prefix"] . '_' . $value["username"]) . '</td>';
            $tableBody .= '<td class="text-center">' . escape($activeProviders[$provider]) . '</td>';
            $tableBody .= '<td class="text-center">' . escape($value["game_name_id"]) . ' </td>';

            $tableBody .= '<td class="text-center">' . $statusHtml . '</td>';


            $tableBody .= '<td class="text-center">' . escape($value["amount"]) . ' 원</td>';
            $tableBody .= '<td class="text-center">' . escape($value["bonusType"]) . '</td>';
            $tableBody .= '<td class="text-center">' . escape($value["timestamp"]) . '</td>';




            $tableBody .= '</tr>';


            // <a href="#delete_modal" class="trigger-btn" data-toggle="delete_modal"><i class="pe-7s-trash"> </i></button></a>



        }

        //navigation
        $pagination = '';


        $bonusHistoryTotal = count($bonusHistory);

        $pages = ceil($bonusHistoryTotal / $activeNumber);

        $current_page = $activePage == 0 ? 1 : $activePage;
        $total_records = $bonusHistoryTotal;
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

                $pagination .= '<li class="page-item"  onclick=displayBonuses(1)><a href="javascript:void(0);" class="page-link" aria-label="First"><span aria-hidden="true">«</span><span class="sr-only">Last</span></a></li>';
                // $pagination .='<li class="page-item"  onclick=displayBonuses('.$previous_link.')><a href="javascript:void(0);"class="page-link">'.$previous_link.' &lt;</a></li>';
                for ($i = ($current_page - 2); $i < $current_page; $i++) { //Create left-hand side links
                    if ($i > 0) {
                        $pagination .= '<li class="page-item"  onclick=displayBonuses(' . $i . ')><a href="javascript:void(0);"class="page-link">' . $i . '</a></li>';

                    }
                }
                $first_link = false; //set first link to false $dateFrom, $dateTo
            }

            //active link
            $pagination .= '<li class="page-item active"><a href="javascript:void(0);"class="page-link">' . $current_page . '</a></li>';

            //create right-hand side links
            for ($i = $current_page + 1; $i < $right_links; $i++) {
                if ($i <= $total_pages) {
                    $pagination .= '<li class="page-item"  onclick=displayBonuses(' . $i . ') ><a href="javascript:void(0);"class="page-link">' . $i . '</a></li>';

                }
            }
            if ($current_page < $total_pages) {
                // $next_link = ($i > $total_pages) ? $total_pages : $i;
                // // $pagination .='<li class="page-item" onclick=displayBonuses('.$next_link.')><a href="javascript:void(0);"class="page-link">'.$next_link.' &gt;</a></li>'; //next

                $pagination .= '<li class="page-item"  onclick=displayBonuses(' . $total_pages . ')><a href="javascript:void(0);" class="page-link" aria-label="Last"><span aria-hidden="true">»</span><span class="sr-only">Last</span></a></li>'; //last

            }

            $pagination .= '</ul>';
        }

        if ($tableBody == '') {
            $tableBody = '<tr> <td class="text-center" colspan="12">기록을 찾을 수 없음!</td> </tr>';
        } else {
            $pageTotalAmount = array_sum(array_column($bonusHistoryPartial, 'amount'));

            $pageTotalAmount = $fmt->format($pageTotalAmount);
            $totalAmount = $fmt->format($totalAmount);


            // add page Total
            $tableBody .= '<tr style="font-weight:bold" class="bg-sunny-morning">';

            $tableBody .= '<td class="text-left">Page Total</td>';
            $tableBody .= '<td class="text-center"></td>';
            $tableBody .= '<td class="text-center"></td>';
            $tableBody .= '<td class="text-center"></td>';
            $tableBody .= '<td class="text-center"></td>';
            $tableBody .= '<td class="text-center"></td>';
            $tableBody .= '<td class="text-center"></td>';
            $tableBody .= '<td class="text-center"></td>';

            $tableBody .= '<td class="text-center"></td>';
            $tableBody .= '<td class="text-center">' . $pageTotalAmount . ' 원</td>';
            $tableBody .= '<td class="text-center"></td>';
            $tableBody .= '<td class="text-center"></td>';

            $tableBody .= '</tr>';

            // add Total
            $tableBody .= '<tr style="/*background-color: rgba(247, 176, 36 ,0.6) !important;*/font-weight:bold" class="bg-sunny-morning">';

            $tableBody .= '<td class="text-left">Total</td>';
            $tableBody .= '<td class="text-center"></td>';
            $tableBody .= '<td class="text-center"></td>';
            $tableBody .= '<td class="text-center"></td>';
            $tableBody .= '<td class="text-center"></td>';
            $tableBody .= '<td class="text-center"></td>';
            $tableBody .= '<td class="text-center"></td>';
            $tableBody .= '<td class="text-center"></td>';

            $tableBody .= '<td class="text-center"></td>';
            $tableBody .= '<td class="text-center">' . $totalAmount . ' 원</td>';
            $tableBody .= '<td class="text-center"></td>';
            $tableBody .= '<td class="text-center"></td>';

            $tableBody .= '</tr>';

        }

        $token = token::generate("display_bonus");
        print_r(json_encode([$tableBody, $pagination, $bonusHistoryTotal, $token]));

    } else {
        $token = token::generate("display_bonus");
        print_r(json_encode([$token]));
    }


} else {
    echo "unauthorized";
    // header('Location: /pages/errors/403.php');      
}
















?>
