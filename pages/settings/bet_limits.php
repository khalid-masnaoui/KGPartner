<?php

require_once __DIR__ . "/../../core/ini.php";
require_once __DIR__ . "/../../core/inc_var.php";
include __DIR__ . '/../../includes/partials/_authorization.php';

$db = DB::getInstance();

$partner = new user();
$partnerPtId = $partner->data()["pt_id"];

$sql = "SELECT id,username,prefix FROM clients WHERE pt_id = ? OR pt_id REGEXP ? ";
$clients = $db->query($sql, ["$partnerPtId", "^$partnerPtId/[0-9/]*$"])->results();
$options = "";

foreach ($clients as $key => $value) {
    $options .= "<option value=" . $value['id'] . " data-prefix=" . $value['prefix'] . ">" . $value["username"] . "</option>";
}

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
$providersProductIdMappings = config::get("providersProductIdMappings");

$providersCasinoOptions = "";
$providersSlotOptions = "";

$providersOptions = "<option value='all'>All</option>";


foreach ($activeCasinoProviders as $key => $value) {
    if (!isset($ProvidersNameMapping[$value])) {
        continue;
    }
    if ($value == "evo") {
        $providersCasinoOptions .= "<option value='" . $providersProductIdMappings[$value] . "' selected>" . $ProvidersNameMapping[$value] . "</option>";
    } else {
        $providersCasinoOptions .= "<option value='" . $providersProductIdMappings[$value] . "'>" . $ProvidersNameMapping[$value] . "</option>";
    }
}

foreach ($activeSlotProviders as $key => $value) {
    if (!isset($ProvidersNameMapping[$value])) {
        continue;
    }
    if ($value == "cq9") {
        $providersSlotOptions .= "<option value='" . $providersProductIdMappings[$value] . "' selected>" . $ProvidersNameMapping[$value] . "</option>";
    } else {
        $providersSlotOptions .= "<option value='" . $providersProductIdMappings[$value] . "'>" . $ProvidersNameMapping[$value] . "</option>";
    }
}

$games = $db->get("game_code, game_name_en", "games_list", [["product_id", "=", 1]])->results();
$gamesOptions = "";

