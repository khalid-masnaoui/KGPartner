<?php
require_once __DIR__ . "/../../../core/ini.php";
require_once __DIR__ . "/../../../functions/sanitize.php";
require_once __DIR__ . "/../../../functions/encryptDecrypt.php";



if (input::exists("post") && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' && isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] == "https://koreagaming.live/pages/member/accounts/action_logs.php") {

    if (token::check(input::get("token"), "display_logs")) {

        $activePage = input::get("page");
        $activeNumber = input::get("number");
        $activePage = ($activePage == 0 || $activePage == '') ? 1 : $activePage;

        $limit = $activeNumber;
        $offset = ($activePage - 1) * $activeNumber;

        $adminOperator = input::get("adminOperator");
        $adminOperator = trim($adminOperator);
        $adminOperator = escape($adminOperator);

        // $partnerSelect = input::get("partnerSelect");
        $partner = new user();
        $partnerId = $partner->data()["id"];
        $partnerName = $partner->data()["username"];

        $actionSelect = input::get("actionSelect");

        $from = input::get("startDate");
        $to = input::get("endDate");

        $from .= ' 00:00:00';
        $to .= ' 23:59:59';

        $db = DB::getInstance();

        $actionLogsData = [];


        //filters
        $filterQuery = "";
        $parametersQuery = [];

        //partner object filter
        $filterQuery .= " AND pal.partner_id = ?";
        array_push($parametersQuery, $partnerId);

        //dates filter
        $filterQuery .= " AND pal.action_date > ? AND pal.action_date < ?";
        array_push($parametersQuery, $from);
        array_push($parametersQuery, $to);


        //action type filter
        if ($actionSelect != "all") {
            $actionSelectText = config::get("logs/partners")[$actionSelect]["query"];

            $filterQuery .= " AND pal.action = ?";
            array_push($parametersQuery, $actionSelectText);
        }

        //admin operator filter
        if ($adminOperator != "") {
            $filterQuery .= " AND pal.partner_id IN (SELECT id from partner_users where username like ?)";
            array_push($parametersQuery, "%$adminOperator%");
        }


        $sql = "SELECT pal.* FROM partner_activity_logs pal  WHERE 1=1 $filterQuery order by pal.action_date desc";

        $actionLogsData = $db->query($sql, $parametersQuery)->results();

        $actionLogsDataPartial = array_slice($actionLogsData, $offset, $limit);

        $tableBody = '';
        $i = 0;
        $i = ($activePage - 1) * $activeNumber;

        foreach ($actionLogsDataPartial as $key => $value) {
            $i++;

            $ipAddress = $value["ip_address"];
            $countryCode = $value["country_code"];

            if ($countryCode == '' || $countryCode == null) {
                $countryCode = ''; //default flag ?
            }

            //the flag
            // $flagSrc = "https://countryflagsapi.com/png/$countryCode";
            $flagSrc = "/assets/images/flags/logs/" . strtolower($countryCode) . ".png";

            $nation = "<img src='$flagSrc' width='24' height='18' alt='$countryCode'>";

            $tableBody .= '<tr>';

            $tableBody .= '<td class="text-center text-muted">' . $i . '</td>';
            $tableBody .= '<td class="text-center">' . escape($value["id"]) . '</td>';
            $tableBody .= '<td class="text-center">' . escape($partnerName) . '</td>';
            $tableBody .= '<td class="text-center">' . escape($partnerName) . '</td>';
            $tableBody .= '<td class="text-center">' . escape($value["action"]) . '</td>';
            $tableBody .= '<td class="text-center">' . escape($ipAddress) . ' </td>';

            $tableBody .= '<td class="text-center">' . $nation . '</td>';
            $tableBody .= '<td class="text-center">' . escape($value["action_date"]) . '</td>';

            $tableBody .= '</tr>';

        }

        //navigation
        $pagination = '';

        // $sql = "SELECT count(*) as rowsCount FROM admin_activity_logs aal LEFT JOIN admin_users ad ON aal.admin_id = ad.id LEFT JOIN clients cl ON aal.object = cl.id";

        // $actionLogsDataCount = $db->query($sql,[])->first();
        // $actionLogsDataTotal = $actionLogsDataCount["rowsCount"];

        $actionLogsDataTotal = count($actionLogsData);


        $pages = ceil($actionLogsDataTotal / $activeNumber);

        $current_page = $activePage == 0 ? 1 : $activePage;
        $total_records = $actionLogsDataTotal;
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

                $pagination .= '<li class="page-item"  onclick=displayActionLogs(1)><a href="javascript:void(0);" class="page-link" aria-label="First"><span aria-hidden="true">«</span><span class="sr-only">Last</span></a></li>';
                for ($i = ($current_page - 2); $i < $current_page; $i++) { //Create left-hand side links
                    if ($i > 0) {
                        $pagination .= '<li class="page-item"  onclick=displayActionLogs(' . $i . ')><a href="javascript:void(0);"class="page-link">' . $i . '</a></li>';

                    }
                }
                $first_link = false; //set first link to false $dateFrom, $dateTo
            }

            //active link
            $pagination .= '<li class="page-item active"><a href="javascript:void(0);"class="page-link">' . $current_page . '</a></li>';

            //create right-hand side links
            for ($i = $current_page + 1; $i < $right_links; $i++) {
                if ($i <= $total_pages) {
                    $pagination .= '<li class="page-item"  onclick=displayActionLogs(' . $i . ') ><a href="javascript:void(0);"class="page-link">' . $i . '</a></li>';

                }
            }
            if ($current_page < $total_pages) {
                // $next_link = ($i > $total_pages) ? $total_pages : $i;
                $pagination .= '<li class="page-item"  onclick=displayActionLogs(' . $total_pages . ')><a href="javascript:void(0);" class="page-link" aria-label="Last"><span aria-hidden="true">»</span><span class="sr-only">Last</span></a></li>'; //last

            }

            $pagination .= '</ul>';
        }

        if ($tableBody == '') {
            $tableBody = '<tr> <td class="text-center" colspan="8">No Records Found!</td> </tr>';
        }

        $token = token::generate("display_logs");
        print_r(json_encode([$tableBody, $pagination, $actionLogsDataTotal, $token]));

    } else {
        $token = token::generate("display_logs");
        print_r(json_encode([$token]));
    }


} else {
    echo "unauthorized";
    // header('Location: /pages/errors/403.php');      
}
















?>
