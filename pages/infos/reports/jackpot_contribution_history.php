<?php

require_once __DIR__ . "/../../../core/ini.php";
require_once __DIR__ . "/../../../core/inc_var.php";
include __DIR__ . '/../../../includes/partials/_authorization.php';

$db = DB::getInstance();
$clients = $db->get("id,username,prefix", "clients", [])->results();
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

        @media (max-width: 620px) {
            .card-header2 {
                flex-direction: column;
                height: 6.2rem !important;
            }

            .status_number_wrapper {
                margin-top: 10px;
            }
        }

        @media (max-width: 410px) {
            .card-header2 {
                height: 7.8rem !important;
            }

            .status_number_wrapper {
                flex-direction: column;

            }
        }

        @media (max-width: 390px) {
            .card-header2 {
                height: 9.2rem !important;
            }
        }

        @media (max-width: 990px) {
            .table-summary {
                max-width: unset !important;
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

        table.summary thead th {
            background-color: rgba(63, 106, 216, 0.9) !important;
            color: white !important;
        }

        table.summary th,
        td {
            width: unset !important;
            height: unset !important;
        }

        .total-row td {
            background-color: rgba(247, 176, 36, 0.6) !important;
        }

        .app-wrapper-footer {
            position: fixed;
            bottom: 0;
            width: 100%;
        }

    </style>
</head>

<?php include __DIR__ . '/../../../includes/partials/_flash_msg.php'; ?>


<body>
    <div class="app-container app-theme-white body-tabs-shadow fixed-sidebar fixed-header">
        <!-- header section  -->
        <?php includeWithVariables('./../../../includes/partials/_header.php', array('title' => 'Korea Gaming'));
        ; ?>



        <div class="app-main" style="display:block;">
            <!-- sidebar section  -->
            <?php includeWithVariables('./../../../includes/partials/_sidebar.php');
            ; ?>

            <div class="app-main__outer">
                <div class="app-main__inner">

                    <!-- inner header section  -->
                    <?php includeWithVariables('./../../../includes/partials/_innerheader.php', array('title' => 'Reports', 'title_text' => 'JackPot Contribution History.', 'icon' => "news-paper")); ?>

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

                                            <div class="input-group  d-flex ml-1 pl-0 pr-0 col-md-5  col-10 col-lg-4 col-xl-3 mt-2  ml-1 pl-0 pr-0 ml-4"
                                                style='width:unset;'>
                                                <div class="input-group-prepend"><span
                                                        class="input-group-text">StartDate</span></div>
                                                <input placeholder="" type="date"
                                                    class="form-control shadow-none startdate" value=<?= date("Y-m-d") ?>>
                                            </div>


                                            <div class="input-group  d-flex ml-1 pl-0 pr-0 col-md-5 col-lg-4 col-xl-3 col-10  mt-2  ml-1 pl-0 pr-0 ml-4 ml-xl-1"
                                                style='width:unset;'>
                                                <div class="input-group-prepend"><span
                                                        class="input-group-text">EndDate</span></div>
                                                <input placeholder="" type="date"
                                                    class="form-control shadow-none enddate" value=<?= date("Y-m-d") ?>>
                                            </div>

                                            <div
                                                class="select-status input-group  d-flex ml-1 pl-0 pr-0 col-md-5 col-lg-4 col-xl-3 col-10 mt-2 pl-0 pr-0 ml-4 ml-xl-1">
                                                <div class="input-group-prepend"><span
                                                        class="input-group-text">Agent</span></div>
                                                <input placeholder="" type="text" id='agent_filtered'
                                                    class="form-control shadow-none">
                                            </div>





                                            <button
                                                class="btn btn-primary filter_jackpot_contribution filter_btn mt-2 ml-4 mr-4">Submit</button>

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
                                    <div class="card-header"
                                        style='display: flex;height: 2.5rem;justify-content: space-between;'>


                                        <span>
                                            JackPot Contribution History
                                        </span>


                                        <div class="dropdown d-inline-block">
                                            <button type="button" aria-haspopup="true" aria-expanded="false"
                                                data-toggle="dropdown"
                                                class="mr-2 dropdown-toggle active-jackpot_contribution-number btn btn-outline-primary"><?= config::get("display/activeNumber"); ?>
                                            </button>
                                            <div tabindex="-1" role="menu" aria-hidden="true"
                                                class="dropdown-menu jackpot_contribution_number_options"
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

                                    <input type="hidden" name="token_display" id="token_display"
                                        value="<?= token::generate("display_jackpot_contribution") ?>">




                                    <div class="table-responsive mb-3 table-jackpot_contribution d-none">
                                        <table
                                            class="align-middle mb-0 table table-bordered table-striped table-hover jackpot_contribution">
                                            <thead>


                                            </thead>
                                            <tbody class='table-body-jackpot_contribution'>

                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="pager ml-3">
                                        <nav class="navigation_jackpot_contribution"
                                            aria-label="navigation_jackpot_contribution">
                                        </nav>
                                    </div>

                                    <div class="body mt-2 mb-2 ml-3">
                                        <small>
                                            No record(s) found!
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
    </script>
</body>

</html>
