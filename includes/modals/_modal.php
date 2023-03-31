


<div class="modal fade <?=$class?>" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" style="display: none;" aria-modal="true">
    <div class="modal-dialog modal-<?=$modal_size?>">
        <div class="modal-content">
            <div class="modal-header" style="    background-image: linear-gradient(to top, #1e3c72 0%, #1e3c72 1%, #2a5298 100%) !important;    color: white;">
                <h5 class="modal-title" style="font-weight: bold;" id="exampleModalLongTitle"><?=$modal_title?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="    color: white;">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
               <?=$modal_body?>
            </div>
            <div class="modal-footer">
                <?=$modal_footer?>
            
            </div>
        </div>
    </div>
</div>
