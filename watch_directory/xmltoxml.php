<?php 
include_once("config/include.php");
include_once("application/model/xml_conversion_model.php");
include_once("application/model/workflow_model.php");
include_once("application/model/tasks_model.php");
include_once("application/model/xmltoxml_model.php");
$taskObj = new Tasks();
$selDes = $taskObj->getXmlDestination();
$selSou = $taskObj->getXmlSource();
// Get Configuration List Dropdown
$wfObj = new ConversionModel();
$selOpt = $wfObj->getConfigurationList();
$xmlObj = new XmlConversionModel();
$selOpt1 = $xmlObj->getxmlConfigurationList();
// Work Flow Id
if (isset($_GET['id']) AND is_numeric($_GET['id'])){
    $wfId['id'] = $_GET['id'];
    $workFlow = new WorkFlow();
    $wfData = $workFlow->getWFById($wfId);
}
// Task ID
if (isset($_GET['tid']) AND is_numeric($_GET['tid'])){
    $wftId = $_GET['tid'];
    $tID = '&tid='.$wftId;
    // Task Class Object
    $wfTaskObj = new Tasks();
    $wfTaskList = $wfTaskObj->getWFTaskById($wftId);
}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// Now determine in which directory to operate
$home_directory = HOME_DIRECTORY;
$self = basename(__FILE__);
if (isset($_GET['dir']))
    $get_dir = $_GET['dir'];

$manager	= str_replace( "\\", "/", dirname(__FILE__) );	// Directory of File Manager is default
$home		= $manager;
$parent1		= str_replace( "\\", "/", dirname($manager) );	// But Parent Directory of File Manager is better if available

$checkDir = strstr($parent1, ':', true);
if($checkDir){
	$parent = $checkDir.":".$get_dir;
} else {
	$parent = "/";
}
$parent = $manager;
if (@opendir($parent)){
    $home = $parent;
    if (@opendir("$parent/$home_directory"))
    {
        $home = "$parent/$home_directory";	// and we go to the specified  directory if there is one
    }
}
// which folder to browse (with failsafe)
if( !empty($_GET['dir']) && @opendir($get_dir) )
    $dir = str_replace( "\\", "/", $get_dir );
else   //default dir
    $dir = $home;
	
$browsing = false;
$showhome = true;
$showfiles = true;
$deletedir = false;
$newdir = $dir;
$browseflag = '';
$targetfile = '';
$inputlabel = 'Path';
$listTypeSrc = '&dir2='.$_GET['dir2'].'&id='.$_GET['id'].$tID;

//$dirFPath = dirname(__DIR__);
$dirFPath = '';
//echo $dirFPath;exit;

