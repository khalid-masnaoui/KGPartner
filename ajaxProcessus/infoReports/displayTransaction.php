<?php
require_once __DIR__ . "/../../core/ini.php";
require_once __DIR__ . "/../../functions/sanitize.php";
require_once __DIR__ . "/../../functions/encryptDecrypt.php";



if (input::exists("post") && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' && isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] == "https://koreagaming.info/pages/infos/reports/transaction_history.php") {

    if (token::check(input::get("token"), "display_transaction")) {

        $activePage = input::get("page");
        $activeNumber = input::get("number");
        $activePage = ($activePage == 0 || $activePage == '') ? 1 : $activePage;

        $limit = $activeNumber;
        $offset = ($activePage - 1) * $activeNumber;

        $activeProviders = config::get("providersNameMappings");
        //we will construct the logic based on the provider --> which table , naming conventions..
        $provider = input::get("provider");

        $transactionID = input::get("transaction");

        $transactionStatus = input::get("transactionStatus");

        $nameFilter = input::get("nameFilter");
        $nameFilter = escape($nameFilter);

        $status = input::get("status");

        $client = input::get("client");

        $from = input::get("startDate");
        $to = input::get("endDate");

        // $from .= ' 00:00:00';
        // $to .= ' 23:59:59';

        $db = DB::getInstance();

        $transactionHistory = [];



        $partner = new user();
        $partnerPtId = $partner->data()["pt_id"];

        $clientQuery = '';
        $parametersQuery = [];


        $partnerFilter = " AND pt_id = ? ";
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


            $creditTableName = $prefix . '_credit';
            $debitTableName = $prefix . '_debit';
            $gamesListTableName = 'games_list';


            $joininAttribute1 = $prefix . '_debit.txn_id';
            $joininAttribute2 = $prefix . '_credit.txn_id';

            $columnCreditAmount = $prefix . '_credit.amount';
            $columnCreditDate = $prefix . '_credit.timestamp';
            $columnCreditType = $prefix . '_credit.type';
            $columnClientResponse = $prefix . '_credit.client_response';


            $columnDebitAmount = $prefix . '_debit.amount';
            $columnDebitProvider = $prefix . '_debit.prd_id';
            $columnDebitGame = $prefix . '_debit.game_name_id';
            $columnGameName = "games_list.game_name_en";
            $columnGameType = "games_list.game_subtype";
            $columnGameId = "games_list.id";
            $columnDebitDate = $prefix . '_debit.timestamp';
            $columnDebitUser = $prefix . '_debit.user_id';

            $usersTableName = $prefix . '_users';
            $columnUsersUsername = $prefix . '_users.username';
            $columnUsersId = $prefix . '_users.user_id';

            //filters
            $filterQuery = "";
            $queryParameters = [];

            // date filter
            $filterQuery .= " AND $columnDebitDate >= ? AND $columnDebitDate <= ?";
            $queryParameters = [$from, $to];

            // player username filter
            if ($nameFilter != '') {
                $filterQuery .= " AND $columnDebitUser IN (SELECT user_id FROM $usersTableName WHERE username LIKE ?)";
                array_push($queryParameters, "%$nameFilter%");
            }

            // win-loss-tie filter 
            if ($transactionStatus != 'pending') {
                if ($status == "win") {
                    $filterQuery .= " AND $columnDebitAmount > $columnCreditAmount";
                } else if ($status == "loss") {
                    $filterQuery .= " AND $columnDebitAmount < $columnCreditAmount";
                } else if ($status == "tie") {
                    $filterQuery .= " AND $columnDebitAmount = $columnCreditAmount";
                }
            }



            if ($transactionID != '') {
                $filterQuery .= " AND $joininAttribute1 = ?";
                array_push($queryParameters, $transactionID);
            }


            $creditTypeFilterQuery = "";

            if ($transactionStatus != 'pending') {
                if ($status == 'all') {
                    $creditTypeFilterQuery = "";
                } else {
                    $creditTypeFilterQuery .= " AND $columnCreditType = ?";

                    $creditType = "c";
                    if ($status == "cancel") {
                        $creditType = "x";
                    }
                    array_push($queryParameters, $creditType);
                }
            }

            if ($transactionStatus == 'pending') {

                $transactionHistoryData = $db->query("SELECT '-' AS creditAmount,  $columnDebitAmount AS debitAmount, $columnDebitDate, $columnGameName AS gameName ,  $columnGameType AS gameType, $columnDebitProvider as gameProviderId, $columnDebitUser, $joininAttribute1, '$prefix' AS prefix, '$clientName' AS clientName, '$parentId' AS parentId, 'pending' as status, $columnUsersUsername
                FROM $debitTableName JOIN $usersTableName ON $columnUsersId = $columnDebitUser LEFT JOIN $gamesListTableName ON $columnDebitGame = $columnGameId WHERE 1=1 $filterQuery AND NOT EXISTS (SELECT 1 FROM $creditTableName 
                  WHERE txn_id = $joininAttribute1)", $queryParameters)->results();

            } else if ($transactionStatus == 'proceeded') {

                $transactionHistoryData = $db->query("SELECT $columnCreditAmount AS creditAmount,  $columnDebitAmount AS debitAmount, $columnDebitDate, $columnGameName AS gameName ,$columnGameType AS gameType, $columnCreditDate AS resultDate, $columnDebitProvider as gameProviderId, $columnDebitUser, $joininAttribute1, '$prefix' AS prefix, '$clientName' AS clientName, '$parentId' AS parentId,
                CASE WHEN $columnCreditType = 'x' THEN 'cancel'
                WHEN $columnDebitAmount > $columnCreditAmount THEN 'win'
                WHEN $columnDebitAmount = $columnCreditAmount THEN 'tie'
                -- WHEN $columnDebitAmount < $columnCreditAmount THEN 'loss'
                ELSE 'loss' END AS 'status' , $columnClientResponse, $columnUsersUsername
                    FROM $debitTableName JOIN  $creditTableName ON $joininAttribute1 = $joininAttribute2 JOIN $usersTableName ON $columnUsersId = $columnDebitUser LEFT JOIN $gamesListTableName ON $columnDebitGame = $columnGameId WHERE 1=1 $filterQuery $creditTypeFilterQuery ", $queryParameters)->results();

            } else {
                $transactionHistoryData = $db->query("SELECT $columnCreditAmount AS creditAmount,  $columnDebitAmount AS debitAmount, $columnDebitDate, $columnGameName AS gameName ,$columnGameType AS gameType, $columnCreditDate AS resultDate, $columnDebitProvider as gameProviderId, $columnDebitUser, $joininAttribute1, '$prefix' AS prefix, '$clientName' AS clientName, '$parentId' AS parentId,
                CASE WHEN $columnCreditType = 'x' THEN 'cancel'
                WHEN $columnDebitAmount > $columnCreditAmount THEN 'win'
                WHEN $columnDebitAmount = $columnCreditAmount THEN 'tie'
                WHEN $columnDebitAmount < $columnCreditAmount THEN 'loss'
                ELSE 'pending' END AS 'status' , $columnClientResponse, $columnUsersUsername
                    FROM $debitTableName LEFT JOIN  $creditTableName ON $joininAttribute1 = $joininAttribute2 JOIN $usersTableName ON $columnUsersId = $columnDebitUser LEFT JOIN $gamesListTableName ON $columnDebitGame = $columnGameId WHERE 1=1  $filterQuery $creditTypeFilterQuery", $queryParameters)->results();
            }


            $transactionHistory = array_merge($transactionHistory, $transactionHistoryData);




        }






        //order them by timestamps

        // Array
        // (
        //     [0] => Array
        //         (
        //                 [creditAmount] => 63.21
        //                 [debitAmount] => 63.21
        //                 [timestamp] => 2022-05-19 10:42:53
        //                 [gameName] => 12
        //                 [gameProviderId] => 1
        //                 [user_id] => waplayer
        //                 [txn_id] => 707659788922192746
        //                 [prefix] => test
        //                 [clientName] => client
        //                 [status] => tie
        // //         )
        // )

        function date_compare($a, $b)
        {
            $t1 = strtotime($a['timestamp']);
            $t2 = strtotime($b['timestamp']);
            return $t2 - $t1; //desc
        }
        usort($transactionHistory, 'date_compare');

        $transactionHistoryPartial = array_slice($transactionHistory, $offset, $limit);



        $tableBody = '';
        $i = 0;
        $i = ($activePage - 1) * $activeNumber;

        foreach ($transactionHistoryPartial as $key => $value) {
            $i++;

            $status = escape($value["status"]);
            $prefix = escape($value["prefix"]);

            $statusHtml = '';
            if ($status == 'win') {
                $statusHtml = '<div class="mb-2 mr-2 badge badge-pill badge-success">승</div>';
            } else if ($status == 'loss') {
                $statusHtml = '<div class="mb-2 mr-2 badge badge-pill badge-danger" style="color:white !important;">패</div>';
            } else if ($status == "tie") {
                $statusHtml = '<div class="mb-2 mr-2 badge badge-pill badge-primary" style="color:white !important;">타이</div>';
            } else if ($status == "cancel") {
                $statusHtml = '<div class="mb-2 mr-2 badge badge-pill badge-warning" style="color:white !important;">취소</div>';
            } else if ($status == "pending") {
                $statusHtml = '<div class="mb-2 mr-2 badge badge-pill badge-secondary" style="color:white !important;">대기</div>';
            }

            if ($transactionStatus == 'pending' || $value["creditAmount"] == '') {
                $value["creditAmount"] = '-';
            } else {
                $value["creditAmount"] = $fmt->format($value["creditAmount"]);
            }
            $value["debitAmount"] = $fmt->format($value["debitAmount"]);

            $parentName = $db->get("username", "partner_users", array(["id", "=", $value["parentId"]]))->first()["username"];


            $clientResponseHtml = '-';
            if ($transactionStatus == 'pending' || $value["client_response"] == null || $value["client_response"] == 1) {
                $clientResponseHtml = '<div class="mb-2 mr-2 badge badge-pill badge-success">Accepted</div>';
            } else {
                $clientResponseHtml = '<div class="mb-2 mr-2 badge badge-pill badge-danger" style="color:white !important;">Error</div>';
            }

            if ($value["gameName"] == '' || $value["gameName"] == null) {
                $value["gameName"] = "-";
            }


            $now = strtotime("now");

            if ($transactionStatus == 'pending') {
                $resultTime = $now;
            } else {
                $resultTime = strtotime($value["resultDate"]);
            }
            $differenceInSeconds = $now - $resultTime;


            $tableBody .= '<tr>';

            $tableBody .= '<td class="text-center text-muted">' . $i . '</td>';

            if ($value["gameProviderId"] == 1 && $status != "pending" && in_array($value["gameType"], ["Baccarat", "RNG Baccarat", "RNG Dragon Tiger", "Dragon Tiger", "Bac Bo"]) && $differenceInSeconds > 60) {

                // if (strpos($value["txn_id"], ":s") !== false) {
                $type = "BC";
                if (in_array($value["gameType"], ["RNG Dragon Tiger", "Dragon Tiger"])) {
                    $type = "DT";
                }

                if (in_array($value["gameType"], ["Bac Bo"])) {
                    $type = "BB";
                }


                $transactionId = str_replace(":s", "", $value["txn_id"]);
                $tableBody .= '<td class="text-center">' . escape($value["txn_id"]) . ' <img src="/assets/images/betDetails/betDetails.png" width="15" height="15" onclick=showBetDetails("' . $transactionId . '","' . $type . '","' . $prefix . '") style="cursor: pointer;"> </td>';

            } else {
                $tableBody .= '<td class="text-center">' . escape($value["txn_id"]) . '</td>';

            }

            // } else {
            //     $tableBody .= '<td class="text-center">' . escape($value["txn_id"]) . '</td>';

            // }

            $tableBody .= '<td class="text-center">' . escape($value["clientName"]) . '</td>';
            $tableBody .= '<td class="text-center">' . escape($parentName) . '</td>';
            $tableBody .= '<td class="text-center">' . escape($value["user_id"]) . '</td>';
            $tableBody .= '<td class="text-center">' . escape($value["prefix"] . '_' . $value["username"]) . '</td>';
            $tableBody .= '<td class="text-center">' . escape($value["gameName"]) . ' </td>';

            $tableBody .= '<td class="text-center">' . $clientResponseHtml . '</td>';
            $tableBody .= '<td class="text-center">' . $statusHtml . '</td>';


            $tableBody .= '<td class="text-center">' . escape($value["debitAmount"]) . ' 원</td>';
            $tableBody .= '<td class="text-center">' . escape($value["creditAmount"]) . ' 원</td>';
            $tableBody .= '<td class="text-center">' . escape($value["timestamp"]) . '</td>';




            $tableBody .= '</tr>';


            // <a href="#delete_modal" class="trigger-btn" data-toggle="delete_modal"><i class="pe-7s-trash"> </i></button></a>



        }

        //navigation
        $pagination = '';


        $transactionHistoryTotal = count($transactionHistory);

        $pages = ceil($transactionHistoryTotal / $activeNumber);

        $current_page = $activePage == 0 ? 1 : $activePage;
        $total_records = $transactionHistoryTotal;
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

                $pagination .= '<li class="page-item"  onclick=displayTransactions(1)><a href="javascript:void(0);" class="page-link" aria-label="First"><span aria-hidden="true">«</span><span class="sr-only">Last</span></a></li>';
                // $pagination .='<li class="page-item"  onclick=displayTransactions('.$previous_link.')><a href="javascript:void(0);"class="page-link">'.$previous_link.' &lt;</a></li>';
                for ($i = ($current_page - 2); $i < $current_page; $i++) { //Create left-hand side links
                    if ($i > 0) {
                        $pagination .= '<li class="page-item"  onclick=displayTransactions(' . $i . ')><a href="javascript:void(0);"class="page-link">' . $i . '</a></li>';

                    }
                }
                $first_link = false; //set first link to false $dateFrom, $dateTo
            }

            //active link
            $pagination .= '<li class="page-item active"><a href="javascript:void(0);"class="page-link">' . $current_page . '</a></li>';

            //create right-hand side links
            for ($i = $current_page + 1; $i < $right_links; $i++) {
                if ($i <= $total_pages) {
                    $pagination .= '<li class="page-item"  onclick=displayTransactions(' . $i . ') ><a href="javascript:void(0);"class="page-link">' . $i . '</a></li>';

                }
            }
            if ($current_page < $total_pages) {
                // $next_link = ($i > $total_pages) ? $total_pages : $i;
                // // $pagination .='<li class="page-item" onclick=displayTransactions('.$next_link.')><a href="javascript:void(0);"class="page-link">'.$next_link.' &gt;</a></li>'; //next

                $pagination .= '<li class="page-item"  onclick=displayTransactions(' . $total_pages . ')><a href="javascript:void(0);" class="page-link" aria-label="Last"><span aria-hidden="true">»</span><span class="sr-only">Last</span></a></li>'; //last

            }

            $pagination .= '</ul>';
        }

        if ($tableBody == '') {
            $tableBody = '<tr> <td class="text-center" colspan="13">기록을 찾을 수 없음!</td> </tr>';
        }

        $token = token::generate("display_transaction");
        print_r(json_encode([$tableBody, $pagination, $transactionHistoryTotal, $token]));

    } else {
        $token = token::generate("display_transaction");
        print_r(json_encode([$token]));
    }


} else {
    echo "unauthorized";
    // header('Location: /pages/errors/403.php');      
}
















?>
