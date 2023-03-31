<style>
/*delete modal*/
#mustRead_modal {
    position: fixed;
    top: 0%;
    left: 0%;
    /* transform: translate(-50%, -25%); */
    z-index: 2000;
    width: 100%;
    height: 100%;
    overflow: hidden;
    outline: 0;
    background-color: rgb(0 0 0 / 50%);
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

.modal-confirm {
    margin: auto !important;
}

@media (max-width: 415px) {
    .modal-confirm {
        width: 320px;
        margin: auto;
    }
}

</style>
<div id="mustRead_modal" class="mustRead_modal fade <?=$displayClass?>">
    <div class="modal-dialog modal-confirm" style="margin-top: 50px !important;">
        <div class="modal-content">
            <div class="modal-header flex-column">
                <div class="icon-box">
                    <i class="pe-7s-close" style='font-size:90px;margin-left: -8px;margin-top: -5px;'> </i>
                </div>
                <h4 class="modal-title w-100">You Must Read The New Mails!</h4>
                <button type="button" class="close disabled" data-dismiss="modal" aria-hidden="true">&times;</button>
            </div>
            <div class="modal-body">
                <p id='text'>You've got new mails in you mailbox, consider reading them before proceeding with any
                    action.</p>
            </div>
            <div class="modal-footer justify-content-center">
                <a type="button" class="btn btn-danger" href="/pages/member/accounts/mailbox.php"
                    style="color:#fff;display: flex;align-items: center;justify-content: center;font-weight: bold;">OK</a>
            </div>
        </div>
    </div>
</div>
