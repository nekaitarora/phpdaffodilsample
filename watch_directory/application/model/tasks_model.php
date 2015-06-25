<?php

/**
 * class for work flow
 */
class Tasks {

    /**
     * Function to Get Conversion Configuration By Id
     * Parameter : Object of ConversionConfiguration
     * Return    : Location and Fields Data
     */
    public function getWFTaskList() {
        $id = $_GET['id'];
        $query = mysql_query("SELECT configuration_name, schedule_id, schedule_sourcedir_path, schedule_destdir_path, schedule_configured, schedule_createdon FROM conversion_wd_schedule ws JOIN conversion_configuration cc ON ws.schedule_configurationid_fk = cc.configuration_id WHERE schedule_status = 0 and schedule_workflow_id =" . $id . "");
        $result = array();
        $i = 0;
        while ($record = mysql_fetch_array($query)) {
            $result[$i]['schedule_id'] = $record['schedule_id'];
            $result[$i]['configuration_name'] = $record['configuration_name'];
            $result[$i]['schedule_sourcedir_path'] = $record['schedule_sourcedir_path'];
            $result[$i]['schedule_destdir_path'] = $record['schedule_destdir_path'];
            $result[$i]['schedule_configured'] = $record['schedule_configured'];
            $result[$i]['schedule_createdon'] = date('d F o, H:i:s', $record['schedule_createdon']);
            $i++;
        }
        return $result;
    }

    /**
     * Function to Delete Task By Id
     * Parameter : Object of Tasks
     * Return    : Location and Fields Data
     */
    public function deleteWFTaskById($data) {
        $txt = $data['id'];
        $query = mysql_query("UPDATE conversion_wd_schedule SET schedule_status=2 WHERE schedule_id = " . $txt . " ");
        if ($query) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    /**
     * Function to Update Task By Id
     * Parameter : Object of Tasks
     * Return    : Location and Fields Data
     */
    public function updateWFTaskById($data) {
        $txt = $data['tId'];
        $txtArr = implode('=>', $data['tVal']);
        //$txtArr = $data['tVal'];
        $query = mysql_query("UPDATE conversion_wd_schedule SET schedule_configured='" . $txtArr . "' WHERE schedule_id = " . $txt . " ");
        if ($query) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    /**
     * Function to Get Task By Id
     * Parameter : Object of Task
     * Return    : Location and Fields Data
     */
    public function getWFTaskById($tID) {
        $query = mysql_query("SELECT schedule_id, schedule_configured FROM conversion_wd_schedule WHERE schedule_id = " . $tID);
        $result = array();
        while ($record = mysql_fetch_array($query)) {
            $result['schedule_id'] = $record['schedule_id'];
            $result['schedule_configured'] = $record['schedule_configured'];
        }
        return $result;
    }

    /**
     * Function to Get Source of xmltoxml Conversion
     * Parameter : Object of task
     * Return : Source and deatination path
     */
    public function getXmlSource() {
        $query = mysql_query("SELECT schedule_id,schedule_sourcedir_path FROM conversion_wd_schedule 
                              INNER JOIN conversion_configuration
                              ON conversion_configuration.configuration_id = conversion_wd_schedule.schedule_configurationid_fk
                              WHERE  conversion_configuration.configuration_type = 2 ");
        $result = array();
        $col .= "<select name='selectsource' id='selectsource' style='width: 150px;'>";
        $col .= "<option value=''>select</option>";
        while ($record = mysql_fetch_array($query)) {
            $col .= "<option value=" . $record['schedule_id'] . ">" . $record['schedule_sourcedir_path'] . "</option>";
        }
        $col .= "</select>";
        return $col;
    }

    /**
     * Function to Get Source of xmltoxml Conversion
     * Parameter : Object of task
     * Return : Source and deatination path
     */
    public function getXmlDestination() {
        $query = mysql_query("SELECT schedule_id,schedule_destdir_path
                 FROM conversion_wd_schedule
                 LEFT JOIN conversion_configuration ON 
                 conversion_configuration.configuration_id = conversion_wd_schedule.schedule_configurationid_fk
                WHERE  conversion_configuration.configuration_type = 2");
        //    $query = mysql_query("SELECT schedule_id, schedule_sourcedir_path FROM conversion_wd schedule WHERE configuration_name!= '' AND configuration_status = 0 AND configuration_type= 2 ");
        $result = array();

        $col .= "<select name='selectdes' id='selectdes' style='width: 250px;'>";
        $col .= "<option value=''>select</option>";

        while ($record = mysql_fetch_array($query)) {

            $col .= "<option value=" . $record['schedule_id'] . ">" . $record['schedule_destdir_path'] . "</option>";
        }
        $col .= "</select>";
        return $col;
    }

}

?>