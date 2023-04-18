<?php

require_once __DIR__ . "/../../core/ini.php";
include __DIR__ . '/../../includes/partials/_authorization.php';

//get bet details json files
$transactionId = $_GET['transactionId'] ?? "";

if ($transactionId == "") {
    echo '<script>alert("No TransactionId specified")</script>';
    die;
}

$curl = curl_init();

curl_setopt_array(
    $curl,
    array(
        CURLOPT_URL => "https://api.spadeapi.org/api/transaction-relation?transaction_id=$transactionId",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HTTPHEADER => array(
            'Accept: application/json',
            'Authorization: Bearer 6f26bd84-bc9d-40d6-8475-5049ad231ce7'
        ),
    )
);

$betDetailsResponse = curl_exec($curl);

$betDetailsResponseDecoded = json_decode($betDetailsResponse, true);

//check bet details are presents
if (!isset($betDetailsResponseDecoded["data"]["transaction_list"]) || $betDetailsResponseDecoded["data"]["transaction_list"] == []) {
    echo '<script>alert("베팅 상세내역 업데이트중입니다. 잠시후 다시 시도해 주세요.")</script>';
    die;
}

if (!isset($betDetailsResponseDecoded["data"]["transaction_list"][0]["detail"]) || $betDetailsResponseDecoded["data"]["transaction_list"][0]["detail"] == null || !isset($betDetailsResponseDecoded["data"]["transaction_list"][1]["detail"]) || $betDetailsResponseDecoded["data"]["transaction_list"][1]["detail"] == null) {
    echo '<script>alert("베팅 상세내역 업데이트중입니다. 잠시후 다시 시도해 주세요.")</script>';
    die;
}

curl_close($curl);

?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta http-equiv="Content-Language" content="en" />
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, shrink-to-fit=no" />
    <meta name="msapplication-tap-highlight" content="no" />
    <link rel="stylesheet" href="/assets/css/betDetails/betDetailsDT.css">
    <link rel="icon" type="image/x-icon" href="/assets/images/favicon.png?v=1.00" />
    <title>Bet Details</title>
</head>
<style>
    img {
        pointer-events: none;
    }

</style>

<body>
    <div class="container">

        <div id="cancel-btn" onclick="runAction('close')">&#10006;</div>

        <div class="top-info">
            <p id="vendor"></p>
            <p id="game-type"></p>
            <p id="table-name"></p>
        </div>

        <div id="dealer-name"></div>
        <img src="" id="outcome-image" alt="">
        <!-- <span id="outcome"></span> -->

        <div id="bottom-left">
            <p id="debit-account" class="mb-12"></p>

            <div class="datetimes mb-12">
                <p>시작시간: <span id="started-at"></span></p>
                <p>배팅시간: <span id="bet-time"></span></p>
                <p>정산시간: <span id="settled-at"></span></p>
            </div>

            <!-- Buttons -->


            <div class="side-btn mb-4">
                <p>유저보유금액</p>
                <p class="yellow-text" id="debit-before-balance"></p>
            </div>

            <div class="side-btn mb-32">
                <p>배팅금액</p>
                <p id="debit-amount"></p>
                <img src="/assets/images/betDetails/dragonTiger/btn-coins.png" class="side-btn-left-image">
            </div>

            <div class="side-btn mb-4">
                <p>남은잔액</p>
                <p class="yellow-text" id="credit-before-balance"></p>
            </div>

            <div class="side-btn">
                <p>당첨금액</p>
                <p id="credit-amount"></p>
            </div>
        </div>

        <!-- Cards -->
        <div class="text-center" id="dragon-cards"></div>
        <div class="text-center" id="tiger-cards"></div>

        <!-- Scores -->

        <span class="scores" id="dragon-score"></span>
        <span class="scores" id="tiger-score"></span>

        <!-- Chips -->
        <div class="chip-container" id="dragon-chip">
            <span class="chip-text" id="dragon"></span>
        </div>

        <div class="chip-container" id="tie-chip">
            <span class="chip-text" id="tie"></span>
        </div>

        <div class="chip-container" id="suited-tie-chip">
            <span class="chip-text" id="suited-tie"></span>
        </div>

        <div class="chip-container" id="tiger-chip">
            <span class="chip-text" id="tiger"></span>
        </div>



        <!-- dragon table -->
        <img src="/assets/images/betDetails/dragonTiger/dragon-win-table.png" alt="" id="dragon-win-table"
            class="win-table">

        <!-- tie1 table -->
        <img src="/assets/images/betDetails/dragonTiger/tie-win-table.png" alt="" id="tie-win-table" class="win-table">
        <span class="table-text" id="tie-win-text">무</span>

        <!-- suited table -->
        <img src="/assets/images/betDetails/dragonTiger/suited-tie-win-table.png" alt="" id="suited-tie-win-table"
            class="win-table">
        <span class="table-text" id="suited-tie-win-text">적절한무</span>

        <!-- tiger table -->
        <img src="/assets/images/betDetails/dragonTiger/tiger-win-table.png" alt="" id="tiger-win-table"
            class="win-table">


        <!-- Payout chips -->
        <div class="yellow-chip-container" id="dragon-payout">
            <span class="chip-text" id="dragon-payout-text"></span>
        </div>

        <div class="yellow-chip-container" id="tie-payout">
            <span class="chip-text" id="tie-payout-text"></span>
        </div>

        <div class="yellow-chip-container" id="suited-tie-payout">
            <span class="chip-text" id="suited-tie-payout-text"></span>
        </div>

        <div class="yellow-chip-container" id="tiger-payout">
            <span class="chip-text" id="tiger-payout-text"></span>
        </div>






    </div>

    <script type="text/javascript">
        var betDetailsData = <?php echo $betDetailsResponse; ?>;
        let allImages = document.querySelectorAll("img");
        allImages.forEach((value) => {
            value.oncontextmenu = (e) => {
                e.preventDefault();
            }
        })
    </script>
    <script src="/assets/scripts/betDetails/betDetailsDT.js?v=1.04"></script>
</body>

</html>
