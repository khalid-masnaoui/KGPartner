<?php

$notificationBuilder = new Notifications();

$unSeenNotifications = $notificationBuilder->checkUnseenNotifications();

$MailBuilder = new Mails();

$unSeenMails = $MailBuilder->checkUnseenMails();

?>

<style>
.new-notifs {
    margin-left: 10px;
    animation: pulse 1.5s 1;
    animation-iteration-count: infinite;
}

.new-mails {
    margin-left: 10px;
    animation: pulse 1.5s 1;
    animation-iteration-count: infinite;
}

@keyframes pulse {
    0% {
        transform: scale(1);
    }

    25% {
        transform: scale(1.1);
    }

    50% {
        transform: scale(1);
    }

    75% {
        transform: scale(0.9);
    }

    100% {
        transform: scale(1);
    }

}

</style>


<div class="app-sidebar sidebar-shadow">
    <div class="app-header__logo">
        <div class="logo-src"></div>
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
    <div class="scrollbar-sidebar" style='overflow: scroll;'>
        <div class="app-sidebar__inner">
            <ul class="vertical-nav-menu">
                <li class="app-sidebar__heading">대시보드</li>
                <li>
                    <a href="/pages/dashboard.php"
                        class=<?= strpos($_SERVER['REQUEST_URI'], 'dashboard.php') !== false ? "mm-active" : ""; ?>>
                        <i class="metismenu-icon pe-7s-home"></i> Dashboard
                    </a>
                </li>
                <li class="app-sidebar__heading">Members</li>
                <li class=<?= strpos($_SERVER['REQUEST_URI'], 'member_management/') !== false ? "mm-active" : ""; ?>>
                    <a href="#"
                        aria-expanded=<?= strpos($_SERVER['REQUEST_URI'], 'member_management/') !== false ? "true" : "false"; ?>>
                        <i class="metismenu-icon pe-7s-users"></i> 회원관리
                        <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                    </a>

                    <ul
                        class=<?= strpos($_SERVER['REQUEST_URI'], 'member_management') !== false ? "mm-collapse mm-show" : ""; ?>>
                        <li>
                            <a href="/pages/member/member_management/partners_list.php"
                                class=<?= strpos($_SERVER['REQUEST_URI'], 'partners_list.php') !== false ? "mm-active" : ""; ?>>
                                <i class="metismenu-icon"></i> 파트너 리스트
                            </a>
                        </li>
                        <li>
                            <a href="/pages/member/member_management/clients_list.php"
                                class=<?= strpos($_SERVER['REQUEST_URI'], 'clients_list.php') !== false ? "mm-active" : ""; ?>>
                                <i class="metismenu-icon"></i> 고객사 리스트
                            </a>
                        </li>
                    </ul>
                </li>

                <li style="display:none"
                    class=<?= strpos($_SERVER['REQUEST_URI'], 'accounts/') !== false ? "mm-active" : ""; ?>>
                    <a href="#"
                        aria-expanded=<?= strpos($_SERVER['REQUEST_URI'], 'accounts/') !== false ? "true" : "false"; ?>>
                        <i class="metismenu-icon pe-7s-folder"></i> Accounts <span
                            class="new-notifs badge badge-primary"
                            style="display:<?= ($unSeenNotifications > 0 or $unSeenMails > 0) ? '' : 'none' ?>">NEW</span>
                        <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                    </a>
                    <ul
                        class=<?= strpos($_SERVER['REQUEST_URI'], 'accounts/') !== false ? "mm-collapse mm-show" : ""; ?>>
                        <li>
                            <a href="/pages/member/accounts/notifications.php"
                                class=<?= strpos($_SERVER['REQUEST_URI'], 'notifications.php') !== false ? "mm-active" : ""; ?>>
                                <i class="metismenu-icon"> </i>Notifications <span
                                    class="new-notifs badge badge-primary"
                                    style="display:<?= ($unSeenNotifications > 0) ? '' : 'none' ?>">NEW</span>
                            </a>
                        </li>
                        <li>
                            <a href="/pages/member/accounts/announcements.php"
                                class=<?= strpos($_SERVER['REQUEST_URI'], 'announcements.php') !== false ? "mm-active" : ""; ?>>
                                <i class="metismenu-icon"> </i>Announcements
                            </a>
                        </li>
                        <li>
                        <li>
                            <a href="/pages/member/accounts/action_logs.php"
                                class=<?= strpos($_SERVER['REQUEST_URI'], 'action_logs.php') !== false ? "mm-active" : ""; ?>>
                                <i class="metismenu-icon"> </i>Action Logs
                            </a>
                        </li>
                        <li>
                        <li>
                            <a href="/pages/member/accounts/mailbox.php"
                                class=<?= strpos($_SERVER['REQUEST_URI'], 'mailbox.php') !== false ? "mm-active" : ""; ?>>
                                <i class="metismenu-icon"> </i>Mailbox <span class="new-mails badge badge-primary"
                                    style="display:<?= ($unSeenMails > 0) ? '' : 'none' ?>">NEW</span>
                            </a>
                        </li>
                        <li>
                            <a href="/pages/member/accounts/sign_out.php"
                                class=<?= strpos($_SERVER['REQUEST_URI'], 'sign_out.php') !== false ? "mm-active" : ""; ?>>
                                <i class="metismenu-icon"> </i>Sign Out
                            </a>
                        </li>
                        <li>
                    </ul>
                </li>

                <li class="app-sidebar__heading">Infos</li>
                <li style="display:none"
                    class=<?= strpos($_SERVER['REQUEST_URI'], 'product/') !== false ? "mm-active" : ""; ?>>
                    <a href="#"
                        aria-expanded=<?= strpos($_SERVER['REQUEST_URI'], 'product/') !== false ? "true" : "false"; ?>>
                        <i class="metismenu-icon pe-7s-portfolio"></i> Product
                        <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                    </a>
                    <ul
                        class=<?= strpos($_SERVER['REQUEST_URI'], 'product/') !== false ? "mm-collapse mm-show" : ""; ?>>
                        <li>
                            <a href="/pages/infos/product/limit_setting.php"
                                class=<?= strpos($_SERVER['REQUEST_URI'], 'limit_setting.php') !== false ? "mm-active" : ""; ?>>
                                <i class="metismenu-icon"> </i>Limit Settings
                            </a>
                        </li>
                    </ul>
                </li>
                <li class=<?= strpos($_SERVER['REQUEST_URI'], 'reports/') !== false ? "mm-active" : ""; ?>>
                    <a href="#"
                        aria-expanded=<?= strpos($_SERVER['REQUEST_URI'], 'reports/') !== false ? "true" : "false"; ?>>
                        <i class="metismenu-icon pe-7s-news-paper"></i> 정산관리
                        <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                    </a>
                    <ul
                        class=<?= strpos($_SERVER['REQUEST_URI'], 'reports/') !== false ? "mm-collapse mm-show" : ""; ?>>
                        <li>
                            <a href="/pages/infos/reports/win_loss.php"
                                class=<?= strpos($_SERVER['REQUEST_URI'], 'win_loss.php') !== false ? "mm-active" : ""; ?>>
                                <i class="metismenu-icon"> </i>윈로스
                            </a>
                        </li>
                        <li>
                            <a href="/pages/infos/reports/transaction_history.php"
                                class=<?= strpos($_SERVER['REQUEST_URI'], 'transaction_history.php') !== false ? "mm-active" : ""; ?>>
                                <i class="metismenu-icon"> </i>게임내역
                            </a>
                        </li>

                        <li>
                            <a href="/pages/infos/reports/summary_report.php"
                                class=<?= strpos($_SERVER['REQUEST_URI'], 'summary_report.php') !== false ? "mm-active" : ""; ?>>
                                <i class="metismenu-icon"> </i>요약보고서
                            </a>
                        </li>

                        <li>
                            <a href="/pages/infos/reports/invoice_list.php" style="display:none"
                                class=<?= strpos($_SERVER['REQUEST_URI'], 'invoice_list.php') !== false ? "mm-active" : ""; ?>>
                                <i class="metismenu-icon"> </i>Invoice List
                            </a>
                        </li>

                        <li>
                            <a href="/pages/infos/reports/pending_event.php" style="display:none"
                                class=<?= strpos($_SERVER['REQUEST_URI'], 'pending_event.php') !== false ? "mm-active" : ""; ?>>
                                <i class="metismenu-icon"> </i>Pending Event
                            </a>
                        </li>
                        <li>
                            <a href="/pages/infos/reports/bonus_winning.php" style="display:none"
                                class=<?= strpos($_SERVER['REQUEST_URI'], 'bonus_winning.php') !== false ? "mm-active" : ""; ?>>
                                <i class="metismenu-icon"> </i>Bonus Winning
                            </a>
                        </li>
                        <li></li>
                        <a href="/pages/infos/reports/commission_report.php" style="display:none"
                            class=<?= strpos($_SERVER['REQUEST_URI'], 'commission_report.php') !== false ? "mm-active" : ""; ?>>
                            <i class="metismenu-icon"> </i>Commission Report
                        </a>
                </li>
                <li>
                    <a href="/pages/infos/reports/jackpot_contribution_history.php" style="display:none"
                        class=<?= strpos($_SERVER['REQUEST_URI'], 'jackpot_contribution_history.php') !== false ? "mm-active" : ""; ?>>
                        <i class="metismenu-icon"> </i>Jackpot Contribution History
                    </a>
                </li>
                <li>
                    <a href="/pages/infos/reports/tp_statements.php" style="display:none"
                        class=<?= strpos($_SERVER['REQUEST_URI'], 'tp_statements.php') !== false ? "mm-active" : ""; ?>>
                        <i class="metismenu-icon"> </i>TB Statements
                    </a>
                </li>
                <li>
                    <a href="/pages/infos/reports/jackpot_tp_statement.php" style="display:none"
                        class=<?= strpos($_SERVER['REQUEST_URI'], 'jackpot_tp_statement.php') !== false ? "mm-active" : ""; ?>>
                        <i class="metismenu-icon"> </i>Jackpot TB Statement
                    </a>
                </li>

            </ul>
            </li>
            <li class=<?= strpos($_SERVER['REQUEST_URI'], 'deposit_withdraw/') !== false ? "mm-active" : ""; ?>>
                <a href="#"
                    aria-expanded=<?= strpos($_SERVER['REQUEST_URI'], 'deposit_withdraw/') !== false ? "true" : "false"; ?>>
                    <i class="metismenu-icon pe-7s-wallet"></i> 포인트관리
                    <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                </a>
                <ul
                    class=<?= strpos($_SERVER['REQUEST_URI'], 'deposit_withdraw/') !== false ? "mm-collapse mm-show" : ""; ?>>
                    <li>
                        <a href="/pages/infos/deposit_withdraw/deposit_withdraw_transaction.php"
                            class=<?= strpos($_SERVER['REQUEST_URI'], 'deposit_withdraw_transaction.php') !== false ? "mm-active" : ""; ?>>
                            <i class="metismenu-icon"> </i>포인트 이동 내역
                        </a>
                    </li>
                    <li>
                        <a href="/pages/infos/deposit_withdraw/deposit_withdraw_bonus.php" style="display:none"
                            class=<?= strpos($_SERVER['REQUEST_URI'], 'deposit_withdraw_bonus.php') !== false ? "mm-active" : ""; ?>>
                            <i class="metismenu-icon"> </i>Deposit/Withdraw Bonus
                        </a>
                    </li>

                    <li>
                        <a href="/pages/infos/deposit_withdraw/extra_charges_list.php" style="display:none"
                            class=<?= strpos($_SERVER['REQUEST_URI'], 'extra_charges_list.php') !== false ? "mm-active" : ""; ?>>
                            <i class="metismenu-icon"> </i>Extra Charges List
                        </a>
                    </li>

                    <li>
                        <a href="/pages/infos/deposit_withdraw/manual_adjustment_list.php" style="display:none"
                            class=<?= strpos($_SERVER['REQUEST_URI'], 'manual_adjustment_list.php') !== false ? "mm-active" : ""; ?>>
                            <i class="metismenu-icon"> </i>Manual Adjustment List
                        </a>
                    </li>

                    <li>
                        <a href="/pages/infos/deposit_withdraw/wa_balance_transaction.php"
                            class=<?= strpos($_SERVER['REQUEST_URI'], 'wa_balance_transaction.php') !== false ? "mm-active" : ""; ?>>
                            <i class="metismenu-icon"> </i>포인트 거래
                        </a>
                    </li>

                </ul>
            </li>
            <li style="display:none"
                class=<?= strpos($_SERVER['REQUEST_URI'], 'commissions/') !== false ? "mm-active" : ""; ?>>
                <a href="#"
                    aria-expanded=<?= strpos($_SERVER['REQUEST_URI'], 'commissions/') !== false ? "true" : "false"; ?>>
                    <i class="metismenu-icon pe-7s-cash"></i> Commissions
                    <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                </a>
                <ul
                    class=<?= strpos($_SERVER['REQUEST_URI'], 'commissions/') !== false ? "mm-collapse mm-show" : ""; ?>>
                    <li>
                        <a href="/pages/infos/commissions/partners_commissions.php"
                            class=<?= strpos($_SERVER['REQUEST_URI'], 'partners_commissions.php') !== false ? "mm-active" : ""; ?>>
                            <i class="metismenu-icon"> </i>Partners Commissions
                        </a>
                    </li>
                    <li>
                        <a href="/pages/infos/commissions/partners_commissions_total.php"
                            class=<?= strpos($_SERVER['REQUEST_URI'], 'partners_commissions_total.php') !== false ? "mm-active" : ""; ?>>
                            <i class="metismenu-icon"> </i>Partners Total Commissions
                        </a>
                    </li>

                </ul>
            </li>

            <li class=<?= strpos($_SERVER['REQUEST_URI'], 'settings/') !== false ? "mm-active" : ""; ?>>
                <a href="#"
                    aria-expanded=<?= strpos($_SERVER['REQUEST_URI'], 'settings/') !== false ? "true" : "false"; ?>>
                    <i class="metismenu-icon pe-7s-settings"></i> API 설정
                    <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                </a>
                <ul class=<?= strpos($_SERVER['REQUEST_URI'], 'settings/') !== false ? "mm-collapse mm-show" : ""; ?>>
                    <li>
                        <a href="/pages/settings/products.php"
                            class=<?= strpos($_SERVER['REQUEST_URI'], 'products.php') !== false ? "mm-active" : ""; ?>>
                            <i class="metismenu-icon pe-7s-photo-gallery"> </i>게임사 리스트
                        </a>
                    </li>
                </ul>
            </li>



            </ul>
        </div>
    </div>
</div>
