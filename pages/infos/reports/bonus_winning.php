<?php

require_once __DIR__ . "/../../../core/ini.php";
require_once __DIR__ . "/../../../core/inc_var.php";
include __DIR__ . '/../../../includes/partials/_authorization.php';

$db = DB::getInstance();
$partner = new user();
$partnerPtId = $partner->data()["pt_id"];

$sql = "SELECT id,username,prefix FROM clients WHERE pt_id = ?";
$clients = $db->query($sql, ["$partnerPtId"])->results();
$options = "";

foreach ($clients as $key => $value) {
    $options .= "<option value=" . $value['id'] . " data-prefix=" . $value['prefix'] . ">" . $value["username"] . "</option>";
}

//providers options
$activeProviders = config::get("display/activeProviders");
$ProvidersNameMapping = config::get("providersNameMappings");

$providersOptions = "";

foreach ($activeProviders as $key => $value) {
    if (!isset($ProvidersNameMapping[$value])) {
        continue;
    }
    $providersOptions .= "<option value=" . $value . ">" . $ProvidersNameMapping[$value] . "</option>";
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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css?ver=5.2.4">
    <link rel="stylesheet" type="text/css" href="https://npmcdn.com/flatpickr/dist/themes/material_blue.css">
    <style>
        .filter-wrapper {
            /* margin-top: 20px;
        margin-bottom: 20px;
        margin-left: 20px; */
            /* width:100%; */
            display: flex;
            align-items: center;
            justify-content: space-between;


        }

        .row_filter_wrapper {
            flex: 2;
            width: 100%;

        }

        .clslct {
            margin-right: 10px;

        }

        .filter_btn {
            margin-left: 20px;
        }

        .btn_action {
            width: max-content;
        }

        .status_ {
            border: none;
            background: transparent;
            margin: 0;
            padding: 0;
            cursor: pointer;
        }

        @media (max-width: 1500px) {
            .filter-wrapper {
                flex-direction: column;
                align-items: flex-start;
            }

            .clslct {
                margin-bottom: 20px;
                margin-left: 10px;

            }
        }

        @media (max-width: 767px) {
            .f-user {
                margin-top: 0.5rem !important;

            }

        }

        @media (max-width: 576px) {
            .row_filter_wrapper>div {


                width: 88% !important;
                /* flex-direction:column; */
            }

        }

        @media (max-width: 595px) {
            .card-header2 {
                flex-direction: column;
                height: 5.4rem !important;
            }
        }

        @media (max-width: 410px) {
            .card-header2 {
                height: 6.4rem !important;
            }

            .card-header2 {
                height: 6.4rem !important;
            }

            .status_number_wrapper {
                flex-direction: column;

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

        .startdate,
        .enddate {
            background: url(https://img.icons8.com/cotton/64/000000/calendar.png) no-repeat;
            background-size: 21px 21px;
            background-position-x: right;
            background-position-y: center;
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
                    <?php includeWithVariables('./../../../includes/partials/_innerheader.php', array('title' => 'Reports', 'title_text' => 'Bonus Winning History.', 'icon' => "news-paper")); ?>

                    <!-- main content -->
                    <section class="main">
                        <div class="row">

                            <div class="col-md-12">

                                <div class="main-card mb-3 card">
                                    <div class="card-header" style='height: 2.5rem;'>FILTER

                                    </div>
                                    <div class="d-flex filter-wrapper">
                                        <div class="row mt-2 row_filter_wrapper mb-3">

                                            <div
                                                class="select-provider input-group  d-flex ml-1 pl-0 pr-0 col-md-5 col-lg-4 col-xl-3 col-10  ml-1 pl-0 pr-0 ml-4">
                                                <div class="input-group-prepend"><span
                                                        class="input-group-text">Provider</span></div> <select
                                                    type="select" id="providerSelect" name="providerSelect"
                                                    class="custom-select">
                                                    <?= $providersOptions; ?>
                                                </select>
                                            </div>

                                            <div class="f1 f-user input-group col-md-5 col-lg-4 col-xl-3 col-10 ml-1 pl-0 pr-0  mt-2 mt-sm-0 ml-4 ml-xl-1"
                                                style='width:unset;'>
                                                <div class="input-group-prepend"><span
                                                        class="input-group-text">Username</span></div>
                                                <input placeholder="" type="text" id='player_name_filtered'
                                                    class="form-control shadow-none">
                                            </div>

                                            <div class="f1 input-group col-md-5 col-lg-4 col-xl-3 col-10 ml-1 pl-0 pr-0  mt-2 mt-xl-0 ml-4 ml-xl-1"
                                                style='width:unset;'>
                                                <div class="input-group-prepend"><span class="input-group-text">Member
                                                        ID</span></div>
                                                <input placeholder="" type="text" id='member_id_filtered'
                                                    class="form-control shadow-none">
                                            </div>

                                            <?php
                                            $tz = 'Asia/Seoul';
                                            $timestamp = time();
                                            $dt = new DateTime("now", new DateTimeZone($tz)); //first argument "must" be a string
                                            $dt->setTimestamp($timestamp); //adjust the object to correct timestamp
                                            $timeRFC = $dt->format('Y-m-d\T00:00:00');
                                            $timeRFC2 = $dt->format('Y-m-d\T23:59:59');
                                            // date("Y-m-d\TH:i:s") //local
                                            ?>

                                            <div class="input-group  d-flex ml-1 pl-0 pr-0 col-md-5  col-10 col-lg-4 col-xl-3 mt-2  ml-1 pl-0 pr-0 ml-4"
                                                style='width:unset;'>
                                                <div class="input-group-prepend"><span
                                                        class="input-group-text">StartDate</span></div>
                                                <input placeholder="" type="datetime-local"
                                                    class="form-control shadow-none startdate" value=<?= $timeRFC ?>>
                                            </div>


                                            <div class="input-group  d-flex ml-1 pl-0 pr-0 col-md-5 col-lg-4 col-xl-3 col-10  mt-2  ml-1 pl-0 pr-0 ml-4 ml-xl-1"
                                                style='width:unset;'>
                                                <div class="input-group-prepend"><span
                                                        class="input-group-text">EndDate</span></div>
                                                <input placeholder="" type="datetime-local"
                                                    class="form-control shadow-none enddate" value=<?= $timeRFC2 ?>>
                                            </div>

                                            <div
                                                class="select-status input-group  d-flex ml-1 pl-0 pr-0 col-md-5 col-lg-4 col-xl-3 col-10 mt-2 pl-0 pr-0 ml-4 ml-xl-1">
                                                <div class="input-group-prepend"><span
                                                        class="input-group-text">Type</span></div> <select type="select"
                                                    id="typeSelect" name="typeSelect" class="custom-select">
                                                    <option value="all">All</option>
                                                    <option value="1">In Game Bonus</option>
                                                    <option value="2">Promotion</option>
                                                    <option value="3">JackPot</option>
                                                </select>
                                            </div>

                                            <button
                                                class="btn btn-primary filter_bonus filter_btn mt-2 ml-4 mr-4">Submit</button>

                                        </div>

                                        <div class="clslct">
                                            <select type="select" id="clientSelect" name="clientSelect"
                                                class="custom-select">
                                                <option value="all">All</option>
                                                <?php
                                                echo $options;

                                                ?>

                                            </select>
                                        </div>
                                    </div>




                                </div>


                            </div>

                        </div>
                        <div class="row">

                            <div class="col-md-12">
                                <div class="main-card mb-3 card">
                                    <div class="card-header  card-header2"
                                        style='display: flex;height: 2.5rem;justify-content: space-between;'>


                                        <span>


                                            Bonus Winning


                                        </span>
                                        <div class="d-flex status_number_wrapper">

                                            <div class="d-flex">
                                                <button id="status_accepted" class='status_ align-self-end'>
                                                    <div class="mb-2 mr-2 badge badge-pill badge-success">
                                                        Accepted</div>
                                                </button>
                                                <button id="status_error" class='status_ align-self-end'>
                                                    <div class="mb-2 mr-2 badge badge-pill badge-danger"
                                                        style="color:white !important;">Error</div>
                                                </button>
                                                <button id="status_all" class='status_ active align-self-end'>
                                                    <div class="mb-2 mr-2 badge badge-pill badge-focus">
                                                        All</div>
                                                </button>




                                            </div>
                                            <div class="dropdown d-inline-block">
                                                <button type="button" aria-haspopup="true" aria-expanded="false"
                                                    data-toggle="dropdown"
                                                    class="mr-2 dropdown-toggle active-bonus-number btn btn-outline-primary"><?= config::get("display/activeNumber"); ?>
                                                </button>
                                                <div tabindex="-1" role="menu" aria-hidden="true"
                                                    class="dropdown-menu bonus_number_options"
                                                    style='min-width: min-content;'>
                                                    <button type="button" data-id='2' tabindex="0"
                                                        class="dropdown-item">2</button>
                                                    <button type="button" data-id='5' tabindex="0"
                                                        class="dropdown-item">5</button>
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

                                    </div>

                                    <div class="table-responsive mb-3">

                                        <input type="hidden" name="token_display" id="token_display"
                                            value="<?= token::generate("display_bonus") ?>">

                                        <table
                                            class="align-middle mb-0 table table-borderless table-striped table-hover">
                                            <thead>
                                                <tr>
                                                    <th class="text-center">#</th>
                                                    <th class="text-center">Transaction Id</th>
                                                    <th class="text-center">Client</th>
                                                    <th class="text-center">Parent</th>
                                                    <th class="text-center">Player ID</th>
                                                    <th class="text-center">Prefix_Username</th>
                                                    <th class="text-center">Provider</th>
                                                    <th class="text-center">Game Name</th>
                                                    <th class="text-center">Status </th>
                                                    <th class="text-center">Amount</th>
                                                    <th class="text-center">Type</th>
                                                    <th class="text-center">Date</th>
                                                </tr>
                                            </thead>
                                            <tbody class='table-body-bonus'>

                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="pager ml-3">
                                        <nav class="navigation_bonus" aria-label="navigation_bonus">
                                        </nav>
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
    <script src="https://cdn.jsdelivr.net/npm/flatpickr?ver=5.2.4"></script>
    <script src="https://npmcdn.com/flatpickr/dist/flatpickr.min.js"></script>
    <script src="https://npmcdn.com/flatpickr/dist/l10n/ko.js"></script>


    <script>
        // document.querySelector(".startdate").value="2022-03-05";
        // document.querySelector(".enddate").value="2022-03-05";


        $(".startdate, .enddate").flatpickr({
            enableTime: true,
            dateFormat: "Y-m-d H:i:S",
            time_24hr: true,
            locale: "ko",
            allowInput: true
        });



        // --- DISPLAY BONUS HISTORY ----
        function displayBonuses(N = 1, status = 'all') {

            var activePage = $(".navigation_bonus li.page-item.active a").text();
            var activeNumber = $(".active-bonus-number").text();
            activeNumber = activeNumber.trim();

            // if (N == true) {
            //     N = activePage;
            // }
            var provider = $("#providerSelect").val();
            var memberID = $("#member_id_filtered").val();
            var text = $("#player_name_filtered").val();
            var startDate = $(".startdate").val();
            var endDate = $(".enddate").val();

            var BonusType = $("#typeSelect").val();

            var client = $("#clientSelect").val()


            var token = $("#token_display").val();

            $.ajax({
                url: '/ajaxProcessus/infoReports/displayBonus.php',
                type: 'POST',
                data: {
                    "display": true,
                    "page": N,
                    "number": activeNumber,
                    provider,
                    memberID,
                    "nameFilter": text,
                    startDate,
                    endDate,
                    BonusType,
                    client,
                    status,
                    token
                },
                // contentType: false,
                // processData: false, 
                cache: false,
                timeout: 10000,
                // cache: false,
                // dataType: 'json', 

                success: function (data) {
                    // var num = data.indexOf("<!DOCTYPE html>");
                    // var rese = data.substr(0, num);
                    // rese = rese.trim();
                    // console.log(data);
                    if (data == 'unauthorized' || data == '') {
                        window.location.href = '/pages/errors/403.php';
                        return;
                    }
                    rese = JSON.parse(data);

                    // console.log(rese[0]);
                    let length = rese.length;


                    if (length == 4) { //success
                        $(".table-body-bonus").html(rese[0]);
                        $(".navigation_bonus").html(rese[1]);

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
        $(".bonus_number_options button").on("click", function (event) {

            var number = event.currentTarget.textContent;
            $(".active-bonus-number").text(number);

            let id = $(".status_.active").attr("id");

            let status = 'all';

            if (id == "status_accepted") {
                status = "accepted";
            } else if (id == "status_error") {
                status = "error";
            }

            displayBonuses(1, status);
        })

        function resetStatusFilter() {
            $(".status_").css("opacity", 1);
            $(".status_").removeClass("active");
            $("#status_all").addClass("active");
        }

        //FILTERs
        $(".filter_bonus").on("click", function (event) {
            resetStatusFilter();

            displayBonuses();
        })

        //CLIENT SELECT
        $("#clientSelect").change(function (event) {
            displayBonuses();
        })

        //status filter
        $(".status_").click(function (event) {
            let status = '';
            let id = $(event.currentTarget).attr("id");
            if (id == "status_all") {
                $(".status_").css("opacity", 1);

                $(".status_").removeClass("active");
                $(this).addClass("active");

                status = "all";

            } else {
                $(".status_").css("opacity", 0.2);
                $(this).css("opacity", 1);

                if (id == "status_accepted") {
                    status = "accepted";
                } else if (id == "status_error") {
                    status = "error";
                }

                $(".status_").removeClass("active");
                $(this).addClass("active");
            }

            displayBonuses(1, status);

        })

        document.addEventListener('DOMContentLoaded', (event) => {

            displayBonuses(0);

        });
    </script>
</body>

</html>
