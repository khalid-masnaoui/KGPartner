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


    @media (max-width: 576px) {
        .row_filter_wrapper>div {


            width: 88% !important;
            /* flex-direction:column; */
        }

    }

    @media (max-width: 540px) {
        .card-header2 {
            flex-direction: column;
            height: 5.4rem !important;
        }
    }

    @media (max-width: 440px) {
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
                    <?php includeWithVariables('./../../includes/partials/_innerheader.php', array('title' => ' API 정보', 'title_text' => '게임사 리스트.', 'icon' => "photo-gallery")); ?>

                    <!-- main content -->
                    <section class="main">
                        <div class="row">

                            <div class="col-md-12">

                                <div class="main-card mb-3 card">
                                    <div class="card-header" style='height: 2.5rem;'>필터

                                    </div>
                                    <div class="d-flex filter-wrapper">
                                        <div class="row mt-2 row_filter_wrapper mb-3">

                                            <div
                                                class="select-status input-group  d-flex ml-1 pl-0 pr-0 col-md-5 col-lg-4 col-xl-3 col-10 mt-2 pl-0 pr-0 ml-4">
                                                <div class="input-group-prepend"><span
                                                        class="input-group-text">카테고리</span>
                                                </div>
                                                <select type="select" id="categorySelect" name="categorySelect"
                                                    class="custom-select">
                                                    <option value="all">전체</option>
                                                    <option value="casino">카지노</option>
                                                    <option value="slot">슬롯</option>
                                                </select>
                                            </div>
                                            <button
                                                class="btn btn-primary filter_productslist filter_btn mt-2 ml-4 mr-4">검색</button>

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


                                            게임사 리스트


                                        </span>
                                        <div class="d-flex status_number_wrapper">

                                            <div class="d-flex">
                                                <button id="status_active" class='status_ align-self-end'>
                                                    <div class="mb-2 mr-2 badge badge-pill badge-success">
                                                        정상</div>
                                                </button>
                                                <button id="status_inactive" class='status_ align-self-end'>
                                                    <div class="mb-2 mr-2 badge badge-pill badge-danger"
                                                        style="color:white !important;">비활성</div>
                                                </button>
                                                <button id="status_all" class='status_ active align-self-end'>
                                                    <div class="mb-2 mr-2 badge badge-pill badge-focus">
                                                        전체</div>
                                                </button>




                                            </div>
                                            <div class="dropdown d-inline-block">
                                                <button type="button" aria-haspopup="true" aria-expanded="false"
                                                    data-toggle="dropdown"
                                                    class="mr-2 dropdown-toggle active-productslist-number btn btn-outline-primary"><?= config::get("display/activeNumber"); ?>
                                                </button>
                                                <div tabindex="-1" role="menu" aria-hidden="true"
                                                    class="dropdown-menu productslist_number_options"
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
                                            value="<?= token::generate("display_productslist") ?>">

                                        <table
                                            class="align-middle mb-0 table table-borderless table-striped table-hover">
                                            <thead>
                                                <tr>
                                                    <th class="text-center">#</th>
                                                    <th class="text-center"> 게임사 ID</th>
                                                    <th class="text-center">게임사(영문)</th>
                                                    <th class="text-center">게임사(한글)</th>
                                                    <th class="text-center">Type</th>
                                                    <th class="text-center">상태</th>
                                                </tr>
                                            </thead>
                                            <tbody class='table-body-productslist'>

                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="pager ml-3">
                                        <nav class="navigation_productslist" aria-label="navigation_productslist">
                                        </nav>
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


    <!-- delete modal  -->
    <?php includeWithVariables('./../../includes/modals/_deleteModal.php'); ?>

    <script>
    // --- DISPLAY WIN/LOSS TRANSACTIONS ----
    function displayProductsList(N = 1, status = 'all') {

        var activePage = $(".navigation_productslist li.page-item.active a").text();
        var activeNumber = $(".active-productslist-number").text();
        activeNumber = activeNumber.trim();

        var category = $("#categorySelect").val();
        var token = $("#token_display").val();

        $.ajax({
            url: '/ajaxProcessus/settings/displayProductsList.php',
            type: 'POST',
            data: {
                "display": true,
                "page": N,
                "number": activeNumber,
                category,
                status,
                token
            },

            cache: false,
            timeout: 10000,

            success: function(data) {

                if (data == 'unauthorized' || data == '') {
                    window.location.href = '/pages/errors/403.php';
                    return;
                }
                rese = JSON.parse(data);

                // console.log(rese[0]);
                let length = rese.length;


                if (length == 4) { //success
                    $(".table-body-productslist").html(rese[0]);
                    $(".navigation_productslist").html(rese[1]);

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

    //category FILTER
    $(".filter_productslist").on("click", function(event) {
        resetStatusFilter();

        displayProductsList();
    })


    //NUMBERS DISPLAYED PER PAGE
    $(".productslist_number_options button").on("click", function(event) {

        console.log("G");

        var number = event.currentTarget.textContent;
        $(".active-productslist-number").text(number);

        let id = $(".status_.active").attr("id");

        let status = 'all';

        if (id == "status_active") {
            status = "active";
        } else if (id == "status_inactive") {
            status = "inactive";
        }

        displayProductsList(1, status);
    })

    function resetStatusFilter() {
        $(".status_").css("opacity", 1);
        $(".status_").removeClass("active");
        $("#status_all").addClass("active");
    }


    //status filter
    $(".status_").click(function(event) {
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

            if (id == "status_active") {
                status = "active";
            } else if (id == "status_inactive") {
                status = "inactive";
            }

            $(".status_").removeClass("active");
            $(this).addClass("active");
        }

        displayProductsList(1, status);

    })




    document.addEventListener('DOMContentLoaded', (event) => {

        displayProductsList(0);
        // clearModalInvalidFeedbacks();



    });
    </script>
</body>

</html>
