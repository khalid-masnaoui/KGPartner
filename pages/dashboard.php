<?php

require_once __DIR__ . "/../core/ini.php";
require_once __DIR__ . "/../core/inc_var.php";
include __DIR__ . '/../includes/partials/_authorization.php';


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta http-equiv="Content-Language" content="en" />
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>
        Korea Gaming
    </title>
    <meta name="viewport"
        content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, shrink-to-fit=no" />
    <meta name="description" content="This is an example dashboard created using build-in elements and components." />
    <meta name="msapplication-tap-highlight" content="no" />

    <?php include __DIR__ . '/../includes/files/_stylesheets.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.0/dist/chart.min.js"></script>



</head>

<?php include __DIR__ . '/../includes/partials/_flash_msg.php'; ?>

<body>
    <div class="app-container app-theme-white body-tabs-shadow fixed-sidebar fixed-header">
        <!-- header section  -->
        <?php includeWithVariables(__DIR__ . '/../includes/partials/_header.php', array('title' => 'Korea Gaming'));
        ; ?>



        <div class="app-main">
            <!-- sidebar section  -->
            <?php includeWithVariables(__DIR__ . '/../includes/partials/_sidebar.php');
            ; ?>

            <div class="app-main__outer">
                <div class="app-main__inner">

                    <!-- inner header section  -->
                    <?php includeWithVariables(__DIR__ . '/../includes/partials/_innerheader.php', array('title' => 'Dashboard', 'title_text' => 'Dashboard conatins some of the most important informations.', 'icon' => "home")); ?>

                    <input type="hidden" name="token_display_stats" id="token_display_stats"
                        value="<?= token::generate("dashboard_display_stats") ?>">

                    <input type="hidden" name="token_display" id="token_display"
                        value="<?= token::generate("display_notifications_dash") ?>">

                    <input type="hidden" name="token_display_turnover_chart" id="token_display_turnover_chart"
                        value="<?= token::generate("display_turnover_chart") ?>">

                    <input type="hidden" name="token_display_winloss_chart" id="token_display_winloss_chart"
                        value="<?= token::generate("display_winloss_chart") ?>">



                    <!-- main content -->
                    <section class="main">
                        <div class="row">
                            <div class="col-lg-6 col-xl-4">
                                <div class="card mb-3 widget-content bg-premium-dark">
                                    <div class="widget-content-wrapper text-white">
                                        <div class="widget-content-left">
                                            <div class="widget-heading">Points</div>
                                            <div class="widget-subheading">Total Points</div>
                                        </div>
                                        <div class="widget-content-right">
                                            <div class="widget-numbers text-warning">
                                                <span class='partners-balance'>-</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-xl-4">
                                <div class="card mb-3 widget-content bg-premium-dark">
                                    <div class="widget-content-wrapper text-white">
                                        <div class="widget-content-left">
                                            <div class="widget-heading">Clients Deposit</div>
                                            <div class="widget-subheading">Total Clients Deposit</div>
                                        </div>
                                        <div class="widget-content-right">
                                            <div class="widget-numbers text-warning"><span
                                                    class='clients-deposit'>-</span></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-xl-4">
                                <div class="card mb-3 widget-content bg-premium-dark">
                                    <div class="widget-content-wrapper text-white">
                                        <div class="widget-content-left">
                                            <div class="widget-heading">Commissions</div>
                                            <div class="widget-subheading">Total Commissions</div>
                                        </div>
                                        <div class="widget-content-right">
                                            <div class="widget-numbers text-warning"><span
                                                    class='partners-commissions'>-</span></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="divider mt-0" style="margin-bottom: 30px;"></div>

                        <div class="main-card mb-3 card">
                            <div class="no-gutters row" style='align-items: center;'>
                                <div class="col-md-4">
                                    <div class="pt-0 pb-0 card-body">
                                        <ul class="list-group list-group-flush">
                                            <li class="list-group-item">
                                                <div class="widget-content p-0">
                                                    <div class="widget-content-outer">
                                                        <div class="widget-content-wrapper">
                                                            <div class="widget-content-left">
                                                                <div class="widget-heading">Clients</div>
                                                                <div class="widget-subheading">Total Clients Count</div>
                                                            </div>
                                                            <div class="widget-content-right">
                                                                <div class="widget-numbers text-success"> <span
                                                                        class="clients-count">-</span> </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>
                                            <li class="list-group-item">
                                                <div class="widget-content p-0">
                                                    <div class="widget-content-outer">
                                                        <div class="widget-content-wrapper">
                                                            <div class="widget-content-left">
                                                                <div class="widget-heading">Players</div>
                                                                <div class="widget-subheading">Total Players Count
                                                                </div>
                                                            </div>
                                                            <div class="widget-content-right">
                                                                <div class="widget-numbers text-primary"><span
                                                                        class="players-count">-</span> </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="pt-0 pb-0 card-body">
                                        <ul class="list-group list-group-flush">
                                            <li class="list-group-item">
                                                <div class="widget-content p-0">
                                                    <div class="widget-content-outer">
                                                        <div class="widget-content-wrapper">
                                                            <div class="widget-content-left">
                                                                <div class="widget-heading">Active Clients</div>
                                                                <div class="widget-subheading">Percentage
                                                                </div>
                                                            </div>
                                                            <div class="widget-content-right"
                                                                style="width: 60%;text-align: right;">
                                                                <div class="widget-numbers text-primary"><span
                                                                        class="clients-active">-</span> </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>
                                            <li class="list-group-item">
                                                <div class="widget-content p-0">
                                                    <div class="widget-content-outer">
                                                        <div class="widget-content-wrapper">
                                                            <div class="widget-content-left">
                                                                <div class="widget-heading">Bet Transactions</div>
                                                                <div class="widget-subheading">Total Bet Transactions
                                                                </div>
                                                            </div>
                                                            <div class="widget-content-right">
                                                                <div class="widget-numbers text-warning"><span
                                                                        class="bet-count">-</span> </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="pt-0 pb-0 card-body">
                                        <ul class="list-group list-group-flush">
                                            <li class="list-group-item">
                                                <div class="widget-content p-0">
                                                    <div class="widget-content-outer">
                                                        <div class="widget-content-wrapper">
                                                            <div class="widget-content-left">
                                                                <div class="widget-heading">Partners</div>
                                                                <div class="widget-subheading">Total Partners Count
                                                                </div>
                                                            </div>
                                                            <div class="widget-content-right">
                                                                <div class="widget-numbers text-success"><span
                                                                        class="partners-count">-</span> </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>
                                            <li class="list-group-item">
                                                <div class="widget-content p-0">
                                                    <div class="widget-content-outer">
                                                        <div class="widget-content-wrapper">
                                                            <div class="widget-content-left">
                                                                <div class="widget-heading">Canceled Transactions</div>
                                                                <div class="widget-subheading">Percentage
                                                                </div>
                                                            </div>
                                                            <div class="widget-content-right"
                                                                style="width: 60%;text-align: right;">
                                                                <div class="widget-numbers text-primary"> <span
                                                                        class="transactions-canceled">-</span></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="divider mt-0" style="margin-bottom: 30px;"></div>

                        <div class="row d-none" style="margin-bottom:30px">
                            <div class="col-md-6">
                                <div class="main-card mb-3 card" style='margin-bottom:0px !important'>
                                    <div class="card-header">
                                        AG Balance Information

                                    </div>
                                    <div class="table-responsive">
                                        <table
                                            class="align-middle mb-0 table table-borderless table-striped table-hover">

                                            <tbody>
                                                <tr>
                                                    <th class="text-right text-muted">Username</th>

                                                    <td class="text-left">Khalid El masnaoui</td>

                                                </tr>
                                                <tr>
                                                    <th class="text-right text-muted">Cash Balance</th>

                                                    <td class="text-left" style="color:#00f;">105,640</td>

                                                </tr>
                                                <tr>
                                                    <th class="text-right text-muted">Total Balance</th>

                                                    <td class="text-left" style="color:red;">-15,610</td>

                                                </tr>
                                                <tr>
                                                    <th class="text-right text-muted">AG Deposit</th>

                                                    <td class="text-left" style="color:#00f;">20,000</td>

                                                </tr>


                                            </tbody>
                                        </table>
                                    </div>

                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row">

                                    <div class="col-md-12">
                                        <div class="main-card mb-3 card" style="margin-bottom:0px !important">
                                            <div class="card-header">Maintenance Status

                                            </div>
                                            <div class="table-responsive">
                                                <table
                                                    class="align-middle mb-0 table table-borderless table-striped table-hover">
                                                    <thead>
                                                        <tr>
                                                            <th class="text-center">#</th>
                                                            <th class="text-center">Provider</th>
                                                            <th class="text-center">Start Time</th>
                                                            <th class="text-center">End Time </th>
                                                            <th class="text-center">Content</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td class="text-center text-muted">
                                                                <div class="color"
                                                                    style="background-color: green;    height: 15px;width: 15px;text-align: center;margin: 0 auto;border-radius: 50%;">
                                                                </div>
                                                            </td>

                                                            <td class="text-center">Pinnacle</td>
                                                            <td class="text-center">2022-03-01 16:00:00</td>
                                                            <td class="text-center">2022-04-01 00:00:00</td>
                                                            <td class="text-center"></td>


                                                        </tr>
                                                        <tr>
                                                            <td class="text-center text-muted">
                                                                <div class="color"
                                                                    style="background-color: green;    height: 15px;width: 15px;text-align: center;margin: 0 auto;border-radius: 50%;">
                                                                </div>
                                                            </td>

                                                            <td class="text-center">Pinnacle</td>
                                                            <td class="text-center">2022-03-01 16:00:00</td>
                                                            <td class="text-center">2022-04-01 00:00:00</td>
                                                            <td class="text-center">Extended Maintenance</td>


                                                        </tr>

                                                        <tr>
                                                            <td class="text-center text-muted">
                                                                <div class="color"
                                                                    style="background-color: green;    height: 15px;width: 15px;text-align: center;margin: 0 auto;border-radius: 50%;">
                                                                </div>
                                                            </td>

                                                            <td class="text-center">PlayTech</td>
                                                            <td class="text-center">2022-03-01 16:00:00</td>
                                                            <td class="text-center">2022-04-01 00:00:00</td>
                                                            <td class="text-center"></td>


                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>

                                        </div>
                                    </div>


                                </div>
                            </div>



                        </div>

                        <div class="divider mt-0 d-none" style="margin-bottom: 30px;"></div>

                        <div class="row">
                            <div class="col-md-6 col-lg-6">
                                <div class="mb-3 card" style='height: 93%;'>
                                    <div class="card-header-tab card-header-tab-animation card-header">
                                        <div class="card-header-title">
                                            <i class="header-icon lnr-apartment icon-gradient bg-love-kiss"> </i> Key
                                            Graph
                                        </div>
                                        <ul class="nav">
                                            <li class="nav-item"><a href="javascript:void(0);"
                                                    class="active nav-link second-tab-toggle turnover turnover-current"
                                                    data-period='current'>Current</a>
                                            </li>
                                            <li class="nav-item"><a href="javascript:void(0);"
                                                    class="nav-link turnover turnover-last" data-period='last'>Last</a>
                                            </li>

                                            <li class="nav-item"><a href="javascript:void(0);"
                                                    class="nav-link third-tab-toggle turnover turnover-weekly"
                                                    data-period='weekly'>weekly</a>
                                            </li>

                                            <li class="nav-item"><a href="javascript:void(0);"
                                                    class="nav-link fourth-tab-toggle"><select type="select"
                                                        id="categorySelect" name="categorySelect" class="custom-select">
                                                        <option value="all">All</option>
                                                        <option value="casino">Casinos</option>
                                                        <option value="slot">Slots</option>
                                                    </select></a></li>

                                        </ul>
                                    </div>
                                    <div class="card-body"
                                        style='display: flex;justify-content: center;align-items: center;margin-bottom:20px'>
                                        <div class="tab-content" style="width: 100%;">

                                            <canvas id="turnOverChart" width="400" height="400"
                                                style="width: 100%;"></canvas>

                                            <div class="tab-pane fade show active text-center" id="tabs-eg-77"
                                                style="font-weight: bold;font-size: 1.2rem;display:none !important">
                                                No Data To Display

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-6">
                                <div class="mb-3 card" style='height: 93%;'>
                                    <div class="card-header-tab card-header">
                                        <div class="card-header-title">
                                            <i class="header-icon lnr-apartment icon-gradient bg-love-kiss"> </i> Key
                                            Graph
                                        </div>
                                        <ul class="nav">
                                            <li class="nav-item"><a href="javascript:void(0);"
                                                    class="active nav-link second-tab-toggle winloss winloss-current"
                                                    data-period='current'>Current</a>
                                            </li>
                                            <li class="nav-item"><a href="javascript:void(0);"
                                                    class="nav-link winloss winloss-last" data-period='last'>Last</a>
                                            </li>

                                            <li class="nav-item"><a href="javascript:void(0);"
                                                    class="nav-link third-tab-toggle winloss winloss-weekly"
                                                    data-period='weekly'>weekly</a>
                                            </li>

                                            <li class="nav-item"><a href="javascript:void(0);"
                                                    class="nav-link fourth-tab-toggle"><select type="select"
                                                        id="categorySelectWinLoss" name="categorySelectWinLoss"
                                                        class="custom-select">
                                                        <option value="all">All</option>
                                                        <option value="casino">Casinos</option>
                                                        <option value="slot">Slots</option>
                                                    </select></a></li>

                                        </ul>
                                    </div>
                                    <div class="card-body"
                                        style='display: flex;justify-content: center;align-items: center;margin-bottom:20px'>
                                        <div class="tab-content" style="width: 100%;">

                                            <canvas id="winLossChart" width="400" height="400"
                                                style="width: 100%;"></canvas>

                                            <div class="tab-pane fade show active text-center" id="tabs-eg-77"
                                                style="font-weight: bold;font-size: 1.2rem;display:none !important">
                                                No Data To Display

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="divider mt-0" style="margin-bottom: 30px;"></div>

                        <div class="row">
                            <div class="col-md-6 col-lg-6">
                                <div class="mb-3 card" style='height: 93%;'>
                                    <div class="card-header-tab card-header-tab-animation card-header">
                                        <div class="card-header-title">
                                            <i class="header-icon lnr-apartment icon-gradient bg-love-kiss"> </i>
                                            Notifications
                                        </div>
                                        <ul class="nav">
                                            <li class="nav-item"><a href="/pages/member/accounts/notifications.php"
                                                    class="nav-link">
                                                    <button class="mb-2 mr-2 btn-transition btn btn-outline-info">View
                                                        More
                                                    </button></a></li>
                                        </ul>
                                    </div>
                                    <div class="card-body"
                                        style='display:flex;justify-content: space-between;align-items: flex-start;flex-direction: column;'>
                                        <div class="tab-content" style="width: 100%;">
                                            <div class="table-responsive mb-3">
                                                <table
                                                    class="align-middle mb-0 table table-borderless table-striped table-hover">
                                                    <thead>
                                                        <tr>
                                                            <th class="text-center">#</th>
                                                            <th class="text-center">UserName</th>
                                                            <th class="text-center">Notification </th>
                                                            <th class="text-center">Created At</th>


                                                        </tr>
                                                    </thead>
                                                    <tbody class="table-body-notifications">

                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="tab-footer">
                                            <small>Note: The date will be based on time zone GMT+09:00 </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-6">
                                <div class="mb-3 card" style='height: 93%;'>
                                    <div class="card-header-tab card-header-tab-animation card-header">
                                        <div class="card-header-title">
                                            <i class="header-icon lnr-apartment icon-gradient bg-love-kiss"> </i>
                                            Others
                                        </div>

                                    </div>
                                    <div class="card-body"
                                        style='display: flex;justify-content: left;align-items: flex-start;'>
                                        <div class="tab-content">
                                            <div class="tab-pane fade show active text-center" id="tabs-eg-77">
                                                No record(s) found!


                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>





                </div>
                <!-- footer section  -->
                <?php includeWithVariables(__DIR__ . '/../includes/partials/_footer.php');
                ; ?>

            </div>
        </div>
    </div>

    <?php include __DIR__ . '/../includes/files/_scripts.php'; ?>

