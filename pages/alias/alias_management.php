<?php

require_once __DIR__ . "/../../core/ini.php";
require_once __DIR__ . "/../../core/inc_var.php";
include __DIR__ . '/../../includes/partials/_authorization.php';

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


    <?php include __DIR__ . '/../../includes/files/_stylesheets.php'; ?>

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

        label::before {
            outline: none !important;
            border: none !important;
        }

    </style>
</head>

<?php include __DIR__ . '/../../includes/partials/_flash_msg.php'; ?>

<body>
    <div class="app-container app-theme-white body-tabs-shadow fixed-sidebar fixed-header">
        <!-- header section  -->
        <?php includeWithVariables('./../../includes/partials/_header.php', array('title' => 'Korea Gaming'));
        ; ?>



        <div class="app-main">
            <!-- sidebar section  -->
            <?php includeWithVariables('./../../includes/partials/_sidebar.php');
            ; ?>

            <div class="app-main__outer">
                <div class="app-main__inner">

                    <!-- inner header section  -->
                    <?php includeWithVariables('./../../includes/partials/_innerheader.php', array('title' => 'ALias', 'title_text' => 'Alias Management.', 'icon' => "edit")); ?>

                    <!-- main content -->
                    <section class="main">

                        <div class="row">

                            <div class="col-md-12">
                                <div class="main-card mb-3 card">
                                    <div class="card-header"
                                        style='display:flex;height: 2.5rem;justify-content: space-between;'>


                                        <span>

                                            Alias Account

                                        </span>
                                        <div class="dropdown d-inline-block">
                                            <button type="button" aria-haspopup="true" aria-expanded="false"
                                                data-toggle="dropdown"
                                                class="mr-2 dropdown-toggle btn btn-outline-primary">20</button>
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
                                    <div class="table-responsive mb-3">
                                        <table
                                            class="align-middle mb-0 table table-borderless table-striped table-hover">
                                            <thead>
                                                <tr>
                                                    <th class="text-center">#</th>
                                                    <th class="text-center">Username</th>
                                                    <th class="text-center">Name</th>

                                                    <th class="text-center">Edit</th>
                                                    <th class="text-center">Status</th>
                                                    <th class="text-center">Lock</th>
                                                    <th class="text-center">Account</th>
                                                    <th class="text-center">Product</th>
                                                    <th class="text-center">Report</th>
                                                    <th class="text-center">Setting</th>
                                                    <th class="text-center">Deposit & Withdraw</th>
                                                    <th class="text-center">Member PT </th>
                                                    <th class="text-center">Last Login</th>
                                                    <th class="text-center">Last IP</th>
                                                    <th class="text-center">Nation</th>
                                                    <th class="text-center">Delete</th>



                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td class="text-center text-muted">1</td>
                                                    <td class="text-center">5670306SUB00</td>
                                                    <td class="text-center">0</td>

                                                    <td class="text-center">서브</td>
                                                    <td class="text-center"><i class="fa fa-fw"
                                                            style="color:#16aaff;cursor:pointer;" aria-hidden="true"
                                                            title="Copy to use edit"></i></td>
                                                    <td class="text-center">Open</td>
                                                    <td class="text-center">Unlock</td>
                                                    <td class="text-center">
                                                        <div class="custom-checkbox custom-control"><input
                                                                type="checkbox" id="exampleCustomCheckbox3" checked=""
                                                                disabled="false" class="custom-control-input"><label
                                                                class="custom-control-label"
                                                                for="exampleCustomCheckbox3"></label></div>
                                                    </td>
                                                    <td class="text-center">
                                                        <div class="custom-checkbox custom-control"><input
                                                                type="checkbox" id="exampleCustomCheckbox3" checked=""
                                                                disabled="false" class="custom-control-input"><label
                                                                class="custom-control-label"
                                                                for="exampleCustomCheckbox3"></label></div>
                                                    </td>
                                                    <td class="text-center">
                                                        <div class="custom-checkbox custom-control"><input
                                                                type="checkbox" id="exampleCustomCheckbox3" checked=""
                                                                disabled="false" class="custom-control-input"><label
                                                                class="custom-control-label"
                                                                for="exampleCustomCheckbox3"></label></div>
                                                    </td>
                                                    <td class="text-center">
                                                        <div class="custom-checkbox custom-control"><input
                                                                type="checkbox" id="exampleCustomCheckbox3" checked=""
                                                                disabled="false" class="custom-control-input"><label
                                                                class="custom-control-label"
                                                                for="exampleCustomCheckbox3"></label></div>
                                                    </td>
                                                    <td class="text-center">
                                                        <div class="custom-checkbox custom-control"><input
                                                                type="checkbox" id="exampleCustomCheckbox3"
                                                                disabled="false" class="custom-control-input"><label
                                                                class="custom-control-label"
                                                                for="exampleCustomCheckbox3"></label></div>
                                                    </td>
                                                    <td class="text-center">2021-09-30 21:17:04 </td>
                                                    <td class="text-center">2021-09-30 21:17:04 </td>
                                                    <td class="text-center">0</td>
                                                    <td class="text-center"><i class="fa fa-fw" aria-hidden="true"
                                                            style="color:#16aaff;cursor:pointer;"
                                                            title="Copy to use trash"></i></td>







                                                </tr>
                                                <td class="text-center">
                                                    <img src="/assets/images/avatars/user.png"
                                                        style="cursor:pointer;height:18px;width:16px"
                                                        onclick="showCreateForm()">
                                                </td>

                                                <td class="text-center"></td>
                                                <td class="text-center"></td>
                                                <td class="text-center"></td>
                                                <td class="text-center"></td>
                                                <td class="text-center"></td>
                                                <td class="text-center"></td>
                                                <td class="text-center"></td>
                                                <td class="text-center"></td>
                                                <td class="text-center"></td>
                                                <td class="text-center"></td>
                                                <td class="text-center"></td>
                                                <td class="text-center"></td>
                                                <td class="text-center"></td>
                                                <td class="text-center"></td>
                                                <td class="text-center"></td>





                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="pager ml-3">
                                        <nav class="" aria-label="Page navigation example">
                                            <ul class="pagination">
                                                <li class="page-item"><a href="javascript:void(0);" class="page-link"
                                                        aria-label="Previous"><span aria-hidden="true">«</span><span
                                                            class="sr-only">Previous</span></a></li>
                                                <li class="page-item"><a href="javascript:void(0);"
                                                        class="page-link">1</a></li>
                                                <li class="page-item active"><a href="javascript:void(0);"
                                                        class="page-link">2</a></li>
                                                <li class="page-item"><a href="javascript:void(0);"
                                                        class="page-link">3</a></li>
                                                <li class="page-item"><a href="javascript:void(0);"
                                                        class="page-link">4</a></li>
                                                <li class="page-item"><a href="javascript:void(0);"
                                                        class="page-link">5</a></li>
                                                <li class="page-item"><a href="javascript:void(0);" class="page-link"
                                                        aria-label="Next"><span aria-hidden="true">»</span><span
                                                            class="sr-only">Next</span></a></li>
                                            </ul>
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
                        <div class="row">

                            <div class="col-md-12">
                                <div class="main-card mb-3 card">
                                    <div class="card-header" style='height: 2.5rem;justify-content: space-between;'>


                                        <span>
                                            Deleted Alias Account

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
                <?php includeWithVariables('./../../includes/partials/_footer.php'); ?>
            </div>
        </div>
    </div>

    <?php include __DIR__ . '/../../includes/files/_scripts.php'; ?>

</body>

</html>
