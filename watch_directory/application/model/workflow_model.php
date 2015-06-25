
<?php

/**
 * class for work flow
 */
class WorkFlow {

    /**
     * Function to Add Conversion Schedule
     * Parameter : Object of ConversionConfiguration
     * Return    : Configuration Id
     */
    public function throw_ex($er) {
        throw new Exception($er);
    }

    public function saveWorkFlow($data) {
        // Save configuration
        $txtQuery = "INSERT INTO conversion_workflow (workflow_name, workflow_description, workflow_createdon, workflow_modifiedon";
        $txtQuery .= " ) VALUES (";
        $txtQuery .= "'" . $data['wfname'] . "',";
        $txtQuery .= "'" . $data['wfdesc'] . "',";
        $txtQuery .= "'" . time() . "',";
        $txtQuery .= "" . time() . "";
        $txtQuery .= ")";
        try {
            $q = @mysql_query($txtQuery) or $this->throw_ex(mysql_error());
            if ($q) {
                return TRUE;
            }
        } catch (exception $e) {
//          echo "[Save Work Flow]: ".$e->getMessage().'<br/>'; 
            return FALSE;
        }
    }

    /**
     * Function to Get Work Flow List
     * Parameter : No
     * Return    : Schedule Data
     */
    public function getWFPage() {
        $rec_limit = 5;
        if (isset($_GET{'page'})) {
            $page = $_GET{'page'} + 1;
            $offset = $rec_limit * $page;
        } else {
            $page = 0;
            $offset = 0;
        }
        $left_rec = $rec_count - ($page * $rec_limit);
        $ar=array();
        $ar['offset']=$offset;
        $ar['left_rec']=$left_rec;
        $ar['page'] = $page;
        return $ar;
    }

    /**
     * Function to Get Work Flow List
     * Parameter : No
     * Return    : Schedule Data
     */
    public function getWFList($start,$limit) {
        
          $query = mysql_query("SELECT workflow_id, workflow_name, workflow_description " .
                "FROM conversion_workflow " .
                "LIMIT $start, $limit");
        $result = array();
        $i = 0;
        while ($record = mysql_fetch_array($query)) {
            $result[$i]['wf_id'] = $record['workflow_id'];
            $result[$i]['wf_name'] = $record['workflow_name'];
            $result[$i]['wf_created'] = date('d F o, H:i:s', $record['workflow_createdon']);
            $i++;
        }
        return $result;
    }

    /**
     * Function to Get Work Flow List
     * Parameter : No
     * Return    : Schedule Data
     */
    public function cntWFList() {
        //$txt = "'" . $data1['newconfig'] . "'";
        $query = mysql_query("SELECT COUNT(workflow_id) as wrk FROM conversion_workflow");
        $total_wrk = mysql_fetch_array($query);
        return $total_wrk['wrk'];
    }

    /**
     * Function to Count Conversion Configuration
     * Parameter : Object of ConversionConfiguration
     * Return    : Configuration Id
     */
    public function cntConfiguration($data) {
        $txt = "'" . $data['newconfig'] . "'";
        $query = mysql_query("SELECT COUNT(configuration_id) as Cnt FROM conversion_configuration WHERE configuration_name = " . $txt . " ");
        $total_rec = mysql_fetch_array($query);
        return $total_rec['Cnt'];
    }

    /**
     * Function to Get Conversion Configuration By Id
     * Parameter : Object of ConversionConfiguration
     * Return    : Location and Fields Data
     */
    public function getWFById($data) {
        $txt = $data['id'];
        $query = mysql_query("SELECT workflow_name, workflow_description FROM conversion_workflow WHERE workflow_id = " . $txt . " ");
        $result = array();
        while ($record = mysql_fetch_array($query)) {
            $result['wf_name'] = $record['workflow_name'];
            $result['wf_desc'] = $record['workflow_description'];
        }
        return $result;
    }

    /**
     * Function to Delete Work Flow By Id
     * Parameter : Object of WorkFlow
     * Return    : Location and Fields Data
     */
    public function deleteWFById($data) {
        $txt = $data['id'];
        $query = mysql_query("UPDATE conversion_workflow SET workflow_status=2 WHERE workflow_id = " . $txt . " ");
        if ($query) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    /**
     * Function to Get Conversion List
     * Parameter : Object of ConversionConfiguration
     * Return    : Configuration Id
     */
    public function getConfigurationList() {
        $query = mysql_query("SELECT configuration_id, configuration_name FROM conversion_configuration WHERE configuration_name!= '' AND configuration_status = 0 ");
        $result = array();
        $col .= "<select name='selctconfig' id='selctconfig' style='width: 150px;'>";
        $col .= "<option value=''>select</option>";
        while ($record = mysql_fetch_array($query)) {
            $col .= "<option value=" . $record['configuration_id'] . ">" . $record['configuration_name'] . "</option>";
        }
        $col .= "</select>";
        return $col;
    }

}

?>