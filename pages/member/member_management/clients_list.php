<?php

require_once __DIR__ . "/../../../core/ini.php";
require_once __DIR__ . "/../../../core/inc_var.php";
include __DIR__ . '/../../../includes/partials/_authorization.php';

$db = DB::getInstance();

$partner = new user();
$partnerPtId = $partner->data()["pt_id"];

$sql = "SELECT id,username,prefix FROM clients WHERE pt_id = ? ";
$clients = $db->query($sql, ["$partnerPtId"])->results();

$options = "";

foreach ($clients as $key => $value) {

    $options .= "<option value=" . $value['id'] . " data-prefix=" . $value['prefix'] . ">" . $value["username"] . "</option>";

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
            width: 30%;
        }

        .filter_btn {
            margin-left: 20px;
        }

        .btn_action {
            width: max-content;
        }

        @media (max-width: 1500px) {
            .filter-wrapper {
                width: 40%;
            }
        }

        @media (max-width: 1070px) {
            .filter-wrapper {
                width: 60%;
            }
        }

        @media (max-width: 650px) {
            .filter-wrapper {
                width: 90%;
            }
        }

        @media (max-width: 540px) {
            .filter-wrapper {
                margin-top: 20px;
                margin-bottom: 20px;
                margin-left: 5px;
                margin-right: 5px;
                width: 90%;
            }
        }

        @media (max-width: 380px) {
            .filter-wrapper {
                width: 94%;
            }
        }

        .invalid {
            border-color: #d92550;
            padding-right: 2.25rem;
            background-repeat: no-repeat;
            background-position: center right calc(2.25rem / 4);
            background-size: calc(2.25rem / 2) calc(2.25rem / 2);
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='%23dc3545' viewBox='-2 -2 7 7'%3e%3cpath stroke='%23d9534f' d='M0 0l3 3m0-3L0 3'/%3e%3ccircle r='.5'/%3e%3ccircle cx='3' r='.5'/%3e%3ccircle cy='3' r='.5'/%3e%3ccircle cx='3' cy='3' r='.5'/%3e%3c/svg%3E");
        }

        .valid {
            border-color: #3ac47d;
            padding-right: 2.25rem;
            background-repeat: no-repeat;
            background-position: center right calc(2.25rem / 4);
            background-size: calc(2.25rem / 2) calc(2.25rem / 2);
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 8 8'%3e%3cpath fill='%2328a745' d='M2.3 6.73L.6 4.53c-.4-1.04.46-1.4 1.1-.8l1.1 1.4 3.4-3.8c.6-.63 1.6-.27 1.2.7l-4 4.6c-.43.5-.8.4-1.1.1z'/%3e%3c/svg%3e");

        }

        .status_ {
            border: none;
            background: transparent;
            margin: 0;
            padding: 0;
            cursor: pointer;
        }

        @media (max-width: 510px) {
            .card-header2 {
                flex-direction: column;
                height: 5.4rem !important;
            }

            .filter_btn {
                margin-left: 5px !important;

            }
        }

        @media (max-width: 410px) {
            .card-header2 {
                height: 6.4rem !important;
            }

            .status_number_wrapper {
                flex-direction: column;

            }
        }

    </style>
</head>

<?php include __DIR__ . '/../../../includes/partials/_flash_msg.php'; ?>
<?php
$modal_title = '신규 고객 생성';
$modal_body = '<form class="">
<div class="position-relative row form-group"><label for="username" class="col-sm-2 col-form-label">아이디</label>
    <div class="col-sm-10"><input name="username" id="username" placeholder="아이디" type="text" class="form-control shadow-none">
                        <div class="invalid-feedback"></div>
    </div>
</div>
<div class="position-relative row form-group password"><label for="password" class="col-sm-2 col-form-label">비밀번호</label>
    <div class="col-sm-10"><input name="password" id="password" placeholder="비밀번호" type="text" class="form-control shadow-none">
                        <div class="invalid-feedback"></div>
    </div>
</div>
<div class="position-relative row form-group"><label for="name" class="col-sm-2 col-form-label"> 프리픽스 : 아이디 앞에 붙는 고객별 고유 식별단어입니다.</label>
    <div class="col-sm-10"><input name="name" id="name" placeholder="고객명" type="text" class="form-control shadow-none">
    <div class="invalid-feedback"></div>
    </div>
