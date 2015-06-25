<?php
include_once("../model/tasks_model.php");
include_once('../../config/include.php');
$tasksObj = new Tasks();

// Delete
if($_POST['type'] == 'delete'){
    $data['id'] = $_POST['id'];
    if($tasksObj->deleteWFTaskById($data)){
        echo "success";
    } else {
        echo "error";
    }
}

// Status Update Config Set Up
if($_POST['type'] == 'status_update_task'){
    $data['tId']  = $_POST['tId'];
    $data['tVal'] = $_POST['tVal'];
    if($tasksObj->updateWFTaskById($data)){
        echo "success";
    } else {
        echo "error";
    }
}
?>