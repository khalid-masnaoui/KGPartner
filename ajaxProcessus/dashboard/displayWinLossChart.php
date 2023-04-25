<?php
require_once __DIR__ . "/../../core/ini.php";
require_once __DIR__ . "/../../functions/sanitize.php";
require_once __DIR__ . "/../../functions/encryptDecrypt.php";
require_once __DIR__ . "/../../vendor/autoload.php";


if (input::exists("post") && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' && isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] == "https://koreagaming.info/pages/dashboard.php") {

    if (token::check(input::get("token"), "display_winloss_chart")) {

        $partner = new user();
        $partnerPtId = $partner->data()["pt_id"];

        //prepare prefixes data
        $db = DB::getInstance();

        $partnerFilter = " AND pt_id = ? ";
        $parametersQuery[] = "$partnerPtId";

        $clientSql = "SELECT prefix FROM clients WHERE 1=1 $partnerFilter ";
        $prefixes = $db->query($clientSql, $parametersQuery)->results();


        //POST DATA
        $period = input::get("period");
        $category = input::get("category");

        $activeProviders = [];
        $activeProvidersNames = config::get("display/providersNameMappings");
        $activeProvidersChartsColors = config::get("display/providersNameMappings");


        if ($category === 'all') {
            $activeProviders = config::get("display/activeProviders"); //order matters

        } else if ($category === 'casino') {

            $casinoProviders = config::get("config/display/casinos");
            foreach ($casinoProviders as $key => $value) {
                $value === "" ? array_push($activeProviders, $key) : '';
            }

        } else if ($category === 'slot') {

            $slotProviders = config::get("config/display/slots");
            foreach ($slotProviders as $key => $value) {
                $value === "" ? array_push($activeProviders, $key) : '';
            }
        }


        //construct labels
        $labels = array();
        if ($period === "current") {


            $date = new DateTime();

            for ($i = 0; $i < 7; $i++) {
                # code...
                $i !== 0 ? $date->modify("-1 day") : $date->modify("-0 day");
                array_unshift($labels, $date->format("Y-m-d"));

            }
        } else if ($period === "last") {


            $date = (new DateTime())->modify("-1 week");

            for ($i = 0; $i < 7; $i++) {
                # code...
                $i !== 0 ? $date->modify("-1 day") : $date->modify("-0 day");
                array_unshift($labels, $date->format("Y-m-d"));

            }
        } else if ($period === "weekly") {


            $date = new DateTime();

            for ($i = 0; $i < 5; $i++) {
                # code...
                $i !== 0 ? $date->modify("-1 week") : $date->modify("-0 week");

                // array_unshift($labels, $date->format("Y-m-d"));
                $endOfTheWeek = $date->format("Y-m-d");

                $startOfTheWeek = (new DateTime($endOfTheWeek))->modify("-6 days");
                $startOfTheWeek = $startOfTheWeek->format("Y-m-d");
                $labels[4 - $i] = [$startOfTheWeek, $endOfTheWeek];

            }
        }

        //construct datasets
        $dataSets = [];
        foreach ($activeProviders as $key => $value) {
            $data = array();
            $data["label"] = $value;
            $data["stack"] = "turnoverStack";
            $data["backgroundColor"] = getWinLossChartsColor($value);


            $data["data"] = getWinLossChartData($labels, $value);


            $dataSets[] = $data;
        }

        $token = token::generate("display_winloss_chart");
        print_r(json_encode([$dataSets, $labels, $token]));

    } else {
        $token = token::generate("display_winloss_chart");
        print_r(json_encode([$token]));
    }

} else {
    echo "unauthorized";
    // header('Location: /pages/errors/403.php');      
}


