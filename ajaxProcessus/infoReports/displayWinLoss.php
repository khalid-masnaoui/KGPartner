<?php
require_once __DIR__ . "/../../core/ini.php";
require_once __DIR__ . "/../../functions/sanitize.php";
require_once __DIR__ . "/../../functions/encryptDecrypt.php";



if (input::exists("post") && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' && isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] == "https://koreagaming.info/pages/infos/reports/win_loss.php") {

    if (token::check(input::get("token"), "display_winloss")) {

        $activePage = input::get("page");
        $activeNumber = input::get("number");
        $activePage = ($activePage == 0 || $activePage == '') ? 1 : $activePage;

        $limit = $activeNumber;
        $offset = ($activePage - 1) * $activeNumber;

        $nameFilter = input::get("nameFilter");
        $nameFilter = escape($nameFilter);

        $status = input::get("status");

        $client = input::get("client");

        $from = input::get("startDate");
        $to = input::get("endDate");

        $from .= ' 00:00:00';
        $to .= ' 23:59:59';

        $db = DB::getInstance();

        $winLoss = [];



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

        $clientSql = "SELECT id, pt_id, username, prefix, rate FROM clients WHERE 1=1 $partnerFilter $clientQuery ";
        $prefixes = $db->query($clientSql, $parametersQuery)->results();


        // evolution
        foreach ($prefixes as $key => $value) {
            $clientId = $value["id"];
            $clientName = $value["username"];
            $prefix = $value["prefix"];
            $parentId = $value["pt_id"];
            $rate = $value["rate"];


            $creditTableName = $prefix . '_credit';
            $debitTableName = $prefix . '_debit';

            $joininAttribute1 = $prefix . '_credit.txn_id';
            $joininAttribute2 = $prefix . '_debit.txn_id';

            $columnCreditAmount = $prefix . '_credit.amount';
            $columnCreditDate = $prefix . '_credit.timestamp';
            $columnCreditType = $prefix . '_credit.type';

            $columnDebitAmount = $prefix . '_debit.amount';
            $columnDebitGame = $prefix . '_debit.prd_id';
            $columnDebitUser = $prefix . '_debit.user_id';

            $usersTableName = $prefix . '_users';
            $columnUsersUsername = $prefix . '_users.username';
            $columnUsersId = $prefix . '_users.user_id';

            //filters 
            $filterQuery = "";
            $queryParameters = [];
            // date filter
            $filterQuery .= " AND $columnCreditDate >= ? AND $columnCreditDate <= ?";
            $queryParameters = ["c", $from, $to];

            // player username filter
            if ($nameFilter != '') {
                $filterQuery .= " AND $columnDebitUser IN (SELECT user_id FROM $usersTableName WHERE username LIKE ?)";
                $queryParameters[] = "%$nameFilter%";
            }



            // win-loss filter 
            $winLossQuery = "";
            if ($status == "win") {
                $winLossQuery .= " HAVING debitAmount > creditAmount";
            } else if ($status == "loss") {
                $winLossQuery .= " HAVING debitAmount < creditAmount";
            } else if ($status == "tie") {
                $winLossQuery .= " HAVING debitAmount = creditAmount";
            }
            // AND $columnDebitAmount <> $columnCreditAmount

            //count from credit --> avoid counting pending transactions

            $winLossData = $db->query("SELECT count($columnCreditAmount) as wagerCount, sum($columnCreditAmount) AS creditAmount,  sum($columnDebitAmount) AS debitAmount, $columnDebitUser, '$prefix' AS prefix, '$clientName' AS clientName, '$rate' AS clientRate, '$parentId' AS parentId, $columnUsersUsername FROM $creditTableName JOIN  $debitTableName ON $joininAttribute1 = $joininAttribute2 JOIN $usersTableName ON $columnUsersId = $columnDebitUser WHERE  $columnCreditType = ? $filterQuery  group by $columnDebitUser $winLossQuery", $queryParameters)->results();

            $winLoss = array_merge($winLoss, $winLossData);
        }


        //order them by timestamps

        // Array
        // (
        //     [0] => Array
        //         (
        //             [wagerCount] => 8.01
        //             [creditAmount] => 8.01
        //             [debitAmount] => 3.01
        //              [rate] => 10.00
        //             [user_id] => waplayer
        //             [prefix] => test
        //             [clientName] => wasolrdf
        //             [status] => loss
        //         )
        // )

        $totalWagerCount = array_sum(array_column($winLoss, 'wagerCount'));
        $totalDebitAmount = array_sum(array_column($winLoss, 'debitAmount'));
        $totalCreditAmount = array_sum(array_column($winLoss, 'creditAmount'));

        $totalCompanyWinValues = array_map(function ($array) {
            return (abs($array["creditAmount"] - $array["debitAmount"]) * $array["clientRate"]) / 100;
        }, $winLoss);
        $totalCompanyWin = array_sum($totalCompanyWinValues);

        $totalMemberWin = $totalCreditAmount - $totalDebitAmount;
        // $totalCompanyWin =  abs($totalMemberWin) * config::get("serviceConfig/companyWinPercentage");
        $totalAgWin = abs($totalMemberWin) - $totalCompanyWin;

        $totalCompanyWin = $totalMemberWin < 0 ? $totalCompanyWin : -$totalCompanyWin;
        $totalAgWin = $totalMemberWin < 0 ? $totalAgWin : -$totalAgWin;

        $winLossPartial = array_slice($winLoss, $offset, $limit);

        // print_r($winLoss);exit;
        $pageTotalCompanyWin = 0;


        $tableBody = '';
        $i = 0;
        $i = ($activePage - 1) * $activeNumber;


        foreach ($winLossPartial as $key => $value) {
            $i++;

            $memberWin = $value["creditAmount"] - $value["debitAmount"];
            // $companyWin =  abs($memberWin) * config::get("serviceConfig/companyWinPercentage");
            $companyWin = (abs($memberWin) * $rate) / 100;

            $agWin = abs($memberWin) - $companyWin;

            $companyWin = $memberWin < 0 ? $companyWin : -$companyWin;
            $agWin = $memberWin < 0 ? $agWin : -$agWin;

            $memberWinColor = $memberWin < 0 ? 'red' : '';
            $agWinColor = $agWin < 0 ? 'red' : '';

            //page win
            $pageTotalCompanyWin += $companyWin;

            //formatting numbers
            $value["debitAmount"] = $fmt->format($value["debitAmount"]);
            $memberWin = $fmt->format($memberWin);
            $agWin = $fmt->format($agWin);
            $companyWin = $fmt->format($companyWin);

            $parentName = $db->get("username", "partner_users", array(["id", "=", $value["parentId"]]))->first()["username"];


            $tableBody .= '<tr>';

            $tableBody .= '<td class="text-center text-muted">' . $i . '</td>';
            $tableBody .= '<td class="text-center">' . escape($value["user_id"]) . '</td>';
            $tableBody .= '<td class="text-center">' . escape($value["prefix"] . '_' . $value["username"]) . '</td>';
            $tableBody .= '<td class="text-center">' . escape($value["clientName"]) . '</td>';
            $tableBody .= '<td class="text-center">' . escape($parentName) . '</td>';
            $tableBody .= '<td class="text-center">' . escape($value["wagerCount"]) . '</td>';
            $tableBody .= '<td class="text-center">' . escape($value["debitAmount"]) . ' 원</td>';

            $tableBody .= '<td class="text-center" style="color:' . $memberWinColor . '">' . $memberWin . ' 원</td>';
            $tableBody .= '<td class="text-center" style="color:' . $agWinColor . '">' . $agWin . ' 원</td>';
            $tableBody .= '<td class="text-center"  style="color:' . $agWinColor . '">' . $companyWin . ' 원</td>';
            $tableBody .= '<td class="text-center">0</td>';

            $tableBody .= '</tr>';


            // <a href="#delete_modal" class="trigger-btn" data-toggle="delete_modal"><i class="pe-7s-trash"> </i></button></a>



        }

        //navigation
        $pagination = '';


        $winLossTotal = count($winLoss);

        $pages = ceil($winLossTotal / $activeNumber);

        $current_page = $activePage == 0 ? 1 : $activePage;
        $total_records = $winLossTotal;
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

                $pagination .= '<li class="page-item"  onclick=displayWinLoss(1)><a href="javascript:void(0);" class="page-link" aria-label="First"><span aria-hidden="true">«</span><span class="sr-only">Last</span></a></li>';
                // $pagination .='<li class="page-item"  onclick=displayWinLoss('.$previous_link.')><a href="javascript:void(0);"class="page-link">'.$previous_link.' &lt;</a></li>';
                for ($i = ($current_page - 2); $i < $current_page; $i++) { //Create left-hand side links
                    if ($i > 0) {
                        $pagination .= '<li class="page-item"  onclick=displayWinLoss(' . $i . ')><a href="javascript:void(0);"class="page-link">' . $i . '</a></li>';

                    }
                }
                $first_link = false; //set first link to false $dateFrom, $dateTo
            }

            //active link
            $pagination .= '<li class="page-item active"><a href="javascript:void(0);"class="page-link">' . $current_page . '</a></li>';

            //create right-hand side links
            for ($i = $current_page + 1; $i < $right_links; $i++) {
                if ($i <= $total_pages) {
                    $pagination .= '<li class="page-item"  onclick=displayWinLoss(' . $i . ') ><a href="javascript:void(0);"class="page-link">' . $i . '</a></li>';

                }
            }
            if ($current_page < $total_pages) {
                // $next_link = ($i > $total_pages) ? $total_pages : $i;
                // // $pagination .='<li class="page-item" onclick=displayWinLoss('.$next_link.')><a href="javascript:void(0);"class="page-link">'.$next_link.' &gt;</a></li>'; //next

                $pagination .= '<li class="page-item"  onclick=displayWinLoss(' . $total_pages . ')><a href="javascript:void(0);" class="page-link" aria-label="Last"><span aria-hidden="true">»</span><span class="sr-only">Last</span></a></li>'; //last

            }

            $pagination .= '</ul>';
        }

        if ($tableBody == '') {
            $tableBody = '<tr> <td class="text-center" colspan="11">기록을 찾을 수 없음!</td> </tr>';
        } else {
            //page Total
            $pageTotalWagerCount = array_sum(array_column($winLossPartial, 'wagerCount'));
            $pageTotalDebitAmount = array_sum(array_column($winLossPartial, 'debitAmount'));
            $pageTotalCreditAmount = array_sum(array_column($winLossPartial, 'creditAmount'));

            $pageTotalMemberWin = $pageTotalCreditAmount - $pageTotalDebitAmount;
            // $pageTotalCompanyWin =  abs($pageTotalMemberWin) * config::get("serviceConfig/companyWinPercentage");
            $pageTotalAgWin = abs($pageTotalMemberWin) - $pageTotalCompanyWin;

            $pageTotalCompanyWin = $pageTotalMemberWin < 0 ? $pageTotalCompanyWin : -$pageTotalCompanyWin;
            $pageTotalAgWin = $pageTotalMemberWin < 0 ? $pageTotalAgWin : -$pageTotalAgWin;

            $pageTotalMemberWinColor = $pageTotalMemberWin < 0 ? 'red' : '';
            $pageTotalAgWinColor = $pageTotalAgWin < 0 ? 'red' : '';


            //formatting numbers
            $pageTotalDebitAmount = $fmt->format($pageTotalDebitAmount);
            $pageTotalMemberWin = $fmt->format($pageTotalMemberWin);
            $pageTotalAgWin = $fmt->format($pageTotalAgWin);
            $pageTotalCompanyWin = $fmt->format($pageTotalCompanyWin);

            // add page Total
            $tableBody .= '<tr style="font-weight:bold" class="bg-sunny-morning">';

            $tableBody .= '<td class="text-left">Page Total</td>';
            $tableBody .= '<td class="text-center"></td>';
            $tableBody .= '<td class="text-center"></td>';
            $tableBody .= '<td class="text-center"></td>';
            $tableBody .= '<td class="text-center"></td>';

            $tableBody .= '<td class="text-center">' . $pageTotalWagerCount . '</td>';
            $tableBody .= '<td class="text-center">' . $pageTotalDebitAmount . ' 원</td>';

            $tableBody .= '<td class="text-center" style="color:' . $pageTotalMemberWinColor . '">' . $pageTotalMemberWin . ' 원</td>';
            $tableBody .= '<td class="text-center" style="color:' . $pageTotalAgWinColor . '">' . $pageTotalAgWin . ' 원</td>';
            $tableBody .= '<td class="text-center"  style="color:' . $pageTotalAgWinColor . '">' . $pageTotalCompanyWin . ' 원</td>';
            $tableBody .= '<td class="text-center">0</td>';

            $tableBody .= '</tr>';


            $totalMemberWinColor = $totalMemberWin < 0 ? 'red' : '';
            $totalAgWinColor = $totalAgWin < 0 ? 'red' : '';

            //formatting numbers
            $totalDebitAmount = $fmt->format($totalDebitAmount);
            $totalMemberWin = $fmt->format($totalMemberWin);
            $totalAgWin = $fmt->format($totalAgWin);
            $totalCompanyWin = $fmt->format($totalCompanyWin);

            // add Total
            $tableBody .= '<tr style="font-weight:bold" class="bg-sunny-morning">';

            $tableBody .= '<td class="text-left">Total</td>';
            $tableBody .= '<td class="text-center"></td>';
            $tableBody .= '<td class="text-center"></td>';
            $tableBody .= '<td class="text-center"></td>';
            $tableBody .= '<td class="text-center"></td>';

            $tableBody .= '<td class="text-center">' . $totalWagerCount . '</td>';
            $tableBody .= '<td class="text-center">' . $totalDebitAmount . ' 원</td>';

            $tableBody .= '<td class="text-center" style="color:' . $totalMemberWinColor . '">' . $totalMemberWin . ' 원</td>';
            $tableBody .= '<td class="text-center" style="color:' . $totalAgWinColor . '">' . $totalAgWin . ' 원</td>';
            $tableBody .= '<td class="text-center"  style="color:' . $totalAgWinColor . '">' . $totalCompanyWin . ' 원</td>';
            $tableBody .= '<td class="text-center">0</td>';

            $tableBody .= '</tr>';

        }

        $token = token::generate("display_winloss");
        print_r(json_encode([$tableBody, $pagination, $winLossTotal, $token]));

    } else {
        $token = token::generate("display_winloss");
        print_r(json_encode([$token]));
    }


} else {
    echo "unauthorized";
    // header('Location: /pages/errors/403.php');      
}
















?>