//======================================================================
//	The filelist function of the program
//======================================================================
function loop_dir($dir){
    global $self, $fname, $content, $subdirs, $refresh, $dir_up, $all_dir, $unread_dir, $all_file, $unread_file, $file_count;
    global $home, $browsing, $browseflag, $listTypeSrc, $dirFPath;

    //check if directory can be open
    if ($handle = @opendir($dir)){
        //loop through the dir for FILES and DIR
        while ( false != ($file = readdir($handle)) )
        {
//            echo $dir;exit;
                $q_file = addslashes($file);
                $q_dir  = addslashes($dir);

                //increase the maximum execute time
                set_time_limit(10);
                $full_path = str_replace( "//", "/", $dir . "/" . $file);
//                $full_path = str_replace( "//", "/", dirname(__DIR__));
//                echo $file;
//                echo "<br/>";
//                echo $dir;
//                exit;
//              echo $full_path;exit;

                if( $file == "." )
                {
                        $refresh = "<tr bgcolor=\"#F5F5F5\">";
                        if (!$browsing)
                        {
                                $refresh .= "<td align=\"left\" oncontextmenu=\"
                                action_file = '';
                                action_dir  = '$q_dir';
                                show_contextmenu(event, refreshmenu);
                                return false;
                                \">";
                        }
                        else
                                $refresh .= "<td align=\"left\">";

                        $refresh .= "<img src=\"".BASE_PATH."/images/sysimg/refresh.png\" border=\"0\" alt=\"\" />
                        <a href=\"$self?dir=$dirFPath$dir$browseflag$listTypeSrc\">Refresh</a></td>
                        <td align=\"center\"></td>
                        <td align=\"left\"></td>
                        <td align=\"left\"></td></tr>\r\n";
                }
                elseif( $file == ".." )
                {
                        if (RESTRICTED_BROWSING && $dir == $home)
                                continue;									// no browsing up from home !

                        $up_lvl = str_replace( "\\", "/", dirname($dir . "..") );
                        $dir_up = "<tr bgcolor=\"#FFFFFF\">
                        <td align=\"left\"><img src=\"".BASE_PATH."/images/sysimg/back.png\" border=\"0\" alt=\"\" />
                        <a href=\"$self?dir=$dirFPath$up_lvl$browseflag$listTypeSrc\">Up one level</a></td>
                        <td align=\"center\"></td>
                        <td align=\"left\"></td>
                        <td align=\"left\"></td></tr>\r\n";
                }
                //this is a directory, set the attr etc.
                elseif( is_dir($full_path) )
                {
                        $perm = substr(sprintf('%o', @fileperms("$full_path")), -4);
                        $time_mod = date("Y M d h:i A" ,filemtime($full_path));

                        //check if the dir can be open or not
                        if( @opendir($full_path) )
                        {
                                //loop to subdirs if specify by user
                                if( $subdirs == 1 )
                                        loop_dir($full_path);

                                if( !empty($fname) && stristr($file, $fname) ) //search for the dir
                                {
                                        //store all dirs in array
                                        $all_dir[] .= "<td align=\"left\" oncontextmenu=\"
                                                action_file = '$q_file';
                                                action_dir  = '$q_dir';
                                                show_contextmenu(event, dirmenu);
                                                return false;
                                        \">
                                        <img src=\"".BASE_PATH."/images/ext_ico/folder.png\" border=\"0\" alt=\"\" />
                                        <a href=\"$self?dir=$dirFPath$full_path$listTypeSrc\" title=\"$full_path\">$file</a></td>
                                        <td align=\"center\">-</td>
                                        <td align=\"center\">$perm</td>
                                        <td align=\"left\">$time_mod</td>";
                                }
                                elseif( empty($content) )						//not searching contents, display them all
                                {
                                        //store all dirs in array

                                        if (!$browsing)
                                                $all_dir[] .= "<td align=\"left\" oncontextmenu=\"
                                                        action_file = '$q_file';
                                                        action_dir  = '$q_dir';
                                                        show_contextmenu(event, dirmenu);
                                                        return false;\">
                                                        <img src=\"".BASE_PATH."/images/ext_ico/folder.png\" border=\"0\" alt=\"\" />
                                                        <a href=\"$self?dir=$dirFPath$full_path$browseflag$listTypeSrc\" title=\"$full_path\">$file</a></td>
                                                        <td align=\"center\">-</td>
                                                        <td align=\"center\">$perm</td>
                                                        <td align=\"left\">$time_mod</td>
                                                        ";
                                        else
                                                $all_dir[] .= "<td align=\"left\">
                                                        <img src=\"".BASE_PATH."/images/ext_ico/folder.png\" border=\"0\" alt=\"\" />
                                                        <a href=\"$self?dir=$dirFPath$full_path$browseflag$listTypeSrc\" title=\"$full_path\">$file</a></td>
                                                        <td align=\"center\">-</td>
                                                        <td align=\"center\">$perm</td>
                                                        <td align=\"left\">$time_mod</td>
                                                        ";
                                }
                        }
                        else	// the dir cannot be read
                        {
                                $unread_dir[] .= "<td align=\"left\">
                                <img src=\"".BASE_PATH."/images/ext_ico/folder2.png\" border=\"0\" alt=\"\" /> 
                                $file
                                </td>
                                <td align=\"center\">-</td>
                                <td align=\"center\">$perm</td>
                                <td align=\"left\">$time_mod</td>";
                        }

                }
                else
                {
                        //for normal file, these are the attr
                        $size = filesize($full_path);

                        if( $size >= 0 && $size < 1024 )
                                $size = $size . " B";

                        elseif( $size >= 1024 && $size < 1048576 )			//round to KB
                                $size = round(($size/1024),2) . " KB";

                        elseif( $size >= 1048576 && $size < 1073741824 )	//round to MB
                                $size = round(($size/1048576),2) . " MB";

                        elseif( $size >= 1073741824 )						//round to GB
                                $size = round(($size/1073741824),2) . " GB";

                        else												//invalid size, error
                                $size = "--";


                        $perm = substr(sprintf('%o', @fileperms("$full_path")), -4);
                        $time_mod = date("Y M d h:i A" ,filemtime($full_path));

                        //check for icon for this filetype
                        $ext = substr(strrchr($file, "."), 1);

                        //icon for normal readable file
                        if( file_exists( "".BASE_PATH."/images/ext_ico/" . $ext . ".png") )
                                $icon_normal = "".BASE_PATH."/images/ext_ico/" . $ext . ".png";
                        else												//set as unknown filetype icon
                                $icon_normal = "".BASE_PATH."/images/ext_ico/file.png";

                        //icon for unreadable file
                        if( file_exists( "".BASE_PATH."/images/ext_ico/" . $ext . "2.png") )
                                $icon_unview = "".BASE_PATH."/images/ext_ico/" . $ext . "2.png";
                        else												//set as unknown filetype icon
                                $icon_unview = "".BASE_PATH."/images/ext_ico/file2.png";

                        //check if the file can be read
                        if( @fopen($full_path, "rb") )
                        {
                                //search for the content as well if user request
                                if( !empty($content) )
                                        $file_data = file_get_contents($full_path);

                                //searching for files and content if so
                                if( !empty($fname) )
                                {
                                        //search for the name 
                                        if( stristr($file, $fname) )
                                        {
                                                //store all files in array
                                                $all_file[] .= "\n<!--$file!-->
                                                <td align=\"left\" oncontextmenu=\"
                                                        action_file = '$q_file';
                                                        action_dir  = '$q_dir';
                                                        show_contextmenu(event, filemenu);
                                                        return false;
                                                \">
                                                <img src=\"$icon_normal\" border=\"0\" alt=\"\" />
                                                <a href=\"$self?fname=$full_path\" title=\"$full_path\">" . $file . "</a></td>
                                                <td align=\"right\">$size</td>
                                                <td align=\"center\">$perm</td>
                                                <td align=\"left\">$time_mod</td>";
                                        }
                                }
                                //search the file with part of this content
                                elseif( !empty($content) )
                                { 
                                        if( stristr($file_data, $content) )
                                        {
                                                //store all files in array
                                                $all_file[] .= "\n<!--$file!-->
                                                <td align=\"left\" oncontextmenu=\"
                                                        action_file = '$q_file';
                                                        action_dir  = '$q_dir';
                                                        show_contextmenu(event, filemenu);
                                                        return false;
                                                \">
                                                <img src=\"$icon_normal\" border=\"0\" alt=\"\" />
                                                <a href=\"$self?fname=$full_path\" title=\"$full_path\">" . $file . "</a></td>
                                                <td align=\"right\">$size</td>
                                                <td align=\"center\">$perm</td>
                                                <td align=\"left\">$time_mod</td>";
                                        }

                                }
                                elseif( empty($fname) )
                                {
                                        //store all files in array
                                        if (!$browsing)								// normal state : regular file is a link with context menu
                                        {						
                                                $all_file[] .= "\n<!--$file!-->
                                                <td align=\"left\" oncontextmenu=\"
                                                        action_file = '$file';
                                                        action_dir  = '$dir';
                                                        show_contextmenu(event, filemenu);
                                                        return false;
                                                \">
                                                <img src=\"$icon_normal\" border=\"0\" alt=\"\" />
                                                <a href=\"$self?fname=$full_path\" title=\"$full_path\">$file</a></td>
                                                <td align=\"right\">$size</td>
                                                <td align=\"center\">$perm</td>
                                                <td align=\"left\">$time_mod</td>";
                                        }
                                        else										// browsing : file is only a picture, no action
                                        {
                                                $all_file[] .= "\n<!--$file!-->
                                                <td align=\"left\"\">
                                                <img src=\"$icon_normal\" border=\"0\" alt=\"\" />$file</td>
                                                <td align=\"right\">$size</td>
                                                <td align=\"center\">$perm</td>
                                                <td align=\"left\">$time_mod</td>";
                                        }
                                }
                        }
                        else
                        {
                                //file cannot be read
                                $unread_file[] .= "\n<!--$file!-->
                                <td align=\"left\"><img src=\"$icon_unview\" border=\"0\" alt=\"\" /> "
                                . $file . "</td>
                                <td align=\"right\">$size</td>
                                <td align=\"center\">$perm</td>
                                <td align=\"left\">$time_mod</td>";
                        }
                }
                //incre the file number
                $file_count++;
        }
        //display the files and dirs
        @natcasesort($all_dir);
        @natcasesort($unread_dir);
        @natcasesort($all_file);
        @natcasesort($unread_file);
    }
}
//==============================End of main function==========================
//run the main function
$all_dir		= array();
$unread_dir		= array();
$all_file		= array();
$unread_file	= array();

