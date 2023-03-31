<?php
session_start();

include_once __DIR__."/globals.php";
require_once __DIR__."/../functions/sanitize.php";


spl_autoload_register("autoload");


function autoload($class){

    $path=__DIR__."/../classes/";
    $extension=".php";

    $pos = strpos($class, "_");

    if ($pos === false) {
        $full_Path=$path.$class.$extension;
    }else{
        $file_name=str_replace("_",DIRECTORY_SEPARATOR,$class);
        $full_Path=$path.$file_name.$extension;
    }

    if(!file_exists($full_Path)){
        return false;
    }

    require_once $full_Path;


}

// the cookie part , logged in automatically
if(cookie::exists(config::get("remember/cookie_name")) && !session::exists(config::get("session/login_name")) ){
     $hash=cookie::get(config::get("remember/cookie_name"));
    
     $hashCheck=DB::getInstance()->get("*","admin_users_session",[["hash","=",$hash]]);
     if($hashCheck->count()){
         $user=new user($hashCheck->first()["admin_users_id"]);

         $user->log();

        //  if(session::exists(config::get("session/role_name")) && session::get(config::get("session/role_name"))!=1){
        //     if(session::get(config::get("session/role_name")==3)){
        //         $role="User";
        //     }else{
        //         $role="Admin";

        //     }
        //     $db=DB::getInstance();            

        //     $today=strtotime(date("Y-m-d H:i:s"));
        //     $today=date("Y-m-d H:i:s", strtotime('+9 hours',$today));
        //     $username=$user->data()->user_name;

        //     $array=["user"=>$username,"role"=>$role,"log_in_date"=>$today];
        //     $db->insert('login_history',$array);

        // }
         // session::flash("welcome_again"," Welcome again!");
         session::flash("messages",array("welcome_again"=>"Welcome again!, You are automatically logged In"));


     }
}

//number formatter
$fmt = new NumberFormatter('en_US', NumberFormatter::DECIMAL);
$fmt->setAttribute(NumberFormatter::FRACTION_DIGITS, 2);


?>
