<?php
class dataManager
{
    public function __construct()
    {
        static $con;

        if(!isset($con))
        {
                $con = mysql_connect(_HOST,_USER,_PASS);
                if (!$con)
                {
                  die('Could not connect: ' . mysql_error());
                }

                //if (!mysqli_select_db($con, _DB))
                if (!mysql_select_db(_DB))
                {
                  die('DataBase not found: ' . mysql_error());
                }

        }

    }
}
?>