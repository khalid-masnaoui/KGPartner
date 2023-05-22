<style>
/*delete modal*/
#delete_modal {
    position: fixed;
    top: 0%;
    left: 0%;
    /* transform: translate(-50%, -25%); */
    z-index: 2000;
    width: 100%;
    height: 100%;
    overflow: hidden;
    outline: 0;
}

.modal-confirm {
    color: #636363;
    width: 400px;
}

.modal-confirm .modal-content {
    padding: 20px;
    border-radius: 5px;
    border: none;
    text-align: center;
    font-size: 14px;
}

.modal-confirm .modal-header {
    border-bottom: none;
    position: relative;
}

.modal-confirm h4 {
    text-align: center;
    font-size: 26px;
    margin: 30px 0 -10px;
}

.modal-confirm .close {
    position: absolute;
    top: -5px;
    right: -2px;
}

.modal-confirm .modal-body {
    color: #999;
}

.modal-confirm .modal-footer {
    border: none;
    text-align: center;
    border-radius: 5px;
    font-size: 13px;
    padding: 10px 15px 25px;
}

.modal-confirm .modal-footer a {
    color: #999;
}

.modal-confirm .icon-box {
    width: 80px;
    height: 80px;
    margin: 0 auto;
    border-radius: 50%;
    z-index: 9;
    text-align: center;
    border: 3px solid #f15e5e;
}

.modal-confirm .icon-box i {
    color: #f15e5e;
    font-size: 46px;
    display: inline-block;
    margin-top: 13px;
}

.modal-confirm .btn,
.modal-confirm .btn:active {
    color: #fff;
    border-radius: 4px;
    background: #60c7c1;
    text-decoration: none;
    transition: all 0.4s;
    line-height: normal;
    min-width: 120px;
    border: none;
    min-height: 40px;
    border-radius: 3px;
    margin: 0 5px;
}

.modal-confirm .btn-secondary {
    background: #c1c1c1;
}

.modal-confirm .btn-secondary:hover,
.modal-confirm .btn-secondary:focus {
    background: #a8a8a8;
}

.modal-confirm .btn-danger {
    background: #f15e5e;
}

.modal-confirm .btn-danger:hover,
.modal-confirm .btn-danger:focus {
    background: #ee3535;
}

/* .trigger-btn {
        display: inline-block;
        margin: 100px auto;
    } */
@media (max-width: 415px) {
    .modal-confirm {
        width: 320px;
        margin: auto;
    }
}

</style>
<div id="delete_modal" class="delete_modal fade d-none">
    <div class="modal-dialog modal-confirm">
        <div class="modal-content">
            <div class="modal-header flex-column">
                <div class="icon-box">
                    <i class="pe-7s-close" style='font-size:90px;margin-left: -8px;margin-top: -5px;'> </i>
                </div>
                <h4 class="modal-title w-100">Are you sure?</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            </div>
            <div class="modal-body">
                <p id='text'>Do you really want to delete this record? This process cannot be undone.</p>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-secondary cancel" data-dismiss="delete_modal">취소</button>

                <button type="button" class="btn btn-danger btn_confirmed_action delete" data-id=''
                    onclick=deleteClient(event)>삭제</button>

                <button type="button" class="btn btn-danger btn_confirmed_action delete_player" data-id=''
                    onclick=deletePlayer(event)>삭제</button>

                <button type="button" class="btn btn-danger btn_confirmed_action edit_partner" data-id=''
                    onclick=editPartner(event)>수정</button>

                <button type="button" class="btn btn-danger btn_confirmed_action kick_player" data-id=''
                    onclick=KickPlayer(event)>KickPlayer</button>

                <button type="button" class="btn btn-danger btn_confirmed_action apply_pt_setting" data-id=''
                    onclick=applyPtSettings(event)>Apply</button>

                <button type="button" class="btn btn-danger btn_confirmed_action delete_pt_setting" data-id=''
                    onclick=deletePtSettings(event)>삭제</button>


                <button type="button" class="btn btn-danger btn_confirmed_action confirm_pt_setting" data-id=''
                    onclick=addMemberPt(event)>확인</button>

                <button type="button" class="btn btn-danger btn_confirmed_action confirm_commission" data-id=''
                    onclick=editCommission(event)>확인</button>

                <button type="button" class="btn btn-danger btn_confirmed_action confirm_skin" data-id=''
                    onclick=editClientSkin(event)>확인</button>

                <button type="button" class="btn btn-danger btn_confirmed_action delete_slot_logo" data-id=''
                    onclick=deleteClientSlotLogo(event)>삭제</button>

                <button type="button" class="btn btn-danger btn_confirmed_action delete_notification" data-id=''
                    onclick=deleteNotification(event)>삭제</button>

                <button type="button" class="btn btn-danger btn_confirmed_action delete_notification_template"
                    data-id='' onclick=deleteNotificationTemplate(event)>삭제</button>

                <button type="button" class="btn btn-danger btn_confirmed_action delete_announcement" data-id=''
                    onclick=deleteAnnouncement(event)>삭제</button>

                <button type="button" class="btn btn-danger btn_confirmed_action delete_announcement_template"
                    data-id='' onclick=deleteAnnouncementTemplate(event)>삭제</button>

                <button type="button" class="btn btn-danger btn_confirmed_action delete_mail" data-id=''
                    onclick=deleteMail(event)>삭제</button>

                <button type="button" class="btn btn-danger btn_confirmed_action delete_mail_template" data-id=''
                    onclick=deleteMailTemplate(event)>삭제</button>

                <button type="button" class="btn btn-danger btn_confirmed_action confirm_deposit" data-id=''
                    onclick=editDeposit(event)>확인</button>

                <button type="button" class="btn btn-danger btn_confirmed_action delete_deposit" data-id=''
                    onclick=deleteDeposit(event)>삭제</button>

                <button type="button" class="btn btn-danger btn_confirmed_action edit_password" data-id=''
                    onclick=editPassword(event)>확인</button>


                <button type="button" class="btn btn-danger btn_confirmed_action delete_bet_limit" data-id=''
                    onclick=deleteBetLimit(event)>삭제</button>
            </div>
        </div>
    </div>
</div>
<script>
function resetDeleteModalScheme() {
    //reset delete modal scheme
    $(".modal-confirm .icon-box").css("border", "3px solid #f15e5e");
    $(".modal-confirm .icon-box i").css("color", "#f15e5e");
    $(".modal-confirm .btn-danger").css("background", "#f15e5e");
    $(".modal-confirm .icon-box i").attr("class", "pe-7s-close");
}
$("#delete_modal button.close").click(function(event) {

    //reset delete modal scheme
    resetDeleteModalScheme()

    $("#delete_modal").removeClass("show");
    $("#delete_modal").addClass("d-none");

});
$("#delete_modal button.cancel").click(function(event) {

    //reset delete modal scheme
    resetDeleteModalScheme()

    $("#delete_modal").removeClass("show");
    $("#delete_modal").addClass("d-none");

});
$("#delete_modal").click(function(event) {
    // $("#delete_modal").removeClass("show");
    // $("#delete_modal").addClass("d-none");

    if (event.target == this) { // only if the target itself has been clicked

        //reset delete modal scheme
        resetDeleteModalScheme()

        $("#delete_modal").removeClass("show");
        $("#delete_modal").addClass("d-none");
    }
});
</script>
