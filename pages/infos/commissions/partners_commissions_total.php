<?php

require_once __DIR__ . "/../../../core/ini.php";
require_once __DIR__ . "/../../../core/inc_var.php";
include __DIR__ . '/../../../includes/partials/_authorization.php';

$db = DB::getInstance();
$partner = new user();
$partnerPtId = $partner->data()["pt_id"];

// $sql = "SELECT id,pt_id,username FROM partner_users WHERE pt_id = ? OR pt_id like ?";
// $partners = $db->query($sql, ["$partnerPtId", "$partnerPtId/%"])->results();

$sql = "SELECT id,pt_id,username FROM partner_users WHERE pt_id = ? OR pt_id REGEXP ?";
$partners = $db->query($sql, ["$partnerPtId", "^$partnerPtId/[0-9]*$"])->results();

$options = "";

foreach ($partners as $key => $value) {
    $options .= "<option value=" . $value['id'] . " data-pt_id=" . $value['pt_id'] . ">" . $value["username"] . "</option>";
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

        .buttons-wrapper {
            display: flex;
            align-items: center;
            justify-content: center;
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

        @media (max-width: 767px) {
            .card-header2 {
                flex-direction: column;
                height: 5.4rem !important;
            }

            .buttons-wrapper {
                width: 100%;
            }
        }

        @media (max-width: 576px) {
            .row_filter_wrapper>div {


                width: 88% !important;
                /* flex-direction:column; */
            }

        }


        @media (max-width: 410px) {
            .card-header2 {
                height: 7.8rem !important;
            }

            .wrapper {
                flex-direction: column !important;

            }

            .deposits_number_wrapper {
                margin-top: 10px;
                margin-bottom: 10px;
            }

        }

        @media (max-width: 338px) {
            .card-header2 {
                flex-direction: column;
                height: 9rem !important;
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
                    <?php includeWithVariables('./../../../includes/partials/_innerheader.php', array('title' => 'Commissions', 'title_text' => 'Partners Commissions.', 'icon' => "cash")); ?>

                    <!-- main content -->
                    <section class="main">

                        <div class="row">

                            <div class="col-md-12">
                                <div class="main-card mb-3 card">
                                    <div class="card-header card-header2"
                                        style='height: 2.5rem;justify-content: space-between;'>


                                        <span>

                                            Partners Total Commissions

                                        </span>
                                        <div class="wrapper"
                                            style="display: flex;justify-content: center;align-items: center;">
                                            <div class="clslct mr-4">
                                                <select type=" select" id="partnerSelect" name="partnerSelect"
                                                    class="custom-select">
                                                    <option value="all">All</option>
                                                    <?php
                                                    echo $options;

                                                    ?>

                                                </select>
                                            </div>
                                            <div class="dropdown d-inline-block deposits_number_wrapper">
                                                <button type="button" aria-haspopup="true" aria-expanded="false"
                                                    data-toggle="dropdown"
                                                    class="mr-2 dropdown-toggle active-commissions-number btn btn-outline-primary"><?= config::get("display/activeNumber"); ?></button>
                                                <div tabindex="-1" role="menu" aria-hidden="true"
                                                    class="dropdown-menu commissions_number_options"
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



                                    <input type="hidden" name="token_display" id="token_display"
                                        value="<?= token::generate("display_commissions_total") ?>">

                                    <div class="table-responsive mb-3">
                                        <table
                                            class="align-middle mb-0 table table-borderless table-striped table-hover">
                                            <thead>
                                                <tr>
                                                    <th class="text-center">#</th>
                                                    <th class="text-center">Partner</th>
                                                    <th class="text-center">Total Amount</th>

                                                </tr>
                                            </thead>
                                            <tbody class='table-body-commissions'>

                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="pager ml-3">
                                        <nav class="navigation_commissions" aria-label="navigation_commissions">
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

    <script>
        // document.querySelector(".startdate").value="2022-03-05";
        // document.querySelector(".enddate").value="2022-03-05";


        // --- DISPLAY DEPOSITS ----
        function displayCommissions(N = 1) {

            var activePage = $(".navigation_commissions li.page-item.active a").text();
            var activeNumber = $(".active-commissions-number").text();
            activeNumber = activeNumber.trim();

            var partner = $("#partnerSelect").val();

            var token = $("#token_display").val();



            $.ajax({
                url: '/ajaxProcessus/commissions/displayPartnersTotalCommissions.php',
                type: 'POST',
                data: {
                    "display": true,
                    "page": N,
                    "number": activeNumber,
                    partner,
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
                        $(".table-body-commissions").html(rese[0]);
                        $(".navigation_commissions").html(rese[1]);

                        $("#token_display").val(rese[3]);
                    } else if (length == 1) { //csrf error
                        $("#token_display").val(rese[0]);
                    } else {
                        location.reload(); //refresh page;
                    }



                }


            })
        }

        //NUMBERS DISPLAYED PER PAGE
        $(".commissions_number_options button").on("click", function (event) {

            var number = event.currentTarget.textContent;
            $(".active-commissions-number").text(number);

            displayCommissions();
        })




        //PARTNER SELECT
        $("#partnerSelect").change(function (event) {
            displayCommissions();
        })



        document.addEventListener('DOMContentLoaded', (event) => {

            displayCommissions(0);
            // clearModalInvalidFeedbacks();


        });
    </script>
</body>

</html>
