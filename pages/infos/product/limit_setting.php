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

$productSkins = $db->get("*", "product_skins", [])->results();

$productSkinSelectOptions = [];
$productSkinSelectOptionsValues = [];


//skin amount [min-max] formatting
$fmtZeroFraction = new NumberFormatter("en-US", NumberFormatter::DECIMAL);
$fmtZeroFraction->setAttribute(NumberFormatter::FRACTION_DIGITS, 0);

$casinoProviders = config::get("config/display/casinos");

$activeCasinoProviders = [];
foreach ($casinoProviders as $key => $value) {
    $value === "" ? array_push($activeCasinoProviders, $key) : '';
}


//grouping 
$productSkinsOptions = array();
foreach ($productSkins as $element) {

    $type = $element["type"];

    $skinAmountProvidersData = array();
    foreach ($activeCasinoProviders as $key => $value) {
        $skinAmount = $element[$value];
        if ($skinAmount == '' || $skinAmount == null) {
            $skinAmount = '-';
        } else {
            $skinAmount = explode("-", $skinAmount);

            $skinAmountMin = trim($skinAmount[0]);
            $skinAmountMax = trim($skinAmount[1]);

            $skinAmountMin = $fmtZeroFraction->format($skinAmountMin);
            $skinAmountMax = $fmtZeroFraction->format($skinAmountMax);

            $skinAmount = $skinAmountMin . ' ~ ' . $skinAmountMax . '₩';

            $productSkinSelectOptions[$value] = !isset($productSkinSelectOptions[$value]) ? '' : $productSkinSelectOptions[$value];
            $productSkinSelectOptions[$value] .= "<option value='$type'>$skinAmount - (Type $type)</option>";
            $productSkinSelectOptionsValues[$value][] = $type;


        }
        $skinAmountProvidersData[$value] = $skinAmount;
    }
    $productSkinsOptions[$type] = $skinAmountProvidersData;

}
krsort($productSkinsOptions);

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

        @media (max-width: 595px) {
            .card-header2 {
                flex-direction: column;
                height: 5.4rem !important;
            }
        }

        @media (max-width: 410px) {
            .card-header2 {
                height: 7.8rem !important;
            }

            .wrapper {
                flex-direction: column !important;

            }

            .clients_number_wrapper {
                margin-top: 10px;
                margin-bottom: 10px;
            }

        }

    </style>
</head>

<?php include __DIR__ . '/../../../includes/partials/_flash_msg.php'; ?>

<?php
//PRODUCT SKIN INFO MODAL
$modal_title = 'Product Skin Info';
$modal_body = '<div class="table-responsive mb-3">
<table class="align-middle mb-0 table table-borderless table-striped table-hover">
    <thead>
        <tr>
            <th class="text-center">Type</th>
            <th class="text-center" style="display:' . config::get("display/casinos/evo") . '">' . config::get("providersNameMappings")["evo"] . '</th>
            <th class="text-center" style="display:' . config::get("display/casinos/bg") . '">' . config::get("providersNameMappings")["bg"] . '</th>
            <th class="text-center" style="display:' . config::get("display/casinos/asg") . '">' . config::get("providersNameMappings")["asg"] . '</th>
            <th class="text-center" style="display:' . config::get("display/casinos/dg") . '">' . config::get("providersNameMappings")["dg"] . '</th>
            <th class="text-center" style="display:' . config::get("display/casinos/sg") . '">' . config::get("providersNameMappings")["sg"] . '</th>
        </tr>
    </thead>
    <tbody>
    ';
foreach ($productSkinsOptions as $key => $value) {

    $popOver = "";
    if (in_array($key, ["A", "B", "C", "D"])) {
        $popOver = '<button type="button" data-toggle="tooltip" data-original-title="If you choose a skin with no additional fees, you will not be charged any additional fees.<br><br>However, the game below is not available to access:<br><br>-Lightning Baccarat<br>-Crazy Time<br>-Monopoly<br>-Lightning Roulette" data-placement="bottom" class="btn-outline-dark" title>-No Extra Commissions</button>';
    }

    $modal_body .=
        '<tr>
            <td class="text-center text-muted">' . $key . '</td> 
            <td class="text-center" style="display:' . config::get("display/casinos/evo") . '"> <p class="mb-2">' . $value["evo"] . '</p>' . $popOver . '</td>

            <td class="text-center" style="display:' . config::get("display/casinos/evo") . '"> <p class="mb-2">' . $value["bg"] . '</p></td>
            <td class="text-center" style="display:' . config::get("display/casinos/evo") . '"> <p class="mb-2">' . $value["asg"] . '</p></td>
            <td class="text-center" style="display:' . config::get("display/casinos/evo") . '"> <p class="mb-2">' . $value["dg"] . '</p></td>
            <td class="text-center" style="display:' . config::get("display/casinos/evo") . '"> <p class="mb-2">' . $value["sg"] . '</p></td>
        </tr>';
}

$modal_body .= '</tbody>
</table>
</div> ';
$modal_footer = '<button type="button" class="btn btn-secondary d-none" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary d-none">Save changes</button> ';

