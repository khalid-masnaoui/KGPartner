<?php
require_once __DIR__ . "/../../../core/ini.php";
require_once __DIR__ . "/../../../core/inc_var.php";
include __DIR__ . '/../../../includes/partials/_authorization.php';


$user = new user();
$user->logOut();
session::flash("messages", array("loggedout" => "로그아웃 되었습니다."));
redirect::to("/pages/authentication/login.php"); //to login page

?>
