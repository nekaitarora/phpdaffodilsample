<?php
include_once("config/include.php");
include_once("application/model/tasks_model.php");
include_once("application/model/workflow_model.php");
// Work Flow Class Object
$wfObj = new WorkFlow();
$selOpt = $wfObj->getWFList();
// Task Class Object
$wfTaskObj = new Tasks();
$wfTaskList = $wfTaskObj->getWFTaskList();
// Work Flow Name
if (isset($_GET['id']) AND is_numeric($_GET['id'])){
    $wfId['id'] = $_GET['id'];
    $wfData = $wfObj->getWFById($wfId);
}
include_once(VIEW_PATH."/task-list.html");
?>