</div>
<div class="position-relative row form-group"><label for="client_prefix" class="col-sm-2 col-form-label">엔드포인트 : https:// </label>
    <div class="col-sm-10"><input name="client_prefix" id="client_prefix" placeholder="프리픽스" type="text" class="form-control shadow-none">
                                    <div class="invalid-feedback"></div>
    </div>
</div>
<div class="position-relative row form-group end_point"><label for="end_point" class="col-sm-2 col-form-label">엔드포인트</label>
    <div class="col-sm-10"><input name="end_point" id="end_point" placeholder="엔드포인트" type="text" class="form-control shadow-none">
                <div class="invalid-feedback"></div>
    </div>
</div>

<div  class="position-relative row form-group partnerRate" ><label for="partnerRate" class="col-sm-2 col-form-label">요율 (%)</label>
    <div class="col-sm-10"><input name="partnerRate" id="partnerRate" placeholder="요율" type="text" class="form-control shadow-none">
    <div class="invalid-feedback"></div>
    </div>
</div>


<input type="hidden" name="token" id="token" value="' . token::generate() . '">
<input type="hidden" name="clientId" id="client_id">

	
<fieldset class="position-relative row form-group">
    <div class="legend col-sm-2">    <legend class="col-form-label col-sm-2" style="max-width: 50% !important;">상태</legend>    </div>
    <div class="col-sm-10">
        <div class="position-relative form-check"><label class="form-check-label"><input name="status" class="status" type="radio" class="form-check-input" value="0"><div class="ml-2 mb-2 mr-2 badge badge-pill badge-warning" style="color:white !important;">대기</div></label></div>
        <div class="position-relative form-check"><label class="form-check-label"><input name="status" class="status" type="radio" class="form-check-input" checked value="1"><div class="ml-2 mb-2 mr-2 badge badge-pill badge-success">정상</div></label></div>
        <div class="position-relative form-check "><label class="form-check-label"><input name="status" class="status" type="radio" class="form-check-input" value="3"><div class="ml-2 mb-2 mr-2 badge badge-pill badge-danger">차단</div></label></div>
    </div>
</fieldset>

<div class="position-relative row form-group skinSelect"><label style="font-weight:bold" for="skinSelect" class="col-sm-2 col-form-label">에볼루션 스킨 선택</label>
    <div class="col-sm-10">
        <select type="select" id="skinSelect" name="skinSelect"
            class="custom-select">
            <option value="1" selected = "selected">1 [1,000,000₩]</option>
            <option value="2">2 [3,000,000₩]</option>
            <option value="3">3 [5,000,000₩]</option>
            <option value="4">4 [10,000,000₩]</option>
            <option value="5">5 [20,000,000₩]</option>
        </select>
        <div class="invalid-feedback"></div>
    </div>
</div>

<div class="position-relative row form-check">
    <div class="col-sm-10 text-center text-sm-left">
        <button class="btn btn-secondary" id="add_client" onclick="addClient(event)">저장</button>
    </div>
    <div class="col-sm-10 text-center text-sm-left">
    <button class="btn btn-secondary" id="edit_client" onclick="editClient(event)">수정</button>
</div>
</div>
</form> ';
$modal_footer = '<button type="button" class="btn btn-secondary d-none" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary d-none">Save</button> ';

$modal_size = 'xl';
includeWithVariables('./../../../includes/modals/_modal.php', array('class' => 'add_client', 'modal_size' => $modal_size, 'modal_title' => $modal_title, 'modal_body' => $modal_body, 'modal_footer' => $modal_footer));


$modal_title = '포인트(알) 전송';
$modal_body = '<form class="">


<div class="position-relative row form-group clientSelectAdd"><label for="Client" class="col-sm-2 col-form-label">고객사</label>
    <div class="col-sm-10">
        <select type="select" id="clientSelectAdd" name="clientSelectAdd"
            class="custom-select">
            <option value="">고객사 선택</option>
            ' . $options . '
        </select>
        <div class="invalid-feedback"></div>
    </div>
</div>

