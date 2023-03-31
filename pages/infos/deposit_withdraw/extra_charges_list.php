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
            width: 30%;
        }

        .filter_btn {
            margin-left: 20px;
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

    </style>
</head>

<?php include __DIR__ . '/../../../includes/partials/_flash_msg.php'; ?>

<?php
$modal_title = 'Total Game Result';
$modal_body = '<div class="row">
<div class="col-6 pdding5px font">TO : 5670306</div>
<div class="col-6 pdding5px font" style="text-align:right">Invoice No : 101779</div>
</div>
<div>
                            <div class="pdding5px font" style="text-align:right">Period : May-2021</div>
                        </div>
                        <br>
                        <br>
                        <br>
                        <br>
                        <br>
                        <br>
                        <br>

                        <div class="row">
                        <div class="col-8 pdding5px font">Total : (KRW)</div>
                        <div class="col-2 pdding5px font"></div>
                        <div class="col-2 pdding5px font" style="text-align:right">87</div>
                    </div>';
$modal_footer = '<button type="button" class="btn btn-secondary d-none" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary d-none">Save changes</button> ';

$modal_size = 'lg';
includeWithVariables('./../../../includes/modals/_modal.php', array('class' => 'extra_charge', 'modal_size' => $modal_size, 'modal_title' => $modal_title, 'modal_body' => $modal_body, 'modal_footer' => $modal_footer));
; ?>



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
                    <?php includeWithVariables('./../../../includes/partials/_innerheader.php', array('title' => 'Deposit & Withdraw', 'title_text' => 'Extra Charges List.', 'icon' => "wallet")); ?>

                    <!-- main content -->
                    <section class="main">

                        <div class="row">

                            <div class="col-md-12">
                                <div class="main-card mb-3 card">
                                    <div class="card-header"
                                        style='display:flex;height: 2.5rem;justify-content: space-between;'>


                                        <span>
                                            Extra Charges List

                                        </span>
                                        <div class="dropdown d-inline-block">
                                            <button type="button" aria-haspopup="true" aria-expanded="false"
                                                data-toggle="dropdown"
                                                class="mr-2 dropdown-toggle btn btn-outline-primary"><?= config::get("display/activeNumber"); ?></button>
                                            <div tabindex="-1" role="menu" aria-hidden="true" class="dropdown-menu"
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
                                    <div class="table-responsive mb-3 d-none">
                                        <table
                                            class="align-middle mb-0 table table-borderless table-striped table-hover">
                                            <thead>
                                                <tr>
                                                    <th class="text-center">#</th>
                                                    <th class="text-center">Agent</th>
                                                    <th class="text-center">Name </th>

                                                    <th class="text-center">Settlement Date </th>
                                                    <th class="text-center">Currency </th>
                                                    <th class="text-center">Amount </th>
                                                    <th class="text-center">Remark </th>
                                                    <th class="text-center">Created At </th>
                                                    <th class="text-center">Action </th>



                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td class="text-center text-muted">1</td>
                                                    <td class="text-center">BERING</td>
                                                    <td class="text-center">malidkha</td>
                                                    <td class="text-center">-</td>
                                                    <td class="text-center">-</td>
                                                    <td class="text-center">87</td>
                                                    <td class="text-center">4월 에볼루션 추가요금 </td>
                                                    <td class="text-center">2021-05-17 17:00:56 </td>

                                                    <td class="text-center">
                                                        <button class="mb-2 mr-2 btn-transition btn btn-outline-primary"
                                                            data-toggle="modal"
                                                            data-target=".extra_charge">View</button>
                                                        <button
                                                            class="mb-2 mr-2 btn-transition btn btn-outline-success">Download</button>
                                                    </td>







                                                </tr>

                                            </tbody>
                                        </table>
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

</body>

</html>
