<?php
#---------------------------DO NOT EDIT VALUES BELOW THIS LINE-----------------------------
error_reporting(E_ALL & ~E_NOTICE);
// Constants
define('HOME_DIRECTORY', 'myfiles');
define('RESTRICTED_BROWSING', true);
define('ROOT_URI', 'http://' .  $_SERVER['HTTP_HOST'].'/watch_folder/');
define('BASE_PATH', 'public/app');
define('VIEW_PATH', 'application/view');
set_time_limit(5);
@ini_set("session.use_cookies", "1");
@ini_set("session.use_trans_sid", "false");
@session_cache_limiter('none');
@ini_set('session.save_handler', 'files');
@session_start();
$_SESSION['IP']=$_SERVER['REMOTE_ADDR'];

?>