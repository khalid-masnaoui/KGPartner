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
            width: 50%;
            display: flex;
            align-items: center;
            justify-content: space-around;


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
                flex-direction: column;
            }

            .grp2 {
                margin-left: 0px !important;
                margin-top: 15px;
                margin-bottom: 20px;
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
                    <?php includeWithVariables('./../../../includes/partials/_innerheader.php', array('title' => 'Reports', 'title_text' => 'JackPot TB Statements.', 'icon' => "news-paper")); ?>

                    <!-- main content -->
                    <section class="main">
                        <div class="row">

                            <div class="col-md-12">

                                <div class="main-card mb-3 card">
                                    <div class="card-header" style='height: 2.5rem;'>FILTER

                                    </div>
                                    <div class="filter-wrapper">
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span
                                                    class="input-group-text">StartDate</span></div>
                                            <input placeholder="" type="date" class="form-control shadow-none startdate"
                                                value=<?= date("Y-m-d") ?>>
                                        </div>
                                        <div class="input-group grp2 ml-3">
                                            <div class="input-group-prepend"><span
                                                    class="input-group-text">EndDate</span></div>
                                            <input placeholder="" type="date" class="form-control shadow-none enddate"
                                                value=<?= date("Y-m-d") ?>>
                                        </div>
                                        <div class='d-flex'>
                                            <button class="btn btn-secondary filter_bt ml-3">Reset</button>
                                            <button class="btn btn-primary filter_btn">Submit</button>
                                        </div>


                                    </div>


                                </div>


                            </div>

                        </div>
                        <div class="row">

                            <div class="col-md-12">
                                <div class="main-card mb-3 card">
                                    <div class="card-header" style='height: 2.5rem;justify-content: space-between;'>


                                        <span>

                                            JackPot TB Statements


                                        </span>

                                    </div>
                                    <div class="mt-2 mb-2 ml-3">
                                        <small>
                                            Displayed Data Period:
                                            <span class="data-period">-</span>
                                        </small>
                                    </div>
                                    <div class="table-responsive mb-3 d-none">
                                        <table
                                            class="align-middle mb-0 table table-borderless table-striped table-hover">
                                            <thead>
                                                <tr>
                                                    <th class="text-center">#</th>
                                                    <th class="text-center">Transaction Type </th>
                                                    <th class="text-center">Amount</th>
                                                    <th class="text-center">Jackpot Contri </th>
                                                    <th class="text-center">Jackpot Paid </th>
                                                    <th class="text-center">Promotion</th>
                                                    <th class="text-center">Total Balance</th>

                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td class="text-center text-muted"></td>
                                                    <td class="text-center">Opening Balance </td>
                                                    <td class="text-center"></td>
                                                    <td class="text-center"></td>

                                                    <td class="text-center"> </td>
                                                    <td class="text-center"></td>

                                                    <td class="text-center">-1</td>




                                                </tr>
                                                <tr>
                                                    <td class="text-center text-muted"></td>
                                                    <td class="text-center">Opening Balance </td>
                                                    <td class="text-center"></td>

                                                    <td class="text-center"> </td>
                                                    <td class="text-center"></td>
                                                    <td class="text-center"></td>

                                                    <td class="text-center">-1</td>




                                                </tr>


                                            </tbody>
                                        </table>
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


        document.addEventListener('DOMContentLoaded', (event) => {

            var date = new Date().toLocaleDateString("en-US", {
                timeZone: "Asia/Seoul"
            });
            // date = formatDate(date, 'YY/mm/dd');
            $(".data-period").text(date);

        });
    </script>
</body>

</html>
