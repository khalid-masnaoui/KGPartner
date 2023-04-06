<?php

if (!session::exists(config::get("session/login_name"))) {

    session::flash("messages", array("authorization" => "다시 로그인 해 주세요"));

    redirect::to("/pages/authentication/login.php"); //to login page

}


?>
