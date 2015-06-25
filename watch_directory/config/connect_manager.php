<?php
function db_connect($Database='') {
	if($Database == '')
	{
		global $db_name;
		$Database = $db_name;
	}
	global $db_server, $db_user, $db_password;

	$Db_Connect = @mysql_connect($db_server,$db_user,$db_password);
	if(!$Db_Connect)
	{
		return "err_connect";
	}
	$SelectDb = @mysql_select_db($Database);
	if(!$SelectDb)
	{
		return "err_db";
	}
	return $Db_Connect;
}
?>