$file_count = 0;
loop_dir($dir);
	
//merge all the files and dirs which in in array
$all_files = array_merge($all_dir, $unread_dir, $all_file, $unread_file);
$count = @count($all_files);

//the rows for up one level and refresh
$body_count = 0;
$body = $dir_up . $refresh;
if ($dir_up != "")
        $body_count += 1;
if ($refresh != "")
        $body_count +=1;

//if the dir isn't empty then loop them out
if( $count > 0 ) {

        //the dirs part
        $bg = "#F5F5F5";
        for( $i = 0; $i < $count; $i++ ) {
                //show all the files and folder with bgcolor switch
                if( $bg == "#FFFFFF" ) {
                        $bg = "#F5F5F5";
                }
                else {
                        $bg = "#FFFFFF";
                }
                $body_count += 1;
                $body .= "<tr bgcolor=\"$bg\">" . $all_files[$i] . "</tr>\r\n";
        }
}
//show the dir is empty
else {
        $body_count += 1;
        $body .= "<tr bgcolor=\"#FFFFFF\">
                <td colspan=\"4\" align=\"center\" valign=\"middle\" height=\"32\">- No Files Found -</td>
        </tr>\r\n";
}
/*************************************************************************************************/
/*************************************************************************************************/
// Now determine in which directory to operate
$home_directory2 = HOME_DIRECTORY;
$self2 = basename(__FILE__);
if (isset($_GET['dir2']))
	$get_dir2 = $_GET['dir2'];
	
