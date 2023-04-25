<?php
require_once __DIR__ . "/../../core/ini.php";
require_once __DIR__ . "/../../functions/sanitize.php";
require_once __DIR__ . "/../../functions/encryptDecrypt.php";



if (input::exists("post") && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' && isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] == "https://koreagaming.info/pages/infos/commissions/partners_commissions_total.php") {

    if (token::check(input::get("token"), "display_commissions_total")) {

        $activePage = input::get("page");
        $activeNumber = input::get("number");
        $activePage = ($activePage == 0 || $activePage == '') ? 1 : $activePage;

        $limit = $activeNumber;
        $offset = ($activePage - 1) * $activeNumber;

        $partner = input::get("partner");
        $partner = trim($partner);
        $partner = escape($partner);

        $partnerUser = new user();
        $partnerPtId = $partnerUser->data()["pt_id"];

        $filterQuery = "";
        $parametersQuery = [];

        //lower partners
        // $filterQuery = " AND ( pu.pt_id = ? OR pu.pt_id like ?)";
        // $parametersQuery[] = "$partnerPtId";
        // $parametersQuery[] = "$partnerPtId/%";

        //current self partner
        $filterQuery = " AND (pu.pt_id = ? OR pu.pt_id REGEXP ?)";
        $parametersQuery[] = "$partnerPtId";
        $parametersQuery[] = "^$partnerPtId/[0-9/]*$";

        if ($partner != 'all') {
            $filterQuery .= " AND pu.id = ?";
            $parametersQuery[] = $partner;
        }


        $sql = "SELECT  pu.username , IFNULL(pb.balance, '0.00') AS balance FROM partner_users pu LEFT JOIN partners_balance pb  ON pu.id = pb.partner_id  WHERE 1=1 {$filterQuery} ORDER BY pu.id desc LIMIT $offset, $limit";

        $db = DB::getInstance();

        $CommissionsData = $db->query($sql, $parametersQuery)->results();

        $pageTotalAmount = 0;
        $tableBody = '';
        $i = 0;
        $i = ($activePage - 1) * $activeNumber;

        foreach ($CommissionsData as $key => $value) {
            $i++;

            //page amount
            $pageTotalAmount += $value["balance"];
            $value["balance"] = $fmt->format($value["balance"]);




            $tableBody .= '<tr>';

            $tableBody .= '<td class="text-center text-muted">' . $i . '</td>';
            $tableBody .= '<td class="text-center">' . escape($value["username"]) . '</td>';
            $tableBody .= '<td class="text-center" style="font-weight: bold;">' . escape($value["balance"]) . ' 원</td>';

            $tableBody .= '</tr>';



        }

        //navigation
        $pagination = '';


        // $commissionsTotal = count($CommissionsData);

        $sql = "SELECT count(*) as rowsCount FROM partner_users pu WHERE 1=1 {$filterQuery}";

        $commissionsTotalCount = $db->query($sql, $parametersQuery)->first();
        $commissionsTotal = $commissionsTotalCount["rowsCount"];

        $pages = ceil($commissionsTotal / $activeNumber);

        $current_page = $activePage == 0 ? 1 : $activePage;
        $total_records = $commissionsTotal;
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

                $pagination .= '<li class="page-item"  onclick=displayCommissions(1)><a href="javascript:void(0);" class="page-link" aria-label="First"><span aria-hidden="true">«</span><span class="sr-only">Last</span></a></li>';
                // $pagination .='<li class="page-item"  onclick=displayCommissions('.$previous_link.')><a href="javascript:void(0);"class="page-link">'.$previous_link.' &lt;</a></li>';
                for ($i = ($current_page - 2); $i < $current_page; $i++) { //Create left-hand side links
                    if ($i > 0) {
                        $pagination .= '<li class="page-item"  onclick=displayCommissions(' . $i . ')><a href="javascript:void(0);"class="page-link">' . $i . '</a></li>';

                    }
                }
                $first_link = false; //set first link to false $dateFrom, $dateTo
            }

            //active link
            $pagination .= '<li class="page-item active"><a href="javascript:void(0);"class="page-link">' . $current_page . '</a></li>';

            //create right-hand side links
            for ($i = $current_page + 1; $i < $right_links; $i++) {
                if ($i <= $total_pages) {
                    $pagination .= '<li class="page-item"  onclick=displayCommissions(' . $i . ') ><a href="javascript:void(0);"class="page-link">' . $i . '</a></li>';

                }
            }
            if ($current_page < $total_pages) {
                // $next_link = ($i > $total_pages) ? $total_pages : $i;
                // // $pagination .='<li class="page-item" onclick=displayCommissions('.$next_link.')><a href="javascript:void(0);"class="page-link">'.$next_link.' &gt;</a></li>'; //next

                $pagination .= '<li class="page-item"  onclick=displayCommissions(' . $total_pages . ')><a href="javascript:void(0);" class="page-link" aria-label="Last"><span aria-hidden="true">»</span><span class="sr-only">Last</span></a></li>'; //last

            }

            $pagination .= '</ul>';
        }

        if ($tableBody == '') {
            $tableBody = '<tr> <td class="text-center" colspan="3">데이터 없음!</td> </tr>';
        } else {
            $pageTotalAmount = $fmt->format($pageTotalAmount);

            // add page Total
            $tableBody .= '<tr style="font-weight:bold" class="bg-sunny-morning">';

            $tableBody .= '<td class="text-left">Page Total</td>';
            $tableBody .= '<td class="text-center"></td>';
            $tableBody .= '<td class="text-center">' . $pageTotalAmount . ' 원</td>';

            //add total
            $sql = "SELECT sum(balance) as totalAmount FROM partners_balance pb JOIN partner_users pu ON  pb.partner_id = pu.id  WHERE 1=1 {$filterQuery}";
            $totalAmount = $db->query($sql, $parametersQuery)->first()["totalAmount"];

            $totalAmount = $fmt->format($totalAmount);


            $tableBody .= '<tr style="font-weight:bold" class="bg-sunny-morning">';

            $tableBody .= '<td class="text-left">Total</td>';
            $tableBody .= '<td class="text-center"></td>';
            $tableBody .= '<td class="text-center">' . $totalAmount . ' 원</td>';

            $tableBody .= '</tr>';
        }

        $token = token::generate("display_commissions_total");
        print_r(json_encode([$tableBody, $pagination, $commissionsTotal, $token]));

    } else {
        $token = token::generate("display_commissions_total");
        print_r(json_encode([$token]));
    }


} else {
    echo "unauthorized";
    // header('Location: /pages/errors/403.php');      
}


















?>
