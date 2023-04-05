<?php

$MailBuilder = new Mails();

$unSeenMails = $MailBuilder->checkUnseenMails();

?>

<style>
#user_info_mailbox {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

@media only screen and (max-width: 1320px) {

    #user_info_mailbox,
    #devider {
        display: ''
    }

    #language_picker {
        margin-bottom: 0px !important;
    }
}

@media only screen and (min-width: 1321px) {

    #user_info_mailbox,
    #devider {
        display: none
    }
}

@media only screen and (max-width: 991px) {

    #language_picker,
    #header_wrapper {
        margin-right: 10px !important;
    }
}

.logo-src {
    cursor: pointer;
}

</style>

<?php
if ($unSeenMails > 0 && strpos($_SERVER['REQUEST_URI'], 'dashboard.php') !== false) {
    includeWithVariables('./../includes/modals/_mustReadMailModal.php', array("displayClass" => 'show'));
} else if ($unSeenMails > 0 && strpos($_SERVER['REQUEST_URI'], 'mailbox.php') === false) {
    includeWithVariables('./../../../includes/modals/_mustReadMailModal.php', array("displayClass" => 'show'));
}
?>

<div class="app-header header-shadow">
    <div class="app-header__logo">
        <div class="logo-src">
            <img src="/assets/images/logo.png" alt="" srcset="" style="width: 100%;margin-top: 8px;">
        </div>
        <div class="header__pane ml-auto">
            <div>
                <button type="button" class="hamburger close-sidebar-btn hamburger--elastic"
                    data-class="closed-sidebar">
                    <span class="hamburger-box">
                        <span class="hamburger-inner"></span>
                    </span>
                </button>
            </div>
        </div>
    </div>
    <div class="app-header__mobile-menu">
        <div>
            <button type="button" class="hamburger hamburger--elastic mobile-toggle-nav">
                <span class="hamburger-box">
                    <span class="hamburger-inner"></span>
                </span>
            </button>
        </div>
    </div>
    <div class="app-header__menu">
        <span>
            <button type="button" class="btn-icon btn-icon-only btn btn-primary btn-sm mobile-toggle-header-nav">
                <span class="btn-icon-wrapper">
                    <i class="fa fa-ellipsis-v fa-w-6"></i>
                </span>
            </button>
        </span>
    </div>
    <div class="app-header__content">
        <div class="app-header-left">
            <div class="search-wrapper">
                <div class="input-holder">
                    <input type="text" class="search-input" placeholder="Type to search" />
                    <button class="search-icon"><span></span></button>
                </div>
                <button class="close"></button>
            </div>
            <ul class="header-menu nav">
                <!-- <li class="nav-item">
                    <a href="javascript:void(0);" class="nav-link">
                        <i class="nav-link-icon fa fa-database"> </i> Statistics
                    </a>
                </li>
                <li class="btn-group nav-item">
                    <a href="javascript:void(0);" class="nav-link">
                        <i class="nav-link-icon fa fa-edit"></i> Projects
                    </a>
                </li>
                <li class="dropdown nav-item">
                    <a href="javascript:void(0);" class="nav-link">
                        <i class="nav-link-icon fa fa-cog"></i> Settings
                    </a>
                </li> -->

                <li class="nav-item">
                    <a href="javascript:void(0);" class="nav-link">
                        <span id='current_datetime'></span>
                    </a>
                </li>
            </ul>
        </div>
        <div class="app-header-right" id='header_wrapper'>
            <div class="header-btn-lg pr-0">
                <div class="widget-content p-0">
                    <div class="widget-content-wrapper">

                        <div class="widget-content-left mr-3 header-user-info">
                            <?php
                            $user = new user();
                            $usernamex = $user->data()["username"];
                            ?>
                            <div class="widget-heading">
                                <?= $usernamex ?>
                            </div>
                            <div class="widget-subheading">파트너</div>
                        </div>
                        <div class="widget-content-left">
                            <div class="btn-group">
                                <a data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="p-0 btn">
                                    <img width="42" class="rounded-circle" src="/assets/images/avatars/user_logo.png"
                                        alt="" />
                                    <i class="fa fa-angle-down ml-2 opacity-8"></i>
                                </a>
                                <div tabindex="-1" role="menu" aria-hidden="true"
                                    class="dropdown-menu dropdown-menu-right">

                                    <button type="button" tabindex="0" class="dropdown-item" id='user_info_mailbox'>
                                        <div class="widget-content-left mr-3" id='user_info'>
                                            <div class="widget-heading">
                                                <?= $usernamex ?>
                                            </div>
                                            <div class="widget-subheading">파트너</div>
                                        </div>
                                        </br>
                                        <div class="widget-content-right ml-3" id='user_mailbox' unSeenReplies>
                                            <a href="/pages/member/accounts/mailbox.php">
                                                <span class="new-mails badge badge-danger"
                                                    style="position: absolute;z-index: 10;bottom: -1px;right: 0;padding: 2px 5px;display:<?= ($unSeenMails > 0) ? '' : 'none' ?>"><?= $unSeenMails; ?>
                                                </span>

                                                <i class="fa text-white  fa-envelope pr-1 pl-1"
                                                    style='font-size: 1.8rem;color: #398AB9 !important;'></i>
                                            </a>
                                        </div>

                                    </button>
                                    <div tabindex="-1" class="dropdown-divider" id="devider"></div>
                                    <button type="button" tabindex="0" class="dropdown-item">
                                        <i class="metismenu-icon  pe-7s-door-lock"
                                            style="text-align: center;width: 34px;height: 34px;line-height: 34px;position: absolute;left: 5px;top: 50%;margin-top: -17px;font-size: 1.5rem;opacity: .5;transition: color 300ms;"></i>
                                        <span style="margin-left: 20px;">비밀번호 변경</span>
                                    </button>
                                    <button type="button" tabindex="0" class="dropdown-item"
                                        onclick="location.href='/pages/member/accounts/sign_out.php'">
                                        <i class="metismenu-icon  pe-7s-lock"
                                            style="text-align: center;width: 34px;height: 34px;line-height: 34px;position: absolute;left: 5px;top: 50%;margin-top: -17px;font-size: 1.5rem;opacity: .5;transition: color 300ms;"></i>

                                        <span style="margin-left: 20px;">로그아웃</span>
                                    </button>

                                </div>
                            </div>
                        </div>
                        <div class="widget-content-right header-user-info ml-3" style='display:none;'>
                            <button type="button" class="btn-shadow p-1 btn btn-primary btn-sm show-toastr-example">
                                <i class="fa text-white fa-calendar pr-1 pl-1"></i>
                            </button>
                        </div>
                        <div class="widget-content-right header-user-info ml-3" style="position:relative;">
                            <a href="/pages/member/accounts/mailbox.php">

                                <i class="fa text-white  fa-envelope pr-1 pl-1"
                                    style='font-size: 1.8rem;color: #398AB9 !important;'></i>

                                <span class="new-mails badge badge-danger"
                                    style="position: absolute;z-index: 10;bottom: -1px;right: 0;padding: 2px 5px;display:<?= ($unSeenMails > 0) ? '' : 'none' ?>"><?= $unSeenMails; ?>
                                </span>
                            </a>
                        </div>
                        <div class="widget-content-right header-user-info ml-3" id="language_picker"
                            style='display:block !important;width: 20px;margin-bottom: 4px;'>
                            <!-- <select class="language_picker" style="border: none;background: transparent;font-size:1.6rem;">

                                <option value="selected" class='langauge_selected' selected>🇬🇧</option>
                                <option value="eng" style='font-size: 1rem;'>🇬🇧&emsp;English</option>
                                <option value="kr" style='font-size: 1rem;'>🇰🇷&emsp;Korean</option>
                                
                            </select> -->

                            <div class="btn-group d-none">
                                <a data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="p-0 btn"
                                    style="font-size: 1.6rem;">
                                    <span id="current_lang">🇬🇧</span>
                                </a>
                                <div tabindex="-1" role="menu" aria-hidden="true"
                                    class="dropdown-menu dropdown-menu-right">
                                    <button type="button" tabindex="0" data-id="eng" class="dropdown-item"
                                        onclick="language(event)">

                                        <span style="margin-left: 5px;">English</span>
                                    </button>
                                    <button type="button" tabindex="0" data-id="kr" class="dropdown-item"
                                        onclick="language(event)">

                                        <span style="margin-left: 5px;">Korean</span>
                                    </button>

                                </div>
                            </div>
                        </div>





                    </div>
                </div>
            </div>
        </div>
    </div>