$manager2	= str_replace( "\\", "/", dirname(__FILE__) );		// Directory of File Manager is default
$home2		= $manager2;

$parent3		= str_replace( "\\", "/", dirname($manager2) );		// But Parent Directory of File Manager is better if available


$checkDir2 = strstr($parent3, ':', true);
if($checkDir){
	$parent2 = $checkDir2.":".$get_dir2;
} else {
	$parent2 = "/";
}
$parent2 = $manager2;

if (@opendir($parent2)){
    $home2 = $parent2;
    if (@opendir("$parent2/$home_directory2"))
    {
            $home2 = "$parent2/$home_directory2";						// and we go to the specified  directory if there is one
    }
}
// which folder to browse (with failsafe)
if( !empty($_GET['dir2']) && @opendir($get_dir2) )
	$dir2 = str_replace( "\\", "/", $get_dir2 );
else   //default dir
	$dir2 = $home2;

$browsing2 = false;
$showhome2 = true;
$showfiles2 = true;
$deletedir2 = false;
$newdir2 = $dir2;
$browseflag2 = '';
$targetfile2 = '';
$inputlabel2 = 'Path';
$listTypeSrc2 = '&dir='.$_GET['dir'].'&id='.$_GET['id'].$tID;
//======================================================================
// The filelist function of the program
//======================================================================
function loop_dir2($dir2){
    global $self2, $fname2, $content2, $subdirs2, $refresh2, $dir_up2, $all_dir2, $unread_dir2, $all_file2, $unread_file2, $file_count2;
    global $home2, $browsing2, $browseflag2, $listTypeSrc2;

    //check if directory can be open
    if ($handle2 = @opendir($dir2)){
        //loop through the dir for FILES and DIR
        while ( false != ($file2 = readdir($handle2)) )
        {
                $q_file2 = addslashes($file2);
                $q_dir2  = addslashes($dir2);

                //increase the maximum execute time
                set_time_limit(10);
                $full_path2 = str_replace( "//", "/", $dir2 . "/" . $file2);

                if( $file2 == "." )
                {
                        $refresh2 = "<tr bgcolor=\"#F5F5F5\">";
                        if (!$browsing2)
                        {
                                $refresh2 .= "<td align=\"left\" oncontextmenu=\"
                                action_file = '';
                                action_dir  = '$q_dir2';
                                show_contextmenu(event, refreshmenu);
                                return false;
                                \">";
                        }
                        else
                                $refresh2 .= "<td align=\"left\">";

                        $refresh2 .= "<img src=\"".BASE_PATH."/images/sysimg/refresh.png\" border=\"0\" alt=\"\" />
                        <a href=\"$self2?dir2=$dir2$browseflag2$listTypeSrc2\">Refresh</a></td>
                        <td align=\"center\"></td>
                        <td align=\"left\"></td>
                        <td align=\"left\"></td></tr>\r\n";
                }
                elseif( $file2 == ".." )
                {
                        if (RESTRICTED_BROWSING && $dir2 == $home2)
                                continue;									// no browsing up from home !

                        $up_lvl2 = str_replace( "\\", "/", dirname($dir2 . "..") );
                        $dir_up2 = "<tr bgcolor=\"#FFFFFF\">
                        <td align=\"left\"><img src=\"".BASE_PATH."/images/sysimg/back.png\" border=\"0\" alt=\"\" />
                        <a href=\"$self2?dir2=$up_lvl2$browseflag2$listTypeSrc2\">Up one level</a></td>
                        <td align=\"center\"></td>
                        <td align=\"left\"></td>
                        <td align=\"left\"></td></tr>\r\n";
                }
                //this is a directory, set the attr etc.
                elseif( is_dir($full_path2) )
                {
                        $perm2 = substr(sprintf('%o', @fileperms("$full_path2")), -4);
                        $time_mod2 = date("Y M d h:i A" ,filemtime($full_path2));

                        //check if the dir can be open or not
                        if( @opendir($full_path2) )
                        {
                                //loop to subdirs if specify by user
                                if( $subdirs2 == 1 )
                                        loop_dir($full_path2);

                                if( !empty($fname2) && stristr($file2, $fname2) ) //search for the dir
                                {
                                        //store all dirs in array
                                        $all_dir2[] .= "<td align=\"left\" oncontextmenu=\"
                                                action_file = '$q_file2';
                                                action_dir  = '$q_dir2';
                                                show_contextmenu(event, dirmenu);
                                                return false;
                                        \">
                                        <img src=\"".BASE_PATH."/images/ext_ico/folder.png\" border=\"0\" alt=\"\" />
                                        <a href=\"$self2?dir2=$full_path2$listTypeSrc2$listTypeSrc2\" title=\"$full_path2\">$file2</a></td>
                                        <td align=\"center\">-</td>
                                        <td align=\"center\">$perm2</td>
                                        <td align=\"left\">$time_mod2</td>";
                                }
                                elseif( empty($content2) )						//not searching contents, display them all
                                {
                                        //store all dirs in array

                                        if (!$browsing2)
                                                $all_dir2[] .= "<td align=\"left\" oncontextmenu=\"
                                                        action_file = '$q_file2';
                                                        action_dir  = '$q_dir2';
                                                        show_contextmenu(event, dirmenu);
                                                        return false;\">
                                                        <img src=\"".BASE_PATH."/images/ext_ico/folder.png\" border=\"0\" alt=\"\" />
                                                        <a href=\"$self2?dir2=$full_path2$browseflag2$listTypeSrc2\" title=\"$full_path2\">$file2</a></td>
                                                        <td align=\"center\">-</td>
                                                        <td align=\"center\">$perm2</td>
                                                        <td align=\"left\">$time_mod2</td>
                                                        ";
                                        else
                                                $all_dir2[] .= "<td align=\"left\">
                                                        <img src=\"".BASE_PATH."/images/ext_ico/folder.png\" border=\"0\" alt=\"\" />
                                                        <a href=\"$self2?dir2=$full_path2$browseflag2$listTypeSrc2\" title=\"$full_path2\">$file2</a></td>
                                                        <td align=\"center\">-</td>
                                                        <td align=\"center\">$perm2</td>
                                                        <td align=\"left\">$time_mod2</td>
                                                        ";
                                }
                        }
                        else	// the dir cannot be read
                        {
                                $unread_dir2[] .= "<td align=\"left\">
                                <img src=\"".BASE_PATH."/images/ext_ico/folder2.png\" border=\"0\" alt=\"\" /> 
                                $file2
                                </td>
                                <td align=\"center\">-</td>
                                <td align=\"center\">$perm2</td>
                                <td align=\"left\">$time_mod2</td>";
                        }

                }
                else
                {
                        //for normal file, these are the attr
                        $size2 = filesize($full_path2);

                        if( $size2 >= 0 && $size2 < 1024 )
                                $size2 = $size2 . " B";

                        elseif( $size2 >= 1024 && $size2 < 1048576 )			//round to KB
                                $size2 = round(($size2/1024),2) . " KB";

                        elseif( $size2 >= 1048576 && $size2 < 1073741824 )	//round to MB
                                $size2 = round(($size2/1048576),2) . " MB";

                        elseif( $size2 >= 1073741824 )						//round to GB
                                $size2 = round(($size2/1073741824),2) . " GB";

                        else												//invalid size, error
                                $size2 = "--";


                        $perm2 = substr(sprintf('%o', @fileperms("$full_path2")), -4);
                        $time_mod2 = date("Y M d h:i A" ,filemtime($full_path2));

                        //check for icon for this filetype
                        $ext2 = substr(strrchr($file2, "."), 1);

                        //icon for normal readable file
                        if( file_exists( "".BASE_PATH."/images/ext_ico/" . $ext2 . ".png") )
                                $icon_normal2 = "".BASE_PATH."/images/ext_ico/" . $ext2 . ".png";
                        else												//set as unknown filetype icon
                                $icon_normal2 = "".BASE_PATH."/images/ext_ico/file.png";

                        //icon for unreadable file
                        if( file_exists( "".BASE_PATH."/images/ext_ico/" . $ext2 . "2.png") )
                                $icon_unview2 = "".BASE_PATH."/images/ext_ico/" . $ext2 . "2.png";
                        else												//set as unknown filetype icon
                                $icon_unview2 = "".BASE_PATH."/images/ext_ico/file2.png";

                        //check if the file can be read
                        if( @fopen($full_path2, "rb") )
                        {
                                //search for the content as well if user request
                                if( !empty($content2) )
                                        $file_data2 = file_get_contents($full_path2);

                                //searching for files and content if so
                                if( !empty($fname2) )
                                {
                                        //search for the name 
                                        if( stristr($file2, $fname2) )
                                        {
                                                //store all files in array
                                                $all_file2[] .= "\n<!--$file2!-->
                                                <td align=\"left\" oncontextmenu=\"
                                                        action_file = '$q_file2';
                                                        action_dir  = '$q_dir2';
                                                        show_contextmenu(event, filemenu);
                                                        return false;
                                                \">
                                                <img src=\"$icon_normal2\" border=\"0\" alt=\"\" />
                                                <a href=\"$self2?fname=$full_path2\" title=\"$full_path2\">" . $file2 . "</a></td>
                                                <td align=\"right\">$size2</td>
                                                <td align=\"center\">$perm2</td>
                                                <td align=\"left\">$time_mod2</td>";
                                        }
                                }
                                //search the file with part of this content
                                elseif( !empty($content2) )
                                { 
                                        if( stristr($file_data2, $content2) )
                                        {
                                                //store all files in array
                                                $all_file2[] .= "\n<!--$file2!-->
                                                <td align=\"left\" oncontextmenu=\"
                                                        action_file = '$q_file2';
                                                        action_dir  = '$q_dir2';
                                                        show_contextmenu(event, filemenu);
                                                        return false;
                                                \">
                                                <img src=\"$icon_normal2\" border=\"0\" alt=\"\" />
                                                <a href=\"$self2?fname=$full_path2\" title=\"$full_path2\">" . $file2 . "</a></td>
                                                <td align=\"right\">$size2</td>
                                                <td align=\"center\">$perm2</td>
                                                <td align=\"left\">$time_mod2</td>";
                                        }

                                }
                                elseif( empty($fname2) )
                                {
                                        //store all files in array
                                        if (!$browsing2)								// normal state : regular file is a link with context menu
                                        {						
                                                $all_file2[] .= "\n<!--$file2!-->
                                                <td align=\"left\" oncontextmenu=\"
                                                        action_file = '$file2';
                                                        action_dir  = '$dir2';
                                                        show_contextmenu(event, filemenu);
                                                        return false;
                                                \">
                                                <img src=\"$icon_normal2\" border=\"0\" alt=\"\" />
                                                <a href=\"$self2?fname=$full_path2\" title=\"$full_path2\">$file2</a></td>
                                                <td align=\"right\">$size2</td>
                                                <td align=\"center\">$perm2</td>
                                                <td align=\"left\">$time_mod2</td>";
                                        }
                                        else										// browsing : file is only a picture, no action
                                        {
                                                $all_file2[] .= "\n<!--$file2!-->
                                                <td align=\"left\"\">
                                                <img src=\"$icon_normal2\" border=\"0\" alt=\"\" />$file2</td>
                                                <td align=\"right\">$size2</td>
                                                <td align=\"center\">$perm2</td>
                                                <td align=\"left\">$time_mod2</td>";
                                        }
                                }
                        }
                        else
                        {
                                //file cannot be read
                                $unread_file2[] .= "\n<!--$file2!-->
                                <td align=\"left\"><img src=\"$icon_unview2\" border=\"0\" alt=\"\" /> "
                                . $file2 . "</td>
                                <td align=\"right\">$size2</td>
                                <td align=\"center\">$perm2</td>
                                <td align=\"left\">$time_mod2</td>";
                        }
                }
                //incre the file number
                $file_count2++;
        }
        //display the files and dirs
        @natcasesort($all_dir2);
        @natcasesort($unread_dir2);
        @natcasesort($all_file2);
        @natcasesort($unread_file2);
    }
}
//==============================End of main function==========================
//run the main function
$all_dir2	= array();
$unread_dir2	= array();
$all_file2      = array();
$unread_file2	= array();

