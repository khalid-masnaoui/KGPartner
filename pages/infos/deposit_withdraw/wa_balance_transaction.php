<?php

require_once __DIR__ . "/../../../core/ini.php";
require_once __DIR__ . "/../../../core/inc_var.php";
include __DIR__ . '/../../../includes/partials/_authorization.php';

$db = DB::getInstance();

$partner = new user();
$id = $partner->data()["id"];
$partnerPtId = $partner->data()["pt_id"];

// $partners = $db->get("id,username", "partner_users", [])->results();

// $sql = "SELECT id,username FROM partner_users WHERE pt_id != ? AND pt_id REGEXP ?";
$sql = "SELECT id,username FROM partner_users WHERE pt_id = ? OR pt_id REGEXP ?";
$partners = $db->query($sql, ["$partnerPtId", "^$partnerPtId/[0-9/]*$"])->results();

$options = "";
$optionsDropDown = "";

foreach ($partners as $key => $value) {

    $options .= "<option value=" . $value['id'] . ">" . $value["username"] . "</option>";

    if ($value['id'] != $id) {
        $optionsDropDown .= "<option value=" . $value['id'] . ">" . $value["username"] . "</option>";
    }


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

<?php
$modal_title = '포인트(알) 전송';
$modal_body = '<form class="">


<div class="position-relative row form-group clientSelectAdd"><label for="Client" class="col-sm-2 col-form-label"> 파트너 </label>
    <div class="col-sm-10">
        <select type="select" class="selectDeposit" id="clientSelectAdd" name="clientSelectAdd"
            class="custom-select">
            <option value="">파트너 선택</option>
            ' . $optionsDropDown . '
        </select>
        <div class="invalid-feedback"></div>
    </div>
</div>

<div class="position-relative row form-group"><label for="deposit"  id="depositLabel" class="col-sm-2 col-form-label">금액 (원)</label>
    <div class="col-sm-10"><input name="depositAmount" id="depositAmount" placeholder="deposit amount..." type="text" class="form-control shadow-none">
                        <div class="invalid-feedback"></div>
    </div>
</div>



<input type="hidden" name="deposit_id" id="deposit_id">



<div class="position-relative row form-check">
    <div class="col-sm-10 text-center text-sm-left">
        <button class="btn btn-secondary" id="add_deposit" onclick="addDeposit(event)"> 포인트 지급</button>
    </div>
    <div class="col-sm-10 text-center text-sm-left">
        <button class="btn btn-secondary" id="edit_deposit" onclick="confirmEditDeposit(event)">수정</button>
    </div>
    <div class="col-sm-10 text-center text-sm-left">
        <button class="btn btn-secondary" id="deduct" onclick="deductAmount(event)">포인트 차감</button>
    </div>
</div>
</form> ';
$modal_footer = '<button type="button" class="btn btn-secondary d-none" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary d-none">Save</button> ';

$modal_size = 'xl';
includeWithVariables('./../../../includes/modals/_modal.php', array('class' => 'add_deposit', 'modal_size' => $modal_size, 'modal_title' => $modal_title, 'modal_body' => $modal_body, 'modal_footer' => $modal_footer));
; ?>

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
                    <?php includeWithVariables('./../../../includes/partials/_innerheader.php', array('title' => '포인트관리', 'title_text' => '포인트 이동내역 (파트너)', 'icon' => "wallet")); ?>

                    <!-- main content -->
                    <section class="main">
                        <div class="row">

                            <div class="col-md-12">

                                <div class="main-card mb-3 card">
                                    <div class="card-header" style='height: 2.5rem;'>필터

                                    </div>
                                    <div class="row mt-2 row_filter_wrapper mb-3">



                                        <div class="input-group  d-flex ml-1 pl-0 pr-0 col-md-5 col-lg-4 col-xl-3  ml-1 pl-0 pr-0 ml-4"
                                            style='width:unset;'>
                                            <div class="input-group-prepend"><span class="input-group-text">시작일</span>
                                            </div>
                                            <input placeholder="" type="date" class="form-control shadow-none startdate"
                                                value=<?= date("Y-m-d") ?>>
                                        </div>


                                        <div class="input-group  d-flex ml-1 pl-0 pr-0 col-md-5 col-lg-4 col-xl-3  ml-1 pl-0 pr-0 ml-4 ml-sm-1 mt-2 mt-sm-0"
                                            style='width:unset;'>
                                            <div class="input-group-prepend"><span class="input-group-text">종료일</span>
                                            </div>
                                            <input placeholder="" type="date" class="form-control shadow-none enddate"
                                                value=<?= date("Y-m-d") ?>>
                                        </div>
                                        <div class="f1 input-group col-md-5 col-lg-4 col-xl-3 ml-1 pl-0 pr-0  mt-2 mt-xl-0 ml-4 ml-xl-1"
                                            style='width:unset;'>
                                            <div class="input-group-prepend"><span class="input-group-text">이동자
                                                </span></div>
                                            <input placeholder="" type="text" id="depositor"
                                                class="form-control shadow-none">
                                        </div>
                                        <div class="buttons-wrapper">
                                            <button
                                                class="btn btn-primary filter_btn mt-2 filter_deposits mt-xl-0">검색</button>

                                            <button
                                                class="btn btn-secondary filter_btn filter_deposits_reset mt-2 mt-xl-0">초기화</button>
                                        </div>


                                    </div>

                                </div>


                            </div>

                        </div>
                        <div class="row">

                            <div class="col-md-12">
                                <div class="main-card mb-3 card">
                                    <div class="card-header card-header2"
                                        style='height: 2.5rem;justify-content: space-between;'>


                                        <span>

                                            포인트 내역

                                        </span>
                                        <div class="wrapper"
                                            style="display: flex;justify-content: center;align-items: center;">
                                            <div class="clslct mr-4">
                                                <select type=" select" id="clientSelect" name="clientSelect"
                                                    class="custom-select">
                                                    <option value="all">전체</option>
                                                    <?php
                                                    echo $options;

                                                    ?>

                                                </select>
                                            </div>
                                            <div class="dropdown d-inline-block deposits_number_wrapper">
                                                <button type="button" aria-haspopup="true" aria-expanded="false"
                                                    data-toggle="dropdown"
                                                    class="mr-2 dropdown-toggle active-deposits-number btn btn-outline-primary"><?= config::get("display/activeNumber"); ?></button>
                                                <div tabindex="-1" role="menu" aria-hidden="true"
                                                    class="dropdown-menu deposits_number_options"
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

                                    <div class="btn_read ml-3">

                                        <button class="mb-2 mt-2 ml-2 btn btn-light active" data-toggle="modal"
                                            data-target=".add_deposit" onclick=hideEditableInputsAndShow()> 포인트 지급
                                            <span style="font-weight: bolder;color: black;">+</span></button>

                                        <button class="mb-2 mt-2 ml-2 btn btn-light active" data-toggle="modal"
                                            data-target=".add_deposit" onclick=hideEditableInputsAndShowDeduct()>포인트 차감
                                            <span style="font-weight: bolder;color: black;">-</span> </button>

                                        <input type="hidden" name="token_display" id="token_display"
                                            value="<?= token::generate("display_waBalance_transactions") ?>">

                                        <input type="hidden" name="token_delete" id="token_delete"
                                            value="<?= token::generate("delete_waBalance") ?>">

                                        <input type="hidden" name="token_make" id="token_make"
                                            value="<?= token::generate("make_waBalance_transaction") ?>">

                                        <input type="hidden" name="token_deduct" id="token_deduct"
                                            value="<?= token::generate("deduct_waBalance") ?>">

                                        <input type="hidden" name="token_edit" id="token_edit"
                                            value="<?= token::generate("edit_waBalance") ?>">
                                    </div>

                                    <div class="table-responsive mb-3">
                                        <table
                                            class="align-middle mb-0 table table-borderless table-striped table-hover">
                                            <thead>
                                                <tr>
                                                    <th class="text-center">#</th>
                                                    <th class="text-center">파트너 ID</th>
                                                    <th class="text-center">아이디</th>
                                                    <th class="text-center">상위파트너</th>
                                                    <th class="text-center">포인트 금액 </th>
                                                    <th class="text-center">이동자</th>
                                                    <th class="text-center">일시</th>
                                                    <th class="text-center">삭제</th>



                                                </tr>
                                            </thead>
                                            <tbody class='table-body-deposits'>

                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="pager ml-3">
                                        <nav class="navigation_deposits" aria-label="navigation_deposits">
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


    <!-- delete modal  -->
    <?php includeWithVariables('./../../../includes/modals/_deleteModal.php'); ?>


    <script>
        // document.querySelector(".startdate").value="2022-03-05";
        // document.querySelector(".enddate").value="2022-03-05";

        //select options search feature
        $(function () {
            $(".selectDeposit").select2({
                dropdownParent: $('.modal')
            });
        });

        //handling invalid feedbacks and switching edit/add
        function clearModalInvalidFeedbacks() {
            var array = ["clientSelectAdd", "depositAmount"];

            array.forEach(element => {
                let target = $(`#${element}`);

                target.next(".invalid-feedback").text("");
                target.next(".invalid-feedback").css("display", "none");
                target.removeClass("invalid");
            });
        }

        function clearModalInvalidFeedbacksOnKeyUp() {

            $("#depositAmount").keyup(function (e) {

                $(this).next(".invalid-feedback").text("");
                $(this).next(".invalid-feedback").css("display", "none");
                $(this).removeClass("invalid");
            })
            $("#clientSelectAdd").change(function (e) {

                $(this).next(".invalid-feedback").text("");
                $(this).next(".invalid-feedback").css("display", "none");
                $(this).removeClass("invalid");
            })

        }

        function hideEditableInputsAndShow() {

            clearModalInvalidFeedbacks();

            //reset
            // $("#clientSelectAdd").val('');
            $("#clientSelectAdd").select2().select2('val', '');
            $("#depositAmount").val('0');


            $("button#add_deposit").show();
            $("button#edit_deposit").hide();
            $("button#deduct").hide();

            $("#exampleModalLongTitle").text("포인트(알) 전송");
            $("#depositLabel").text("금액 (원)");

            $("#clientSelectAdd").prop('disabled', false);


        }

        function hideEditableInputsAndShowDeduct() {

            clearModalInvalidFeedbacks();

            //reset
            // $("#clientSelectAdd").val('');
            $("#clientSelectAdd").select2().select2('val', '');
            $("#depositAmount").val('0');


            $("button#add_deposit").hide();
            $("button#edit_deposit").hide();
            $("button#deduct").show();


            $("#exampleModalLongTitle").text("포인트(알) 차감");
            $("#depositLabel").text("금액 (원)");

            $("#clientSelectAdd").prop('disabled', false);


        }

        function showEditableInputsAndHide() {

            clearModalInvalidFeedbacks();

            $("button#add_deposit").hide();
            $("button#edit_deposit").show();
            $("button#deduct").hide();


            $("#exampleModalLongTitle").text("Edit Deposit");
            $("#depositLabel").text("Deposit Amount (원)");

            $("#clientSelectAdd").prop('disabled', true);


        }

        function number_format(number, decimals, dec_point, thousands_sep) {
            // Strip all characters but numerical ones.
            // number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
            number = (number + '').replace(/[^0-9+\-.]/g, '');

            var n = !isFinite(+number) ? 0 : +number,
                prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
                sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
                dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
                s = '',
                toFixedFix = function (n, prec) {
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

        $("#depositAmount").focusout(function (event) {
            var number = $(this).val();

            if (number == '') {
                $(this).val("0");

            } else {
                var d = number_format(number, 0, ".", ",");

                $(this).val(d);
            }

        })

        clearModalInvalidFeedbacksOnKeyUp();

        // --- DISPLAY DEPOSITS ----
        function displayDeposits(N = 1) {

            var activePage = $(".navigation_deposits li.page-item.active a").text();
            var activeNumber = $(".active-deposits-number").text();
            activeNumber = activeNumber.trim();

            var startDate = $(".startdate").val();
            var endDate = $(".enddate").val();
            var depositor = $("#depositor").val();

            var client = $("#clientSelect").val();

            var token = $("#token_display").val();



            $.ajax({
                url: '/ajaxProcessus/depositWithdraw/waBalance/displayTransactions.php',
                type: 'POST',
                data: {
                    "display": true,
                    "page": N,
                    "number": activeNumber,
                    depositor,
                    startDate,
                    endDate,
                    client,
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
                        $(".table-body-deposits").html(rese[0]);
                        $(".navigation_deposits").html(rese[1]);

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
        $(".deposits_number_options button").on("click", function (event) {

            var number = event.currentTarget.textContent;
            $(".active-deposits-number").text(number);

            displayDeposits();
        })

        //USERNAME FILTER
        $(".filter_deposits").on("click", function (event) {
            displayDeposits();
        })

        //RESET FILTERS
        $(".filter_deposits_reset").on("click", function (event) {

            //username
            $("#depositor").val('');

            //client
            $("#clientSelect").val("all")

            //dates
            let currentDate = new Date().toJSON().slice(0, 10);

            var startDate = $(".startdate").val(currentDate);
            var endDate = $(".enddate").val(currentDate);

            displayDeposits();
        })



        //CLIENT SELECT
        $("#clientSelect").change(function (event) {
            displayDeposits();
        })


        // ---ADD---
        function addDeposit(event) {
            event.preventDefault();

            //getting data
            var client = $("#clientSelectAdd").val();
            var username = $('#clientSelectAdd').find(":selected").text();
            var depositAmount = $("#depositAmount").val().replace(/,/g, '');
            var token = $("#token_make").val();

            //validating & sanitizing data

            var constraints = {
                clientSelectAdd: {
                    presence: {
                        allowEmpty: false
                    },

                },
                depositAmount: {
                    numericality: {
                        greaterThan: 0,
                    },
                    format: {
                        pattern: "^[0-9]+\.[0-9]{2}$",
                        message: "Is Not a valid deposit amount!"
                    }
                }
            };


            var errors = validate({
                "clientSelectAdd": client,
                depositAmount

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
                depositAmount = DOMPurify.sanitize(depositAmount, {
                    SAFE_FOR_JQUERY: true
                });


                $.ajax({
                    url: '/ajaxProcessus/depositWithdraw/waBalance/makeWaBalanceDeposit.php',
                    type: 'POST',
                    data: {
                        depositAmount,
                        client,
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
                        var response = rese.response
                        var received_token = rese.token
                        var serverGeneratedErrors = rese.errors

                        if (response == 1) { //added


                            $("button.close").trigger("click");
                            $("#token_make").val(received_token);
                            vt.success(
                                `${username}에게 포인트 지급 완료되었습니다.`, {
                                title: "지급 완료!",
                                duration: 6000,
                                closable: true,
                                focusable: true,
                                callback: () => {
                                    console.log("completed");
                                }
                            });

                            var activePage = $(".navigation_deposits li.page-item.active a").text();
                            displayDeposits(activePage);
                            // clearModalInvalidFeedbacks();

                        } else if (response == 2) { //csrf error
                            $("button.close").trigger("click");
                            $("#token_make").val(received_token);

                            vt.error(
                                `CSRF 에러 입니다. 관리자에게 문의 주시기 바랍니다.`, {
                                title: "CSRF 에러.",
                                duration: 6000,
                                closable: true,
                                focusable: true,
                                callback: () => {
                                    console.log("completed");
                                }
                            });
                        } else if (response == 4) { //db error
                            $("button.close").trigger("click");
                            $("#token_make").val(received_token);


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
                            $("#token_make").val(received_token);

                            for (var key in serverGeneratedErrors) {
                                var msg = serverGeneratedErrors[key];

                                // console.log($(`#${key}`));
                                $(`#${key}`).addClass("invalid");
                                $(`#${key}`).next(".invalid-feedback").text(msg);
                                $(`#${key}`).next(".invalid-feedback").css("display", "block");
                            }
                        }
                    }
                })
            }
        }

        //DEDUCT
        function deductAmount(event) {
            event.preventDefault();

            //getting data
            var client = $("#clientSelectAdd").val();
            var username = $('#clientSelectAdd').find(":selected").text();
            var depositAmount = $("#depositAmount").val().replace(/,/g, '');
            var token = $("#token_deduct").val();

            //validating & sanitizing data

            var constraints = {
                clientSelectAdd: {
                    presence: {
                        allowEmpty: false
                    },

                },
                depositAmount: {
                    numericality: {
                        greaterThan: 0,
                    },
                    format: {
                        pattern: "^[0-9]+\.[0-9]{2}$",
                        message: "Is Not a valid deposit amount!"
                    }
                }
            };


            var errors = validate({
                "clientSelectAdd": client,
                depositAmount

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
                depositAmount = DOMPurify.sanitize(depositAmount, {
                    SAFE_FOR_JQUERY: true
                });


                $.ajax({
                    url: '/ajaxProcessus/depositWithdraw/waBalance/deduct.php',
                    type: 'POST',
                    data: {
                        depositAmount,
                        client,
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
                        var response = rese.response
                        var received_token = rese.token
                        var serverGeneratedErrors = rese.errors

                        if (response == 1) { //added


                            $("button.close").trigger("click");
                            $("#token_deduct").val(received_token);
                            vt.success(`${username}으로부터 포인트 차감 완료되었습니다.`, {
                                title: "차감 완료!",
                                duration: 6000,
                                closable: true,
                                focusable: true,
                                callback: () => {
                                    console.log("completed");
                                }
                            });

                            var activePage = $(".navigation_deposits li.page-item.active a").text();
                            displayDeposits(activePage);
                            // clearModalInvalidFeedbacks();

                        } else if (response == 2) { //csrf error
                            $("button.close").trigger("click");
                            $("#token_deduct").val(received_token);

                            vt.error(
                                `CSRF 에러 입니다. 관리자에게 문의 주시기 바랍니다.`, {
                                title: "CSRF 에러.",
                                duration: 6000,
                                closable: true,
                                focusable: true,
                                callback: () => {
                                    console.log("completed");
                                }
                            });
                        } else if (response == 4) { //db error
                            $("button.close").trigger("click");
                            $("#token_deduct").val(received_token);


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
                            $("#token_deduct").val(received_token);

                            for (var key in serverGeneratedErrors) {
                                var msg = serverGeneratedErrors[key];

                                // console.log($(`#${key}`));
                                $(`#${key}`).addClass("invalid");
                                $(`#${key}`).next(".invalid-feedback").text(msg);
                                $(`#${key}`).next(".invalid-feedback").css("display", "block");
                            }
                        }
                    }
                })
            }
        }




        // -----DELETE-----
        function confirmDeleteDeposit(event) {
            let id = event.currentTarget.getAttribute("data-id");


            $("#delete_modal button.delete_deposit").attr("data-id", id);

            $("#delete_modal button.btn_confirmed_action").hide();
            $("#delete_modal button.delete_deposit").show();



            $("#delete_modal #text").text("Do you really want to delete this record? This process cannot be undone.");

            $("#delete_modal").addClass("show");
            $("#delete_modal").removeClass("d-none");

        }

        function deleteDeposit(event) {
            let id = event.currentTarget.getAttribute("data-id");
            var token = $("#token_delete").val();

            // console.log(id);

            $.ajax({
                url: '/ajaxProcessus/depositWithdraw/waBalance/deleteWaBalance.php',
                type: 'POST',
                data: {
                    id,
                    token
                },
                cache: false,
                timeout: 10000,

                success: function (data) {
                    if (data == 'unauthorized' || data == '') {
                        window.location.href = '/pages/errors/403.php';
                        return;
                    }

                    data = data.split("###");

                    if (data.length == 2) {
                        received_token = data[1];
                        $("#token_delete").val(received_token);
                    }
                    data = data[0];

                    if (data == 1) {
                        $("#delete_modal").removeClass("show");
                        $("#delete_modal").addClass("d-none");

                        vt.success(`내역이 삭제되었습니다.`, {
                            title: "삭제 완료!",
                            duration: 6000,
                            closable: true,
                            focusable: true,
                            callback: () => {
                                console.log("completed");
                            }
                        });

                        displayDeposits();

                    } else {
                        $("#delete_modal").removeClass("show");
                        $("#delete_modal").addClass("d-none")

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
                    }



                }


            })


        }

        //----EDIT DEPOSIT---

        function showDepositData(event) {
            showEditableInputsAndHide();

            let data = event.currentTarget.getAttribute("data-values");

            //populating modal with data

            data = data.replace(new RegExp("&&xx&&", "g"), " ");

            data = JSON.parse(data);


            // $("#clientSelectAdd").val(data["partner_id"]);
            $("#clientSelectAdd").select2().select2('val', data["partner_id"]);
            $("#depositAmount").val(data["amount"]);


            $("#deposit_id").val(data["id"]);


        }

        function confirmEditDeposit(e) {

            e.preventDefault();

            $("#delete_modal button.btn_confirmed_action").hide();
            $("#delete_modal button.confirm_deposit").show();


            $("#delete_modal #text").text("Do you really want to Edit this Deposit?.");

            $(".modal-confirm .icon-box").css("border", "0px solid #3ac47d");
            $(".modal-confirm .icon-box i").css("color", "#3ac47d");
            $(".modal-confirm .btn-danger").css("background", "#3ac47d");
            $(".modal-confirm .icon-box i").attr("class", "pe-7s-check");


            $("#delete_modal").addClass("show");
            $("#delete_modal").removeClass("d-none");
        }

        function editDeposit(event) {
            event.preventDefault();

            //getting data
            var depositAmount = $("#depositAmount").val().replace(/,/g, '');
            var depositId = $("#deposit_id").val().trim();

            var token = $("#token_edit").val();

            var username = $("#clientSelectAdd option:selected").text();



            //validating & sanitizing data

            var constraints = {
                depositAmount: {
                    numericality: {
                        greaterThan: 0,
                    },
                    format: {
                        pattern: "^[0-9]+\.[0-9]{2}$",
                        message: "Is Not a valid deposit amount!"
                    }
                }


            };

            var errors = validate({
                depositAmount

            }, constraints);
            if (typeof errors != undefined && errors != undefined) {

                // console.log(errors);
                for (var key in errors) {

                    $(`#${key}`).addClass("invalid");
                    $(`#${key}`).next(".invalid-feedback").text(errors[key]);
                    $(`#${key}`).next(".invalid-feedback").css("display", "block");
                }
            } else {
                //sanitize

                depositAmount = DOMPurify.sanitize(depositAmount, {
                    SAFE_FOR_JQUERY: true
                });
                depositId = DOMPurify.sanitize(depositId, {
                    SAFE_FOR_JQUERY: true
                });
                username = DOMPurify.sanitize(username, {
                    SAFE_FOR_JQUERY: true
                });


                $.ajax({
                    url: '/ajaxProcessus/depositWithdraw/waBalance/editWaBalance.php',
                    type: 'POST',
                    data: {
                        depositAmount,
                        depositId,
                        token
                    },

                    cache: false,
                    timeout: 10000,

                    success: function (data) {

                        if (data == 'unauthorized' || data == '') {
                            window.location.href = '/pages/errors/403.php';
                            return;
                        }
                        // console.log(data);
                        rese = JSON.parse(data);
                        var response = rese.response
                        var received_token = rese.token
                        var serverGeneratedErrors = rese.errors



                        if (response == 1) { //updated

                            $("button.close").trigger("click");
                            $("#token_edit").val(received_token);
                            vt.success(
                                `You successfully updated the partner's Deposit : ${username}.`, {
                                title: "Partner's Deposit Updated!",
                                duration: 6000,
                                closable: true,
                                focusable: true,
                                callback: () => {
                                    console.log("completed");
                                }
                            });

                            var activePage = $(".navigation_deposits li.page-item.active a").text();
                            displayDeposits(activePage);
                            clearModalInvalidFeedbacks();

                        } else if (response == 2) { //csrf error
                            $("button.close").trigger("click");
                            $("#token_edit").val(received_token);

                            vt.error(
                                `CSRF 에러 입니다. 관리자에게 문의 주시기 바랍니다.`, {
                                title: "CSRF 에러.",
                                duration: 6000,
                                closable: true,
                                focusable: true,
                                callback: () => {
                                    console.log("completed");
                                }
                            });
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
                            $("#delete_modal").addClass("d-none");
                            $("#delete_modal").removeClass("show");
                            $("#token_edit").val(received_token);

                            for (var key in serverGeneratedErrors) {
                                var msg = serverGeneratedErrors[key];

                                // console.log($(`#${key}`));
                                $(`#${key}`).addClass("invalid");
                                $(`#${key}`).next(".invalid-feedback").text(msg);
                                $(`#${key}`).next(".invalid-feedback").css("display", "block");
                            }
                        }
                    }
                })
            }
        }

        //select search option

        document.addEventListener('DOMContentLoaded', (event) => {

            displayDeposits(0);
            // clearModalInvalidFeedbacks();



        });
    </script>
</body>

</html>
