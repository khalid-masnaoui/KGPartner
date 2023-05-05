<?php
require_once __DIR__ . "/../../../core/ini.php";
require_once __DIR__ . "/../../../functions/sanitize.php";
require_once __DIR__ . "/../../../functions/encryptDecrypt.php";


if (input::exists("post") && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' && isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] == "https://koreagaming.info/pages/member/member_management/partners_list.php") {

    if (token::check(input::get("token"), "display_partners")) {

        $activePage = input::get("page");
        $activeNumber = input::get("number");
        $activePage = ($activePage == 0 || $activePage == '') ? 1 : $activePage;

        $limit = $activeNumber;
        $offset = ($activePage - 1) * $activeNumber;

        $nameFilter = input::get("nameFilter");
        $nameFilter = escape($nameFilter);

        $statusFilter = input::get("status");

        $db = DB::getInstance();

        $queryParameters = [];
        $statusFilterQuery = '';

        $partner = new user();
        $partnerPtId = $partner->data()["pt_id"];
        $partnerPtName = $partner->data()["username"];


        $partnerFilter = " AND  (pt_id = ? OR pt_id REGEXP ?)";
        $queryParameters[] = "$partnerPtId";
        $queryParameters[] = "^$partnerPtId/[0-9/]*$";

        if ($statusFilter != '') {
            $statusFilterQuery .= " AND status = ? ";
            $queryParameters[] = $statusFilter;
        }
        $nameFilterQuery = $nameFilter != '' ? " AND username like ?" : "";
        if ($nameFilter != '') {
            $queryParameters[] = "%$nameFilter%";
        }


        $partners = $db->query("SELECT * from partner_users where 1=1 $partnerFilter  $statusFilterQuery $nameFilterQuery order by id  limit $limit offset $offset", $queryParameters)->results();

        // print_r($partners);
        // die;
        // SELECT * from partner_users where 1=1 AND  (pt_id = '1' OR pt_id REGEXP '^1/[0-9]*$') order by id desc limit 20 offset 0

        $tableBody = '';
        $i = 0;
        $i = ($activePage - 1) * $activeNumber;

        $i = count($partners);

        foreach ($partners as $key => $value) {
            // $i++;

            // $value["rate"] = $fmt->format($value["rate"]);

            //get parent
            $parent = 'self';
            $parentName = 'site';
            $partnerId = $value["pt_id"];
            if (strpos($partnerId, "/") !== false && substr($partnerId, 0, 1) != "/") {

                $parentsArray = explode("/", $partnerId);
                $length = count($parentsArray);

                $parent = $parentsArray[$length - 2];
                $parentName = $db->get("username", "partner_users", array(["id", "=", $parent]))->first()["username"];

            }

            $value["parent"] = $parent;


            $jsonValues = str_replace(" ", "&&xx&&", json_encode($value));
            $jsonValues = escape($jsonValues);

            $status = $value["status"];

            $statusHtml = '';
            if ($status == 1) {
                $statusHtml = '<div class="mb-2 mr-2 badge badge-pill badge-success">정상</div>';
            } else if ($status == 0) {
                $statusHtml = '<div class="mb-2 mr-2 badge badge-pill badge-warning" style="color:white !important;">대기</div>';
            } else {
                $statusHtml = '<div class="mb-2 mr-2 badge badge-pill badge-danger">차단</div>';
            }

            $value["wa_balance"] = $fmt->format($value["wa_balance"]);


            $tableBody .= '<tr>';

            $tableBody .= '<td class="text-center text-muted">' . $i . '</td>';
            $tableBody .= '<td class="text-center">' . escape($value["username"]) . '</td>';
            $tableBody .= '<td class="text-center">' . escape($value["wa_balance"]) . '</td>';
            $tableBody .= '<td class="text-center">' . escape($value["rate"]) . ' %</td>';
            $tableBody .= '<td class="text-center">' . escape($parentName) . '</td>';
            $tableBody .= '<td class="text-center">' . $statusHtml . '</td>';

            // if ($parentName === $partnerPtName) {
            //     $tableBody .= '<td class="text-center">
            //     <button class="mb-2 mr-2 btn-transition  btn btn-outline-primary btn_action_edit" data-toggle="modal"
            //     data-target=".add_partner" data-values=' . $jsonValues . ' onclick=showPartnerData(event)><i class="pe-7s-edit"> </i></button>
            //     </td>';
            // } else {
            //     $tableBody .= '<td class="text-center">-</td>';
            // }

             if ($partnerPtName !== $value["username"]) {
                $tableBody .= '<td class="text-center">
                <button class="mb-2 mr-2 btn-transition  btn btn-outline-primary btn_action_edit" data-toggle="modal"
                data-target=".add_partner" data-values=' . $jsonValues . ' onclick=showPartnerData(event)><i class="pe-7s-edit"> </i></button>
                </td>';
            } else {
                $tableBody .= '<td class="text-center">-</td>';
            }

            $tableBody .= '</tr>';

            $i--;
        }

        //navigation
        $pagination = '';
        $partnersTotal = $db->query("SELECT count(*) as total from partner_users where 1=1 $partnerFilter $statusFilterQuery $nameFilterQuery", $queryParameters)->first();

        $partnersTotal = $partnersTotal["total"];

        $pages = ceil($partnersTotal / $activeNumber);

        $current_page = $activePage == 0 ? 1 : $activePage;
        $total_records = $partnersTotal;
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

                $pagination .= '<li class="page-item"  onclick=displayPartners(1,' . $statusFilter . ')><a href="javascript:void(0);" class="page-link" aria-label="First"><span aria-hidden="true">«</span><span class="sr-only">Last</span></a></li>';

                for ($i = ($current_page - 2); $i < $current_page; $i++) { //Create left-hand side links
                    if ($i > 0) {
                        $pagination .= '<li class="page-item"  onclick=displayPartners(' . $i . ',' . $statusFilter . ')><a href="javascript:void(0);"class="page-link">' . $i . '</a></li>';

                    }
                }
                $first_link = false; //set first link to false $dateFrom, $dateTo
            }

            //active link
            $pagination .= '<li class="page-item active"><a href="javascript:void(0);"class="page-link">' . $current_page . '</a></li>';

            //create right-hand side links
            for ($i = $current_page + 1; $i < $right_links; $i++) {
                if ($i <= $total_pages) {
                    $pagination .= '<li class="page-item"  onclick=displayPartners(' . $i . ',' . $statusFilter . ') ><a href="javascript:void(0);"class="page-link">' . $i . '</a></li>';
                }
            }
            //last
            if ($current_page < $total_pages) {
                $pagination .= '<li class="page-item"  onclick=displayPartners(' . $total_pages . ',' . $statusFilter . ')><a href="javascript:void(0);" class="page-link" aria-label="Last"><span aria-hidden="true">»</span><span class="sr-only">Last</span></a></li>';
            }

            $pagination .= '</ul>';
        }

        if ($tableBody == '') {
            $tableBody = '<tr> <td class="text-center" colspan="7">데이터 없음!</td> </tr>';
        }


        $token = token::generate("display_partners");
        print_r(json_encode([$tableBody, $pagination, $partnersTotal, $token]));

    } else {
        $token = token::generate("display_partners");
        print_r(json_encode([$token]));
    }

} else {
    echo "unauthorized";
    // header('Location: /pages/errors/403.php');      
}

















?>
