<?php

require_once __DIR__ . "/../../../core/ini.php";
require_once __DIR__ . "/../../../core/inc_var.php";
include __DIR__ . '/../../../includes/partials/_authorization.php';


$db = DB::getInstance();

$logsActions = config::get("logs/partners");
$logsOptions = "";

foreach ($logsActions as $key => $value) {

    $logsOptions .= "<option value=$key data-text='" . $value['query'] . "'>" . $value["display"] . "</option>";

}



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


    <?php include __DIR__ . '/../../../includes/files/_stylesheets.php'; ?>
    <style>
        .filter-wrapper {
            margin-top: 20px;
            margin-bottom: 20px;
            margin-left: 20px;
            /* width:100%; */
            display: flex;
            align-items: center;
            /* justify-content: space-around; */


        }

        .filter_btn {
            margin-left: 20px;
        }

        .btn_action {
            width: max-content;
        }

        @media (max-width: 767px) {
            .filter_btn {
                margin: auto;
            }
        }

        @media (max-width: 540px) {
            .filter-wrapper {
                margin-top: 20px;
                margin-bottom: 20px;
                margin-left: 5px;
                margin-right: 5px;
                width: 95%;
            }
        }

        @media (max-width: 768px) {

            .f1,
            .f2,
            .f5 {


                width: 88% !important;
                /* flex-direction:column; */
            }

        }

        @media (max-width: 576px) {
            .row_filter_wrapper>div {


                width: 88% !important;
                /* flex-direction:column; */
            }

        }

        .nav-tabs .nav-link.active {
            color: #fff;
            background-image: linear-gradient(to top, #1e3c72 0%, #1e3c72 1%, #2a5298 100%) !important;
            /* background-image: linear-gradient(to right, #0f2027, #203a43, #2c5364) !important; */
        }

        .nav-tabs .nav-link.active:hover {
            color: #fff !important;
        }

    </style>
</head>

<?php include __DIR__ . '/../../../includes/partials/_flash_msg.php'; ?>


<body>
    <div class="app-container app-theme-white body-tabs-shadow fixed-sidebar fixed-header">
        <!-- header section  -->
        <?php includeWithVariables('./../../../includes/partials/_header.php', array('title' => 'Korea Gaming'));
        ; ?>



        <div class="app-main">
            <!-- sidebar section  -->
            <?php includeWithVariables('./../../../includes/partials/_sidebar.php');
            ; ?>

            <div class="app-main__outer">
                <div class="app-main__inner">

                    <!-- inner header section  -->
                    <?php includeWithVariables('./../../../includes/partials/_innerheader.php', array('title' => 'Account', 'title_text' => 'Action Logs.', 'icon' => "folder")); ?>

                    <!-- main content -->
                    <section class="main">
                        <div class="row">

                            <div class="col-md-12">

                                <div class="main-card mb-3 card">
                                    <div class="card-header" style='height: 2.5rem;'>FILTER

                                    </div>
                                    <div class="row mt-2 row_filter_wrapper">

                                        <div class="f1 input-group col-md-5 col-lg-4 col-xl-3 ml-1 pl-0 pr-0 ml-4"
                                            style='width:unset;'>
                                            <div class="input-group-prepend"><span class="input-group-text">Operator
                                                    Admin</span></div>
                                            <input id="adminOperator" name="adminOperator" placeholder="" type="text"
                                                class="form-control shadow-none">
                                        </div>
                                        <div
                                            class="f2 d-flex ml-1 pl-0 pr-0 col-md-5 col-lg-3 col-xl-2  ml-4  ml-md-1 mt-2 mt-md-0">
                                            <div class="input-group-prepend"><span class="input-group-text"
                                                    style="border-top-right-radius: 0;border-bottom-right-radius: 0;">Partner</span>
                                            </div>
                                            <select type="select" id="partnerSelect" name="partnerSelect"
                                                class="custom-select"
                                                style="border-top-left-radius: 0;border-bottom-left-radius: 0;">
                                                <option value="all">All</option>
                                            </select>
                                        </div>
                                        <div class="input-group ml-1 pl-0 pr-0 d-flex ml-1 pl-0 pr-0 col-md-5 col-lg-4 col-xl-3  mt-2 mt-lg-0 ml-4 ml-lg-1 "
                                            style='width:unset;'>
                                            <div class="input-group-prepend"><span
                                                    class="input-group-text">StartDate</span></div>
                                            <input placeholder="" type="date" class="form-control shadow-none startdate"
                                                value=<?= date("Y-m-d") ?>>
                                        </div>


                                        <div class="input-group grp2 ml-1 pl-0 pr-0 d-flex ml-1 pl-0 pr-0 col-md-5 col-lg-4 col-xl-3 mt-2 mt-xl-0 ml-1 ml-lg-4 ml-xl-1   ml-4 ml-sm-1 "
                                            style='width:unset;'>
                                            <div class="input-group-prepend"><span
                                                    class="input-group-text">EndDate</span></div>
                                            <input placeholder="" type="date" class="form-control shadow-none enddate"
                                                value=<?= date("Y-m-d") ?>>
                                        </div>
                                    </div>
                                    <div class="row mt-2 mt-md-3 mb-3 row_filter_wrapper">

                                        <div class="f5 d-flex col-md-5 col-lg-4 col-xl-3 pl-0 pr-0 ml-1 pl-0 pr-0 ml-4">
                                            <div class="input-group-prepend"><span class="input-group-text"
                                                    style="border-top-right-radius: 0;border-bottom-right-radius: 0;">Action</span>
                                            </div>
                                            <select type="select" id="actionSelect" name="actionSelect"
                                                class="custom-select"
                                                style="border-top-left-radius: 0;border-bottom-left-radius: 0;">
                                                <option value="all" selected="">All</option>
                                                <?= $logsOptions ?>
                                            </select>
                                        </div>
                                        <div class="custom-checkbox custom-control custom-control-inline ml-1 pl-0 pr-0 col-md-5 col-lg-2 col-xl-2  d-flex  ml-5  ml-md-5 mt-2 mt-md-0"
                                            style='display:none !important;align-items: center;   /* margin-left: 30px !important;*/'>
                                            <input type="checkbox" id="downline" class="custom-control-input">
                                            <label class="custom-control-label" for="downline">All
                                                Downline</label>
                                        </div>


                                        <button class="btn btn-primary filter_btn mt-3 mt-lg-0">Submit</button>

                                    </div>


                                </div>


                            </div>

                        </div>
                        <div class="row">

                            <div class="col-md-12">
                                <div class="main-card mb-3 card">
                                    <div class="card-header"
                                        style='display:flex;height: 2.5rem;justify-content: space-between;'>


                                        <span>
                                            Action Logs

                                        </span>
                                        <div class="dropdown d-inline-block clients_number_wrapper">

                                            <button type="button" aria-haspopup="true" aria-expanded="false"
                                                data-toggle="dropdown"
                                                class="mr-2 dropdown-toggle active-logs-number btn btn-outline-primary"><?= config::get("display/activeNumber"); ?></button>
                                            <div tabindex="-1" role="menu" aria-hidden="true"
                                                class="dropdown-menu logs_number_options"
                                                style='min-width: min-content;'>
                                                <button type="button" data-id='20' tabindex="0"
                                                    class="dropdown-item">20</button>
                                                <button type="button" data-id='50' tabindex="0"
                                                    class="dropdown-item">50</button>
                                                <button type="button" data-id='100' tabindex="0"
                                                    class="dropdown-item">100</button>
                                                <button type="button" data-id='200' tabindex="0"
                                                    class="dropdown-item">200</button>
                                                <button type="button" data-id='500' tabindex="0"
                                                    class="dropdown-item">500</button>

                                            </div>
                                        </div>

                                    </div>

                                    <input type="hidden" name="token_display" id="token_display"
                                        value="<?= token::generate("display_logs") ?>">

                                    <div class="table-responsive mb-3">
                                        <table
                                            class="align-middle mb-0 table table-borderless table-striped table-hover">
                                            <thead>
                                                <tr>
                                                    <th class="text-center">#</th>
                                                    <th class="text-center">Id</th>
                                                    <th class="text-center">Operator Admin</th>
                                                    <th class="text-center">Partner Name</th>
                                                    <th class="text-center">Details </th>
                                                    <th class="text-center">IP Address</th>
                                                    <th class="text-center">Nation</th>
                                                    <th class="text-center">Timestamp</th>


                                                </tr>
                                            </thead>
                                            <tbody class='table-body-logs'>

                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="pager ml-3">
                                        <nav class="navigation_logs" aria-label="navigation_logs">
                                        </nav>
                                    </div>

                                    <div class="notice mt-3 ml-3">



                                        <small>
                                            Note: The date will be based on time zone GMT+09:00
                                        </small>
                                    </div>





                                </div>
                            </div>


                        </div>






                    </section>






                </div>
                <!-- footer section  -->
                <?php includeWithVariables('./../../../includes/partials/_footer.php'); ?>
            </div>
        </div>
    </div>
    <?php include __DIR__ . '/../../../includes/files/_scripts.php'; ?>

    <script>
        // document.querySelector(".startdate").value="2022-03-05";
        // document.querySelector(".enddate").value="2022-03-05";

        // --- DISPLAY ACTION LOGS ----
        function displayActionLogs(N = 1) {

            var activePage = $(".navigation_logs li.page-item.active a").text();
            var activeNumber = $(".active-logs-number").text();
            activeNumber = activeNumber.trim();

            // if (N == true) {
            //     N = activePage;
            // }
            var adminOperator = $("#adminOperator").val();
            var partnerSelect = $("#partnerSelect").val()

            var startDate = $(".startdate").val();
            var endDate = $(".enddate").val();

            var actionSelect = $("#actionSelect").val();

            var token = $("#token_display").val();

            $.ajax({
                url: '/ajaxProcessus/memberAccounts/logs/displayActionLogs.php',
                type: 'POST',
                data: {
                    "display": true,
                    "page": N,
                    "number": activeNumber,
                    adminOperator,
                    partnerSelect,
                    startDate,
                    endDate,
                    actionSelect,
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
                        $(".table-body-logs").html(rese[0]);
                        $(".navigation_logs").html(rese[1]);

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


        //NUMBERS DISPLAYED PER PAGE
        $(".logs_number_options button").on("click", function (event) {

            var number = event.currentTarget.textContent;
            $(".active-logs-number").text(number);

            displayActionLogs(1);
        })

        //filter_btn
        $(".filter_btn").click(function (e) {
            displayActionLogs();
        })

        document.addEventListener('DOMContentLoaded', (event) => {

            displayActionLogs(0);

        });
    </script>
</body>

</html>