$file_count2 = 0;
loop_dir2($dir2);
//merge all the files and dirs which in in array
$all_files2 = array_merge($all_dir2, $unread_dir2, $all_file2, $unread_file2);
$count2 = @count($all_files2);
//the rows for up one level and refresh
$body_count2 = 0;
$body2 = $dir_up2 . $refresh2;
if ($dir_up2 != "")
        $body_count2 += 1;
if ($refresh2 != "")
    $body_count2 +=1;
//if the dir isn't empty then loop them out
if( $count2 > 0 ) {
    //the dirs part
    $bg2 = "#F5F5F5";
    for( $i = 0; $i < $count2; $i++ ) {
        //show all the files and folder with bgcolor switch
        if( $bg2 == "#FFFFFF" ) {
                $bg2 = "#F5F5F5";
        }
        else {
                $bg2 = "#FFFFFF";
        }
        $body_count2 += 1;
        $body2 .= "<tr bgcolor=\"$bg2\">" . $all_files2[$i] . "</tr>\r\n";
    }
}
//show the dir is empty
else {
    $body_count2 += 1;
    $body2 .= "<tr bgcolor=\"#FFFFFF\">
            <td colspan=\"4\" align=\"center\" valign=\"middle\" height=\"32\">- No Files Found -</td>
    </tr>\r\n";
}


/*************************************************************************************************/    
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
include_once(VIEW_PATH."/xmltotxl.html");
?>
