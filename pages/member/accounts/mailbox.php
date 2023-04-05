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

    @media (max-width: 991px) {

        .replyTextHolder {
            margin-bottom: 80px !important;
        }
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

    @media (max-width: 437px) {

        .replyTextHolder {
            margin-bottom: 100px !important;
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


    @media (max-width: 366px) {

        .replyTextHolder {
            margin-bottom: 120px !important;
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
//modal for displaying Mails info

$modal_title = 'Mail Info';
$modal_body = '
<div class="card-body bg-light">

    <div style="margin-left:20px">
        <h5 class="card-title" id="cardSubject"></h5>
        <div id="cardMessage"></div>
    </div>

    <p class="card-subtitle"> Date : <small id="date" style="color: black;font-weight: bold"></small>

</div>
<input type="hidden" name="mail_id" id="mail_id">
<hr>

<div id="previousReplies">

</div>

<div class="position-relative replyTextHolder row form-group" style="margin-bottom: 50px;margin-left: 2px;margin-right: 2px;">
    <label for="replyText" class="col-sm-2 col-form-label d-none">Reply</label>
    <div class="col-sm-12" style="padding-right: 0px;padding-left: 0px;">
        <div name="replyText" id="replyText" class="form-control"></div>
        <div class="invalid-feedback"></div>

    </div>
</div>
';
$modal_footer = '
<button class="btn btn-primary" id="reply_mail" onclick="replyMail(event)">Reply</button>
<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
';

$modal_size = 'xl';
includeWithVariables('./../../../includes/modals/_modal.php', array('class' => 'show_mail', 'modal_size' => $modal_size, 'modal_title' => $modal_title, 'modal_body' => $modal_body, 'modal_footer' => $modal_footer));

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
                    <?php includeWithVariables('./../../../includes/partials/_innerheader.php', array('title' => '어카운트', 'title_text' => '쪽지.', 'icon' => "folder")); ?>

                    <!-- main content -->
                    <section class="main">
                        <div class="row">

                            <div class="col-md-12">

                                <div class="main-card mb-3 card">
                                    <div class="card-header" style='height: 2.5rem;'>검색

                                    </div>
                                    <div class="d-flex filter-wrapper">
                                        <div class="row mt-2 row_filter_wrapper mb-3">
                                            <div class="input-group grp2 ml-4" style="width: unset;">
                                                <div class="input-group-prepend"><span
                                                        class="input-group-text">쪽지제목</span></div>
                                                <input placeholder="" id="filterSubject" type="text"
                                                    class="form-control shadow-none">
                                            </div>
                                            <div class="btn_wrapper" style="display: flex;align-items: center;">
                                                <button class="btn btn-primary filter_btn mr-2">검색</button>
                                                <button class="btn btn-secondary reset_btn">리셋</button>
                                            </div>
                                        </div>

                                    </div>


                                </div>


                            </div>

                        </div>
                        <div class="row">

                            <div class="col-md-12">
                                <div class="main-card mb-3 card">
                                    <div class="card-header"
                                        style='display:flex;height: 2.5rem;justify-content: space-between;'>


                                        <span>
                                            쪽지내역

                                        </span>
                                        <div class="dropdown d-inline-block mails_number_wrapper">


                                            <button type="button" aria-haspopup="true" aria-expanded="false"
                                                data-toggle="dropdown"
                                                class="mr-2 dropdown-toggle active-mails-number btn btn-outline-primary"><?= config::get("display/activeNumber"); ?></button>
                                            <div tabindex="-1" role="menu" aria-hidden="true"
                                                class="dropdown-menu mails_number_options"
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
                                        value="<?= token::generate("display_mails") ?>">

                                    <input type="hidden" name="token_seen" id="token_seen"
                                        value="<?= token::generate("mark_mails") ?>">

                                    <input type="hidden" name="token_reply" id="token_reply"
                                        value="<?= token::generate("reply_mails") ?>">

                                    <input type="hidden" name="token_display_reply" id="token_display_reply"
                                        value="<?= token::generate("display_reply_mails") ?>">


                                    <div class="table-responsive mb-3">
                                        <table
                                            class="align-middle mb-0 table table-borderless table-striped table-hover">
                                            <thead>
                                                <tr>
                                                    <th class="text-center">#</th>
                                                    <th class="text-center"><input type='checkbox' class='selectAll'
                                                            name='selectAll'>
                                                    </th>
                                                    <th class="text-center">이름 </th>
                                                    <th class="text-center">제목 </th>
                                                    <th class="text-center">받은날짜</th>


                                                </tr>
                                            </thead>
                                            <tbody class='table-body-mails'>

                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="pager ml-3">
                                        <nav class="navigation_mails" aria-label="navigation_mails">
                                        </nav>
                                    </div>
                                    <div class="notice mt-3 ml-3">



                                        <small>
                                            노트: 날짜는 GMT +09:00 기준입니다.
                                        </small>
                                    </div>
                                    <div class="d-block text-left card-footer ml-0">
                                        <a href="javascript:void(0);" class="btn-wide btn-shadow btn btn-primary"
                                            onclick=confirmMarkedMailsAsSeen()>읽음으로 표시</a>
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

    <div class="alert alert-info alert-dismissible noData-alert" role="alert"
        style="    position: fixed;top: 20%;left: 60%;z-index: 1000000;width: 30%;display:none;font-weight: bolder;">
        <button type="button" class="close close-alert" aria-label="Close"><span aria-hidden="true">×</span></button>
        No Data Selected!
    </div>

    <?php include __DIR__ . '/../../../includes/files/_scripts.php'; ?>
    <!-- Include the Quill library -->
    <script src="https://cdn.quilljs.com/1.0.0/quill.js"></script>

    <!-- delete modal  -->
    <?php includeWithVariables('./../../../includes/modals/_deleteModal.php'); ?>

    <script>
    // --- DISPLAY MAILS HISTORY ----
    function displayMails(N = 1) {

        var activePage = $(".navigation_mails li.page-item.active a").text();
        var activeNumber = $(".active-mails-number").text();
        activeNumber = activeNumber.trim();

        var filterSubject = $("#filterSubject").val()

        var token = $("#token_display").val();

        $.ajax({
            url: '/ajaxProcessus/memberAccounts/mails/displayMails.php',
            type: 'POST',
            data: {
                "display": true,
                "page": N,
                "number": activeNumber,
                filterSubject,
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
                    $(".table-body-mails").html(rese[0]);
                    $(".navigation_mails").html(rese[1]);

                    // $('input[name="selectAll"]').prop('checked', false);

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
    $(".mails_number_options button").on("click", function(event) {

        var number = event.currentTarget.textContent;
        $(".active-mails-number").text(number);

        displayMails(1, $status);
    })



    //filter_btn
    $(".filter_btn").click(function(e) {
        displayMails();
    })

    //reset_btn
    $(".reset_btn").click(function(e) {
        $("#filterSubject").val("");

        displayMails();
    })

    $(".close-alert").click(function(event) {
        $(".noData-alert").hide();
    })

    //mark as read
    //select all
    $('.selectAll:checkbox').change(function() {
        if (this.checked) {
            $(`input[name="mailSelected[]"]`).prop('checked', true);
        } else {
            $(`input[name="mailSelected[]"]`).prop('checked', false);
        }
    });

    //confirm we have selected data
    function confirmMarkedMailsAsSeen() {
        var checks = $('input[name="mailSelected[]"]:checked').map(function() {
            return $(this).attr("data-id");
        }).get() //[...];

        if (checks.length == 0) {

            $(".noData-alert").show();

            setTimeout(function() {
                $(".noData-alert").hide();

            }, 5000);

            return;
        }

        markMailsAsSeen(checks);

    }

    function markMailsAsSeen(checks) {

        var token = $("#token_seen").val();

        $.ajax({
            url: '/ajaxProcessus/memberAccounts/mails/markMailsAsSeen.php',
            type: 'POST',
            data: {
                checks,
                token
            },
            cache: false,
            timeout: 10000,

            success: function(data) {
                if (data == 'unauthorized' || data == '') {
                    window.location.href = '/pages/errors/403.php';
                    return;
                }

                data = data.split("###");

                if (data.length == 2) {
                    received_token = data[1];
                    $("#token_seen").val(received_token);
                }
                data = data[0];

                if (data == 1) {
                    vt.success(`You successfully Marked Those Mails As Read.`, {
                        title: "Mails Marked As Read!",
                        duration: 1000,
                        closable: true,
                        focusable: true,
                        callback: () => {
                            console.log("completed");
                            location.reload(); //reload for the account/data new badge update
                        }
                    });


                } else if (data == 0) {
                    vt.error(
                        `This can be a CSRF error!, if you see this error please contact our support about it.`, {
                            title: "CSRF Error",
                            duration: 1200,
                            closable: true,
                            focusable: true,
                            callback: () => {
                                console.log("completed");
                                location.reload();
                            }
                        });



                } else {
                    vt.error(`There has been an error while processing your delete request!.`, {
                        title: "Mails are not Marked As Read!",
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

    //show mails modal info
    function showMailDataInfo(event) {

        //mail data Info
        //reset fields:
        $("#date").text("");

        $("#cardSubject").text("");
        $("#cardMessage").html("");

        $("#previousReplies").html("");


        let data = event.currentTarget.getAttribute("data-values");

        //populating modal with data
        data = data.replace(new RegExp("&&xx&&", "g"), " ");
        data = JSON.parse(data);

        $("#mail_id").val(data["id"]);


        $("#date").text(data["created_at"]);

        $("#cardSubject").text(data["subject"]);
        $("#cardMessage").html(data["message"]);


        //get the previous replies

        getPreviousReplies(data["id"]);

        //write-reply section
        clearModalInvalidFeedbacks();

        quill.setContents([{
            insert: '\n'
        }]);


        //mark as seen
        markOneMailAsSeen(data["id"]);
    }

    function getPreviousReplies(id) {

        var token = $("#token_display_reply").val();

        $.ajax({
            url: '/ajaxProcessus/memberAccounts/mails/getPreviousReplies.php',
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

                rese = JSON.parse(data);

                let length = rese.length;


                if (length == 2) { //success
                    $("div#previousReplies").html(rese[0]);

                    $("#token_display_reply").val(rese[1]);
                } else if (length == 1) { //csrf error
                    $("#token_display_reply").val(rese[0]);
                } else {
                    //refresh page;
                    location.reload();
                }

            }
        })
    }

    function markOneMailAsSeen(id) {

        var token = $("#token_seen").val();

        $.ajax({
            url: '/ajaxProcessus/memberAccounts/mails/markOneMailAsSeen.php',
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

                data = data.split("###");

                if (data.length >= 2) {
                    received_token = data[1];
                    $("#token_seen").val(received_token);
                }
                dataResponse = data[0];

                if (dataResponse == 1) {
                    //success

                    //hide the new badge on the table row
                    $(`span[data-mailNewBadge='${id}']`).css("display", "none");

                    //check if need to hide account/mail new badges
                    let unSeenMails = data[2];
                    let unSeenNotifications = data[3];

                    if (unSeenMails <= 0) {
                        $(`.new-mails`).css("display", "none");

                        if (unSeenNotifications <= 0) {
                            $(`.new-notifs`).css("display", "none");
                        }
                    } else {
                        //update header mails icon number
                        $(".header-user-info .new-mails").text(unSeenMails);
                        $("#user_mailbox .new-mails").text(unSeenMails);
                    }



                } else if (dataResponse == 0) {
                    vt.error(
                        `There is a CSRF error!, if you see this error please contact our support about it. The page will be reloaded automatically!`, {
                            title: "CSRF Error",
                            duration: 1200,
                            closable: true,
                            focusable: true,
                            callback: () => {
                                console.log("completed");
                                location.reload();
                            }
                        }
                    );


                }
            }
        })
    }

    // --- SEND REPLY ---

    //INITIATE THE TEXT EDITOR
    var quill = new Quill('#replyText', {
        modules: {
            toolbar: [
                [{
                    'font': []
                }, {
                    'header': [1, 2, 3, 4, 5, 6, false]
                }],

                ['bold', 'italic', 'underline', 'strike'],
                [{
                    'color': []
                }, {
                    'background': []
                }],
                ['link', 'blockquote', 'code-block', 'image'],
                [{
                    list: 'ordered'
                }, {
                    list: 'bullet'
                }],
                [{
                    'align': []
                }],
                ['clean']
            ]
        },
        placeholder: 'Reply Message...',
        theme: 'snow'
    });



    function clearModalInvalidFeedbacks() {
        var array = ["replyText"];

        array.forEach(element => {
            let target = $(`#${element}`);

            target.next(".invalid-feedback").text("");
            target.next(".invalid-feedback").css("display", "none");
            target.removeClass("invalid");

        });
    }

    function clearModalInvalidFeedbacksOnChange() {

        quill.on('text-change', function() {
            $("#replyText").next(".invalid-feedback").text("");
            $("#replyText").next(".invalid-feedback").css("display", "none");
            $("#replyText").removeClass("invalid");
        });
    }
    clearModalInvalidFeedbacksOnChange();


    // send
    function replyMail(event) {
        event.preventDefault();

        //getting data
        var replyText = quill.getText();
        var replyTextHtml = quill.root.innerHTML;

        var mailId = $("#mail_id").val();

        var token = $("#token_reply").val();


        //validating & sanitizing data
        var constraints = {
            replyText: {
                presence: {
                    allowEmpty: false
                },
            },
        };


        var errors = validate({
            replyText,
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

            mailId = DOMPurify.sanitize(mailId, {
                SAFE_FOR_JQUERY: true
            });
            replyTextHtml = DOMPurify.sanitize(replyTextHtml, {
                SAFE_FOR_JQUERY: true
            });
            replyText = DOMPurify.sanitize(replyText, {
                SAFE_FOR_JQUERY: true
            });

            $.ajax({
                url: '/ajaxProcessus/memberAccounts/mails/replyMail.php',
                type: 'POST',
                data: {
                    "mail": replyTextHtml,
                    "mailPlain": replyText,
                    mailId,
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
                    var serverValidationErrors = rese.errors
                    // return;


                    if (response == 1) { //added


                        $("button.close").trigger("click");
                        $("#token_reply").val(received_token);
                        vt.success(
                            `You successfully Replied to this Mail.`, {
                                title: "Mail Sent!",
                                duration: 6000,
                                closable: true,
                                focusable: true,
                                callback: () => {
                                    console.log("completed");
                                }
                            });


                        displayMails();
                        clearModalInvalidFeedbacks();

                    } else if (response == 2) { //csrf error
                        $("button.close").trigger("click");
                        $("#token_reply").val(received_token);

                        vt.error(
                            `This can be a CSRF error!, if you see this error please contact our support about it.`, {
                                title: "CSRF Error",
                                duration: 6000,
                                closable: true,
                                focusable: true,
                                callback: () => {
                                    console.log("completed");
                                }
                            });
                    } else if (response == 4) { //db error
                        $("button.close").trigger("click");
                        $("#token_reply").val(received_token);


                        vt.error(
                            `We could not process your request due to an unknown error!, please try again.`, {
                                title: "Unknown error",
                                duration: 6000,
                                closable: true,
                                focusable: true,
                                callback: () => {
                                    console.log("completed");
                                }
                            });
                    } else if (response == 0) { //serverValidationErrors
                        // $("button.close").trigger("click");
                        $("#token_reply").val(received_token);

                        for (var key in serverValidationErrors) {
                            var msg = serverValidationErrors[key];

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

        displayMails(0);

    });
    </script>
</body>

</html>
