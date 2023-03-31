<?php

require_once __DIR__ . "/../../../core/ini.php";
require_once __DIR__ . "/../../../core/inc_var.php";
include __DIR__ . '/../../../includes/partials/_authorization.php';

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

        @media (max-width: 540px) {
            .filter-wrapper {
                margin-top: 20px;
                margin-bottom: 20px;
                margin-left: 5px;
                margin-right: 5px;

                width: 95%;
                /* flex-direction:column; */
            }

            /* .grp2{
                    margin-left:0px  !important;
                    margin-top:15px;
                    margin-bottom:20px;
                } */
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
                    <?php includeWithVariables('./../../../includes/partials/_innerheader.php', array('title' => 'Deposit & Withdraw', 'title_text' => 'Manual Adjustment List.', 'icon' => "wallet")); ?>

                    <!-- main content -->
                    <section class="main">
                        <div class="row">

                            <div class="col-md-12">

                                <div class="main-card mb-3 card">
                                    <div class="card-header" style='height: 2.5rem;'>FILTER

                                    </div>
                                    <div class="row mt-2 row_filter_wrapper mb-3">


                                        <div
                                            class="f2 d-flex ml-1 pl-0 pr-0 col-md-5 col-lg-2 col-xl-2  ml-4  mt-2 mt-md-0 ml-1 pl-0 pr-0 ml-4">
                                            <div class="input-group-prepend"><span class="input-group-text"
                                                    style="border-top-right-radius: 0;border-bottom-right-radius: 0;">Type</span>
                                            </div>
                                            <select type="select" id="exampleCustomSelect" name="customSelect"
                                                class="custom-select"
                                                style="border-top-left-radius: 0;border-bottom-left-radius: 0;">
                                                <option value="">All</option>
                                                <option>Deposit / Withdraw</option>
                                                <option>Member Winnings</option>

                                            </select>
                                        </div>
                                        <div class="input-group ml-1 pl-0 pr-0 d-flex ml-1 pl-0 pr-0 col-md-5 col-lg-4 col-xl-3  mt-2 mt-md-0 ml-4 ml-lg-1 "
                                            style='width:unset;'>
                                            <div class="input-group-prepend"><span
                                                    class="input-group-text">StartDate</span></div>
                                            <input placeholder="" type="date" class="form-control shadow-none startdate"
                                                value=<?= date("Y-m-d") ?>>
                                        </div>


                                        <div class="input-group grp2 ml-1 pl-0 pr-0 d-flex ml-1 pl-0 pr-0 col-md-5 col-lg-4 col-xl-3 mt-2 mt-lg-0 ml-4 ml-lg-1 ml-xl-1   ml-4"
                                            style='width:unset;'>
                                            <div class="input-group-prepend"><span
                                                    class="input-group-text">EndDate</span></div>
                                            <input placeholder="" type="date" class="form-control shadow-none enddate"
                                                value=<?= date("Y-m-d") ?>>
                                        </div>
                                        <button class="btn btn-primary filter_btn mt-2 mt-lg-0">Submit</button>

                                    </div>



                                </div>


                            </div>

                        </div>
                        <div class="row">

                            <div class="col-md-12">
                                <div class="main-card mb-3 card">
                                    <div class="card-header" style='height: 2.5rem;justify-content: space-between;'>


                                        <span>
                                            Manual Adjustment


                                        </span>

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
