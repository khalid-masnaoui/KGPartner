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
                    <?php includeWithVariables('./../../../includes/partials/_innerheader.php', array('title' => 'Deposit & Withdraw', 'title_text' => 'Deposit & Withdraw Bonus.', 'icon' => "wallet")); ?>

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
                                                    class="input-group-text">Username</span></div>
                                            <input placeholder="" type="text" class="form-control shadow-none">
                                            <button class="btn btn-primary filter_btn">Submit</button>
                                        </div>
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
                                            Deposit Withdraw Bonus

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
                                                    <th class="text-center">Username</th>
                                                    <th class="text-center">Name </th>

                                                    <th class="text-center">Jackpot Contribution </th>
                                                    <th class="text-center">Jackpot Paid </th>
                                                    <th class="text-center">Promotion </th>
                                                    <th class="text-center">Total Transferred </th>
                                                    <th class="text-center">Balance </th>


                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td class="text-center text-muted">1</td>
                                                    <td class="text-center">Khalid El masnaoui</td>
                                                    <td class="text-center">malidkha</td>
                                                    <td class="text-center text-danger">-1</td>
                                                    <td class="text-center">0</td>
                                                    <td class="text-center">0</td>
                                                    <td class="text-center">0</td>
                                                    <td class="text-center">-1</td>






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
