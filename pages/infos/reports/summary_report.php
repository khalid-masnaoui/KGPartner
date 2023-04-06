<?php

require_once __DIR__ . "/../../../core/ini.php";
require_once __DIR__ . "/../../../core/inc_var.php";
include __DIR__ . '/../../../includes/partials/_authorization.php';

$db = DB::getInstance();


$partner = new user();
$partnerPtId = $partner->data()["pt_id"];

$sql = "SELECT id,username,prefix FROM clients WHERE pt_id = ?";
$clients = $db->query($sql, ["$partnerPtId"])->results();

$options = "";

foreach ($clients as $key => $value) {
    $options .= "<option value=" . $value['id'] . " data-prefix=" . $value['prefix'] . ">" . $value["username"] . "</option>";
}

// $clients = $db->get("id,username,prefix","clients",[])->results();
// $options = "";

// foreach ($clients as $key => $value) {
//     $options .=  "<option value=".$value['id']." data-prefix=".$value['prefix'].">".$value["username"]."</option>";
// }

//providers options
$activeProviders = config::get("display/activeProviders");
$casinoProviders = config::get("config/display/casinos");
$slotProviders = config::get("config/display/slots");

$activeCasinoProviders = [];
$activeSlotProviders = [];

foreach ($casinoProviders as $key => $value) {
    $value === "" ? array_push($activeCasinoProviders, $key) : '';
}
foreach ($slotProviders as $key => $value) {
    $value === "" ? array_push($activeSlotProviders, $key) : '';
}
$ProvidersNameMapping = config::get("providersNameMappings");

$providersCasinoOptions = "";
$providersSlotOptions = "";
$providersOptions = "<option value='all'>전체</option>";



foreach ($activeCasinoProviders as $key => $value) {
    if (!isset($ProvidersNameMapping[$value])) {
        continue;
    }
    $providersCasinoOptions .= "<option value=" . $value . ">" . $ProvidersNameMapping[$value] . "</option>";
}

