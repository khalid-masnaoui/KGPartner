<?php  

if(!session::exists(config::get("session/login_name"))){

    session::flash("messages",array("authorization"=>"authorization not granted !, you need first to login in order to access this content."));
  
    redirect::to("/pages/authentication/login.php"); //to login page

}


?>