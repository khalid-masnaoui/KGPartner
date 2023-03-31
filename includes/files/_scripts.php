<div class="loader-spin">
    <div class="rectangle-bounce">
        <div class="rect-1"></div>
        <div class="rect-2"></div>
        <div class="rect-3"></div>
        <div class="rect-4"></div>
        <div class="rect-5"></div>
    </div>
</div>

<script type="text/javascript" src="/assets/scripts/main.js"></script>
<script src="/assets/scripts/vanilla-toast.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/validate.js/0.13.1/validate.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/dompurify/2.2.7/purify.min.js"></script>
<!--//only deposit page-->
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.0/js/select2.full.min.js"></script>



<script>
    var flash_message = <?php echo json_encode($msg, JSON_HEX_TAG); ?>; // Don't forget the extra semicolon!
    if (flash_message != '') {
        var type = Object.keys(flash_message)[0]
        console.log(type);
        var msg = flash_message[Object.keys(flash_message)[0]];
        if (type == "authorization") {
            vt.warn(msg, {
                title: "Authorization is not granted",
                duration: 6000,
                closable: true,
                focusable: true,
                callback: () => {
                    console.log("completed");
                }
            });
        } else if (type == "logged" || type == "logged_already" || type == "welcome_again") {
            vt.success(msg, {
                title: "Logged In",
                duration: 6000,
                closable: true,
                focusable: true,
                callback: () => {
                    console.log("completed");
                }
            });
        }

    }

    $(document).ajaxSend(function () {
        $(".loader-spin").fadeIn(250);
    });
    $(document).ajaxComplete(function () {
        $(".loader-spin").fadeOut(250);
    });
</script>