foreach ($games as $key => $value) {
    $gamesOptions .= "<option value=" . $value['game_code'] . ">" . $value["game_name_en"] . "(" . $value["game_code"] . ")" . "</option>";
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
        WA Solution
    </title>
    <meta name="viewport"
        content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, shrink-to-fit=no" />
    <meta name="description" content="This is an example dashboard created using build-in elements and components." />
    <meta name="msapplication-tap-highlight" content="no" />


    <?php include __DIR__ . '/../../includes/files/_stylesheets.php'; ?>
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

    @media (max-width: 1000px) {
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

    /* .notice {
        width: 45%;
    } */

    @media (max-width: 480px) {
        /* .card-header2 {
            height: 7.8rem !important;
        } */

        /* .notice {
            width: 45%;
        } */

        .status_number_wrapper {
            flex-direction: column;

        }
    }

    @media (max-width: 390px) {
        /* .card-header2 {
            height: 9.2rem !important;
        } */
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

<?php include __DIR__ . '/../../includes/partials/_flash_msg.php'; ?>
<?php
$modal_title = 'Add Bet Limit';
$modal_body = '<form class="">


<div class="position-relative row form-group clientModalSelect"><label for="clientModalSelect" class="col-sm-2 col-form-label">Client</label>
    <div class="col-sm-10">
        <select type="select" id="clientModalSelect" name="clientModalSelect"
            class="custom-select">
            <option value="all">Pick A Client</option>
            ' . $options . '
        </select>
        <div class="invalid-feedback"></div>
    </div>
</div>

<div class="position-relative row form-group providerModalSelect"><label style="font-weight:bold" for="providerModalSelect" class="col-sm-2 col-form-label">Provider</label>
    <div class="col-sm-10">
        <select
        type="select" id="providerModalSelect" name="providerModalSelect"
        class="custom-select">
        ' . $providersOptions . $providersCasinoOptions . $providersSlotOptions . '
        </select>
        <div class="invalid-feedback"></div>
    </div>
</div>

<div class="position-relative row form-group gameModalSelect"><label style="font-weight:bold" for="gameModalSelect" class="col-sm-2 col-form-label">Table (gameId)</label>
    <div class="col-sm-10">
        <select type="select" id="gameModalSelect"
            name="gameModalSelect" class="custom-select">
            <option value="all">All</option>
            ' . $gamesOptions . '
        </select>
        <div class="invalid-feedback"></div>
    </div>
</div>

<div class="position-relative row form-group"><label for="limitAmount" id="depositLabel" class="col-sm-2 col-form-label">Max Bet Amount (원)</label>
    <div class="col-sm-10"><input name="limitAmount" id="limitAmount" placeholder="Max Bet Amount..." type="text" class="form-control shadow-none">
                        <div class="invalid-feedback"></div>
    </div>
</div>

<input type="hidden" name="limitId" id="limit_id">

<div class="position-relative row form-check">
    <div class="col-sm-10 text-center text-sm-left">
        <button class="btn btn-secondary" id="add_limit" onclick="addBetLimit(event)">Add Limit</button>
    </div>
    <div class="col-sm-10 text-center text-sm-left">
        <button class="btn btn-secondary" id="edit_limit" onclick="editBetLimit(event)">Edit Limit</button>
    </div>
</div>



</form> ';
$modal_footer = '<button type="button" class="btn btn-secondary d-none" data-dismiss="modal">Close</button>
<button type="button" class="btn btn-primary d-none">Save</button> ';

$modal_size = 'xl';
includeWithVariables(
    './../../includes/modals/_modal.php',
    array(
        'class' => 'add_limit',
        'modal_size' => $modal_size,
        'modal_title' => $modal_title,
        'modal_body' => $modal_body,
        'modal_footer' => $modal_footer
    )
);

?>

<body>
    <div class="app-container app-theme-white body-tabs-shadow fixed-sidebar fixed-header">
        <!-- header section  -->
        <?php includeWithVariables('./../../includes/partials/_header.php', array('title' => 'WA Solution'));
        ; ?>



        <div class="app-main" style="display:block;">
            <!-- sidebar section  -->
            <?php includeWithVariables('./../../includes/partials/_sidebar.php');
            ; ?>

            <div class="app-main__outer">
                <div class="app-main__inner">

                    <!-- inner header section  -->
                    <?php includeWithVariables('./../../includes/partials/_innerheader.php', array('title' => 'API 정보', 'title_text' => 'Bet Limits.', 'icon' => "photo-gallery")); ?>

                    <!-- main content -->
                    <section class="main">
                        <div class="row">

                            <div class="col-md-12">

                                <div class="main-card mb-3 card">
                                    <div class="card-header" style='height: 2.5rem;'>FILTER

                                    </div>
                                    <div class="d-flex filter-wrapper">
                                        <div class="row mt-2 row_filter_wrapper mb-3">

                                            <div
                                                class="select-status input-group  d-flex pl-0 pr-0 col-md-5 col-lg-4 col-xl-3 col-10 mt-2 pl-0 pr-0 ml-4">
                                                <div class="input-group-prepend"><span
                                                        class="input-group-text">Category</span></div> <select
                                                    type="select" id="categorySelect" name="categorySelect"
                                                    class="custom-select">
                                                    <option value='all'>All</option>
                                                    <option value="casino">Casinos</option>
                                                    <option value="slot">Slots</option>
                                                </select>
                                            </div>

                                            <div
                                                class="select-provider input-group  d-flexml-1 pl-0 pr-0 col-md-5  col-10 col-lg-4 col-xl-3 mt-2  ml-1 pl-0 pr-0 ml-4">
                                                <div class="input-group-prepend"><span
                                                        class="input-group-text">Provider</span></div> <select
                                                    type="select" id="providerSelect" name="providerSelect"
                                                    class="custom-select">
                                                    <?= $providersOptions . $providersCasinoOptions . $providersSlotOptions; ?>
                                                </select>
                                            </div>

                                            <div
                                                class="select-status input-group  d-flex pl-0 pr-0 col-md-5 col-lg-4 col-xl-3 col-10 mt-2 pl-0 pr-0 ml-4">
                                                <div class="input-group-prepend"><span class="input-group-text">Table
                                                        (gameId)</span></div> <select type="select" id="gameSelect"
                                                    name="gameSelect" class="custom-select">
                                                    <option value='all'>All</option>
                                                    <?php
                                                    echo $gamesOptions;

                                                    ?>
                                                </select>
                                            </div>

                                            <button
                                                class="btn btn-primary filter_limits filter_btn mt-2 ml-4 mr-4">Submit</button>

                                        </div>

                                        <div class="clslct">
                                            <select type="select" id="clientSelect" name="clientSelect"
                                                class="custom-select">
                                                <option value="all">All</option>
                                                <?php
                                                echo $options;

                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="notice mt-3 ml-3">
                                        <small>
                                            * Game-Specific settings take precedence over global settings ("All-Tables"
                                            settings).
                                            If the global setting is 500,000 won and the game setting is 300,000 won,
                                            Game-specific settings take precedence, and the 300,000 won limit for that
                                            game
                                            setting is applied.
                                            </br>
                                            * The application of the bet limit in koreaGaming Api after setting the
                                            maximum bet
                                            limit is immediate!.
                                        </small>
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
                                            Bet Limits
                                            <button class="mb-0 ml-2 btn btn-success betLimitBtn" data-toggle="modal"
                                                data-target=".add_limit" onclick=hideEditeableInputsAndShow()>Set A
                                                Limit<span class="badge badge-pill badge-light"> <img
                                                        src="/assets/images/settings.png"
                                                        style="width: 12px;height: 12px;"> </span></button>

                                        </span>
                                        <div class="d-flex status_number_wrapper">
                                            <div class="dropdown d-inline-block">
                                                <button type="button" aria-haspopup="true" aria-expanded="false"
                                                    data-toggle="dropdown"
                                                    class="mr-2 dropdown-toggle active-limits-number btn btn-outline-primary"><?= config::get("display/activeNumber"); ?>
                                                </button>
                                                <div tabindex="-1" role="menu" aria-hidden="true"
                                                    class="dropdown-menu limits_number_options"
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
                                        value="<?= token::generate("display_limits") ?>">

                                    <input type="hidden" name="token_add" id="token_add"
                                        value="<?= token::generate("add_limits") ?>">

                                    <input type="hidden" name="token_edit" id="token_edit"
                                        value="<?= token::generate("edit_limits") ?>">

                                    <input type="hidden" name="token_delete" id="token_delete"
                                        value="<?= token::generate("delete_limits") ?>">

                                    <input type="hidden" name="token_get_games" id="token_get_games"
                                        value="<?= token::generate("get_games") ?>">



                                    <div class="table-responsive mb-3 table-limits" style="max-width:''">
                                        <table
                                            class="align-middle mb-0 table table-borderless table-striped table-hover">
                                            <thead>
                                                <tr>
                                                    <th class="text-center">#</th>
                                                    <th class="text-center">ClientID</th>
                                                    <th class="text-center">UserName</th>
                                                    <th class="text-center">Product</th>
                                                    <th class="text-center">Game Name (gameId)</th>
                                                    <th class="text-center">Bet Limit</th>
                                                    <th class="text-center">Operator</th>
                                                    <th class="text-center">Date</th>
                                                    <th class="text-center">Edit/Delete</th>

                                                </tr>
                                            </thead>
                                            <tbody class='table-body-limits'>

                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="pager ml-3">
                                        <nav class="navigation_limits" aria-label="navigation_limits">
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
    function clearModalInvalidFeedbacks() {
        var array = ["clientModalSelect", "providerModalSelect", "gameModalSelect", "limitAmount"];

        array.forEach(element => {
            let target = $(`#${element}`);

            target.next(".invalid-feedback").text("");
            target.next(".invalid-feedback").css("display", "none");
            target.removeClass("invalid");
        });
    }

    function clearModalInvalidFeedbacksOnKeyUp() {

        $("#clientModalSelect").change(function(e) {

            $(this).next(".invalid-feedback").text("");
            $(this).next(".invalid-feedback").css("display", "none");
            $(this).removeClass("invalid");
        })

        $("#providerModalSelect").change(function(e) {

            $(this).next(".invalid-feedback").text("");
            $(this).next(".invalid-feedback").css("display", "none");
            $(this).removeClass("invalid");
        })

        $("#gameModalSelect").change(function(e) {

            $(this).next(".invalid-feedback").text("");
            $(this).next(".invalid-feedback").css("display", "none");
            $(this).removeClass("invalid");
        })

        $("#limitAmount").keyup(function(e) {

            $(this).next(".invalid-feedback").text("");
            $(this).next(".invalid-feedback").css("display", "none");
            $(this).removeClass("invalid");
        })

    }

    clearModalInvalidFeedbacksOnKeyUp();


    function hideEditeableInputsAndShow() {

        clearModalInvalidFeedbacks();

        $("#clientModalSelect").prop('disabled', false);
        $("#providerModalSelect").prop('disabled', false);
        $("#gameModalSelect").prop('disabled', false);

        //reset
        $('#clientModalSelect option[value="all"]').prop('selected', true);
        $('#providerModalSelect option[value="1"]').prop('selected', true);
        $("#gameModalSelect").html("<?= $providersOptions . $gamesOptions; ?>");
        $('#gameModalSelect option[value="all"]').prop('selected', true);
        $("#limitAmount").val('0.00');

        $("button#add_limit").show();
        $("button#edit_limit").hide();

        $("#exampleModalLongTitle").text("Add Bet Limit");
    }

    function showEditeableInputsAndHide() {

        clearModalInvalidFeedbacks();

        $("#clientModalSelect").prop('disabled', true);
        $("#providerModalSelect").prop('disabled', true);
        $("#gameModalSelect").prop('disabled', true);

        $("button#add_limit").hide();
        $("button#edit_limit").show();

        $("#exampleModalLongTitle").text("Edit Bet Limit");

    }

    function number_format(number, decimals, dec_point, thousands_sep) {
        // Strip all characters but numerical ones.
        number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
        var n = !isFinite(+number) ? 0 : +number,
            prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
            sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
            dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
            s = '',
            toFixedFix = function(n, prec) {
                var k = Math.pow(10, prec);
                return '' + Math.round(n * k) / k;
            };
        // Fix for IE parseFloat(0.55).toFixed(0) = 0;
        s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
        if (s[0].length > 3) {
            s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
        }
        if ((s[1] || '').length < prec) {
            s[1] = s[1] || '';
            s[1] += new Array(prec - s[1].length + 1).join('0');
        }
        return s.join(dec);
    }

    $("#limitAmount").focusout(function(event) {
        var number = $(this).val();

        if (number == '') {
            $(this).val("0.00");

        } else {
            var d = number_format(number, 2, ".", ",");

            $(this).val(d);
        }
    })

    // --- DISPLAY Bets Limits ----
    function displayLimits(N = 1) {

        var activePage = $(".navigation_limits li.page-item.active a").text();
        var activeNumber = $(".active-limits-number").text();
        activeNumber = activeNumber.trim();

        // if (N == true) {
        //     N = activePage;
        // }
        var provider = $("#providerSelect").val();
        var category = $("#categorySelect").val();

        var gameId = $("#gameSelect").val();

        var client = $("#clientSelect").val()

        var token = $("#token_display").val();

        $.ajax({
            url: '/ajaxProcessus/settings/displayLimits.php',
            type: 'POST',
            data: {
                "display": true,
                "page": N,
                "number": activeNumber,
                provider,
                category,
                gameId,
                client,
                token,
            },
            // contentType: false,
            // processData: false, 
            cache: false,
            // timeout: 300000,
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
                    $(".table-body-limits").html(rese[0]);
                    $(".navigation_limits").html(rese[1]);

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
            $('#providerSelect option[value="all"]').prop('selected', true);
            $('#gameSelect option[value="all"]').prop('selected', true);
            $('#gameSelect').find('option').not(':first').remove();
        }

        if (category == "slot") {
            $("#providerSelect").html("<?= $providersOptions . $providersSlotOptions; ?>")
            $('#providerSelect option[value="all"]').prop('selected', true);
            $('#gameSelect option[value="all"]').prop('selected', true);
            $('#gameSelect').find('option').not(':first').remove();

        }

        if (category == "all") {
            $("#providerSelect").html(
                "<?= $providersOptions . $providersCasinoOptions . $providersSlotOptions; ?>");
            $('#providerSelect option[value="all"]').prop('selected', true);
            $('#gameSelect option[value="all"]').prop('selected', true);
            $('#gameSelect').find('option').not(':first').remove();
        }
    })

    //provider change -> change games lists
    $("#providerSelect").on("change", function(event) {
        let provider = $(this).val();
        if (provider == "all") {
            $('#gameSelect option[value="all"]').prop('selected', true);
            $('#gameSelect').find('option').not(':first').remove();
        } else {

            //get provider games list
            var token = $("#token_get_games").val();
            $.ajax({
                url: '/ajaxProcessus/settings/getGamesList.php',
                type: 'POST',
                data: {
                    provider,
                    token
                },

                cache: false,
                // timeout: 10000,
                success: function(data) {

                    if (data == 'unauthorized' || data == '') {
                        window.location.href = '/pages/errors/403.php';
                        return;
                    }
                    // window.open("/", "_blank");
                    rese = JSON.parse(data);

                    // console.log(rese);
                    let length = rese.length;

                    if (length == 2) { //success
                        $("#token_get_games").val(rese[1]);
                        $('#gameSelect option[value="all"]').prop('selected', true);
                        $('#gameSelect').find('option').not(':first').remove();
                        $('#gameSelect').append(rese[0]);

                    } else if (length == 1) {
                        $("#token_get_games").val(rese[0]);
                    } else {
                        //refresh page;
                        location.reload();
                    }
                }
            })
        }
    })

    //provider modal change -> change games lists
    $("#providerModalSelect").on("change", function(event) {
        let provider = $(this).val();
        if (provider == "all") {
            $('#gameModalSelect option[value="all"]').prop('selected', true);
            $('#gameModalSelect').find('option').not(':first').remove();
        } else {

            //get provider games list
            var token = $("#token_get_games").val();
            $.ajax({
                url: '/ajaxProcessus/settings/getGamesList.php',
                type: 'POST',
                data: {
                    provider,
                    token
                },

                cache: false,
                // timeout: 10000,
                success: function(data) {

                    if (data == 'unauthorized' || data == '') {
                        window.location.href = '/pages/errors/403.php';
                        return;
                    }
                    // window.open("/", "_blank");
                    rese = JSON.parse(data);

                    // console.log(rese);
                    let length = rese.length;

                    if (length == 2) { //success
                        $("#token_get_games").val(rese[1]);
                        $('#gameModalSelect option[value="all"]').prop('selected', true);
                        $('#gameModalSelect').find('option').not(':first').remove();
                        $('#gameModalSelect').append(rese[0]);

                    } else if (length == 1) {
                        $("#token_get_games").val(rese[0]);
                    } else {
                        //refresh page;
                        location.reload();
                    }
                }
            })
        }
    })


    //NUMBERS DISPLAYED PER PAGE
    $(".limits_number_options button").on("click", function(event) {

        var number = event.currentTarget.textContent;
        $(".active-limits-number").text(number);

        let id = $(".status_.active").attr("id");

        let status = 'all';

        if (id == "status_win") {
            status = "win";
        } else if (id == "status_loss") {
            status = "loss";
        } else if (id == "status_tie") {
            status = "tie";
        }

        displayLimits(1);
    })



    //FILTERs
    $(".filter_limits").on("click", function(event) {
        displayLimits();
    })

    //CLIENT SELECT
    $("#clientSelect").change(function(event) {
        displayLimits();
    })



    //ADD BET Limit

    function addBetLimit(event) {
        event.preventDefault();

        //getting data
        var client = $("#clientModalSelect").val();
        var provider = $("#providerModalSelect").val();
        var gameId = $("#gameModalSelect").val();
        var limitAmount = $("#limitAmount").val().replace(/,/g, '');
        var token = $("#token_add").val();

        //validating & sanitizing data

        var constraints = {
            clientModalSelect: {
                presence: {
                    allowEmpty: false
                },
                exclusion: {
                    within: {
                        all: "All",
                    },
                    message: "^Please Select A client"
                },
                numericality: {
                    onlyInteger: true,
                    greaterThan: 0,
                }
            },
            providerModalSelect: {
                presence: {
                    allowEmpty: false
                },
                exclusion: {
                    within: {
                        all: "All",
                    },
                    message: "^Please Select A Product"
                },
                numericality: {
                    onlyInteger: true,
                    greaterThan: 0,
                    lessThanOrEqualTo: 300
                }
            },
            gameModalSelect: {
                presence: {
                    allowEmpty: false
                },
                // exclusion: {
                //     within: {
                //         all: "All",
                //     },
                //     message: "^Please Select A Table"
                // }
            },
            limitAmount: {
                numericality: {
                    greaterThan: 0,
                },
                format: {
                    pattern: "^[0-9]+\.[0-9]{2}$",
                    message: "Is Not a valid limit!"
                }
            }
        };


        var errors = validate({
            "clientModalSelect": client,
            "providerModalSelect": provider,
            "gameModalSelect": gameId,
            limitAmount

        }, constraints);

        if (typeof errors != undefined && errors != undefined) {
            // console.log(errors);
            for (var key in errors) {
                let key2 = key;

                $(`#${key2}`).addClass("invalid");
                $(`#${key2}`).next(".invalid-feedback").text(errors[key]);
                $(`#${key2}`).next(".invalid-feedback").css("display", "block");
            }
        } else {
            //sanitize

            client = DOMPurify.sanitize(client, {
                SAFE_FOR_JQUERY: true
            });
            provider = DOMPurify.sanitize(provider, {
                SAFE_FOR_JQUERY: true
            });
            gameId = DOMPurify.sanitize(gameId, {
                SAFE_FOR_JQUERY: true
            });
            limitAmount = DOMPurify.sanitize(limitAmount, {
                SAFE_FOR_JQUERY: true
            });


            $.ajax({
                url: '/ajaxProcessus/settings/addBetLimit.php',
                type: 'POST',
                data: {
                    client,
                    provider,
                    gameId,
                    limitAmount,
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
                    var response = rese.response
                    var received_token = rese.token
                    var serverGeneratedErrors = rese.errors

                    if (response == 1) { //added
                        $("button.close").trigger("click");
                        $("#token_add").val(received_token);
                        vt.success(`You successfully set a new Bet-Limit for the Client : ${client}.`, {
                            title: "New Bet-Limit Added!",
                            duration: 6000,
                            closable: true,
                            focusable: true,
                            callback: () => {
                                console.log("completed");
                            }
                        });

                        var activePage = $(".navigation_limits li.page-item.active a").text();
                        displayLimits(activePage);
                        // clearModalInvalidFeedbacks();

                    } else if (response == 2) { //csrf error
                        $("button.close").trigger("click");
                        $("#token_add").val(received_token);

                        vt.error(
                            `CSRF 에러 입니다. 관리자에게 문의 주시기 바랍니다.`, {
                                title: "CSRF Error",
                                duration: 6000,
                                closable: true,
                                focusable: true,
                                callback: () => {
                                    console.log("completed");
                                }
                            });
                        location.reload();
                    } else if (response == 4) { //db error
                        $("button.close").trigger("click");
                        $("#token_add").val(received_token);


                        vt.error(
                            `알수 없는 에러로 처리를  할 수 없습니다. 다시 시도해 주세요.`, {
                                title: "알수 없는 에러",
                                duration: 6000,
                                closable: true,
                                focusable: true,
                                callback: () => {
                                    console.log("completed");
                                }
                            });
                    } else if (response == 0) { //serverGeneratedErrors
                        // $("button.close").trigger("click");
                        $("#token_add").val(received_token);

                        for (var key in serverGeneratedErrors) {
                            var msg = serverGeneratedErrors[key];

                            var key2 = key;

                            key2 = key == "client" ? 'clientModalSelect' : key2;
                            key2 = key == "provider" ? 'providerModalSelect' : key2;
                            key2 = key == "gameId" ? 'gameModalSelect' : key2;

                            // console.log($(`#${key}`));
                            $(`#${key2}`).addClass("invalid");
                            $(`#${key2}`).next(".invalid-feedback").text(msg);
                            $(`#${key2}`).next(".invalid-feedback").css("display", "block");
                        }
                    }
                }
            })
        }
    }

    //EDIT BET Limit

    // ----EDIT----
    function showBetLimitsData(event) {
        showEditeableInputsAndHide();

        let data = event.currentTarget.getAttribute("data-values");

        //populating modal with data

        data = data.replace(new RegExp("&&xx&&", "g"), " ");

        data = JSON.parse(data);

        // console.log(data);

        $("#clientModalSelect").val(data["client_id"]);
        $("#providerModalSelect").val(data["product_id"]);

        var gameNameId = data["game_name_en"] + "(" + data["game_code"] + ")";

        if (!data["game_name_en"]) {
            gameNameId = "All";
        }

        var gameOptionTag = "<option value=" + data['game_code'] + ">" + gameNameId + "</option>";

        $("#gameModalSelect").html(gameOptionTag);
        $("#gameModalSelect").val(data["game_code"]);


        $("#limitAmount").val(data["max_amount"]);

        $("#limit_id").val(data["id"]);
    }


    function editBetLimit(event) {
        event.preventDefault();

        //getting data
        var client = $("#clientModalSelect").val();
        var limitAmount = $("#limitAmount").val().replace(/,/g, '');
        var token = $("#token_edit").val();

        var limitId = $("#limit_id").val();

        //validating & sanitizing data

        var constraints = {
            limitAmount: {
                numericality: {
                    greaterThan: 0,
                },
                format: {
                    pattern: "^[0-9]+\.[0-9]{2}$",
                    message: "Is Not a valid limit!"
                }
            }
        };


        var errors = validate({
            limitAmount

        }, constraints);

        if (typeof errors != undefined && errors != undefined) {
            // console.log(errors);
            for (var key in errors) {
                let key2 = key;

                $(`#${key2}`).addClass("invalid");
                $(`#${key2}`).next(".invalid-feedback").text(errors[key]);
                $(`#${key2}`).next(".invalid-feedback").css("display", "block");
            }
        } else {
            //sanitize
            limitAmount = DOMPurify.sanitize(limitAmount, {
                SAFE_FOR_JQUERY: true
            });


            $.ajax({
                url: '/ajaxProcessus/settings/editBetLimit.php',
                type: 'POST',
                data: {
                    limitId,
                    limitAmount,
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
                    var response = rese.response
                    var received_token = rese.token
                    var serverGeneratedErrors = rese.errors

                    if (response == 1) { //added
                        $("button.close").trigger("click");
                        $("#token_edit").val(received_token);
                        vt.success(`You successfully updated the Bet-Limit for the Client : ${client}.`, {
                            title: "Bet-Limit Updated!",
                            duration: 6000,
                            closable: true,
                            focusable: true,
                            callback: () => {
                                console.log("completed");
                            }
                        });

                        var activePage = $(".navigation_limits li.page-item.active a").text();
                        displayLimits(activePage);
                        // clearModalInvalidFeedbacks();

                    } else if (response == 2) { //csrf error
                        $("button.close").trigger("click");
                        $("#token_edit").val(received_token);

                        vt.error(
                            `CSRF 에러 입니다. 관리자에게 문의 주시기 바랍니다.`, {
                                title: "CSRF Error",
                                duration: 6000,
                                closable: true,
                                focusable: true,
                                callback: () => {
                                    console.log("completed");
                                }
                            });
                        location.reload();
                    } else if (response == 4) { //db error
                        $("button.close").trigger("click");
                        $("#token_edit").val(received_token);


                        vt.error(
                            `알수 없는 에러로 처리를  할 수 없습니다. 다시 시도해 주세요.`, {
                                title: "알수 없는 에러",
                                duration: 6000,
                                closable: true,
                                focusable: true,
                                callback: () => {
                                    console.log("completed");
                                }
                            });
                    } else if (response == 0) { //serverGeneratedErrors
                        // $("button.close").trigger("click");
                        $("#token_edit").val(received_token);

                        for (var key in serverGeneratedErrors) {
                            var msg = serverGeneratedErrors[key];

                            var key2 = key;
                            key2 = key == "client" ? 'clientModalSelect' : key2;

                            // console.log($(`#${key}`));
                            $(`#${key2}`).addClass("invalid");
                            $(`#${key2}`).next(".invalid-feedback").text(msg);
                            $(`#${key2}`).next(".invalid-feedback").css("display", "block");
                        }
                    }
                }
            })
        }
    }

    //DELETE BET LIMIT
    function confirmDeleteBetLimit(event) {
        let id = event.currentTarget.getAttribute("data-id");
        $("#delete_modal button.delete_bet_limit").attr("data-id", id);



        $("#delete_modal button.btn_confirmed_action").hide();
        $("#delete_modal button.delete_bet_limit").show();

        $("#delete_modal #text").text("Do you really want to delete this Bet-Limit? This process cannot be undone.");

        $("#delete_modal").addClass("show");
        // $("body").addClass("deleteModalOn");
        $("#delete_modal").removeClass("d-none");

    }

    function deleteBetLimit(event) {
        let id = event.currentTarget.getAttribute("data-id");

        var token = $("#token_delete").val();


        $.ajax({
            url: '/ajaxProcessus/settings/deleteBetLimits.php',
            type: 'POST',
            data: {
                id,
                token
            },

            cache: false,
            timeout: 10000,


            success: function(data) {
                if (data == 'unauthorized' || data == '') {
                    window.location.href = '/pages/errors/403.php';
                    return;
                }

                // console.log(data);
                data = data.split("###");

                if (data.length == 2) {
                    received_token = data[1];
                    $("#token_delete").val(received_token);
                }

                data = data[0];

                if (data == 1) {
                    $("#delete_modal").removeClass("show");
                    $("#delete_modal").addClass("d-none");

                    vt.success(`You successfully Deleted The Bet-Limit.`, {
                        title: "Bet-Limit Removed!",
                        duration: 6000,
                        closable: true,
                        focusable: true,
                        callback: () => {
                            console.log("completed");
                        }
                    });

                    // var activePage = $(".navigation_clients li.page-item.active a").text();
                    displayLimits();

                } else {
                    $("#delete_modal").removeClass("show");
                    $("#delete_modal").addClass("d-none")

                    vt.error(`There has been an error while processing your delete request!.`, {
                        title: "Bet-Limit is not Removed!",
                        duration: 6000,
                        closable: true,
                        focusable: true,
                        callback: () => {
                            console.log("completed");
                        }
                    });
                }
            }
        })
    }



    document.addEventListener('DOMContentLoaded', (event) => {
        $('#providerSelect option[value="1"]').prop('selected', true);
        displayLimits(0);
    });
    </script>
</body>

</html>