</div>




<script>
var element = document.getElementById("current_datetime");

function formatDate(date, format) {
    const map = {
        mm: ('0' + (date.getMonth() + 1)).slice(-2),
        dd: ('0' + date.getDate()).slice(-2),
        yy: date.getFullYear().toString().slice(-2),
        YY: date.getFullYear(),

        hh: ('0' + (date.getHours())).slice(-2),
        MM: ('0' + (date.getMinutes())).slice(-2),
        ss: ('0' + (date.getSeconds())).slice(-2),

        M: date.toLocaleString('default', {
            month: 'long'
        })


    }

    // const map = {
    //     mm: ('0' + (date.getUTCMonth() + 1)).slice(-2),
    //     dd: ('0' + date.getUTCDate()).slice(-2),
    //     yy: date.getUTCFullYear().toString().slice(-2),
    //     YY: date.getUTCFullYear(),

    //     hh: ('0' + (date.getUTCHours())).slice(-2),
    //     MM: ('0' + (date.getUTCMinutes())).slice(-2),
    //     ss: ('0' + (date.getUTCSeconds())).slice(-2),

    //     M: date.toLocaleString('default', {
    //         month: 'long'
    //     })


    // }

    return format.replace(/mm|dd|yy|YY|M|MM|ss|hh/gi, matched => map[matched])
}