foreach ($activeSlotProviders as $key => $value) {
    if (!isset($ProvidersNameMapping[$value])) {
        continue;
    }
    $providersSlotOptions .= "<option value=" . $value . ">" . $ProvidersNameMapping[$value] . "</option>";
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

    @media (max-width: 710px) {
        .card-header2 {
            flex-direction: column;
            height: 6.2rem !important;
        }

        .status_number_wrapper {
            margin-top: 10px;
        }
    }

    @media (max-width: 480px) {
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

    /* .total-row td {
        background-color: rgba(247, 176, 36, 0.6) !important;
    } */

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
                    <?php includeWithVariables('./../../../includes/partials/_innerheader.php', array('title' => '정산관리', 'title_text' => '요약보고서', 'icon' => "news-paper")); ?>

                    <!-- main content -->
                    <section class="main">
                        <div class="row">

                            <div class="col-md-12">

                                <div class="main-card mb-3 card">
                                    <div class="card-header" style='height: 2.5rem;'>필터

                                    </div>
                                    <div class="d-flex filter-wrapper">
                                        <div class="row mt-2 row_filter_wrapper mb-3">



                                            <div class="input-group  d-flex ml-1 pl-0 pr-0 col-md-5  col-10 col-lg-4 col-xl-3 mt-2  ml-1 pl-0 pr-0 ml-4"
                                                style='width:unset;'>
                                                <div class="input-group-prepend"><span
                                                        class="input-group-text">시작일</span></div>
                                                <input placeholder="" type="date"
                                                    class="form-control shadow-none startdate"
                                                    value=<?= date("Y-m-d") ?>>
                                            </div>


                                            <div class="input-group  d-flex ml-1 pl-0 pr-0 col-md-5 col-lg-4 col-xl-3 col-10  mt-2  ml-1 pl-0 pr-0 ml-4 ml-xl-1"
                                                style='width:unset;'>
                                                <div class="input-group-prepend"><span
                                                        class="input-group-text">종료일</span></div>
                                                <input placeholder="" type="date"
                                                    class="form-control shadow-none enddate" value=<?= date("Y-m-d") ?>>
                                            </div>

                                            <div
                                                class="select-status input-group  d-flex ml-1 pl-0 pr-0 col-md-5 col-lg-4 col-xl-3 col-10 mt-2 pl-0 pr-0 ml-4 ml-xl-1">
                                                <div class="input-group-prepend"><span
                                                        class="input-group-text">카테고리</span></div> <select type="select"
                                                    id="categorySelect" name="categorySelect" class="custom-select">
                                                    <option value='all'>전체</option>
                                                    <option value="casino">카지노</option>
                                                    <option value="slot">슬롯</option>
                                                </select>
                                            </div>

                                            <div
                                                class="select-provider input-group  d-flexml-1 pl-0 pr-0 col-md-5  col-10 col-lg-4 col-xl-3 mt-2  ml-1 pl-0 pr-0 ml-4">
                                                <div class="input-group-prepend"><span
                                                        class="input-group-text">게임사</span></div> <select type="select"
                                                    id="providerSelect" name="providerSelect" class="custom-select">
                                                    <?= $providersOptions . $providersCasinoOptions . $providersSlotOptions; ?>
                                                </select>
                                            </div>





                                            <button
                                                class="btn btn-primary filter_summary filter_btn mt-2 ml-4 mr-4">검색</button>

                                        </div>

                                        <div class="clslct">
                                            <select type="select" id="clientSelect" name="clientSelect"
                                                class="custom-select">
                                                <option value="all">전체</option>
                                                <?php
                                                echo $options;

                                                ?>

                                            </select>
                                        </div>
                                    </div>




                                </div>


                            </div>

                        </div>
                        <div class="row" style="margin-bottom: 70px;">

                            <div class="col-md-12">
                                <div class="main-card mb-3 card">
                                    <div class="card-header  card-header2"
                                        style='display: flex;height: 2.5rem;justify-content: space-between;'>


                                        <span>


                                            요약보고서

                                            <button class="mb-0 ml-2 btn btn-light ExcelBtn">엑셀 다운로드<span
                                                    class="badge badge-pill badge-light"><i
                                                        class="pe-7s-cloud-download"> </i></span></button>


                                        </span>
                                        <div class="d-flex status_number_wrapper">

                                            <div class="d-flex">
                                                <button id="status_win" class='status_ align-self-end'>
                                                    <div class="mb-2 mr-2 badge badge-pill badge-success">
                                                        승</div>
                                                </button>
                                                <button id="status_loss" class='status_ align-self-end'>
                                                    <div class="mb-2 mr-2 badge badge-pill badge-danger"
                                                        style="color:white !important;">패</div>
                                                </button>
                                                <button id="status_tie" class='status_ align-self-end'>
                                                    <div class="mb-2 mr-2 badge badge-pill badge-primary"
                                                        style="color:white !important;">타이</div>
                                                </button>
                                                <button id="status_all" class='status_ active align-self-end'>
                                                    <div class="mb-2 mr-2 badge badge-pill badge-focus">
                                                        전체</div>
                                                </button>




                                            </div>
                                            <div class="dropdown d-inline-block">
                                                <button type="button" aria-haspopup="true" aria-expanded="false"
                                                    data-toggle="dropdown"
                                                    class="mr-2 dropdown-toggle active-summary-number btn btn-outline-primary"><?= config::get("display/activeNumber"); ?>
                                                </button>
                                                <div tabindex="-1" role="menu" aria-hidden="true"
                                                    class="dropdown-menu summary_number_options"
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
                                        value="<?= token::generate("display_summary") ?>">

                                    <input type="hidden" name="token_download" id="token_download"
                                        value="<?= token::generate("download_summary") ?>">



                                    <div class="table-responsive mb-3 table-summary" style="max-width:''">
                                        <table
                                            class="align-middle mb-0 table table-bordered table-striped table-hover summary">
                                            <thead>
                                                <tr>
                                                    <th class="text-center" colspan="4"></th>
                                                    <?php
                                                    foreach ($activeProviders as $key => $value) {
                                                        if (!isset($ProvidersNameMapping[$value])) {
                                                            continue;
                                                        }
                                                        echo '<th class="text-center" colspan="3">' . $ProvidersNameMapping[$value] . '</th>';
                                                    }

                                                    ?>

                                                    <th class="text-center" colspan="3">총 합계</th>
                                                </tr>
                                                <tr>
                                                    <th class="text-center 3f-cells">UID</th>
                                                    <th class="text-center 3f-cells">Username</th>
                                                    <th class="text-center 3f-cells">Client</th>
                                                    <!-- <th class="text-center 3f-cells">Parent</th> -->
                                                    <?php
                                                    foreach ($activeProviders as $key => $value) {
                                                        if (!isset($ProvidersNameMapping[$value])) {
                                                            continue;
                                                        }
                                                        echo '<th class="text-center">Wager</th>' . PHP_EOL;
                                                        echo '<th class="text-center">Turnover</th>' . PHP_EOL;
                                                        echo '<th class="text-center">WinLoss</th>' . PHP_EOL;

                                                    }

                                                    ?>
                                                    <th class="text-center">Wager</th>
                                                    <th class="text-center">Turnover</th>
                                                    <th class="text-center">WinLoss</th>


                                                </tr>
                                            </thead>
                                            <tbody class='table-body-summary'>

                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="pager ml-3">
                                        <nav class="navigation_summary" aria-label="navigation_summary">
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

    // --- DISPLAY Summary Report ----
    function displaySummary(N = 1, status = 'all') {

        var activePage = $(".navigation_summary li.page-item.active a").text();
        var activeNumber = $(".active-summary-number").text();
        activeNumber = activeNumber.trim();

        // if (N == true) {
        //     N = activePage;
        // }
        var provider = $("#providerSelect").val();
        var category = $("#categorySelect").val();

        var startDate = $(".startdate").val();
        var endDate = $(".enddate").val();


        var client = $("#clientSelect").val()


        var token = $("#token_display").val();

        $.ajax({
            url: '/ajaxProcessus/infoReports/displaySummary.php',
            type: 'POST',
            data: {
                "display": true,
                "page": N,
                "number": activeNumber,
                provider,
                category,
                startDate,
                endDate,
                client,
                status,
                token
            },
            // contentType: false,
            // processData: false, 
            cache: false,
            timeout: 300000,
            // cache: false,
            // dataType: 'json', 

            success: function(data) {
                // var num = data.indexOf("<!DOCTYPE html>");
                // var rese = data.substr(0, num);
                // rese = rese.trim();
                // console.log(data);
                if (data == 'unauthorized' || data == '') {
                    window.location.href = '/pages/errors/403.php';
                    return;
                }
                rese = JSON.parse(data);

                // console.log(rese[0]);
                let length = rese.length;


                if (length == 4) { //success
                    $(".table-body-summary").html(rese[0]);
                    $(".navigation_summary").html(rese[1]);

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

    //Category change -> change providers lists
    $("#categorySelect").on("change", function(event) {
        let category = $(this).val();

        if (category == "casino") {
            $("#providerSelect").html("<?= $providersOptions . $providersCasinoOptions; ?>")
        }

        if (category == "slot") {
            $("#providerSelect").html("<?= $providersOptions . $providersSlotOptions; ?>")
        }

        if (category == "all") {
            $("#providerSelect").html(
                "<?= $providersOptions . $providersCasinoOptions . $providersSlotOptions; ?>")
            $('#providerSelect option[value="evo"]').prop('selected', true);

        }

    })


    //NUMBERS DISPLAYED PER PAGE
    $(".summary_number_options button").on("click", function(event) {

        var number = event.currentTarget.textContent;
        $(".active-summary-number").text(number);

        let id = $(".status_.active").attr("id");

        let status = 'all';

        if (id == "status_win") {
            status = "win";
        } else if (id == "status_loss") {
            status = "loss";
        } else if (id == "status_tie") {
            status = "tie";
        }

        displaySummary(1, status);
    })

    function resetStatusFilter() {
        $(".status_").css("opacity", 1);
        $(".status_").removeClass("active");
        $("#status_all").addClass("active");
    }

    //FILTERs
    $(".filter_summary").on("click", function(event) {
        resetStatusFilter();

        displaySummary();
    })

    //CLIENT SELECT
    $("#clientSelect").change(function(event) {
        displaySummary();
    })

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

            if (id == "status_win") {
                status = "win";
            } else if (id == "status_loss") {
                status = "loss";
            } else if (id == "status_tie") {
                status = "tie";
            }

            $(".status_").removeClass("active");
            $(this).addClass("active");
        }

        displaySummary(1, status);

    })


    //excel download
    $(".ExcelBtn").on("click", function(event) {

        $("table.summary th").css({
            "text-align": "center",
            "background-color": "black",
            "font-weight": "Bold",
            "color": "white",
            "height": "30px"
        });

        $("table.summary td").css({
            "text-align": "center",
            "height": "20px",
            "width": "80px"

        });
        $(".3f-cells").css({
            "width": "120px"

        });
        $(".total-row td").css({
            "background-color": "beige",
            "height": "25px"

        })
        // "background-color": "rgba(63, 106, 216, 0.9)",

        // return;
        var htmlTable = $(".table-summary").html();
        var token = $("#token_download").val();

        // console.log(token);
        $.ajax({
            url: '/ajaxProcessus/infoReports/excels/downloadSummary.php',
            type: 'POST',
            htmlTable,
            data: {
                htmlTable,
                token
            },

            cache: false,
            timeout: 10000,
            success: function(data) {

                if (data == 'unauthorized' || data == '') {
                    window.location.href = '/pages/errors/403.php';
                    return;
                }
                // window.open("/", "_blank");
                rese = JSON.parse(data);

                // console.log(rese);


                // console.log(rese[0]);
                let length = rese.length;


                if (length == 3) { //success
                    $("#token_download").val(rese[0]);
                    //download the file
                    var $a = $("<a>");
                    $a.attr("href", rese[1]);
                    $("body").append($a);
                    $a.attr("download", rese[2] + ".xls");
                    $a[0].click();
                    $a.remove();
                } else if (length == 1) {
                    $("#token_download").val(rese[0]);
                } else {
                    //refresh page;
                    location.reload();
                }



            }


        })

    })

    document.addEventListener('DOMContentLoaded', (event) => {

        displaySummary(0);
    });
    </script>
</body>

</html>
