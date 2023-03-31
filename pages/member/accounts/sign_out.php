<?php
require_once __DIR__."/../../../core/ini.php";
require_once __DIR__."/../../../core/inc_var.php";
include __DIR__.'/../../../includes/partials/_authorization.php';

    
$user = new user();
$user->logOut();
session::flash("messages",array("loggedout"=>"You have been successfully logged out, don't forget to visit us again!."));
redirect::to("/pages/authentication/login.php"); //to login page

?>
