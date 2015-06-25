<?php
// Database Information
@session_start();
$ss = session_id();
if($_SERVER["HTTP_HOST"] == '127.0.0.1' || $_SERVER["HTTP_HOST"] == 'localhost' || $_SERVER["HTTP_HOST"] == 'localhost:81')
{ 
    define('_HOST','localhost');
    define('_DB','conversion1');
    define('_USER','root');
    define('_PASS','');
    define('_root','http://127.0.0.1/watch_folder/');
    ini_set("display_errors",0);
    $_SESSION["site_root"] = "http://".$_SERVER["HTTP_HOST"]."/watch_folder/";
}
else
{
    define('_HOST','');
    define('_DB','conversion1');
    define('_USER','aaaaa');
    define('_PASS','bbbb');
    define('_root','http://www.conversion1.com');
    ini_set("display_errors",0);
}
?>