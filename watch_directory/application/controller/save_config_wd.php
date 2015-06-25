<?php
include_once("../model/watch_directory_configuration.php");
include_once('../../config/include.php');

if($_REQUEST['folderpath']== ''){
    echo "Please provide directory path.";
    die;
}
if($_REQUEST['selctconfig'] == ''){
    echo "Please select file conversion configuration.";
    die;
}

// Save Cron
$saveConfig['save_sch_id']     = $_REQUEST['save_sch_id'];
$saveConfig['folderpath']      = $_REQUEST['folderpath'];
$saveConfig['selctconfig']     = $_REQUEST['selctconfig'];
$saveConfig['destfolderpath']  = $_REQUEST['destfolderpath'];

// Save configuration details
$conversionConfiguration = new ConversionConfiguration();
$saveCron = $conversionConfiguration->saveSchedule($saveConfig);
if($saveCron){
    echo "Schedule created successfully.";
    die;
} else {
    echo "Something went wrong, please try again.";
    die;
}
?>