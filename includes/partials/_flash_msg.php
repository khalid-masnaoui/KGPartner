<?php
if(session::exists("messages") ){
    $session=session::get("messages");
    $key=array_keys($session)[0];
    $msg=[];
    $msg[$key]=session::flash("messages")[$key];
}else{
    $msg='';
}
?>