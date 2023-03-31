<?php

require_once __DIR__ . "/../../../core/ini.php";
require_once __DIR__ . "/../../../core/inc_var.php";
include __DIR__ . '/../../../includes/partials/_authorization.php';

$db = DB::getInstance();

$notificationBuilder = new Notifications();

$markNotificationsAsSeen = $notificationBuilder->markNotificationsAsSeen();

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
    <!-- Include Quill stylesheet -->
    <link href="https://cdn.quilljs.com/1.0.0/quill.snow.css" rel="stylesheet" />
    <style>
        .filter-wrapper {
            margin-top: 20px;
            margin-bottom: 20px;
            margin-left: 20px;
            width: 20%;
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

        .status_ {
            border: none;
            background: transparent;
            margin: 0;
            padding: 0;
            cursor: pointer;
        }



        @media (max-width: 1050px) {
            fieldset {
                margin-top: 100px !important;
            }
        }

        @media (max-width: 575px) {
            #invalidType {
                margin-left: 20px !important;
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

            .wrapper {
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

        td ol li {
            text-align: left;
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
                    <?php includeWithVariables('./../../../includes/partials/_innerheader.php', array('title' => 'Account', 'title_text' => 'Notifications.', 'icon' => "folder")); ?>

                    <!-- main content -->
                    <section class="main">

                        <div class="row">

                            <div class="col-md-12">
                                <div class="main-card mb-3 card">
                                    <div class="card-header card-header2"
                                        style='height: 2.5rem;justify-content: space-between;'>


                                        <span>
                                            Notifications

                                        </span>
                                        <div class="wrapper"
                                            style="display: flex;justify-content: center;align-items: center;">

                                            <div class="d-flex">
                                                <button id="status_notification" class='status_ align-self-end'>
                                                    <div class="mb-1 mr-2 badge badge-pill badge-success">
                                                        Notification</div>
                                                </button>
                                                <button id="status_suspend" class='status_ align-self-end'>
                                                    <div class="mb-1 mr-2 badge badge-pill badge-danger"
                                                        style="color:white !important;">Suspend</div>
                                                </button>
                                                <button id="status_all" class='status_ active align-self-end'>
                                                    <div class="mb-1 mr-2 badge badge-pill badge-focus">
                                                        All</div>
                                                </button>




                                            </div>

                                            <div class="dropdown d-inline-block clients_number_wrapper">

                                                <button type="button" aria-haspopup="true" aria-expanded="false"
                                                    data-toggle="dropdown"
                                                    class="mr-2 dropdown-toggle active-notifications-number btn btn-outline-primary"><?= config::get("display/activeNumber"); ?></button>
                                                <div tabindex="-1" role="menu" aria-hidden="true"
                                                    class="dropdown-menu notifications_number_options"
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

                                    </div>

                                    <input type="hidden" name="token_display" id="token_display"
                                        value="<?= token::generate("display_notifications") ?>">


                                    <div class="table-responsive mb-3">
                                        <table
                                            class="align-middle mb-0 table table-borderless table-striped table-hover">
                                            <thead>
                                                <tr>
                                                    <th class="text-center">#</th>
                                                    <th class="text-center">Name</th>
                                                    <th class="text-center">Notification </th>
                                                    <th class="text-center">Type</th>
                                                    <th class="text-center">Created At</th>


                                                </tr>
                                            </thead>
                                            <tbody class='table-body-notifications'>

                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="pager ml-3">
                                        <nav class="navigation_notifications" aria-label="navigation_notifications">
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
    <!-- Include the Quill library -->
    <script src="https://cdn.quilljs.com/1.0.0/quill.js"></script>

    <!-- delete modal  -->
    <?php includeWithVariables('./../../../includes/modals/_deleteModal.php'); ?>

    <script>
        // document.querySelector(".startdate").value="2022-03-05";
        // document.querySelector(".enddate").value="2022-03-05";

        // --- DISPLAY NOTIFICATIONS HISTORY ----
        function displayNotifications(N = 1, status = 'all') {

            var activePage = $(".navigation_notifications li.page-item.active a").text();
            var activeNumber = $(".active-notifications-number").text();
            activeNumber = activeNumber.trim();

            var token = $("#token_display").val();

            $.ajax({
                url: '/ajaxProcessus/memberAccounts/displayNotifications.php',
                type: 'POST',
                data: {
                    "display": true,
                    "page": N,
                    "number": activeNumber,
                    status,
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

                    let length = rese.length;


                    if (length == 4) { //success
                        $(".table-body-notifications").html(rese[0]);
                        $(".navigation_notifications").html(rese[1]);

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
        $(".notifications_number_options button").on("click", function (event) {

            var number = event.currentTarget.textContent;
            $(".active-notifications-number").text(number);

            let id = $(".status_.active").attr("id");

            let status = 'all';

            if (id == "status_notification") {
                status = "notification";
            } else if (id == "status_suspend") {
                status = "suspend";
            }

            displayNotifications(1, status);
        })

        function resetStatusFilter() {
            $(".status_").css("opacity", 1);
            $(".status_").removeClass("active");
            $("#status_all").addClass("active");
        }

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

                if (id == "status_notification") {
                    status = "notification";
                } else if (id == "status_suspend") {
                    status = "suspend";
                }

                $(".status_").removeClass("active");
                $(this).addClass("active");
            }

            displayNotifications(1, status);

        })


        document.addEventListener('DOMContentLoaded', (event) => {

            displayNotifications(0);

            //hide new badge
            $(".new-notifs").css("display", "none");

        });
    </script>
</body>

</html>
