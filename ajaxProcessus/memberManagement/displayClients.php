<?php
require_once __DIR__ . "/../../core/ini.php";
require_once __DIR__ . "/../../functions/sanitize.php";
require_once __DIR__ . "/../../functions/encryptDecrypt.php";


if (input::exists("post") && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' && isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] == "https://koreagaming.info/pages/member/member_management/clients_list.php") {

    if (token::check(input::get("token"), "display")) {

        $activePage = input::get("page");
        $activeNumber = input::get("number");
        $activePage = ($activePage == 0 || $activePage == '') ? 1 : $activePage;

        $limit = $activeNumber;
        $offset = ($activePage - 1) * $activeNumber;

        $nameFilter = input::get("nameFilter");
        $nameFilter = escape($nameFilter);

        $statusFilter = input::get("status");
        $sort = input::get("sort");

        $db = DB::getInstance();

        $queryParameters = [];
        $statusFilterQuery = '';

        $partner = new user();
        $partnerPtId = $partner->data()["pt_id"];
        $partnerPtName = $partner->data()["username"];


        $partnerFilter = " AND  (cl.pt_id = ? OR cl.pt_id REGEXP ?)";
        $queryParameters[] = "$partnerPtId";
        $queryParameters[] = "^$partnerPtId/[0-9/]*$";



        if ($statusFilter != '') {
            $statusFilterQuery .= " AND cl.status = ? ";
            $queryParameters[] = $statusFilter;
        }
        $nameFilterQuery = $nameFilter != '' ? " AND cl.username like ?" : "";
        if ($nameFilter != '') {
            $queryParameters[] = "%$nameFilter%";
        }


        $clients = $db->query("SELECT  cl.id,cl.rate, cl.username,cl.name,cl.prefix,cl.register_date, cl.status,cl.spadeEvoSkin, cl.DgSkin, cl.DwSkin, cl.WmSkin, cl.OrSkin, cl.AgSkin, cl.BgSkin, cl.BtSkin, pu.username as parent, IFNULL(cb.balance, '0.00') as balance , GROUP_CONCAT(cp.product_id SEPARATOR ',') as productsIds from clients cl LEFT JOIN clients_balance  cb ON cl.id = cb.client_id LEFT JOIN client_products cp ON cl.id = cp.client_id  JOIN partner_users pu ON cl.pt_id = pu.pt_id $partnerFilter where 1=1 $statusFilterQuery  $nameFilterQuery group by cl.id ,pu.username order by id $sort limit $limit offset $offset", $queryParameters)->results();


        $tableBody = '';
        $i = 0;
        $i = ($activePage - 1) * $activeNumber;

        $i = $sort == "DESC" ? count($clients) : 0;

        foreach ($clients as $key => $value) {
            // $i++;
            if ($sort == "ASC") {
                $i++;
            }

            $value["balance"] = $fmt->format($value["balance"]);

            if ($value["productsIds"] != null) {
                $value["productsIds"] = array_map('trim', explode(',', $value["productsIds"]));
            }

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

            $balanceColor = '';
            $roundedBalance = (int) str_replace(',', '', $value["balance"]);
            if ($roundedBalance - 100000000 < 0) {
                $balanceColor = 'red';
            }

            $tableBody .= '<tr>';

            $tableBody .= '<td class="text-center text-muted">' . $i . '</td>';
            $tableBody .= '<td class="text-center">' . escape($value["parent"]) . '</td>';
            $tableBody .= '<td class="text-center">' . escape($value["username"]) . '</td>';
            $tableBody .= '<td class="text-center">' . escape($value["name"]) . '</td>';
            $tableBody .= '<td class="text-center">' . escape($value["prefix"]) . '</td>';
            $tableBody .= '<td class="text-center" style="color:' . $balanceColor . '">' . escape($value["balance"]) . ' 원</td>';
            $tableBody .= '<td class="text-center">' . escape($value["rate"]) . ' %</td>';
            $tableBody .= '<td class="text-center">' . escape($value["register_date"]) . '</td>';
            $tableBody .= '<td class="text-center">' . $statusHtml . '</td>';


            // if ($value["parent"] === $partnerPtName) {
            //     $tableBody .= '<td class="text-center">
            //     <button class="mb-2 mr-2 btn-transition  btn btn-outline-primary btn_action_edit" data-toggle="modal"
            //     data-target=".add_client" data-values=' . $jsonValues . ' onclick=showClientData(event)><i class="pe-7s-edit"> </i></button>
            //     </td>';
            // } else {
            //     $tableBody .= '<td class="text-center">-</td>';
            // }

            $tableBody .= '<td class="text-center"><button class="mb-2 mt-2 ml-2 btn btn-light active" data-toggle="modal"
            data-target=".add_deposit" onclick=hideEditableInputsAndShow2();setTheCP(' . $value["id"] . ');> <span style="font-weight: bolder;color: black;">+</span></button>
            <button class="mb-2 mt-2 ml-2 btn btn-light active" data-toggle="modal"
                                            data-target=".add_deposit" onclick=hideEditableInputsAndShowDeduct();setTheCP(' . $value["id"] . ');>
                                            <span style="font-weight: bolder;color: black;">-</span> </button>
                                            </td>';

            $tableBody .= '<td class="text-center">
                <button class="mb-2 mr-2 btn-transition  btn btn-outline-primary btn_action_edit" data-toggle="modal"
                data-target=".add_client" data-values=' . $jsonValues . ' onclick=showClientData(event)><i class="pe-7s-edit"> </i></button>
                </td>';

            $tableBody .= '</tr>';

            if ($sort == "DESC") {
                $i--;
            }
        }

        //navigation
        $pagination = '';
        $clientsTotal = $db->query("SELECT count(*) as total from clients cl where 1=1 $partnerFilter $statusFilterQuery $nameFilterQuery", $queryParameters)->first();

        $clientsTotal = $clientsTotal["total"];

        $pages = ceil($clientsTotal / $activeNumber);

        $current_page = $activePage == 0 ? 1 : $activePage;
        $total_records = $clientsTotal;
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

                $pagination .= '<li class="page-item"  onclick=displayClients(1,' . $statusFilter . ')><a href="javascript:void(0);" class="page-link" aria-label="First"><span aria-hidden="true">«</span><span class="sr-only">Last</span></a></li>';

                for ($i = ($current_page - 2); $i < $current_page; $i++) { //Create left-hand side links
                    if ($i > 0) {
                        $pagination .= '<li class="page-item"  onclick=displayClients(' . $i . ',' . $statusFilter . ')><a href="javascript:void(0);"class="page-link">' . $i . '</a></li>';

                    }
                }
                $first_link = false; //set first link to false $dateFrom, $dateTo
            }

            //active link
            $pagination .= '<li class="page-item active"><a href="javascript:void(0);"class="page-link">' . $current_page . '</a></li>';

            //create right-hand side links
            for ($i = $current_page + 1; $i < $right_links; $i++) {
                if ($i <= $total_pages) {
                    $pagination .= '<li class="page-item"  onclick=displayClients(' . $i . ',' . $statusFilter . ') ><a href="javascript:void(0);"class="page-link">' . $i . '</a></li>';
                }
            }
            //last
            if ($current_page < $total_pages) {
                $pagination .= '<li class="page-item"  onclick=displayClients(' . $total_pages . ',' . $statusFilter . ')><a href="javascript:void(0);" class="page-link" aria-label="Last"><span aria-hidden="true">»</span><span class="sr-only">Last</span></a></li>';
            }

            $pagination .= '</ul>';
        }

        if ($tableBody == '') {
            $tableBody = '<tr> <td class="text-center" colspan="11">데이터 없음!</td> </tr>';
        }


        $token = token::generate("display");
        print_r(json_encode([$tableBody, $pagination, $clientsTotal, $token]));

    } else {
        $token = token::generate("display");
        print_r(json_encode([$token]));
    }

} else {
    echo "unauthorized";
    // header('Location: /pages/errors/403.php');      
}

















?>