Date.prototype.addHours = function(h) {
    this.setHours(this.getHours() + h);
    return this;
}
Date.prototype.addMinutes = function(h) {
    this.setMinutes(this.getMinutes() + h);
    return this;
}
Date.prototype.substractMinutes = function(h) {
    this.setMinutes(this.getMinutes() - h);
    return this;
}

function updateDateTime() {

    var date = new Date();

    //specify how many hours to add/subtract 
    var hoursMinutes = date.toString();

    //Mon Mar 20 2023 17:29:50 GMT+0000 (GMT)

    hoursMinutes = hoursMinutes.split("(")[0];
    // hoursMinutes = hoursMinutes.split(")")[0];
    hoursMinutes = hoursMinutes.split("GMT")[1];

    let isAdd = 1;
    let number = "";


    if (hoursMinutes[0] == '-') {
        isAdd = 0;
        hoursMinutes = hoursMinutes.split("-")[1];
    } else {
        hoursMinutes = hoursMinutes.split("+")[1];
    }

    // let hours = hoursMinutes.split(":")[0];
    // let minutes = hoursMinutes.split(":")[1];

    var offset = Math.abs(date.getTimezoneOffset());

    let vHours = offset / 60;
    let hours = Math.floor(vHours);
    var vMinutes = (vHours - hours) * 60;
    var minutes = Math.round(vMinutes);

    hours = parseInt(hours);
    minutes = parseInt(minutes);

    if (isAdd == 1) {
        hours = 9 - hours;
        date.addHours(hours);
        date.substractMinutes(minutes);
    } else {
        hours = 9 + hours;
        date.addHours(hours);
        date.addMinutes(minutes);
    }


    const weekday = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];
    date = formatDate(date, 'YY-mm-dd M hh:MM:ss');
    element.innerText = date + ' GMT +9:00';
}

updateDateTime();
setInterval(updateDateTime, 1000);

var flags_emoji = {
    "eng": "🇬🇧",
    "kr": "🇰🇷"
}

function language(e) {
    var val = e.currentTarget.getAttribute("data-id");


    var emoji = flags_emoji[val];
    document.getElementById("current_lang").innerText = emoji;

}

var element = document.getElementsByClassName("logo-src");

element[0].addEventListener("click", function() {

    window.location.href = '/'; //relative to domain

})
</script>
