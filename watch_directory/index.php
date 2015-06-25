<?php
include_once("config/include.php");
include_once("application/model/workflow_model.php");
$wfObj = new WorkFlow();
$selOpt = $wfObj->getWFList();
$cntWrk = $wfObj->cntWFList();
//echo "<pre>";
//print_r($selOpt);
//exit;
include_once(VIEW_PATH."/home.html");
?>
