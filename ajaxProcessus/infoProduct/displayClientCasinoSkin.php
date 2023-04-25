<?php
require_once __DIR__ . "/../../core/ini.php";
require_once __DIR__ . "/../../functions/sanitize.php";
require_once __DIR__ . "/../../functions/encryptDecrypt.php";



if (input::exists("post") && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' && isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] == "https://koreagaming.info/pages/infos/product/limit_setting.php") {

    if (token::check(input::get("token"), "display_skins")) {

        $activePage = input::get("page");
        $activeNumber = input::get("number");
        $activePage = ($activePage == 0 || $activePage == '') ? 1 : $activePage;

        $limit = $activeNumber;
        $offset = ($activePage - 1) * $activeNumber;

        $clientId = input::get("client");

        $clientQuery = '';
        $parametersQuery = [];


        $partner = new user();
        $partnerPtId = $partner->data()["pt_id"];

        $partnerFilter = " AND ( cl.pt_id = ? OR cl.pt_id like ?)";
        $parametersQuery[] = "$partnerPtId";
        $parametersQuery[] = "$partnerPtId/%";

        if ($clientId != 'all') {
            $clientQuery = ' AND cl.id = ?';
            $parametersQuery[] = $clientId;
        }


        $db = DB::getInstance();


        //GET PRODUCT SKINS
        $productSkins = $db->get("*", "product_skins", [])->results();

        //skin amount [min-max] formatting
        $fmtZeroFraction = new NumberFormatter("en-US", NumberFormatter::DECIMAL);
        $fmtZeroFraction->setAttribute(NumberFormatter::FRACTION_DIGITS, 0);

        $casinoProviders = config::get("config/display/casinos"); //using that cause we want all data --> later we control the display

        //grouping 
        $productSkinsOptions = array();
        foreach ($productSkins as $element) {

            $skinAmountProvidersData = array();
            foreach ($casinoProviders as $key => $value) {
                $skinAmount = $element[$key];
                if ($skinAmount == '' || $skinAmount == null) {
                    $skinAmount = '-';
                } else {
                    $skinAmount = explode("-", $skinAmount);

                    $skinAmountMax = trim($skinAmount[1]);
                    $skinAmountMax = $fmtZeroFraction->format($skinAmountMax);

                    $skinAmount = $skinAmountMax . '₩';

                }
                $skinAmountProvidersData[$key] = $skinAmount;
            }
            $type = $element["type"];
            $productSkinsOptions[$type] = $skinAmountProvidersData;

        }


        //getting clients skins options
        $ProvidersSkinsDefault = "CASE WHEN ck.evo IS NULL THEN 'A' ELSE ck.evo END AS evo,
                                CASE WHEN ck.bg IS NULL THEN 'C' ELSE ck.bg END AS bg,
                                CASE WHEN ck.asg IS NULL THEN 'A' ELSE ck.asg END AS asg,
                                CASE WHEN ck.dg IS NULL THEN 'A' ELSE ck.sg END AS dg,
                                CASE WHEN ck.sg IS NULL THEN 'A' ELSE ck.sg END AS sg";

        $clientSkins = $db->query("SELECT cl.id, cl.pt_id, cl.name ,$ProvidersSkinsDefault  from clients cl LEFT JOIN clients_skins ck on cl.id = ck.client_id where 1=1 $partnerFilter $clientQuery", $parametersQuery)->results();


        $tableBody = '';
        $i = 0;
        $i = ($activePage - 1) * $activeNumber;

        foreach ($clientSkins as $key => $value) {
            $i++;

            $evo = $productSkinsOptions[$value["evo"]]["evo"] . '<br> (Type ' . $value["evo"] . ')';
            $bg = $productSkinsOptions[$value["bg"]]["bg"] . '<br> (Type ' . $value["bg"] . ')';
            $asg = $productSkinsOptions[$value["asg"]]["asg"] . '<br> (Type ' . $value["asg"] . ')';
            $dg = $productSkinsOptions[$value["dg"]]["dg"] . '<br> (Type ' . $value["dg"] . ')';
            $sg = $productSkinsOptions[$value["sg"]]["sg"] . '<br> (Type ' . $value["sg"] . ')';

            $jsonValues = json_encode($value);
            $jsonValues = escape($jsonValues);


            $parentName = $db->get("username", "partner_users", array(["id", "=", $value["pt_id"]]))->first()["username"];


            $tableBody .= '<tr>';

            $tableBody .= '<td class="text-center text-muted">' . $i . '</td>';
            $tableBody .= '<td class="text-center">' . escape($value["id"]) . '</td>';
            $tableBody .= '<td class="text-center">' . escape($value["name"]) . '</td>';
            $tableBody .= '<td class="text-center">' . escape($parentName) . '</td>';
            $tableBody .= '<td class="text-center" style="display:' . config::get("display/casinos/evo") . '">' . $evo . '</td>';
            $tableBody .= '<td class="text-center" style="display:' . config::get("display/casinos/bg") . '">' . $bg . '</td>';
            $tableBody .= '<td class="text-center" style="display:' . config::get("display/casinos/asg") . '">' . $asg . '</td>';
            $tableBody .= '<td class="text-center" style="display:' . config::get("display/casinos/dg") . '">' . $dg . '</td>';
            $tableBody .= '<td class="text-center" style="display:' . config::get("display/casinos/sg") . '">' . $sg . '</td>';

            $tableBody .= '</tr>';

        }

        //navigation
        $pagination = '';


        $clientSkinsTotal = count($clientSkins);

        $pages = ceil($clientSkinsTotal / $activeNumber);

        $current_page = $activePage == 0 ? 1 : $activePage;
        $total_records = $clientSkinsTotal;
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

                $pagination .= '<li class="page-item"  onclick=displayClientCasinoSkin(1)><a href="javascript:void(0);" class="page-link" aria-label="First"><span aria-hidden="true">«</span><span class="sr-only">Last</span></a></li>';
                for ($i = ($current_page - 2); $i < $current_page; $i++) { //Create left-hand side links
                    if ($i > 0) {
                        $pagination .= '<li class="page-item"  onclick=displayClientCasinoSkin(' . $i . ')><a href="javascript:void(0);"class="page-link">' . $i . '</a></li>';

                    }
                }
                $first_link = false; //set first link to false $dateFrom, $dateTo
            }

            //active link
            $pagination .= '<li class="page-item active"><a href="javascript:void(0);"class="page-link">' . $current_page . '</a></li>';

            //create right-hand side links
            for ($i = $current_page + 1; $i < $right_links; $i++) {
                if ($i <= $total_pages) {
                    $pagination .= '<li class="page-item"  onclick=displayClientCasinoSkin(' . $i . ') ><a href="javascript:void(0);"class="page-link">' . $i . '</a></li>';

                }
            }
            if ($current_page < $total_pages) {
                // $next_link = ($i > $total_pages) ? $total_pages : $i;
                $pagination .= '<li class="page-item"  onclick=displayClientCasinoSkin(' . $total_pages . ')><a href="javascript:void(0);" class="page-link" aria-label="Last"><span aria-hidden="true">»</span><span class="sr-only">Last</span></a></li>'; //last

            }

            $pagination .= '</ul>';
        }

        if ($tableBody == '') {
            $j = 3;
            $j += count(config::get("display/activeProviders"));
            $tableBody = '<tr> <td class="text-center" colspan="' . $j . '">기록을 찾을 수 없음!</td> </tr>';
        }

        $token = token::generate("display_skins");
        print_r(json_encode([$tableBody, $pagination, $clientSkinsTotal, $token]));

    } else {
        $token = token::generate("display_skins");
        print_r(json_encode([$token]));
    }


} else {
    echo "unauthorized";
    // header('Location: /pages/errors/403.php');      
}
















?>
