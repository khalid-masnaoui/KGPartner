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
    <!-- Include Quill stylesheet -->
    <link href="https://cdn.quilljs.com/1.0.0/quill.snow.css" rel="stylesheet" />
    <style>
        .filter-wrapper {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .btn_wrapper {
            margin-left: 20px;
        }

        .row_filter_wrapper {
            flex: 2;
            width: 100%;

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

        .btn-holder {
            margin-top: 100px !important;
        }

        @media (max-width: 740px) {
            .filter-wrapper {
                flex-direction: column;
                align-items: flex-start;
            }

            .clslct {
                margin-bottom: 10px;
            }


        }

        @media (max-width: 576px) {
            .row_filter_wrapper>div {
                width: 88% !important;
                /* flex-direction:column; */
            }

            .btn_wrapper {
                margin-top: 10px;
                margin-bottom: 10px;
                margin-left: 25px;
            }

        }

        @media (max-width: 575px) {
            #invalidType {
                margin-left: 20px !important;
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

            .wrapper {
                flex-direction: column;

            }
        }

        @media (max-width: 352px) {
            .btn-holder {
                margin-top: 150px !important;
            }
        }

        @media (max-width: 265px) {
            .btn-holder {
                margin-top: 200px !important;
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

<?php
//modal for displaying announcements info
$modal_title = 'Announcement Info';
$modal_body = '
<div class="card-body" style="padding-bottom: 0;">

    <div style="margin-left:20px">
        <h5 class="card-title" id="cardTitle"></h5>
        <div id="cardMessage"></div>
    </div>

    <p class="card-subtitle"> Date : <small id="date" style="color: black;font-weight: bold"></small>

</div>
';
$modal_footer = '<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>';

$modal_size = 'xl';
includeWithVariables('./../../../includes/modals/_modal.php', array('class' => 'show_announcement', 'modal_size' => $modal_size, 'modal_title' => $modal_title, 'modal_body' => $modal_body, 'modal_footer' => $modal_footer));

?>


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
                    <?php includeWithVariables('./../../../includes/partials/_innerheader.php', array('title' => 'Account', 'title_text' => 'Announcements.', 'icon' => "folder")); ?>

                    <!-- main content -->
                    <section class="main">

                        <div class="row">

                            <div class="col-md-12">
                                <div class="main-card mb-3 card">
                                    <div class="card-header"
                                        style='display: flex;height: 2.5rem;justify-content: space-between;'>


                                        <span>
                                            Announcements

                                        </span>
                                        <div class="dropdown d-inline-block clients_number_wrapper">

                                            <button type="button" aria-haspopup="true" aria-expanded="false"
                                                data-toggle="dropdown"
                                                class="mr-2 dropdown-toggle active-announcements-number btn btn-outline-primary"><?= config::get("display/activeNumber"); ?></button>
                                            <div tabindex="-1" role="menu" aria-hidden="true"
                                                class="dropdown-menu announcements_number_options"
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
                                        value="<?= token::generate("display_announcements") ?>">

                                    <div class="table-responsive mb-3">
                                        <table
                                            class="align-middle mb-0 table table-borderless table-striped table-hover">
                                            <thead>
                                                <tr>
                                                    <th class="text-center">#</th>
                                                    <th class="text-center">Title </th>
                                                    <th class="text-center">Created At</th>


                                                </tr>
                                            </thead>
                                            <tbody class='table-body-announcements'>

                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="pager ml-3">
                                        <nav class="navigation_announcements" aria-label="navigation_announcements">
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
        // --- DISPLAY ANNOUNCEMENTS HISTORY ----
        function displayAnnouncements(N = 1) {

            var activePage = $(".navigation_announcements li.page-item.active a").text();
            var activeNumber = $(".active-announcements-number").text();
            activeNumber = activeNumber.trim();

            var token = $("#token_display").val();

            $.ajax({
                url: '/ajaxProcessus/memberAccounts/displayAnnouncements.php',
                type: 'POST',
                data: {
                    "display": true,
                    "page": N,
                    "number": activeNumber,
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
                        $(".table-body-announcements").html(rese[0]);
                        $(".navigation_announcements").html(rese[1]);

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
        $(".announcements_number_options button").on("click", function (event) {

            var number = event.currentTarget.textContent;
            $(".active-announcements-number").text(number);

            displayAnnouncements(1);
        })



        //show announcement modal info
        function showAnnouncementDataInfo(event) {

            //reset fields:
            $("#date").text("");

            $("#cardTitle").text("");
            $("#cardMessage").html("");

            let data = event.currentTarget.getAttribute("data-values");

            //populating modal with data

            data = data.replace(new RegExp("&&xx&&", "g"), " ");
            data = JSON.parse(data);

            let name = data["name"];
            if (name == '' || name == null) {
                name = 'All';
            }

            $("#date").text(data["created_at"]);

            $("#cardTitle").text(data["title"]);
            $("#cardMessage").html(data["message"]);
        }



        document.addEventListener('DOMContentLoaded', (event) => {

            displayAnnouncements(0);

        });
    </script>
</body>

</html>