</body>

<script>
    //formatting numbers
    function number_format(number, decimals, dec_point, thousands_sep) {
        // Strip all characters but numerical ones.
        number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
        var n = !isFinite(+number) ? 0 : +number,
            prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
            sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
            dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
            s = '',
            toFixedFix = function (n, prec) {
                var k = Math.pow(10, prec);
                return '' + Math.round(n * k) / k;
            };
        // Fix for IE parseFloat(0.55).toFixed(0) = 0;
        s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
        if (s[0].length > 3) {
            s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
        }
        if ((s[1] || '').length < prec) {
            s[1] = s[1] || '';
            s[1] += new Array(prec - s[1].length + 1).join('0');
        }
        return s.join(dec);
    }
    //-----DISPLAY DASHBOARD STATS--------
    function displayDashboardStats(N = 1) {

        var token = $("#token_display_stats").val();

        $.ajax({
            url: '/ajaxProcessus/dashboard/displayDashboardStats.php',
            type: 'POST',
            data: {
                "display": true,
                token
            },
            cache: false,
            timeout: 10000,
            success: function (data) {

                if (data == 'unauthorized' || data == '') {
                    window.location.href = '/pages/errors/403.php';
                    return;
                }
                rese = JSON.parse(data);

                // console.log(rese[0]);
                let length = rese.length;


                if (length == 2) { //success
                    let statsData = rese[0];
                    // console.log(statsData);

                    //first row
                    // $(".clients-balance").text(`₩${statsData.clientsBalance}`);
                    $(".partners-balance").text(`${statsData.currentPartnerBalance}`);
                    $(".clients-deposit").text(`₩${statsData.clientsDeposit}`);
                    $(".partners-commissions").text(`₩${statsData.partnersBalance}`);

                    //second row
                    //first sub-row
                    $(".clients-count").text(`${statsData.clientsCount}`);
                    $(".clients-active").text(`${statsData.activeClientsPercentage} %`);
                    $(".partners-count").text(`${statsData.partnersCount}`);

                    //second sub-row
                    $(".players-count").text(`${statsData.playersCount}`);
                    $(".bet-count").text(`${statsData.betTransactionsCount}`);
                    $(".transactions-canceled").text(`${statsData.canceledBetTransactionsPercentage} %`);




                    $("#token_display_stats").val(rese[1]);
                } else if (length == 1) { //csrf error
                    $("#token_display_stats").val(rese[0]);
                } else {
                    //refresh page;
                    location.reload();
                }



            }


        })
    }

    //----------CHARTS--------------
    const ctx = document.getElementById('turnOverChart').getContext('2d');
    const ctxWinLoss = document.getElementById('winLossChart').getContext('2d');



    //setup
    const data = {
        labels: [],
        datasets: [{
            label: '',
            data: [],
            backgroundColor: "",
            stack: "",
        },
        {
            label: '',
            data: [],
            backgroundColor: "",
            stack: "",
        },
        {
            label: '',
            data: [],
            backgroundColor: "",
            stack: "",

        },
        ]
    };
    //config turnover
    const config = {
        type: 'bar',
        data: data,
        options: {
            title: {
                display: true,
                text: 'Turnovers (KRW)',
                fontSize: window.innerWidth > 1450 ? 30 : (window.innerWidth > 910 ? 20 : 12),
            },
            legend: {
                display: true,
                position: "bottom",
            },
            tooltips: {
                callbacks: {
                    // labelColor: function(tooltipItem, chart) {
                    //     return {
                    //         borderColor: 'rgb(255, 0, 0)',
                    //         backgroundColor: 'rgb(255, 0, 0)'
                    //     };
                    // },
                    // labelTextColor: function(tooltipItem, chart) {
                    //     return '#543453';
                    // },
                    footer: function (tooltipItems) {
                        let sum = 0;

                        tooltipItems.forEach(function (tooltipItem) {
                            sum += tooltipItem.yLabel;
                        });
                        return 'Total: ' + sum;
                    },
                }
            },
            responsive: true,
            scales: {
                xAxes: [{
                    ticks: {
                        beginAtZero: true,
                        maxRotation: 90,
                        minRotation: 0,
                    }
                }],
                yAxes: [{
                    scaleLabel: {
                        display: true,
                        // labelString: 'Measure : 10k'
                    },
                    ticks: {
                        beginAtZero: true,
                        stepSize: 10000,
                        // min: 0,
                        // max: 300,
                        userCallback: function (value, index, values) {
                            //2 decimals , {, thousands separators},
                            //newValue = number_format(newValue, 0, "", "");
                            let newValue = value / 1000;
                            return newValue + 'k';

                        }
                    }
                }]
            },
            animation: {
                duration: 1,
            }

        }
    };

    //config winloss
    const configWinLoss = {
        type: 'bar',
        data: data,
        options: {
            title: {
                display: true,
                text: 'WinLoss (KRW)',
                fontSize: window.innerWidth > 1450 ? 30 : (window.innerWidth > 910 ? 20 : 12),
            },
            legend: {
                display: true,
                position: "bottom",
            },
            tooltips: {
                callbacks: {
                    // labelColor: function(tooltipItem, chart) {
                    //     return {
                    //         borderColor: 'rgb(255, 0, 0)',
                    //         backgroundColor: 'rgb(255, 0, 0)'
                    //     };
                    // },
                    // labelTextColor: function(tooltipItem, chart) {
                    //     return '#543453';
                    // },
                    footer: function (tooltipItems) {
                        let sum = 0;

                        tooltipItems.forEach(function (tooltipItem) {
                            sum += tooltipItem.yLabel;
                        });
                        return 'Total: ' + sum;
                    },
                }
            },
            responsive: true,
            scales: {
                xAxes: [{
                    ticks: {
                        // beginAtZero: true,
                        maxRotation: 90,
                        minRotation: 0,
                    }
                }],
                yAxes: [{
                    scaleLabel: {
                        display: true,
                        // labelString: 'Measure : 10k'
                    },
                    ticks: {
                        // beginAtZero: true,
                        stepSize: 2000,
                        // min: 0,
                        // max: 300,
                        userCallback: function (value, index, values) {
                            //2 decimals , {, thousands separators},
                            //newValue = number_format(newValue, 0, "", "");
                            let newValue = value / 1000;
                            return newValue + 'k';

                        }
                    }
                }]
            },
            animation: {
                duration: 1,
            }

        }
    };
    //render turnover chart
    const myChart = new Chart(ctx, config);

    //render winLOss chart
    const myChartWinLoss = new Chart(ctxWinLoss, configWinLoss);



    //y-axis label on top 
    var myBarExtend = Chart.controllers.bar.prototype.draw;

    Chart.helpers.extend(Chart.controllers.bar.prototype, {
        draw: function () {
            myBarExtend.apply(this, arguments);
            var controller = this.chart.controller;
            var chart = controller.chart;
            var yAxis = controller.scales['y-axis-0'];
            var xOffset = chart.width - (chart.width - 5);
            var yOffset = chart.height - (chart.height - 18);
            //turnover
            ctx.font = window.innerWidth > 1450 ? '20px serif' : (window.innerWidth > 910 ? '15px serif' :
                '10px serif');
            ctx.fillText('Measure : 10k', xOffset, yOffset);
            //winloss
            ctxWinLoss.font = window.innerWidth > 1450 ? '20px serif' : (window.innerWidth > 910 ?
                '15px serif' :
                '10px serif');
            ctxWinLoss.fillText('Measure : 2k', xOffset, yOffset);
        }
    });

    //chart actions
    //-----turnover----
    //time period modifiers
    $(".turnover").click(function (e) {
        $(".turnover").removeClass("active");
        $(this).addClass("active");

        renderTurnoverChart();
    })
    //casino/slots modifiers
    $("#categorySelect").change(function (e) {
        renderTurnoverChart();
    })


    //-----winloss----
    //time period modifiers
    $(".winloss").click(function (e) {
        $(".winloss").removeClass("active");
        $(this).addClass("active");

        renderWinLossChart();
    })
    //casino/slots modifiers
    $("#categorySelectWinLoss").change(function (e) {
        renderWinLossChart();
    })

    //render charts
    //turnover chart
    function renderTurnoverChart() {
        var timePeriod = $(".turnover.active").attr("data-period");
        var category = $("#categorySelect").val();
        var token = $("#token_display_turnover_chart").val();

        $.ajax({
            url: '/ajaxProcessus/dashboard/displayTurnoverChart.php',
            type: 'POST',
            data: {
                "display": true,
                category,
                'period': timePeriod,
                token
            },
            cache: false,
            timeout: 10000,
            success: function (data) {

                if (data == 'unauthorized' || data == '') {
                    window.location.href = '/pages/errors/403.php';
                    return;
                }
                rese = JSON.parse(data);

                // console.log(rese[0]);
                let length = rese.length;


                if (length == 3) { //success
                    // console.log(rese[1]); //labels
                    // console.log(rese[0]); //data
                    let labels = [];
                    if (timePeriod !== "weekly") {
                        labels = rese[1];
                    } else {
                        Object.values(rese[1]).forEach(element => {
                            labels.push([element[0], '- ' + element[1]]);
                        });

                    }

                    //setup for chart
                    const data = {
                        labels: labels,
                        datasets: rese[0],
                    }

                    myChart.data = data;
                    // re-render the chart
                    myChart.update();


                    $("#token_display_turnover_chart").val(rese[2]);
                } else if (length == 1) { //csrf error
                    $("#token_display_turnover_chart").val(rese[0]);

                } else {
                    //refresh page;
                    location.reload();
                }



            }


        })
    }

    //turnover chart
    function renderWinLossChart() {
        var timePeriod = $(".winloss.active").attr("data-period");
        var category = $("#categorySelectWinLoss").val();
        var token = $("#token_display_winloss_chart").val();

        $.ajax({
            url: '/ajaxProcessus/dashboard/displayWinLossChart.php',
            type: 'POST',
            data: {
                "display": true,
                category,
                'period': timePeriod,
                token
            },
            cache: false,
            timeout: 10000,
            success: function (data) {

                if (data == 'unauthorized' || data == '') {
                    window.location.href = '/pages/errors/403.php';
                    return;
                }
                rese = JSON.parse(data);

                // console.log(rese[0]);
                let length = rese.length;


                if (length == 3) { //success
                    // console.log(rese[1]); //labels
                    // console.log(rese[0]); //data

                    let labels = [];
                    if (timePeriod !== "weekly") {
                        labels = rese[1];
                    } else {
                        Object.values(rese[1]).forEach(element => {
                            labels.push([element[0], '- ' + element[1]]);
                        });

                    }

                    //setup for chart
                    const data = {
                        labels: labels,
                        datasets: rese[0],
                    }

                    myChartWinLoss.data = data;
                    // re-render the chart
                    myChartWinLoss.update();


                    $("#token_display_winloss_chart").val(rese[2]);
                } else if (length == 1) { //csrf error
                    $("#token_display_winloss_chart").val(rese[0]);

                } else {
                    //refresh page;
                    location.reload();
                }



            }


        })
    }

    // --- DISPLAY NOTIFICATIONS HISTORY ----
    function displayNotifications(N = 1) {

        var token = $("#token_display").val();

        $.ajax({
            url: '/ajaxProcessus/dashboard/displayNotifications.php',
            type: 'POST',
            data: {
                "display": true,
                token
            },
            cache: false,
            timeout: 10000,
            success: function (data) {

                if (data == 'unauthorized' || data == '') {
                    window.location.href = '/pages/errors/403.php';
                    return;
                }
                rese = JSON.parse(data);

                // console.log(rese[0]);
                let length = rese.length;


                if (length == 4) { //success
                    $(".table-body-notifications").html(rese[0]);

                    $("#token_display").val(rese[3]);
                } else if (length == 1) { //csrf error
                    $("#token_display").val(rese[0]);

                } else {
                    //refresh page;
                    location.reload();
                }



            }


        })
    }
    document.addEventListener('DOMContentLoaded', (event) => {
        displayDashboardStats();
        displayNotifications();
        renderTurnoverChart();
        renderWinLossChart();
    });
</script>

</html>
