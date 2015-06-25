<?php
 /**
  * class to save configuration for conversion
  */
class ConversionModel{
    
    /**
    * Function to Add Conversion Configuration
    * Parameter : Object of ConversionConfiguration
    * Return    : Configuration Id
    */
    public function addConfiguration($data){
              // Save configuration
        $txtQuery = "INSERT INTO conversion_configuration (configuration_name, configuration_table_name, configuration_conversion_type, configuration_type,configuration_createdon";
        $txtQuery .= " ) VALUES (";
        $txtQuery .= "'" . $data['newconfig'] . "',";
        $txtQuery .= "'" . $data['tablename'] . "',";
        $txtQuery .= "'" . $data['filetype'] . "',";
        $txtQuery .= "'" . $data['txttoxml'] . "',";
        $txtQuery .= "'" . time() . "'";
        $txtQuery .= ")";
        // Save configuration details
        if ($result = @mysql_query($txtQuery)) { 
            if($insert_id = mysql_insert_id()){
                foreach ($data['field'] as $key => $value) {
                    $txtQuery = "INSERT INTO conversion_configuration_details (configuration_id_fk, configuration_details_location, configuration_details_field";
                    $txtQuery .= " ) VALUES (";
                    $txtQuery .= "" . $insert_id . ",";
                    $txtQuery .= "'" . $data['location'][$key] . "',";
                    $txtQuery .= "'" . $value . "'";
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
            return TRUE;
        } 
    }
    
    /**
    * Function to Add Conversion Configuration
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
    * Function to Add Conversion Configuration
    * Parameter : Object of ConversionConfiguration
    * Return    : Configuration Id
    */
    public function getConfigurationById($data){
        $txt = $data['confidId'];
        $query=mysql_query("SELECT configuration_details_location, configuration_details_field FROM conversion_configuration_details WHERE configuration_id_fk = ".$txt." "); 	
        $result = array();
        while ($record = mysql_fetch_array($query)) {
             $result['location'][] = $record['configuration_details_location'];
             $result['field'][]    = $record['configuration_details_field'];
        }
        return $result;
    }
    
    /**
    * Function to Add Conversion Configuration
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