<div class="position-relative row form-group"><label for="deposit" id="depositLabel" class="col-sm-2 col-form-label">금액 (원)</label>
    <div class="col-sm-10"><input name="depositAmount" id="depositAmount" placeholder="deposit amount..." type="text" class="form-control shadow-none">
                        <div class="invalid-feedback"></div>
    </div>
</div>



<input type="hidden" name="deposit_id" id="deposit_id">



<div class="position-relative row form-check">
    <div class="col-sm-10 text-center text-sm-left">
        <button class="btn btn-secondary" id="add_deposit" onclick="addDeposit(event)">지급</button>
    </div>
    
    <div class="col-sm-10 text-center text-sm-left">
        <button class="btn btn-secondary" id="deduct" onclick="deductAmount(event)">차감</button>
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
                    <?php includeWithVariables('./../../../includes/partials/_innerheader.php', array('title' => '회원관리', 'title_text' => '고객사 리스트', 'icon' => "users")); ?>

                    <!-- main content -->
                    <section class="main">
                        <div class="row">

                            <div class="col-md-12">

                                <div class="main-card mb-3 card">
                                    <div class="card-header" style='height: 2.5rem;'>필터

                                    </div>
                                    <div class="filter-wrapper">
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text">아이디</span>
                                            </div>
                                            <input placeholder="" type="text" class="form-control shadow-none"
                                                id='client_name_filtered'>
                                            <button class="btn btn-primary filter_btn filter_clients">검색</button>
                                            <button
                                                class="btn btn-secondary filter_btn filter_clients_reset">초기화</button>

                                        </div>
                                    </div>


                                </div>


                            </div>

                        </div>
                        <div class="row">

                            <div class="col-md-12">
                                <div class="main-card mb-3 card">
                                    <div class="card-header card-header2"
                                        style='display: flex;height: 2.5rem;justify-content: space-between;'>



                                        <span>
                                            고객사 리스트

                                        </span>
                                        <div class="d-flex status_number_wrapper">
                                            <div class="d-flex">
                                                <button id="status_active" class='status_ align-self-end'>
                                                    <div class="mb-2 mr-2 badge badge-pill badge-success">
                                                        정상</div>
                                                </button>
                                                <button id="status_pending" class='status_ align-self-end'>
                                                    <div class="mb-2 mr-2 badge badge-pill badge-warning"
                                                        style="color:white !important;">대기</div>
                                                </button>
                                                <button id="status_blocked" class='status_ align-self-end'>
                                                    <div class="mb-2 mr-2 badge badge-pill badge-danger">
                                                        차단</div>
                                                </button>
                                                <button id="status_all" class='status_ active align-self-end'>
                                                    <div class="mb-2 mr-2 badge badge-pill badge-focus">
                                                        전체</div>
                                                </button>



                                            </div>
                                            <div class="dropdown d-inline-block">
                                                <button type="button" aria-haspopup="true" aria-expanded="false"
                                                    data-toggle="dropdown"
                                                    class="mr-2 dropdown-toggle active-clients-number btn btn-outline-primary"><?= config::get("display/activeNumber"); ?></button>
                                                <div tabindex="-1" role="menu" aria-hidden="true"
                                                    class="dropdown-menu clients_number_options"
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
                                        <button class="mb-2 mt-2 ml-2 btn btn-success active" data-toggle="modal"
                                            data-target=".add_client" onclick=hideEditableInputsAndShow()>신규 고객사
                                            생성</button>

                                        <button class="mb-2 mt-2 ml-2 btn btn-light active" data-toggle="modal"
                                            data-target=".add_deposit" onclick=hideEditableInputsAndShow2()>포인트 지급 <span
                                                style="font-weight: bolder;color: black;">+</span></button>

                                        <button class="mb-2 mt-2 ml-2 btn btn-light active" data-toggle="modal"
                                            data-target=".add_deposit" onclick=hideEditableInputsAndShowDeduct()>포인트 차감
                                            <span style="font-weight: bolder;color: black;">-</span> </button>
                                    </div>
                                    <div class="table-responsive mb-3">
                                        <input type="hidden" name="token_display" id="token_display"
                                            value="<?= token::generate("display") ?>">

                                        <input type="hidden" name="token_delete" id="token_delete"
                                            value="<?= token::generate("delete") ?>">

                                        <input type="hidden" name="token_make" id="token_make"
                                            value="<?= token::generate("make_deposit") ?>">

                                        <input type="hidden" name="token_deduct" id="token_deduct"
                                            value="<?= token::generate("deduct") ?>">

                                        <table
                                            class="align-middle mb-0 table table-borderless table-striped table-hover">
                                            <thead>
                                                <tr>
                                                    <th class="text-center">#</th>
                                                    <th class="text-center">아이디</th>
                                                    <th class="text-center">고객명</th>
                                                    <th class="text-center">프리픽스 </th>
                                                    <th class="text-center"> 포인트(알) </th>
                                                    <th class="text-center">요율 </th>
                                                    <th class="text-center">상위파트너 </th>
                                                    <th class="text-center">상태 </th>
                                                    <th class="text-center">등록일 </th>
                                                    <th class="text-center">요율수정</th>


                                                </tr>
                                            </thead>
                                            <tbody class='table-body-clients'>

                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="pager ml-3">
                                        <nav class="navigation_clients" aria-label="navigation_clients">
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
        function clearModalInvalidFeedbacks() {
            var array = ["username", "password", "name", "client_prefix", "end_point", "partnerRate"];

            array.forEach(element => {
                let target = $(`#${element}`);

                target.next(".invalid-feedback").text("");
                target.next(".invalid-feedback").css("display", "none");
                target.removeClass("invalid");

            });
        }

        function clearModalInvalidFeedbacksOnKeyUp() {

            $("#username, #password, #name, #client_prefix, #end_point, #partnerRate").keyup(function (e) {

                $(this).next(".invalid-feedback").text("");
                $(this).next(".invalid-feedback").css("display", "none");
                $(this).removeClass("invalid");
            })
        }

        clearModalInvalidFeedbacksOnKeyUp();


        function hideEditableInputsAndShow() {

            clearModalInvalidFeedbacks();


            $(".password").show();
            $(".end_point").show();
            // $(".whitelist_ips").show();
            //$("fieldset").show();




            //reset
            $("#username").val('');
            $("#password").val('');
            $("#name").val('');
            $("#client_prefix").val('');
            $("#end_point").val('');
            $("#partnerRate").val('');
            $("#skinSelect").val('1');


            // $("#whitelist_ips").val('');
            $("input[name=status][value=1]").prop('checked', true); //active

            $("button#add_client").show();
            $("button#edit_client").hide();

            $("#exampleModalLongTitle").text("고객 추가");

            $("#username").prop('disabled', false);
            $("#name").prop('disabled', false);
            $("#client_prefix").prop('disabled', false);


        }

        function showEditableInputsAndHide() {

            clearModalInvalidFeedbacks();

            $(".password").hide();
            $(".end_point").hide();
            // $(".whitelist_ips").hide();
            //$("fieldset").hide();




            $("button#add_client").hide();
            $("button#edit_client").show();

            $("#exampleModalLongTitle").text("고객사 요율 수정");


            $("#username").prop('disabled', true);
            $("#name").prop('disabled', true);
            $("#client_prefix").prop('disabled', true);


        }

        function clearModalInvalidFeedbacks2() {
            var array = ["clientSelectAdd", "depositAmount"];

            array.forEach(element => {
                let target = $(`#${element}`);

                target.next(".invalid-feedback").text("");
                target.next(".invalid-feedback").css("display", "none");
                target.removeClass("invalid");
            });
        }


        function clearModalInvalidFeedbacksOnKeyUp2() {

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
        clearModalInvalidFeedbacksOnKeyUp2();


        function hideEditableInputsAndShow2() {

            clearModalInvalidFeedbacks2();

            //reset
            $("#clientSelectAdd").val('');
            $("#clientSelectAdd").select2().select2('val', '');
            $("#depositAmount").val('0.00');


            $("button#add_deposit").show();
            $("button#edit_deposit").hide();
            $("button#deduct").hide();

            $("#exampleModalLongTitle").text(" 포인트(알) 전송");
            $("#depositLabel").text("금액 (원)");


            $("#clientSelectAdd").prop('disabled', false);


        }

        function hideEditableInputsAndShowDeduct() {

            clearModalInvalidFeedbacks2();

            //reset
            // $("#clientSelectAdd").val('');
            $("#clientSelectAdd").select2().select2('val', '');
            $("#depositAmount").val('0.00');


            $("button#add_deposit").hide();
            $("button#edit_deposit").hide();
            $("button#deduct").show();


            $("#exampleModalLongTitle").text("포인트(알) 차감");
            $("#depositLabel").text("금액 (원)test");

            $("#clientSelectAdd").prop('disabled', false);


        }



        function ValidateIPaddress(ipAddress) {

            let ipv46_regex =
                /(?:^(?:25[0-5]|2[0-4]\d|1\d\d|[1-9]\d|\d)(?:\.(?:25[0-5]|2[0-4]\d|1\d\d|[1-9]\d|\d)){3}$)|(?:^(?:(?:[a-fA-F\d]{1,4}:){7}(?:[a-fA-F\d]{1,4}|:)|(?:[a-fA-F\d]{1,4}:){6}(?:(?:25[0-5]|2[0-4]\d|1\d\d|[1-9]\d|\d)(?:\\.(?:25[0-5]|2[0-4]\d|1\d\d|[1-9]\d|\d)){3}|:[a-fA-F\d]{1,4}|:)|(?:[a-fA-F\d]{1,4}:){5}(?::(?:25[0-5]|2[0-4]\d|1\d\d|[1-9]\d|\d)(?:\\.(?:25[0-5]|2[0-4]\d|1\d\d|[1-9]\d|\d)){3}|(?::[a-fA-F\d]{1,4}){1,2}|:)|(?:[a-fA-F\d]{1,4}:){4}(?:(?::[a-fA-F\d]{1,4}){0,1}:(?:25[0-5]|2[0-4]\d|1\d\d|[1-9]\d|\d)(?:\\.(?:25[0-5]|2[0-4]\d|1\d\d|[1-9]\d|\d)){3}|(?::[a-fA-F\d]{1,4}){1,3}|:)|(?:[a-fA-F\d]{1,4}:){3}(?:(?::[a-fA-F\d]{1,4}){0,2}:(?:25[0-5]|2[0-4]\d|1\d\d|[1-9]\d|\d)(?:\\.(?:25[0-5]|2[0-4]\d|1\d\d|[1-9]\d|\d)){3}|(?::[a-fA-F\d]{1,4}){1,4}|:)|(?:[a-fA-F\d]{1,4}:){2}(?:(?::[a-fA-F\d]{1,4}){0,3}:(?:25[0-5]|2[0-4]\d|1\d\d|[1-9]\d|\d)(?:\\.(?:25[0-5]|2[0-4]\d|1\d\d|[1-9]\d|\d)){3}|(?::[a-fA-F\d]{1,4}){1,5}|:)|(?:[a-fA-F\d]{1,4}:){1}(?:(?::[a-fA-F\d]{1,4}){0,4}:(?:25[0-5]|2[0-4]\d|1\d\d|[1-9]\d|\d)(?:\\.(?:25[0-5]|2[0-4]\d|1\d\d|[1-9]\d|\d)){3}|(?::[a-fA-F\d]{1,4}){1,6}|:)|(?::(?:(?::[a-fA-F\d]{1,4}){0,5}:(?:25[0-5]|2[0-4]\d|1\d\d|[1-9]\d|\d)(?:\\.(?:25[0-5]|2[0-4]\d|1\d\d|[1-9]\d|\d)){3}|(?::[a-fA-F\d]{1,4}){1,7}|:)))(?:%[0-9a-zA-Z]{1,})?$)/gm;

            if (ipv46_regex.test(ipAddress)) {
                return (true)
            }
            return (false)
        }

        function number_format(number, decimals, dec_point, thousands_sep) {
            // Strip all characters but numerical ones.
            number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
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


        $("#partnerRate").focusout(function (event) {
            var number = $(this).val();

            if (number == '') {
                $(this).val("0.00");
            } else {
                var d = number_format(number, 2, ".", "");

                $(this).val(d);
            }

        })

        $("#depositAmount").focusout(function (event) {
            var number = $(this).val();

            if (number == '') {
                $(this).val("0.00");

            } else {
                var d = number_format(number, 2, ".", ",");

                $(this).val(d);
            }

        })




        // --- DISPLAY CLIENTS ----
        function displayClients(N = 1, status = '') {

            var activePage = $(".navigation_clients li.page-item.active a").text();
            var activeNumber = $(".active-clients-number").text();
            activeNumber = activeNumber.trim();

            var text = $("#client_name_filtered").val();

            var token = $("#token_display").val();

            $.ajax({
                url: '/ajaxProcessus/memberManagement/displayClients.php',
                type: 'POST',
                data: {
                    "display": true,
                    "page": N,
                    "number": activeNumber,
                    "nameFilter": text,
                    "status": status,
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
                        $(".table-body-clients").html(rese[0]);
                        $(".navigation_clients").html(rese[1]);

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
        $(".clients_number_options button").on("click", function (event) {

            var number = event.currentTarget.textContent;
            $(".active-clients-number").text(number);

            let id = $(".status_.active").attr("id");

            let status = '';

            if (id == "status_active") {
                status = 1;
            } else if (id == "status_pending") {
                status = 0;
            } else if (id == "status_blocked") {
                status = 3;
            }

            displayClients(1, status);
        })

        function resetStatusFilter() {
            $(".status_").css("opacity", 1);
            $(".status_").removeClass("active");
            $("#status_all").addClass("active");
        }

        //USERNAME FILTER
        $(".filter_clients").on("click", function (event) {
            resetStatusFilter();

            displayClients(1);

        })

        //RESET FILTERS
        $(".filter_clients_reset").on("click", function (event) {
            resetStatusFilter();

            $("#client_name_filtered").val('');


            displayClients(1);

        })



        //status filter
        $(".status_").click(function (event) {
            let status = '';
            let id = $(event.currentTarget).attr("id");
            if (id == "status_all") {
                $(".status_").css("opacity", 1);

                $(".status_").removeClass("active");
                $(this).addClass("active");

            } else {
                $(".status_").css("opacity", 0.2);
                $(this).css("opacity", 1);

                if (id == "status_active") {
                    status = 1;
                } else if (id == "status_pending") {
                    status = 0;
                } else {
                    status = 3;
                }


                $(".status_").removeClass("active");
                $(this).addClass("active");
            }

            displayClients(1, status);

        })


        // ---ADD---
        function addClient(event) {
            event.preventDefault();

            //getting data
            var username = $("#username").val().trim();
            var password = $("#password").val().trim();
            var name = $("#name").val().trim();
            var client_prefix = $("#client_prefix").val().trim();
            var end_point = $("#end_point").val().trim();
            // var whitelist_ips = $("#whitelist_ips").val().trim();
            var status = $(".status:checked").val();
            var token = $("#token").val();
            var partnerRate = $("#partnerRate").val().trim();

            var skinSelect = $("#skinSelect").val();

            // console.log(status);

            //validating & sanitazing data

            var constraints = {
                username: {
                    presence: true,
                    length: {
                        minimum: 1,
                        maximum: 30,
                        message: "^1~30자를 입력하세요.."
                    },
                },
                password: {
                    presence: true,
                    length: {
                        minimum: 8,
                        maximum: 30,
                        message: "^8~30자를 입력하세요."
                    },
                },
                name: {
                    presence: true,
                    length: {
                        minimum: 1,
                        maximum: 20,
                        message: "^ 1~20자를 입력하세요."
                    },
                },
                client_prefix: {
                    presence: true,
                    // length: {
                    //     minimum: 1,
                    //     maximum: 8,
                    //     message: "Field is required and should be {1 to 8} characters long."
                    // },
                    format: {
                        pattern: "^[A-Za-z][A-Za-z0-9]{1,8}$",
                        message: "^1~8자를 입력하세요. 영어 + 숫자를 조합할 수 있습니다."
                    }
                },
                end_point: {
                    presence: true,
                    length: {
                        minimum: 1,
                        maximum: 100,
                        message: "^엔드포인트는 필수 항목이며 1~100자를 입력하세요. ",
                    },
                    url: {
                        allowDataUrl: false,
                        message: '^"http(s)://" 형태로 입력해 주세요. '

                    }
                },
                partnerRate: {
                    presence: true,
                    numericality: {
                        greaterThanOrEqualTo: 0,
                        lessThanOrEqualTo: 100,
                        message: "^"
                    },
                    format: {
                        pattern: "^[0-9]{1,2}\.[0-9]{2}$",
                        message: "^본인의 요율보다 같거나 높게 입력해 주세요."
                    }
                },


            };


            //validate whitelist ips
            var validated_ips = 1;
            var invalidated_ips_value = '';
            // whitelist_ips = whitelist_ips.trim();

            // if (whitelist_ips != '') {
            //     var array = whitelist_ips.split(",");
            //     for (let index = 0; index < array.length; index++) {
            //         let element = array[index];
            //         element = element.trim();

            //         if (!ValidateIPaddress(element)) {
            //             validated_ips = 0;
            //             invalidated_ips_value = element;
            //             break;
            //         }
            //     }
            // }

            var errors = validate({
                username,
                name,
                password,
                client_prefix,
                end_point,
                partnerRate
            }, constraints);
            if (typeof errors != undefined && errors != undefined) {

                // console.log(errors);
                for (var key in errors) {
                    $(`#${key}`).addClass("invalid");
                    $(`#${key}`).next(".invalid-feedback").text(errors[key]);
                    $(`#${key}`).next(".invalid-feedback").css("display", "block");
                }
            } else if (validated_ips == 0) {
                var error_text =
                    `This field contains some invalidated ip addresses values: "${invalidated_ips_value}". Only ipv4/ipv6 values are allowed`;
                $(`#whitelist_ips`).addClass("invalid");
                $(`#whitelist_ips`).next(".invalid-feedback").text(error_text);
                $(`#whitelist_ips`).next(".invalid-feedback").css("display", "block");
            } else {
                //sanitize

                username = DOMPurify.sanitize(username, {
                    SAFE_FOR_JQUERY: true
                });
                password = DOMPurify.sanitize(password, {
                    SAFE_FOR_JQUERY: true
                });
                client_prefix = DOMPurify.sanitize(client_prefix, {
                    SAFE_FOR_JQUERY: true
                });
                end_point = DOMPurify.sanitize(end_point, {
                    SAFE_FOR_JQUERY: true
                });
                name = DOMPurify.sanitize(name, {
                    SAFE_FOR_JQUERY: true
                });
                // whitelist_ips = DOMPurify.sanitize(whitelist_ips, {
                //     SAFE_FOR_JQUERY: true
                // });
                partnerRate = DOMPurify.sanitize(partnerRate, {
                    SAFE_FOR_JQUERY: true
                });

                $.ajax({
                    url: '/ajaxProcessus/memberManagement/addClient.php',
                    type: 'POST',
                    data: {
                        username,
                        password,
                        "prefix": client_prefix,
                        end_point,
                        name,
                        // whitelist_ips,
                        status,
                        token,
                        partnerRate,
                        skinSelect
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
                            $("#token").val(received_token);
                            vt.success(`신규 고객사 ${username} 가 추가 완료 되었습니다.`, {
                                title: "신규 고객사 추가 완료!",
                                duration: 6000,
                                closable: true,
                                focusable: true,
                                callback: () => {
                                    console.log("completed");
                                }
                            });

                            var activePage = $(".navigation_clients li.page-item.active a").text();
                            displayClients(activePage);
                            clearModalInvalidFeedbacks();

                            resetStatusFilter();

                        } else if (response == 2) { //csrf error
                            $("button.close").trigger("click");
                            $("#token").val(received_token);

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
                            $("#token").val(received_token);


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
                        } else if (response == 0) { //serverGeneratedErrors error
                            // $("button.close").trigger("click");
                            $("#token").val(received_token);

                            for (var key in serverGeneratedErrors) {
                                var msg = serverGeneratedErrors[key];
                                if (key == "prefix") {
                                    key = "client_prefix";
                                }
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

        // ---ADD---
        function addDeposit(event) {
            event.preventDefault();

            //getting data
            var client = $("#clientSelectAdd").val();
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
                    url: '/ajaxProcessus/depositWithdraw/makeDeposit.php',
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
                            vt.success(`${client} 충전 완료 되었습니다.`, {
                                title: "충전 완료!",
                                duration: 6000,
                                closable: true,
                                focusable: true,
                                callback: () => {
                                    console.log("completed");
                                }
                            });

                            var activePage = $(".navigation_clients li.page-item.active a").text();
                            displayClients(activePage);
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
                            location.reload();
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
                    url: '/ajaxProcessus/depositWithdraw/deduct.php',
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
                            vt.success(`${client} 차감 완료 되었습니다.`, {
                                title: "차감 완료!",
                                duration: 6000,
                                closable: true,
                                focusable: true,
                                callback: () => {
                                    console.log("completed");
                                }
                            });

                            var activePage = $(".navigation_clients li.page-item.active a").text();
                            displayClients(activePage);
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
                            location.reload();
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



        // ----EDIT----
        function showClientData(event) {
            showEditableInputsAndHide();

            let data = event.currentTarget.getAttribute("data-values");

            //populating modal with data

            data = data.replace(new RegExp("&&xx&&", "g"), " ");

            data = JSON.parse(data);


            $("#partnerRate").val(data["rate"]);

            $("#username").val(data["username"]);
            $("#name").val(data["name"]);
            $("#client_prefix").val(data["prefix"]);

            $("#client_id").val(data["id"]);

            $("input[name=status][value=" + data["status"] + "]").prop('checked', true);

            $("#skinSelect").val(data["spadeEvoSkin"]);

        }

        function editClient(event) {
            event.preventDefault();

            //getting data
            var partnerRate = $("#partnerRate").val().trim();
            var username = $("#username").val().trim();
            var status = $(".status:checked").val();



            var id = $("#client_id").val();

            var token = $("#token").val();

            var skinSelect = $("#skinSelect").val().trim();

            // console.log(status);

            //validating & sanitizing data

            var constraints = {
                partnerRate: {
                    presence: true,
                    numericality: {
                        greaterThanOrEqualTo: 0,
                        lessThanOrEqualTo: 100,
                    },
                    format: {
                        pattern: "^[0-9]{1,2}\.[0-9]{2}$",
                        message: "Is Not a valid commission number. MUST be a DECIMAL number from 0-99. Example : 45.30."
                    }
                },
            };




            var errors = validate({
                partnerRate
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

                partnerRate = DOMPurify.sanitize(partnerRate, {
                    SAFE_FOR_JQUERY: true
                });
                token = DOMPurify.sanitize(token, {
                    SAFE_FOR_JQUERY: true
                });
                id = DOMPurify.sanitize(id, {
                    SAFE_FOR_JQUERY: true
                });
                username = DOMPurify.sanitize(username, {
                    SAFE_FOR_JQUERY: true
                });
                status = DOMPurify.sanitize(status, {
                    SAFE_FOR_JQUERY: true
                });

                $.ajax({
                    url: '/ajaxProcessus/memberManagement/editClient.php',
                    type: 'POST',
                    data: {
                        id,
                        token,
                        partnerRate,
                        status,
                        skinSelect
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

                        if (response == 1) { //updated

                            $("button.close").trigger("click");
                            $("#token").val(received_token);
                            vt.success(`'${username}' 의 요율 수정이 완료 되었습니다.`, {
                                title: "요율 수정 완료!",
                                duration: 6000,
                                closable: true,
                                focusable: true,
                                callback: () => {
                                    console.log("completed");
                                }
                            });

                            var activePage = $(".navigation_clients li.page-item.active a").text();
                            displayClients(activePage);
                            clearModalInvalidFeedbacks();

                            resetStatusFilter();

                        } else if (response == 2) { //csrf error
                            $("button.close").trigger("click");
                            $("#token").val(received_token);

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
                            $("#token").val(received_token);


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
                            $("#token").val(received_token);

                            for (var key in serverGeneratedErrors) {
                                var msg = serverGeneratedErrors[key];

                                $(`#${key}`).addClass("invalid");
                                $(`#${key}`).next(".invalid-feedback").text(msg);
                                $(`#${key}`).next(".invalid-feedback").css("display", "block");
                            }
                        }
                    }
                })
            }
        }

        document.addEventListener('DOMContentLoaded', (event) => {

            displayClients(0);
            // clearModalInvalidFeedbacks();



        });
    </script>


</body>

</html>
