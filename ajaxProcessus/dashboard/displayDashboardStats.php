<?php
require_once __DIR__ . "/../../core/ini.php";
require_once __DIR__ . "/../../functions/sanitize.php";
require_once __DIR__ . "/../../functions/encryptDecrypt.php";
require_once __DIR__ . "/../../vendor/autoload.php";


if (input::exists("post") && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' && isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] == "https://koreagaming.info/pages/dashboard.php") {

    if (token::check(input::get("token"), "dashboard_display_stats")) {

        $partner = new user();
        $partnerPtId = $partner->data()["pt_id"];
        $partnerId = $partner->data()["id"];


        //prepare prefixes data
        $db = DB::getInstance();

        //ONLY OWN CLIENTS
        // $partnerFilter = " AND ( pt_id = ? OR pt_id like ?)";
        $partnerFilter = " AND pt_id = ?";
        $parametersQuery[] = "$partnerPtId";
        // $parametersQuery[] = "$partnerPtId/%";

        $clientSql = "SELECT prefix FROM clients WHERE 1=1 $partnerFilter ";
        $prefixes = $db->query($clientSql, $parametersQuery)->results();



        $dashboardStatsData = [];

        $fmtPadding = new NumberFormatter('en_US', NumberFormatter::PADDING_POSITION);
        $fmtPadding->setAttribute(NumberFormatter::FRACTION_DIGITS, 2);

        $db = DB::getInstance();

        //clients balance and deposit
        // $clientsBalanceAndDepositSql = "SELECT sum(cb.balance) as balanceTotal , sum(cb.deposit) as depositTotal FROM clients_balance cb JOIN clients cl ON cb.client_id = cl.id WHERE cl.pt_id = ? or cl.pt_id like ?";
        $clientsBalanceAndDepositSql = "SELECT sum(cb.balance) as balanceTotal , sum(cb.deposit) as depositTotal FROM clients_balance cb JOIN clients cl ON cb.client_id = cl.id WHERE cl.pt_id = ?";
        $balanceAndDepositData = $db->query($clientsBalanceAndDepositSql, $parametersQuery)->first();

        $dashboardStatsData["clientsBalance"] = $fmt->format($balanceAndDepositData["balanceTotal"]);
        // $dashboardStatsData["clientsDeposit"] = $fmt->format($balanceAndDepositData["depositTotal"]);


        $partnersTotalDepositSql1 = "SELECT sum(amount) as depositTotal FROM deposits WHERE admin_id = ?";
        $partnersTotalDepositData1 = $db->query($partnersTotalDepositSql1, ["p:$partnerId"])->first();


        $partnersTotalDepositSql2 = "SELECT sum(amount) as depositTotal FROM wa_balance_deposits WHERE admin_id = ?";
        $partnersTotalDepositData2 = $db->query($partnersTotalDepositSql2, ["p:$partnerId"])->first();

        $dashboardStatsData["clientsDeposit"] = $fmt->format($partnersTotalDepositData1["depositTotal"] + $partnersTotalDepositData2["depositTotal"]);


        //current partner balance --- TO CHANGE TO WA BALANCE
        // $partnersBalanceSql = "SELECT sum(pb.balance) as balanceTotal FROM partners_balance pb WHERE pb.partner_id = ?";
        // $balanceData = $db->query($partnersBalanceSql, [$partnerId])->first();

        // $dashboardStatsData["currentPartnerBalance"] = $fmtPadding->format($balanceData["balanceTotal"]);

        // $partnersWaBalanceSql = "SELECT wa_balance FROM partner_users WHERE id = ?";
        // $partnersWaBalance = $db->query($partnersWaBalanceSql, [$partnerId])->first();

        $dashboardStatsData["currentPartnerBalance"] = $fmt->format($partner->data()["wa_balance"]);


        //partners commissions
        // $partnersBalanceSql = "SELECT sum(pb.balance) as balanceTotal FROM partners_balance pb JOIN partner_users pu ON pb.partner_id = pu.id WHERE pu.pt_id = ? or pu.pt_id like ?";
        // $partnersBalanceSql = "SELECT sum(pb.balance) as balanceTotal FROM partners_balance pb JOIN partner_users pu ON pb.partner_id = pu.id WHERE pu.pt_id = ?";
        // $balanceData = $db->query($partnersBalanceSql, $parametersQuery)->first();

        // $dashboardStatsData["partnersBalance"] = $fmtPadding->format($balanceData["balanceTotal"]);

        //clients count
        $clientsCountSql = "SELECT count(*) as clientsTotal FROM clients WHERE pt_id = ?";
        $dashboardStatsData["clientsCount"] = $db->query($clientsCountSql, $parametersQuery)->first()["clientsTotal"];


        //active Clients count
        $activeClientsCountSql = "SELECT count(*) as activeClientsTotal FROM clients where status=1 AND pt_id = ?";
        $activeClientsTotalCount = $db->query($activeClientsCountSql, $parametersQuery)->first()["activeClientsTotal"];

        if ($dashboardStatsData["clientsCount"] != 0) {
            $activeClientsPercentage = ($activeClientsTotalCount / $dashboardStatsData["clientsCount"]) * 100;
        } else {
            $activeClientsPercentage = 0;
        }

        $dashboardStatsData["activeClientsPercentage"] = $fmtPadding->format($activeClientsPercentage);

        //partners count
        $partnersCountSql = "SELECT count(*) as partnersTotal FROM partner_users pu WHERE pu.pt_id = ? and pu.id != pu.pt_id ";
        $dashboardStatsData["partnersCount"] = $db->query($partnersCountSql, $parametersQuery)->first()["partnersTotal"];


        //players count and transaction counts
        $playersCount = 0;
        $betTransactionsCount = 0;
        $canceledBetTransactionsCount = 0;

        foreach ($prefixes as $key => $value) {
            $prefix = $value["prefix"];

            $usersTableName = $prefix . '_users';
            $debitTableName = $prefix . '_debit';
            $creditTableName = $prefix . '_credit';


            $playersCountSql = "SELECT count(*) as playersCount FROM {$usersTableName}";
            $betTransactionsCountSql = "SELECT count(*) as betTransactionsCount FROM {$debitTableName}";
            $canceledBetTransactionsCountSql = "SELECT count(*) as canceledBetTransactionsCount FROM {$creditTableName} where type = 'x'";

            //results
            $playersCount += $db->query($playersCountSql, [])->first()["playersCount"];
            $betTransactionsCount += $db->query($betTransactionsCountSql, [])->first()["betTransactionsCount"];
            $canceledBetTransactionsCount += $db->query($canceledBetTransactionsCountSql, [])->first()["canceledBetTransactionsCount"];

        }

        $dashboardStatsData["playersCount"] = $playersCount;
        $dashboardStatsData["betTransactionsCount"] = $betTransactionsCount;

        if ($betTransactionsCount != 0) {
            $canceledBetTransactionsPercentage = ($canceledBetTransactionsCount / $betTransactionsCount) * 100;
        } else {
            $canceledBetTransactionsPercentage = 0;
        }
        $dashboardStatsData["canceledBetTransactionsPercentage"] = $fmtPadding->format($canceledBetTransactionsPercentage);


        $token = token::generate("display_notifications_dash");
        print_r(json_encode([$dashboardStatsData, $token]));

    } else {
        $token = token::generate("display_notifications_dash");
        print_r(json_encode([$token]));
    }

} else {
    echo "unauthorized";
    // header('Location: /pages/errors/403.php');      
}
