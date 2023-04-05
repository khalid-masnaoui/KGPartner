<?php
require_once __DIR__ . "/../../core/ini.php";
require_once __DIR__ . "/../../functions/sanitize.php";
require_once __DIR__ . "/../../functions/encryptDecrypt.php";



if (input::exists("post") && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' && isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] == "https://koreagaming.live/pages/infos/deposit_withdraw/deposit_withdraw_transaction.php") {

    if (token::check(input::get("token"), "display_deposits")) {

        $activePage = input::get("page");
        $activeNumber = input::get("number");
        $activePage = ($activePage == 0 || $activePage == '') ? 1 : $activePage;

        $limit = $activeNumber;
        $offset = ($activePage - 1) * $activeNumber;

        $depositor = input::get("depositor");
        $depositor = trim($depositor);
        $depositor = escape($depositor);

        $client = input::get("client");
        $client = trim($client);
        $client = escape($client);

        $from = input::get("startDate");
        $to = input::get("endDate");

        $from .= ' 00:00:00';
        $to .= ' 23:59:59';

        $partner = new user();
        $partnerPtId = $partner->data()["pt_id"];

        $filterQuery = "";
        $parametersQuery = [];


        $filterQuery = " AND cl.pt_id = ?";
        $parametersQuery[] = "$partnerPtId";

        if ($client != 'all') {
            $filterQuery .= " AND d.client_id = ?";
            $parametersQuery[] = $client;
        }

        $filterQuery .= " AND (d.created_at >= ? AND d.created_at <= ?) ";
        $parametersQuery[] = $from;
        $parametersQuery[] = $to;

        if ($depositor != '') {
            $filterQuery .= " AND cl.username like ?";
            $parametersQuery[] = "%$depositor%";
        }
        $db = DB::getInstance();

        $sql = "SELECT cl.username , d.*,  pu.username as parent  FROM deposits d JOIN clients cl ON d.client_id = cl.id  JOIN partner_users pu ON cl.pt_id = pu.pt_id  WHERE 1=1 {$filterQuery} ORDER BY d.id desc LIMIT $offset, $limit";

        $depositsData = $db->query($sql, $parametersQuery)->results();


        $pageTotalAmount = 0;

        $tableBody = '';
        $i = 0;
        $i = ($activePage - 1) * $activeNumber;

        foreach ($depositsData as $key => $value) {
            $i++;

            //page amount
            $pageTotalAmount += $value["amount"];
            $value["amount"] = $fmt->format($value["amount"]);


            $jsonValues = str_replace(" ", "&&xx&&", json_encode($value));
            $jsonValues = escape($jsonValues);

            $issuer = $value["admin_id"];
            if (strpos($issuer, 'p:') === false) {
                $issuer = "Site";
            } else {
                $issuer = explode("p:", $issuer)[1];
                $issuerSql = $db->get("username", "partner_users", [["id", "=", $issuer]]);
                if ($issuerSql->count()) {
                    $issuer = $issuerSql->first()["username"];
                }
            }

            $balanceColor = '';
            $roundedBalance = (int) str_replace(',', '', $value["amount"]);
            if ($roundedBalance < 0) {
                $balanceColor = 'red';
            }

            $tableBody .= '<tr>';

            $tableBody .= '<td class="text-center text-muted">' . $i . '</td>';
            $tableBody .= '<td class="text-center">' . escape($value["client_id"]) . '</td>';
            $tableBody .= '<td class="text-center">' . escape($value["username"]) . '</td>';
            $tableBody .= '<td class="text-center">' . escape($value["parent"]) . '</td>';
            $tableBody .= '<td class="text-center" style="font-weight: bold;color:' . $balanceColor . '">' . escape($value["amount"]) . ' 원</td>';
            $tableBody .= '<td class="text-center">' . escape($issuer) . '</td>';
            $tableBody .= '<td class="text-center">' . escape($value["created_at"]) . '</td>';
            // $tableBody .= '<td class="text-center">
            // <button class="mb-2 mr-2 btn-transition  btn btn-outline-primary btn_action_edit" data-toggle="modal"
            // data-target=".add_deposit" data-values=' . $jsonValues . ' onclick=showDepositData(event)><i class="pe-7s-edit"> </i></button>
            // <button class="mb-2 mr-2 btn btn-danger active btn_action_delete"  data-id=' . $value['id'] . '  onclick=confirmDeleteDeposit(event)>
            // <i class="pe-7s-trash"> 
            // </td>';

            $tableBody .= '<td class="text-center">
            <button class="mb-2 mr-2 btn btn-danger active btn_action_delete"  data-id=' . $value['id'] . '  onclick=confirmDeleteDeposit(event)>
            <i class="pe-7s-trash"> 
            </td>';


            $tableBody .= '</tr>';



        }

        //navigation
        $pagination = '';


        // $depositsTotal = count($depositsData);

        $sql = "SELECT count(*) as rowsCount FROM deposits d JOIN clients cl ON d.client_id = cl.id WHERE 1=1 {$filterQuery}";

        $depositsTotalCount = $db->query($sql, $parametersQuery)->first();
        $depositsTotal = $depositsTotalCount["rowsCount"];

        $db = DB::getInstance();

        $pages = ceil($depositsTotal / $activeNumber);

        $current_page = $activePage == 0 ? 1 : $activePage;
        $total_records = $depositsTotal;
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

                $pagination .= '<li class="page-item"  onclick=displayDeposits(1)><a href="javascript:void(0);" class="page-link" aria-label="First"><span aria-hidden="true">«</span><span class="sr-only">Last</span></a></li>';
                // $pagination .='<li class="page-item"  onclick=displayDeposits('.$previous_link.')><a href="javascript:void(0);"class="page-link">'.$previous_link.' &lt;</a></li>';
                for ($i = ($current_page - 2); $i < $current_page; $i++) { //Create left-hand side links
                    if ($i > 0) {
                        $pagination .= '<li class="page-item"  onclick=displayDeposits(' . $i . ')><a href="javascript:void(0);"class="page-link">' . $i . '</a></li>';

                    }
                }
                $first_link = false; //set first link to false $dateFrom, $dateTo
            }

            //active link
            $pagination .= '<li class="page-item active"><a href="javascript:void(0);"class="page-link">' . $current_page . '</a></li>';

            //create right-hand side links
            for ($i = $current_page + 1; $i < $right_links; $i++) {
                if ($i <= $total_pages) {
                    $pagination .= '<li class="page-item"  onclick=displayDeposits(' . $i . ') ><a href="javascript:void(0);"class="page-link">' . $i . '</a></li>';

                }
            }
            if ($current_page < $total_pages) {
                // $next_link = ($i > $total_pages) ? $total_pages : $i;
                // // $pagination .='<li class="page-item" onclick=displayDeposits('.$next_link.')><a href="javascript:void(0);"class="page-link">'.$next_link.' &gt;</a></li>'; //next

                $pagination .= '<li class="page-item"  onclick=displayDeposits(' . $total_pages . ')><a href="javascript:void(0);" class="page-link" aria-label="Last"><span aria-hidden="true">»</span><span class="sr-only">Last</span></a></li>'; //last

            }

            $pagination .= '</ul>';
        }

        if ($tableBody == '') {
            $tableBody = '<tr> <td class="text-center" colspan="8">데이터 없음!</td> </tr>';
        } else {
            $pageTotalAmount = $fmt->format($pageTotalAmount);

            // add page Total
            $tableBody .= '<tr style="font-weight:bold" class="bg-sunny-morning">';

            $tableBody .= '<td class="text-left">페이지 합계</td>';
            $tableBody .= '<td class="text-center"></td>';
            $tableBody .= '<td class="text-center"></td>';
            $tableBody .= '<td class="text-center"></td>';
            $tableBody .= '<td class="text-center">' . $pageTotalAmount . ' 원</td>';
            $tableBody .= '<td class="text-center"></td>';
            $tableBody .= '<td class="text-center"></td>';
            $tableBody .= '<td class="text-center"></td>';

            $tableBody .= '</tr>';

            //add total
            $sql = "SELECT sum(amount) as totalAmount FROM deposits d JOIN clients cl ON d.client_id = cl.id WHERE 1=1 {$filterQuery}";
            $totalAmount = $db->query($sql, $parametersQuery)->first()["totalAmount"];

            $totalAmount = $fmt->format($totalAmount);


            $tableBody .= '<tr style="font-weight:bold" class="bg-sunny-morning">';

            $tableBody .= '<td class="text-left">총 합계</td>';
            $tableBody .= '<td class="text-center"></td>';
            $tableBody .= '<td class="text-center"></td>';
            $tableBody .= '<td class="text-center"></td>';
            $tableBody .= '<td class="text-center">' . $totalAmount . ' 원</td>';
            $tableBody .= '<td class="text-center"></td>';
            $tableBody .= '<td class="text-center"></td>';
            $tableBody .= '<td class="text-center"></td>';

            $tableBody .= '</tr>';
        }

        $token = token::generate("display_deposits");
        print_r(json_encode([$tableBody, $pagination, $depositsTotal, $token]));

    } else {
        $token = token::generate("display_deposits");
        print_r(json_encode([$token]));
    }


} else {
    echo "unauthorized";
    // header('Location: /pages/errors/403.php');      
}


















?>
