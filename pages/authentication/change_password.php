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
        WA Solution
    </title>
    <meta name="viewport"
        content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, shrink-to-fit=no" />
    <meta name="msapplication-tap-highlight" content="no" />


    <?php include __DIR__ . '/../../includes/files/_stylesheets.php'; ?>
    <link rel="stylesheet" href="/assets/css/password.css?v=1.06">

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
        background-position: center right calc(8.25rem / 4) !important;
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
                    <?php includeWithVariables('./../../includes/partials/_innerheader.php', array('title' => '보안설정', 'title_text' => '비밀번호 변경', 'icon' => "safe")); ?>

                    <!-- main content -->
                    <section class="main">
                        <div class="row">

                            <div class="col-md-12">

                                <div class="main-card mb-3 card">
                                    <div class="card-header" style='height: 2.5rem;'>비밀번호 변경

                                    </div>
                                    <div class="section section--fill">
                                        <input type="hidden" name="token_edit" id="token_edit"
                                            value="<?= token::generate("edit_password") ?>">
                                        <!-- [START PASSWORD] -->
                                        <label id="login-password-1"
                                            class="old_password login__field login__field--password login__field--fill ">
                                            <input class="login__input" id="oldPassword" type="password"
                                                placeholder="현재 비밀번호" autocomplete="new-password" />
                                            <!-- [START TOGGLE] -->
                                            <button type="button" class="login__trailing">
                                                <i class="fa fa-eye"></i>
                                            </button>
                                            <!-- [END TOGGLE] -->
                                        </label>
                                        <!-- [END PASSWORD] -->

                                        <!-- [START PASSWORD] -->
                                        <label id="login-password-2"
                                            class="new_password login__field login__field--password login__field--fill ">
                                            <input class="login__input" id="newPassword" type="password"
                                                placeholder="신규 비밀번호" autocomplete="new-password" />
                                            <!-- [START TOGGLE] -->
                                            <button type="button" class="login__trailing">
                                                <i class="fa fa-eye"></i>
                                            </button>
                                            <!-- [END TOGGLE] -->
                                        </label>
                                        <!-- [END PASSWORD] -->

                                        <!-- [START PASSWORD] -->
                                        <label id="login-password-3"
                                            class="confirm_Password login__field login__field--password login__field--fill ">
                                            <input class="login__input" id="confirmPassword" type="password"
                                                placeholder="신규 비밀번호 재입력" autocomplete="new-password" />
                                            <!-- [START TOGGLE] -->
                                            <button type="button" class="login__trailing">
                                                <i class="fa fa-eye"></i>
                                            </button>
                                            <!-- [END TOGGLE] -->
                                        </label>
                                        <!-- [END PASSWORD] -->
                                        <div class="invalid-feedback oldPassword" style="text-align: center;"></div>
                                        <div class="invalid-feedback newPassword" style="text-align: center;"></div>
                                        <div class="invalid-feedback confirmPassword" style="text-align: center;"></div>

                                        <button class="mb-2 mt-2 btn btn-primary" style="border-radius: 5px;"
                                            onclick="confirmEditPassword(event)">Change</button>
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
    <script src="https://cdn.jsdelivr.net/gh/miko-github/miko-github@v1.1.0/codepen.js"></script>

    <!-- delete modal  -->
    <?php includeWithVariables('./../../includes/modals/_deleteModal.php'); ?>


    <script>
    function clearModalInvalidFeedbacks() {
        var array = ["newPassword", "confirmPassword", "oldPassword"];

        array.forEach(element => {
            let target = $(`#${element}`);

            target.val("");

        });
    }

    function clearModalInvalidFeedbacksOnKeyUp() {

        $("#newPassword, #confirmPassword, #oldPassword").keyup(function(e) {

            var key = $(this).attr('id');

            $(`.invalid-feedback.${key}`).text("");
            $(`.invalid-feedback.${key}`).css("display", "none");
            $(this).parent().removeClass("invalid");


        })
    }

    clearModalInvalidFeedbacksOnKeyUp();


    //CONFIRM EDIT PASSWORD
    function confirmEditPassword(e) {
        e.preventDefault();

        $("#delete_modal button.btn_confirmed_action").hide();
        $("#delete_modal button.edit_password").show();

        $("#delete_modal #text").text("Do you really want to Edit your Password?.");

        $(".modal-confirm .icon-box").css("border", "0px solid #3ac47d");
        $(".modal-confirm .icon-box i").css("color", "#3ac47d");
        $(".modal-confirm .btn-danger").css("background", "#3ac47d");
        $(".modal-confirm .icon-box i").attr("class", "pe-7s-check");

        $("#delete_modal").addClass("show");
        $("#delete_modal").removeClass("d-none");

    }

    function editPassword(event) {
        event.preventDefault();

        //getting data
        var oldPassword = $("#oldPassword").val().trim();
        var newPassword = $("#newPassword").val().trim();
        var confirmPassword = $("#confirmPassword").val().trim();

        var token = $("#token_edit").val();

        // console.log(status);

        //validating & sanitazing data
        var constraints = {
            newPassword: {
                presence: true,
                length: {
                    minimum: 8,
                    maximum: 30,
                    message: "^8~30자를 입력하세요."
                },
            },
            confirmPassword: {
                presence: true,
                equality: {
                    attribute: "newPassword",
                    message: "^Passwords are not matching."
                },
            },
        };


        var errors = validate({
            newPassword,
            confirmPassword,
        }, constraints);

        if (typeof errors != undefined && errors != undefined) {

            // console.log(errors);
            for (var key in errors) {
                $(`#${key}`).parent().addClass("invalid");
                $(`.invalid-feedback.${key}`).text(errors[key]);
                $(`.invalid-feedback.${key}`).css("display", "block");
            }
            $("button.close").trigger("click");

        } else {
            //sanitize

            oldPassword = DOMPurify.sanitize(oldPassword, {
                SAFE_FOR_JQUERY: true
            });
            newPassword = DOMPurify.sanitize(newPassword, {
                SAFE_FOR_JQUERY: true
            });
            confirmPassword = DOMPurify.sanitize(confirmPassword, {
                SAFE_FOR_JQUERY: true
            });
            token = DOMPurify.sanitize(token, {
                SAFE_FOR_JQUERY: true
            });

            $.ajax({
                url: '/ajaxProcessus/authentication/changePassword.php',
                type: 'POST',
                data: {
                    oldPassword,
                    newPassword,
                    confirmPassword,
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

                    $("#delete_modal").removeClass("show");
                    $("#delete_modal").addClass("d-none");


                    if (response == 1) { //updated

                        $("button.close").trigger("click");
                        $("#token_edit").val(received_token);
                        vt.success(`You successfully updated Your Password`, {
                            title: "Password Updated!",
                            duration: 6000,
                            closable: true,
                            focusable: true,
                            callback: () => {
                                console.log("completed");
                            }
                        });


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
                    } else if (response == 0) { //unicity error
                        $("button.close").trigger("click");
                        $("#token_edit").val(received_token);

                        for (var key in serverGeneratedErrors) {
                            var msg = serverGeneratedErrors[key];

                            $(`#${key}`).parent().addClass("invalid");
                            $(`.invalid-feedback.${key}`).text(msg);
                            $(`.invalid-feedback.${key}`).css("display", "block");
                        }
                    }
                }
            })
        }
    }

    const fields = {
        fill: {
            password: query(`#login-password-1`),
            input: query(`#login-password-1`).firstElementChild,
            trailing: query(`#login-password-1`).lastElementChild,
            icon: query(`#login-password-1`).lastElementChild.firstElementChild
        },
    };

    const fields2 = {
        fill: {
            password: query(`#login-password-2`),
            input: query(`#login-password-2`).firstElementChild,
            trailing: query(`#login-password-2`).lastElementChild,
            icon: query(`#login-password-2`).lastElementChild.firstElementChild
        },
    };

    const fields3 = {
        fill: {
            password: query(`#login-password-3`),
            input: query(`#login-password-3`).firstElementChild,
            trailing: query(`#login-password-3`).lastElementChild,
            icon: query(`#login-password-3`).lastElementChild.firstElementChild
        },
    };



    const _toggle_class_ = `login__field--run`;

    // SLIDE //
    function slide({
        password,
        input,
        trailing,
        icon
    }) {
        trailing.setAttribute(`disabled`, ``);
        input.setAttribute(`readonly`, ``);
        password.classList.toggle(_toggle_class_);
        icon.classList.toggle(`fa-eye`);
        icon.classList.toggle(`fa-eye-slash`);
        setTimeout(
            () =>
            input.setAttribute(
                `type`,
                input.getAttribute(`type`) === `password` ? `text` : `password`
            ),
            300
        );

        setTimeout(function() {
            trailing.removeAttribute(`disabled`);
            input.removeAttribute(`readonly`);
            password.classList.toggle(_toggle_class_);
        }, 1050);
    }

    // FILL //
    function _fill_({
        password,
        input,
        trailing,
        icon
    }) {
        trailing.setAttribute(`disabled`, ``);
        input.setAttribute(`readonly`, ``);
        password.classList.toggle(_toggle_class_);
        icon.classList.toggle(`fa-eye`);
        icon.classList.toggle(`fa-eye-slash`);
        input.setAttribute(
            `type`,
            input.getAttribute(`type`) === `password` ? `text` : `password`
        );

        setTimeout(function() {
            trailing.removeAttribute(`disabled`);
            input.removeAttribute(`readonly`);
        }, 1050);
    }

    // listener(fields.slide.trailing, "click", () => {
    //     slide(fields.slide);
    // });
    listener(fields.fill.trailing, "click", () => {
        _fill_(fields.fill);
    });

    listener(fields2.fill.trailing, "click", () => {
        _fill_(fields2.fill);
    });

    listener(fields3.fill.trailing, "click", () => {
        _fill_(fields3.fill);
    });

    // document.addEventListener('DOMContentLoaded', (event) => {

    //     // displayPartners(0);
    //     // clearModalInvalidFeedbacks();



    // });
    </script>


</body>

</html>
