<?php
require_once __DIR__ . "/../../core/ini.php";
require_once __DIR__ . "/../../functions/sanitize.php";
require_once __DIR__ . "/../../functions/encryptDecrypt.php";



if (input::exists("post") && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' && isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] == "https://koreagaming.live/pages/infos/reports/summary_report.php") {

    if (token::check(input::get("token"), "display_summary")) {

        $activePage = input::get("page");
        $activeNumber = input::get("number");
        $activePage = ($activePage == 0 || $activePage == '') ? 1 : $activePage;

        $limit = $activeNumber;
        $offset = ($activePage - 1) * $activeNumber;

        //we will construct the logic based on the provider --> which table , naming conventions..
        $provider = input::get("provider");
        $category = input::get("category");


        $activeProviders = config::get("display/activeProviders"); //order matters
        //same order on the page --> same order for data collectors/groupers to have for display order

        $summaryDataCollector = [];
        $totalDataCollector = [];

        foreach ($activeProviders as $key => $value) {
            $totalDataCollector[$value] = [
                "wagerCount" => 0,
                "turnover" => 0,
                "winLoss" => 0,
            ]; //order matters
        }

        $status = input::get("status");

        $client = input::get("client");

        $from = input::get("startDate");
        $to = input::get("endDate");

        $from .= ' 00:00:00';
        $to .= ' 23:59:59';

        $db = DB::getInstance();

        $summaryHistory = [];

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


            $creditTableName = $prefix . '_credit';
            $debitTableName = $prefix . '_debit';
            $usersTableName = $prefix . '_users';


            $joininAttribute1 = $prefix . '_debit.txn_id';
            $joininAttribute2 = $prefix . '_credit.txn_id';

            $joininAttribute3 = $prefix . '_debit.user_id';
            $joininAttribute4 = $prefix . '_users.user_id';

            $columnCreditAmount = $prefix . '_credit.amount';
            $columnCreditDate = $prefix . '_credit.timestamp';
            $columnCreditType = $prefix . '_credit.type';

            $columnDebitAmount = $prefix . '_debit.amount';
            $columnDebitGame = $prefix . '_debit.prd_id';
            $columnDebitDate = $prefix . '_debit.timestamp';
            $columnDebitUser = $prefix . '_debit.user_id';

            $columnUsersUsername = $prefix . '_users.username';

            //filters
            $filterQuery = "";
            $queryParameters = [];

            // date filter
            $filterQuery .= " AND ($columnDebitDate >= ? AND $columnDebitDate <= ?) OR $columnDebitDate is NULL ";

            // win-loss filter 
            $winLossQuery = "";
            if ($status == "win") {
                $winLossQuery .= " HAVING debitAmount > creditAmount";
            } else if ($status == "loss") {
                $winLossQuery .= " HAVING debitAmount < creditAmount";
            } else if ($status == "tie") {
                $winLossQuery .= " HAVING debitAmount = creditAmount";
            }


            $queryParameters = [$from, $to];

            $summaryHistoryData = $db->query("SELECT count($columnCreditAmount) as wagerCount,  sum(CASE WHEN $columnCreditAmount is not null then $columnCreditAmount ELSE 0 end) AS creditAmount,  sum(CASE WHEN $columnCreditAmount is not null then $columnDebitAmount ELSE 0 end) AS debitAmount, $joininAttribute4, '$prefix' AS prefix, '$clientName' AS clientName, '$parentId' AS parentId ,$columnUsersUsername FROM $usersTableName LEFT JOIN  $debitTableName ON $joininAttribute4 = $joininAttribute3 $filterQuery LEFT JOIN $creditTableName ON $joininAttribute1 = $joininAttribute2   WHERE 1=1 AND $columnCreditType = 'c' OR $columnCreditType IS NULL group by $joininAttribute4 $winLossQuery", $queryParameters)->results();

            $summaryHistory = array_merge($summaryHistory, $summaryHistoryData);


        }

        // Array
        // (
        //     [0] => Array
        // (
        //     [wagerCount] => 2
        //     [creditAmount] => 4040.00
        //     [debitAmount] => 275.00
        //     [user_id] => malidtest2
        //     [prefix] => malidk2
        //     [clientName] => khalidclient
        //     [username] => dert
        // )
        // )

        $summaryHistoryPartial = array_slice($summaryHistory, $offset, $limit);

        //grouping 
        $summaryHistoryPartialGrouped = array();
        foreach ($summaryHistoryPartial as $element) {

            //populate others provider empty data  for each user
            foreach ($activeProviders as $key => $value) {
                $summaryHistoryPartialGrouped[$element['prefix'] . '_' . $element['user_id']][$value] = []; //order matters

            }
            $summaryHistoryPartialGrouped[$element['prefix'] . '_' . $element['user_id']]["evo"] = $element;

        }

        $summaryDataCollector = $summaryHistoryPartialGrouped;


        $tableBody = '';

        foreach ($summaryDataCollector as $player => $summaryPlayerData) {

            $playerRaw = explode("_", $player)[1];
            $prefix = explode("_", $player)[0];

            $playerRaw = $summaryPlayerData["evo"]["user_id"];
            $playerName = $summaryPlayerData["evo"]["username"];
            $clientName = $summaryPlayerData["evo"]["clientName"];

            // $parentName = $db->get("username", "partner_users", array(["id", "=", $summaryPlayerData["evo"]["parentId"]]))->first()["username"];


            $tableBody .= '<tr>';
            $tableBody .= '<td class="text-center 3f-cells" style="font-weight:bold">' . escape($playerRaw) . '</td>';
            $tableBody .= '<td class="text-center 3f-cells" style="font-weight:bold">' . escape($playerName) . '</td>';
            $tableBody .= '<td class="text-center 3f-cells" style="font-weight:bold">' . escape($clientName) . '</td>';
            // $tableBody .= '<td class="text-center">' . escape($parentName) . '</td>';



            $playerTotalWagerCount = 0;
            $playerTotalDebitAmount = 0;
            $playerTotalCreditAmount = 0;

            //row
            foreach ($summaryPlayerData as $key => $value) {

                //for this example --> real example would be populated with values from mysql
                $data = !empty($value) ? $value : array(
                    "wagerCount" => 0,
                    "creditAmount" => 0,
                    "debitAmount" => 0,
                    "user_id" => $playerRaw,
                    "prefix" => '',
                    "clientName" => '',
                    "username" => $playerName,
                );

                //player -> row total
                $playerTotalWagerCount += $data["wagerCount"];
                $playerTotalDebitAmount += $data["debitAmount"];
                $playerTotalCreditAmount += $data["creditAmount"];

                //provider -> column total
                $totalDataCollector[$key]["wagerCount"] += $data["wagerCount"];
                $totalDataCollector[$key]["turnover"] += $data["debitAmount"];
                $totalDataCollector[$key]["winLoss"] += $data["creditAmount"] - $data["debitAmount"];

                $winLoss = $data["creditAmount"] - $data["debitAmount"];
                $winLossColor = $winLoss < 0 ? 'red' : '';


                //formatting numbers
                $data["debitAmount"] = $fmt->format($data["debitAmount"]);
                $winLoss = $fmt->format($winLoss);


                $tableBody .= '<td class="text-center">' . escape($data["wagerCount"]) . '</td>';
                $tableBody .= '<td class="text-center">' . escape($data["debitAmount"]) . '</td>';
                $tableBody .= '<td class="text-center" style="color:' . $winLossColor . '">' . escape($winLoss) . '</td>';
            }

            $playerTotalWinLoss = $playerTotalCreditAmount - $playerTotalDebitAmount;
            $playerTotalWinLossColor = $playerTotalWinLoss < 0 ? 'red' : '';

            //formatting numbers
            $playerTotalDebitAmount = $fmt->format($playerTotalDebitAmount);
            $playerTotalWinLoss = $fmt->format($playerTotalWinLoss);

            $tableBody .= '<td class="text-center" style="font-weight:bold">' . escape($playerTotalWagerCount) . '</td>';
            $tableBody .= '<td class="text-center" style="font-weight:bold">' . escape($playerTotalDebitAmount) . '</td>';
            $tableBody .= '<td class="text-center" style="font-weight:bold;color:' . $playerTotalWinLossColor . '">' . escape($playerTotalWinLoss) . '</td>';

            $tableBody .= '</tr>';





        }

        //navigation
        $pagination = '';


        $summaryHistoryTotal = count($summaryHistory);

        $pages = ceil($summaryHistoryTotal / $activeNumber);

        $current_page = $activePage == 0 ? 1 : $activePage;
        $total_records = $summaryHistoryTotal;
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

                $pagination .= '<li class="page-item"  onclick=displaySummary(1)><a href="javascript:void(0);" class="page-link" aria-label="First"><span aria-hidden="true">«</span><span class="sr-only">Last</span></a></li>';
                for ($i = ($current_page - 2); $i < $current_page; $i++) { //Create left-hand side links
                    if ($i > 0) {
                        $pagination .= '<li class="page-item"  onclick=displaySummary(' . $i . ')><a href="javascript:void(0);"class="page-link">' . $i . '</a></li>';

                    }
                }
                $first_link = false; //set first link to false $dateFrom, $dateTo
            }

            //active link
            $pagination .= '<li class="page-item active"><a href="javascript:void(0);"class="page-link">' . $current_page . '</a></li>';

            //create right-hand side links
            for ($i = $current_page + 1; $i < $right_links; $i++) {
                if ($i <= $total_pages) {
                    $pagination .= '<li class="page-item"  onclick=displaySummary(' . $i . ') ><a href="javascript:void(0);"class="page-link">' . $i . '</a></li>';

                }
            }
            if ($current_page < $total_pages) {
                $pagination .= '<li class="page-item"  onclick=displaySummary(' . $total_pages . ')><a href="javascript:void(0);" class="page-link" aria-label="Last"><span aria-hidden="true">»</span><span class="sr-only">Last</span></a></li>'; //last
            }

            $pagination .= '</ul>';
        }

        if ($tableBody == '') {
            $rowSpan = count($activeProviders) * 3 + 7;
            $tableBody = '<tr> <td class="text-center" rowspan=2 colspan="' . $rowSpan . '"  style="font-weight:bold">기록을 찾을 수 없음!</td> </tr>';
        } else {
            //totals
            $tableBody .= '<tr class="total-row bg-sunny-morning">';
            $tableBody .= '<td class="text-center 3f-cells" style="font-weight:bold" colspan=3>총 합계</td>';

            $totalOfTotalWagerCount = 0;
            $totalOfTotalTurnover = 0;
            $totalOfTotalWinLoss = 0;


            foreach ($totalDataCollector as $key => $value) {


                $totalOfTotalWagerCount += $value["wagerCount"];
                $totalOfTotalTurnover += $value["turnover"];
                $totalOfTotalWinLoss += $value["winLoss"];

                $totalWinLossColor = $value["winLoss"] < 0 ? 'red' : '';

                //formatting numbers
                $value["turnover"] = $fmt->format($value["turnover"]);
                $value["winLoss"] = $fmt->format($value["winLoss"]);

                $tableBody .= '<td class="text-center" style="font-weight:bold">' . escape($value["wagerCount"]) . '</td>';
                $tableBody .= '<td class="text-center" style="font-weight:bold">' . escape($value["turnover"]) . '</td>';
                $tableBody .= '<td class="text-center" style="font-weight:bold;color:' . $totalWinLossColor . '">' . escape($value["winLoss"]) . '</td>';
            }

            $totalOfTotalWinLossColor = $totalOfTotalWinLoss < 0 ? 'red' : '';

            $totalOfTotalTurnover = $fmt->format($totalOfTotalTurnover);
            $totalOfTotalWinLoss = $fmt->format($totalOfTotalWinLoss);

            $tableBody .= '<td class="text-center" style="font-weight:bold">' . escape($totalOfTotalWagerCount) . '</td>';
            $tableBody .= '<td class="text-center" style="font-weight:bold">' . escape($totalOfTotalTurnover) . '</td>';
            $tableBody .= '<td class="text-center" style="font-weight:bold;color:' . $totalOfTotalWinLossColor . '">' . escape($totalOfTotalWinLoss) . '</td>';

            $tableBody .= '</tr>';
        }

        $token = token::generate("display_summary");
        print_r(json_encode([$tableBody, $pagination, $summaryHistoryTotal, $token]));

    } else {
        $token = token::generate("display_summary");
        print_r(json_encode([$token]));
    }


} else {
    echo "unauthorized";
    // header('Location: /pages/errors/403.php');      
}
















?>
