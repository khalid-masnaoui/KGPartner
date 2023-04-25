<?php
require_once __DIR__ . "/../../core/ini.php";
require_once __DIR__ . "/../../functions/sanitize.php";
require_once __DIR__ . "/../../functions/encryptDecrypt.php";



if (input::exists("post") && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' && isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] == "https://koreagaming.info/pages/settings/products.php") {

    if (token::check(input::get("token"), "display_productslist")) {

        $activePage = input::get("page");
        $activeNumber = input::get("number");
        $activePage = ($activePage == 0 || $activePage == '') ? 1 : $activePage;

        $limit = $activeNumber;
        $offset = ($activePage - 1) * $activeNumber;

        $category = input::get("category");
        $status = input::get("status");

        $db = DB::getInstance();

        $whereClause = [];

        //status constraint
        if ($status == "active") {
            $whereClause[] = ["status", "=", 1];
        }
        if ($status == "inactive") {
            $whereClause[] = ["status", "=", 0];
        }

        // category constraint
        if ($category == "casino") {
            $whereClause[] = ["type", "=", "casino"];
        }
        if ($category == "slot") {
            $whereClause[] = ["type", "=", "slot"];
        }


        $productsList = $db->get("*", "products_list", $whereClause);

        if ($productsList->error()) {
            $token = token::generate("display_productslist");
            print_r(json_encode([$token]));
        }

        $productsList = $productsList->results();

        $productsListPartial = array_slice($productsList, $offset, $limit);


        $tableBody = '';
        $i = 0;
        $i = ($activePage - 1) * $activeNumber;


        foreach ($productsListPartial as $key => $value) {
            $i++;

            $status = $value["status"];

            $statusHtml = '';
            $editBtnClass = 'success';

            if ($status == 1) {
                $statusHtml = '<div class="mb-2 mr-2 badge badge-pill badge-success">정상</div>';
                $editBtnClass = 'danger';
            } else if ($status == 0) {
                $statusHtml = '<div class="mb-2 mr-2 badge badge-pill badge-danger" style="color:white !important;">비활성</div>';
            }





            $tableBody .= '<tr>';

            $tableBody .= '<td class="text-center text-muted">' . $i . '</td>';
            $tableBody .= '<td class="text-center">' . escape($value["product_id"]) . '</td>';
            $tableBody .= '<td class="text-center">' . escape($value["name_en"]) . '</td>';
            $tableBody .= '<td class="text-center">' . escape($value["name_kr"]) . '</td>';
            $tableBody .= '<td class="text-center">' . escape($value["type"]) . '</td>';
            $tableBody .= '<td class="text-center">' . $statusHtml . '</td>';

            $tableBody .= '</tr>';


            // <a href="#delete_modal" class="trigger-btn" data-toggle="delete_modal"><i class="pe-7s-trash"> </i></button></a>



        }

        //navigation
        $pagination = '';


        $productsListTotal = count($productsList);

        $pages = ceil($productsListTotal / $activeNumber);

        $current_page = $activePage == 0 ? 1 : $activePage;
        $total_records = $productsListTotal;
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

                $pagination .= '<li class="page-item"  onclick=displayProductsList(1)><a href="javascript:void(0);" class="page-link" aria-label="First"><span aria-hidden="true">«</span><span class="sr-only">Last</span></a></li>';
                // $pagination .='<li class="page-item"  onclick=displayProductsList('.$previous_link.')><a href="javascript:void(0);"class="page-link">'.$previous_link.' &lt;</a></li>';
                for ($i = ($current_page - 2); $i < $current_page; $i++) { //Create left-hand side links
                    if ($i > 0) {
                        $pagination .= '<li class="page-item"  onclick=displayProductsList(' . $i . ')><a href="javascript:void(0);"class="page-link">' . $i . '</a></li>';

                    }
                }
                $first_link = false; //set first link to false $dateFrom, $dateTo
            }

            //active link
            $pagination .= '<li class="page-item active"><a href="javascript:void(0);"class="page-link">' . $current_page . '</a></li>';

            //create right-hand side links
            for ($i = $current_page + 1; $i < $right_links; $i++) {
                if ($i <= $total_pages) {
                    $pagination .= '<li class="page-item"  onclick=displayProductsList(' . $i . ') ><a href="javascript:void(0);"class="page-link">' . $i . '</a></li>';

                }
            }
            if ($current_page < $total_pages) {
                // $next_link = ($i > $total_pages) ? $total_pages : $i;
                // // $pagination .='<li class="page-item" onclick=displayProductsList('.$next_link.')><a href="javascript:void(0);"class="page-link">'.$next_link.' &gt;</a></li>'; //next

                $pagination .= '<li class="page-item"  onclick=displayProductsList(' . $total_pages . ')><a href="javascript:void(0);" class="page-link" aria-label="Last"><span aria-hidden="true">»</span><span class="sr-only">Last</span></a></li>'; //last

            }

            $pagination .= '</ul>';
        }

        if ($tableBody == '') {
            $tableBody = '<tr> <td class="text-center" colspan="6">기록을 찾을 수 없음!</td> </tr>';
        }

        $token = token::generate("display_productslist");
        print_r(json_encode([$tableBody, $pagination, $productsListTotal, $token]));

    } else {
        $token = token::generate("display_productslist");
        print_r(json_encode([$token]));
    }


} else {
    echo "unauthorized";
    // header('Location: /pages/errors/403.php');      
}
















?>