$modal_size = 'xl';
includeWithVariables('./../../../includes/modals/_modal.php', array('class' => 'product_skin_info', 'modal_size' => $modal_size, 'modal_title' => $modal_title, 'modal_body' => $modal_body, 'modal_footer' => $modal_footer));
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
                    <?php includeWithVariables('./../../../includes/partials/_innerheader.php', array('title' => 'Product', 'title_text' => 'Limit Settings.', 'icon' => "portfolio")); ?>

                    <!-- main content -->
                    <section class="main">

                        <div class="row">

                            <div class="col-md-12">
                                <div class="main-card mb-3 card">
                                    <div class="card-header card-header2"
                                        style='display:flex;height: 2.5rem;justify-content: space-between;'>


                                        <span>
                                            Product Skins

                                        </span>
                                        <div class="wrapper"
                                            style="display: flex;justify-content: center;align-items: center;">
                                            <div class="clslct mr-0 mr-sm-4">
                                                <select type=" select" id="clientSelect" name="clientSelect"
                                                    class="custom-select">
                                                    <option value="all">All</option>
                                                    <?php
                                                    echo $options;

                                                    ?>

                                                </select>
                                            </div>
                                            <div class="dropdown d-inline-block clients_number_wrapper">
                                                <button type="button" aria-haspopup="true" aria-expanded="false"
                                                    data-toggle="dropdown"
                                                    class="mr-2 dropdown-toggle  active-clients-number btn btn-outline-primary"><?= config::get("display/activeNumber"); ?></button>
                                                <div tabindex="-1" role="menu" aria-hidden="true"
                                                    class="dropdown-menu clients_number_options"
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
                                    <div class="btn_read ml-3">
                                        <button class="mb-2 mt-2 ml-2 btn btn-success active" data-toggle="modal"
                                            data-target=".product_skin_info">Product Skin Info</button>

                                    </div>
                                    <div class="table-responsive mb-3">
                                        <input type="hidden" name="token_display" id="token_display"
                                            value="<?= token::generate("display_skins") ?>">

                                        <table
                                            class="align-middle mb-0 table table-borderless table-striped table-hover">
                                            <thead>
                                                <tr>
                                                    <th class="text-center">#</th>
                                                    <th class="text-center">AG Code</th>
                                                    <th class="text-center">Name</th>
                                                    <th class="text-center">Parent</th>
                                                    <th class="text-center"
                                                        style="display:<?= config::get("display/casinos/evo"); ?>">
                                                        <?= config::get("providersNameMappings")["evo"]; ?>
                                                    </th>
                                                    <th class="text-center"
                                                        style="display:<?= config::get("display/casinos/bg"); ?>">
                                                        <?= config::get("providersNameMappings")["bg"]; ?>
                                                    </th>
                                                    <th class="text-center"
                                                        style="display:<?= config::get("display/casinos/asg"); ?>">
                                                        <?= config::get("providersNameMappings")["asg"]; ?>
                                                    </th>
                                                    <th class="text-center"
                                                        style="display:<?= config::get("display/casinos/dg"); ?>">
                                                        <?= config::get("providersNameMappings")["dg"]; ?>
                                                    </th>

                                                    <th class="text-center"
                                                        style="display:<?= config::get("display/casinos/sg"); ?>">
                                                        <?= config::get("providersNameMappings")["sg"]; ?>
                                                    </th>



                                                </tr>
                                            </thead>
                                            <tbody class="table-body-skins">

                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="pager ml-3">
                                        <nav class="navigation_skins" aria-label="navigation_skins">
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

    <!-- delete modal  -->
    <?php includeWithVariables('./../../../includes/modals/_deleteModal.php'); ?>


    <script>
        function capitalizeFirstLetter(string) {
            return string.charAt(0).toUpperCase() + string.slice(1);
        }

        // ----DISPLAY
        function displayClientCasinoSkin(N = 1) {
            var activeNumber = $(".active-clients-number").text();
            activeNumber = activeNumber.trim();

            var client = $("#clientSelect").val()
            var token = $("#token_display").val();

            $.ajax({
                url: '/ajaxProcessus/infoProduct/displayClientCasinoSkin.php',
                type: 'POST',
                data: {
                    "display": true,
                    "page": N,
                    client,
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

                    // console.log(rese[0]);
                    let length = rese.length;

                    if (length == 4) { //success
                        $(".table-body-skins").html(rese[0]);
                        $(".navigation_skins").html(rese[1]);

                        $("#token_display").val(rese[3]);
                    } else if (length == 1) { //csrf error
                        $("#token_display").val(rese[0]);
                        location.reload();

                    } else {
                        //refresh page;
                        // location.reload();
                    }
                }

            })
        }

        //NUMBERS DISPLAYED PER PAGE
        $(".clients_number_options button").on("click", function (event) {

            var number = event.currentTarget.textContent;
            $(".active-clients-number").text(number);

            displayClientCasinoSkin(1);
        })

        //CLIENT SELECT
        $("#clientSelect").change(function (event) {
            displayClientCasinoSkin();
        })



        document.addEventListener('DOMContentLoaded', (event) => {

            displayClientCasinoSkin();

        });
    </script>


</body>

</html>
