<?php
require_once __DIR__ . "/../../core/ini.php";
require_once __DIR__ . "/../../functions/sanitize.php";
require_once __DIR__ . "/../../functions/encryptDecrypt.php";
require_once __DIR__ . "/../../vendor/autoload.php";


if (input::exists("post") && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' && isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] == "https://koreagaming.live/pages/member/accounts/announcements.php") {

    if (token::check(input::get("token"), "display_announcements")) {

        $activePage = input::get("page");
        $activeNumber = input::get("number");
        $activePage = ($activePage == 0 || $activePage == '') ? 1 : $activePage;

        $limit = $activeNumber;
        $offset = ($activePage - 1) * $activeNumber;

        $partner = new user();
        $partnerId = $partner->data()["id"];

        //preparing filters data
        $filterQuery = '';
        $parametersQuery = [];

        $filterQuery .= " AND ca.receiver = ? OR ca.receiver='all' "; // or receiver = 'all'
        $parametersQuery[] = $partnerId;


        $sql = "SELECT ca.* FROM partners_announcements ca where 1=1 $filterQuery ORDER BY updated_at DESC";


        $announcementsBuilder = new Announcements();

        $announcementsData = $announcementsBuilder->getAnnouncementsCustomData($sql, $parametersQuery);

        $announcementsDataPartial = array_slice($announcementsData, $offset, $limit);



        $db = DB::getInstance();

        $tableBody = '';
        $i = 0;
        $i = ($activePage - 1) * $activeNumber;

        $purifier = new HTMLPurifier();

        foreach ($announcementsDataPartial as $key => $value) {
            $i++;

            //purify the message --> we need to display the html
            $value["message"] = $purifier->purify($value["message"]);

            $jsonValues = str_replace(" ", "&&xx&&", json_encode($value));
            $jsonValues = escape($jsonValues);


            $tableBody .= '<tr>';

            $tableBody .= '<td class="text-center text-muted">' . $i . '</td>';
            $tableBody .= '<td class="text-center" data-toggle="modal"
            data-target=".show_announcement" data-values=' . $jsonValues . ' onclick=showAnnouncementDataInfo(event) style="cursor: pointer;">' . escape($value["title"]) . '</td>';
            $tableBody .= '<td class="text-center">' . escape($value["updated_at"]) . '</td>';



            $tableBody .= '</tr>';

        }

        //navigation
        $pagination = '';
        $announcementsTotal = count($announcementsData);

        $pages = ceil($announcementsTotal / $activeNumber);

        $current_page = $activePage == 0 ? 1 : $activePage;
        $total_records = $announcementsTotal;
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

                $pagination .= '<li class="page-item"  onclick=displayAnnouncements(1)><a href="javascript:void(0);" class="page-link" aria-label="First"><span aria-hidden="true">«</span><span class="sr-only">Last</span></a></li>';

                for ($i = ($current_page - 2); $i < $current_page; $i++) { //Create left-hand side links
                    if ($i > 0) {
                        $pagination .= '<li class="page-item"  onclick=displayAnnouncements(' . $i . ')><a href="javascript:void(0);"class="page-link">' . $i . '</a></li>';

                    }
                }
                $first_link = false; //set first link to false $dateFrom, $dateTo
            }

            //active link
            $pagination .= '<li class="page-item active"><a href="javascript:void(0);"class="page-link">' . $current_page . '</a></li>';

            //create right-hand side links
            for ($i = $current_page + 1; $i < $right_links; $i++) {
                if ($i <= $total_pages) {
                    $pagination .= '<li class="page-item"  onclick=displayAnnouncements(' . $i . ') ><a href="javascript:void(0);"class="page-link">' . $i . '</a></li>';
                }
            }
            //last
            if ($current_page < $total_pages) {
                $pagination .= '<li class="page-item"  onclick=displayAnnouncements(' . $total_pages . ')><a href="javascript:void(0);" class="page-link" aria-label="Last"><span aria-hidden="true">»</span><span class="sr-only">Last</span></a></li>';
            }

            $pagination .= '</ul>';
        }

        if ($tableBody == '') {
            $tableBody = '<tr> <td class="text-center" colspan="3">No Data Available!</td> </tr>';
        }


        $token = token::generate("display_announcements");
        print_r(json_encode([$tableBody, $pagination, $announcementsTotal, $token]));

    } else {
        $token = token::generate("display_announcements");
        print_r(json_encode([$token]));
    }

} else {
    echo "unauthorized";
    // header('Location: /pages/errors/403.php');      
}


//truncate a string only at a whitespace
function truncate($text, $length)
{
    $length = abs((int) $length);
    if (strlen($text) > $length) {
        $text = preg_replace("/^(.{1,$length})(\s.*|$)/s", '\\1...', $text);
    }
    return ($text);
}
