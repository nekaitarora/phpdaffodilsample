<?php

/**
 * class to save configuration for conversion
 */
class XmlConversionModel {
    
    
     /**
    * Function to Add Conversion Schedule
    * Parameter : Object of ConversionConfiguration
    * Return    : Configuration Id
    */
    public function saveSchedule($data){
        // Save configuration
        $txtQuery = "INSERT INTO conversion_wd_schedule (schedule_configurationid_fk,schedule_workflow_id ,schedule_sourcedir_path, schedule_destdir_path, schedule_createdon";
        $txtQuery .= " ) VALUES (";
        $txtQuery .= "" . $data['xmlconfig'] . ",";
        $txtQuery .= "" . $data['save_sch_id'] . ",";
        $txtQuery .= "'" .$data['folderpath'] . "',";
        $txtQuery .= "'" . $data['destfolderpath'] . "',";
        $txtQuery .= "" . time() . "";
        $txtQuery .= ")";
        // Save configuration details
        if($result = @mysql_query($txtQuery)){
            return TRUE;
        } else {
            return FALSE;
        } 
    }
    
      /**
    * Function to Add Conversion Configuration
    * Parameter : Object of ConversionConfiguration
    * Return    : Configuration Id
    */
     public function addConfig($data){
       
              // Save configuration
        $txtQuery = "INSERT INTO conversion_configuration (configuration_name, configuration_type,configuration_createdon";
        $txtQuery .= " ) VALUES (";
        $txtQuery .= "'" . $data['xmlconfig'] . "',";
        $txtQuery .= "'" . $data['xmltoxml'] . "',";
        $txtQuery .= "'" . time() . "'";
        $txtQuery .= ")";
        // Save configuration details
        if ($result = @mysql_query($txtQuery)) { 
            if($insert_id = mysql_insert_id()){
                foreach ($data['field'] as $key => $value) {
                    $txtQuery = "INSERT INTO conversion_configuration_details (configuration_id_fk";
                    $txtQuery .= " ) VALUES (";
                    $txtQuery .= "" . $insert_id . ",";
                    $txtQuery .= ")";
                    if ($result = @mysql_query($txtQuery)) {
                        if($configId = mysql_insert_id()){
                            
                        }
                    }
                }
            }
        }
         if(mysql_errno()){
            return FALSE;
        } else {
            return $insert_id;
        } 
    }
    /**
     * Function to Get Conversion Configuration
     * Parameter : Object of ConversionConfiguration
     * Return    : Configuration List
     */
    public function getxmlConfigurationList() {
        $query = mysql_query("SELECT configuration_id, configuration_name FROM conversion_configuration WHERE configuration_name!= '' AND configuration_status = 0 AND configuration_type= 2 ");
        $result = array();
        $col .= "<select name='exisconfig' id='xmlconfig' style='width: 150px;'>";
        $col .= "<option value=''>select</option>";
        while ($record = mysql_fetch_array($query)) {
            $col .= "<option value=" . $record['configuration_id'] . ">" . $record['configuration_name'] . "</option>";
        }
        $col .= "</select>";
        return $col;
    }
    

}

?>