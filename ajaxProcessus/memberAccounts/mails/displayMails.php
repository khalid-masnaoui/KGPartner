<?php
require_once __DIR__ . "/../../../core/ini.php";
require_once __DIR__ . "/../../../functions/sanitize.php";
require_once __DIR__ . "/../../../functions/encryptDecrypt.php";
require_once __DIR__ . "/../../../vendor/autoload.php";


if (input::exists("post") && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' && isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] == "https://koreagaming.live/pages/member/accounts/mailbox.php") {

    if (token::check(input::get("token"), "display_mails")) {

        $activePage = input::get("page");
        $activeNumber = input::get("number");
        $activePage = ($activePage == 0 || $activePage == '') ? 1 : $activePage;

        $limit = $activeNumber;
        $offset = ($activePage - 1) * $activeNumber;

        $partner = new user();
        $partnerId = $partner->data()["id"];
        $partnerName = $partner->data()["username"];

        $subject = input::get("filterSubject");
        $subject = trim($subject);


        //preparing filters data
        $filterQuery = '';
        $parametersQuery = [];

        $filterQuery .= " AND (cm.receiver = ? OR cm.receiver = 'all')"; // or receiver = 'all'
        $parametersQuery[] = $partnerId;

        if ($subject != "" && $subject != null) {
            $filterQuery .= " AND cm.subject like ?";
            $parametersQuery[] = "%$subject%";
        }


        $sql = "SELECT cm.* FROM partners_mailbox cm where 1=1 $filterQuery ORDER BY updated_at DESC";


        $mailsBuilder = new Mails();

        $mailsData = $mailsBuilder->getMailsCustomData($sql, $parametersQuery);

        $mailsDataPartial = array_slice($mailsData, $offset, $limit);



        $db = DB::getInstance();

        $tableBody = '';
        $i = 0;
        $i = ($activePage - 1) * $activeNumber;

        $purifier = new HTMLPurifier();

        foreach ($mailsDataPartial as $key => $value) {
            $i++;

            //purify the message --> we need to display the html
            $value["message"] = $purifier->purify($value["message"]);

            $jsonValues = str_replace(" ", "&&xx&&", json_encode($value));
            $jsonValues = escape($jsonValues);

            $id = $value["id"];

            $checkBox = "<input type='checkbox' class='mailSelected' name='mailSelected[]' data-id='$id' data-values=" . $jsonValues . ">";

            $newBadge = '';
            if ($value["status"] == 0) {
                $newBadge = '<span class="new-mails badge badge-info" data-mailNewBadge="' . $id . '" style="margin-right: 10px;">NEW</span>';
            }

            $tableBody .= '<tr>';

            $tableBody .= '<td class="text-center text-muted">' . $i . '</td>';
            $tableBody .= '<td class="text-center text-muted" data-id = "' . $id . '">' . $checkBox . '</td>';
            $tableBody .= '<td class="text-center">' . escape($partnerName) . '</td>';

            $tableBody .= '<td class="text-center" data-toggle="modal"
            data-target=".show_mail" data-values=' . $jsonValues . ' onclick=showMailDataInfo(event) style="cursor: pointer;">' . $newBadge . escape($value["subject"]) . '</td>';

            $tableBody .= '<td class="text-center">' . escape($value["updated_at"]) . '</td>';


            $tableBody .= '</tr>';

        }

        //navigation
        $pagination = '';
        $mailsTotal = count($mailsData);

        $pages = ceil($mailsTotal / $activeNumber);

        $current_page = $activePage == 0 ? 1 : $activePage;
        $total_records = $mailsTotal;
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

                $pagination .= '<li class="page-item"  onclick=displayMails(1,' . $status . ')><a href="javascript:void(0);" class="page-link" aria-label="First"><span aria-hidden="true">«</span><span class="sr-only">Last</span></a></li>';

                for ($i = ($current_page - 2); $i < $current_page; $i++) { //Create left-hand side links
                    if ($i > 0) {
                        $pagination .= '<li class="page-item"  onclick=displayMails(' . $i . ',' . $status . ')><a href="javascript:void(0);"class="page-link">' . $i . '</a></li>';

                    }
                }
                $first_link = false; //set first link to false $dateFrom, $dateTo
            }

            //active link
            $pagination .= '<li class="page-item active"><a href="javascript:void(0);"class="page-link">' . $current_page . '</a></li>';

            //create right-hand side links
            for ($i = $current_page + 1; $i < $right_links; $i++) {
                if ($i <= $total_pages) {
                    $pagination .= '<li class="page-item"  onclick=displayMails(' . $i . ',' . $status . ') ><a href="javascript:void(0);"class="page-link">' . $i . '</a></li>';
                }
            }
            //last
            if ($current_page < $total_pages) {
                $pagination .= '<li class="page-item"  onclick=displayMails(' . $total_pages . ',' . $status . ')><a href="javascript:void(0);" class="page-link" aria-label="Last"><span aria-hidden="true">»</span><span class="sr-only">Last</span></a></li>';
            }

            $pagination .= '</ul>';
        }

        if ($tableBody == '') {
            $tableBody = '<tr> <td class="text-center" colspan="7">No Data Available!</td> </tr>';
        }


        $token = token::generate("display_mails");
        print_r(json_encode([$tableBody, $pagination, $mailsTotal, $token]));

    } else {
        $token = token::generate("display_mails");
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
