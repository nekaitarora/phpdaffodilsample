<?php
 /**
  * class to save configuration for conversion
  */
class ConversionConfiguration{
    
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
        $txtQuery .= "'" . $data['folderpath'] . "',";
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
    * Function to Get Schedule List
    * Parameter : No
    * Return    : Schedule Data
    */
    public function getScheduleList(){
        $query=mysql_query("SELECT schedule_configurationid_fk, schedule_sourcedir_path, schedule_destdir_path FROM conversion_wd_schedule WHERE schedule_status = 0 AND schedule_configured != '' "); 	
        $result = array();
        $i = 0;
        while ($record = mysql_fetch_array($query)) {
            $result[$i]['source_dir_path'] = $record['schedule_sourcedir_path'];
            $result[$i]['destin_dir_path'] = $record['schedule_destdir_path'];
            // Configuration location and fields for xml
            $query2=mysql_query("SELECT configuration_details_location, configuration_details_field, configuration_table_name, configuration_conversion_type FROM conversion_configuration_details cd JOIN conversion_configuration cf ON cf.configuration_id = cd.configuration_id_fk WHERE configuration_id_fk=".$record['schedule_configurationid_fk']); 	
            while ($record2 = mysql_fetch_array($query2)) {
                $result[$i]['location'][] = $record2['configuration_details_location'];
                $result[$i]['field'][]    = $record2['configuration_details_field'];
                $result[$i]['table']    = $record2['configuration_table_name'];
                $result[$i]['conversion']    = $record2['configuration_conversion_type'];
            }
            $i++;
        }
        return $result;
    }
    
    /**
    * Function to Count Conversion Configuration
    * Parameter : Object of ConversionConfiguration
    * Return    : Configuration Id
    */
    public function cntConfiguration($data){
        $txt = "'".$data['newconfig']."'";
        $query=mysql_query("SELECT COUNT(configuration_id) as Cnt FROM conversion_configuration WHERE configuration_name = ".$txt." "); 	
        $total_rec = mysql_fetch_array($query);
        return $total_rec['Cnt'];
    }
    
    /**
    * Function to Get Conversion Configuration By Id
    * Parameter : Object of ConversionConfiguration
    * Return    : Location and Fields Data
    */
    public function getConfigurationById($data){
        $txt = $data['confidId'];
        $query=mysql_query("SELECT configuration_details_location, configuration_details_field FROM conversion_configuration_details WHERE configuration_id = ".$txt." "); 	
        $result = array();
        while ($record = mysql_fetch_array($query)) {
             $result['location'][] = $record['configuration_details_location'];
             $result['field'][]    = $record['configuration_details_field'];
        }
        return $result;
    }
    
    /**
    * Function to Get Conversion List
    * Parameter : Object of ConversionConfiguration
    * Return    : Configuration Id
    */
    public function getConfigurationList(){
        $query=mysql_query("SELECT configuration_id, configuration_name FROM conversion_configuration WHERE configuration_name!= '' AND configuration_status = 0 "); 	
        $result = array();
        $col .= "<select name='selctconfig' id='selctconfig' style='width: 150px;'>";
        $col .= "<option value=''>select</option>";
        while ($record = mysql_fetch_array($query)) {
            $col .= "<option value=".$record['configuration_id'].">".$record['configuration_name']."</option>";
        }
        $col .= "</select>";
        return $col;
    }
}
?>