//colors assigned to casinos and slots
function getWinLossChartsColor($key)
{
    switch ($key) {
        //casinos
        case 'evo':
            return "rgba(255, 99, 132, 0.2)";

        case 'pp':
            return "rgba(245, 40, 145, 0.8)";

        case 'vivo':
            return "rgba(68, 60, 104, 1)";

        case 'cq9':
            return "rgba(54, 162, 235, 1)";

        case 'popok':
            return "rgba(54, 162, 235, 0.2)";

        case 'mg':
            return "rgba(54, 162, 235, 0.2)";

        case 'og':
            return "rgba(54, 162, 235, 0.3)";

        case 'ag':
            return "rgba(54, 162, 235, 0.4)";

        case 'bg':
            return "rgba(54, 162, 235, 0.5)";

        case 'dg':
            return "rgba(54, 162, 235, 0.6)";

        case 'ez':
            return "rgba(54, 162, 235, 0.7)";

        case 'bota':
            return "rgba(54, 162, 235, 0.8)";

        case 'dw':
            return "rgba(54, 162, 235, 0.9)";

        case 'ts':
            return "rgba(54, 162, 235, 1)";

        case 'wm':
            return "rgba(34, 112, 235, 1)";

        //slots
        case 'cq9 slot':
            return "rgba(54, 162, 235, 0.2)";

        case 'ps':
            return "rgba(255, 206, 86, 0.2)";

        case 'netent':
            return "rgba(255, 99, 132, 1)";

        case 'redtiger':
            return "rgba(255, 206, 86, 1)";

        case 'nlc':
            return "rgba(54, 162, 235, 1)";

        case 'btg':
            return "rgba(159, 130, 145, 0.8)";

        case 'pp slot':
            return "rgba(31, 138, 112, 0.8)";

        case 'ygg':
            return "rgba(168, 100, 100, 1)";

        case 'popok slot':
            return "rgba(168, 100, 100, 0.2)";

        case 'rk':
            return "rgba(159, 130, 145, 0.8)";

        case 'bp':
            return "rgba(159, 130, 145, 0.8)";

        case 'dr':
            return "rgba(159, 130, 145, 0.8)";

        case 'els':
            return "rgba(159, 130, 145, 0.8)";

        case 'mg slot':
            return "rgba(159, 130, 145, 0.8)";

        case 'qs':
            return "rgba(159, 130, 145, 0.8)";

        case 'rx':
            return "rgba(159, 130, 145, 0.8)";

        case 'rr':
            return "rgba(159, 130, 145, 0.8)";

        case 'sh':
            return "rgba(159, 130, 145, 0.8)";

        case 'ns':
            return "rgba(159, 130, 145, 0.8)";

        case 'ng':
            return "rgba(159, 130, 145, 0.8)";

        case 'ga':
            return "rgba(159, 130, 145, 0.8)";

        case 'ftg':
            return "rgba(159, 130, 145, 0.8)";

        case 'ap':
            return "rgba(159, 130, 145, 0.8)";

        case 'bs':
            return "rgba(159, 130, 145, 0.8)";

        case 'png':
            return "rgba(159, 130, 145, 0.8)";

        case 'hs':
            return "rgba(159, 130, 145, 0.8)";

        case 'aux':
            return "rgba(159, 130, 145, 0.8)";

        case 'bog':
            return "rgba(159, 130, 145, 0.8)";

        case 'psn':
            return "rgba(159, 130, 145, 0.8)";

        case 'pgs':
            return "rgba(159, 130, 145, 0.8)";

        case 'hb':
            return "rgba(159, 130, 145, 0.8)";

        case 'gmw':
            return "rgba(159, 130, 145, 0.8)";

        case 'ag slot':
            return "rgba(159, 130, 145, 0.8)";

        case 'sw':
            return "rgba(159, 130, 145, 0.8)";

        case 'upg':
            return "rgba(159, 130, 145, 0.8)";


        default:
            # code...
            break;
    }
}

//get turnover data
function getWinLossChartData($labels, $key)
{
    global $db;
    global $period;
    global $prefixes;

    //number formatter
    $fmt = new NumberFormatter('en_US', NumberFormatter::DECIMAL);
    $fmt->setAttribute(NumberFormatter::FRACTION_DIGITS, 0);
    $fmt->setSymbol(NumberFormatter::GROUPING_SEPARATOR_SYMBOL, "");

    $count = count($labels);
    $data = array_fill(0, $count, 0);

    if ($key !== "evo") {
        return $data;
    }

    //constructs the sql queries

    for ($i = 0; $i < $count; $i++) {

        if ($period === "weekly") {
            $periodOne = $labels[$i][0];
            $periodTwo = $labels[$i][1];
        } else {
            $periodOne = $labels[$i];
        }


        foreach ($prefixes as $key2 => $value2) {
            $prefix = $value2["prefix"];

            $debitTableName = $prefix . '_debit';
            $creditTableName = $prefix . '_credit';



            if ($period === "weekly") {
                //for weekly data
                $periodOneParameter = $periodOne . ' 00:00:00';
                $periodTwoParameter = $periodTwo . ' 23:59:59';
            } else {
                //for daily data
                $periodOneParameter = $periodOne . ' 00:00:00';
                $periodTwoParameter = $periodOne . ' 23:59:59';
            }




            $debitSql = "SELECT sum(d.amount) as debitTotal, sum(c.amount) as creditTotal from {$debitTableName} as d JOIN {$creditTableName} as c ON d.txn_id = c.txn_id where d.timestamp >= ? AND d.timestamp <= ?";
            $parametersQuery = [$periodOneParameter, $periodTwoParameter];

            $queryData = $db->query($debitSql, $parametersQuery)->first();

            $debitTotal = $queryData["debitTotal"];
            $creditTotal = $queryData["creditTotal"];


            $data[$i] += $creditTotal - $debitTotal;

        }
        $data[$i] = $fmt->format($data[$i]);

    }
    return $data;




}
