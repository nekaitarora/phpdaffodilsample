<?php
include_once("../model/xml_conversion_model.php");
include_once('../../config/include.php');
// Create conversion class object
$conversionConfiguration = new ConversionModel();
// Check for configuration
if(isset($_REQUEST['newconfig']) AND $_REQUEST['selctconfig'] == ''){
    $saveConfig['newconfig'] = $_REQUEST['newconfig'];
    if(trim($saveConfig['newconfig']) != ''){
        $chkConfig = $conversionConfiguration->cntConfiguration($saveConfig);
        if($chkConfig > 0){
            echo "Configuration name already exist.";
            die;
        }
    } else {
        echo "Please select configuration.";
        die;
    }
}
// Check for file name
if($_REQUEST['filename'] == ''){
  echo "Please upload a file.";
  die;  
}

if(!empty($_REQUEST['tablename'])){
    $tableName = $_REQUEST['tablename'];
} else {
    $tableName = 'table';
}
// Get Text File Data and Loop through the data
$filedata = file_get_contents('../../public/uploaded_files/'.$_REQUEST['filename']);
$array = explode("\n",$filedata);
// Check file type individual OR batch(single)
if($_REQUEST['filetype'] == 'individual'){
    $i = 1;
    // Loop through data from text file
    foreach(array_filter($array) as $k=>$v){
        /* create a dom document with encoding utf8 */
        $domtree = new DOMDocument('1.0', 'UTF-8');
        /* create the root element of the xml tree */
        $xmlRoot = $domtree->createElement("xml");
        /* append it to the document created */
        $xmlRoot = $domtree->appendChild($xmlRoot);
        $currentTrack = $domtree->createElement($tableName);
        $currentTrack = $xmlRoot->appendChild($currentTrack);
        // Extracting string row from file
        $expFields = explode(' ', $v);
        $fieldsFilter = array_filter($expFields);
        $strContent = $fieldsFilter[0];
        $strData = $v;
        $locationArr = $_REQUEST['location'];
        $j=1;
        // Get configuration record
        $saveConfig['confidId']  = $_REQUEST['selctconfig'];
        if(!empty($saveConfig['confidId'])){
            $getConfig = $conversionConfiguration->getConfigurationById($saveConfig);
        }
        if($_REQUEST['selctconfig'] != ''){
            $dataArrField    = $getConfig['field'];
            $dataArrLocation = $getConfig['location'];
        } else {
            $dataArrField    = $_REQUEST['field'];
            $dataArrLocation = $_REQUEST['location'];
        }
        // Loop through fields to render in XML file
        foreach ($dataArrField as $key => $value) {
            $locationVal = $dataArrLocation[$key];
            // Spliting the string and setting in their fields
            if($locationVal != ''){
                $nextVal = $dataArrLocation[$key + 1];
                $prevVal = $dataArrLocation[$key - 1];
                $diffVal  = $nextVal - $locationVal;
                if($nextVal != ''){
                    if($diffVal >= 0){
                        $subStrVal = substr($strData, $locationVal, $diffVal);
                    } else {
                        $subStrVal = substr($strData, $locationVal);
                    }
                } else {
                    $subStrVal = substr($strData, $locationVal);
                }
                if(empty($value)){
                    $fieldValue = 'field'.$j;
                } else {
                    $fieldValue = $value;
                }
                // Creating XML fields.
                $currentTrack->appendChild($domtree->createElement($fieldValue,$subStrVal)); 
            }
            $j++;
        }
        // Creating XML files.
        $myfile_terms = fopen("../../public/xml_files/".$_REQUEST['filename'].'_'.$i.".xml", "w") or die("Unable to open file !");
        fwrite($myfile_terms, $domtree->saveXML());
        fclose($myfile_terms);
        ++$i;
    }
} else {
    $ii = 2;
    /* create a dom document with encoding utf8 */
    $domtree = new DOMDocument('1.0', 'UTF-8');
    /* create the root element of the xml tree */
    $xmlRoot = $domtree->createElement("xml");
    /* append it to the document created */
    $xmlRoot = $domtree->appendChild($xmlRoot);
    
    // Loop through data from text file
    foreach(array_filter($array) as $k=>$v){
        
        $currentTrack{$ii} = $domtree->createElement($tableName);
        $currentTrack{$ii} = $xmlRoot->appendChild($currentTrack{$ii});
        // Extracting string row from file
        $expFields = explode(' ', $v);
        $fieldsFilter = array_filter($expFields);
        $strContent = $fieldsFilter[0];
        $strData = $v;
        $locationArr = $_REQUEST['location'];
        $j=1;
        // Get configuration record
        $saveConfig['confidId']  = $_REQUEST['selctconfig'];
        if(!empty($saveConfig['confidId'])){
            $getConfig = $conversionConfiguration->getConfigurationById($saveConfig);
        }
        if($_REQUEST['selctconfig'] != ''){
            $dataArrField    = $getConfig['field'];
            $dataArrLocation = $getConfig['location'];
        } else {
            $dataArrField    = $_REQUEST['field'];
            $dataArrLocation = $_REQUEST['location'];
        }
        // Loop through fields to render in XML file
        foreach ($dataArrField as $key => $value) {
            $locationVal = $dataArrLocation[$key];
            // Spliting the string and setting in their fields
            if($locationVal != ''){
                $nextVal = $dataArrLocation[$key + 1];
                $prevVal = $dataArrLocation[$key - 1];
                $diffVal  = $nextVal - $locationVal;
                if($nextVal != ''){
                    if($diffVal >= 0){
                        $subStrVal = substr($strData, $locationVal, $diffVal);
                    } else {
                        $subStrVal = substr($strData, $locationVal);
                    }
                } else {
                    $subStrVal = substr($strData, $locationVal);
                }
                if(empty($value)){
                    $fieldValue = 'field'.$j;
                } else {
                    $fieldValue = $value;
                }
                // Creating XML fields.
                $currentTrack{$ii}->appendChild($domtree->createElement($fieldValue,$subStrVal)); 
            }
            $j++;
        }
        ++$ii;
    }
    
    // Creating XML files.
    $myfile_terms = fopen("../../public/xml_files/".$_REQUEST['filename'].'_batch'.".xml", "w") or die("Unable to open file !");
    fwrite($myfile_terms, $domtree->saveXML());
    fclose($myfile_terms);
}
// Save configuration
$configName = $_REQUEST['newconfig'];
if(isset($configName) && $_REQUEST['selctconfig'] == ''){
    $saveConfig['location']  = $_REQUEST['location'];
    $saveConfig['field']     = $_REQUEST['field'];
    $saveConfig['confidId']  = $_REQUEST['selctconfig'];
    $saveConfig['newconfig'] = $_REQUEST['newconfig'];
    $saveConfig['filetype']  = $_REQUEST['filetype'];
    if($_REQUEST['txttoxml']){
    $saveConfig['txttoxml'] = '1';
    }
 else {
      $saveConfig['txttoxml'] = '2';  
    }
    $saveConfig['tablename'] = $tableName;
    // Save configuration details
    $addConfig = $conversionConfiguration->addConfiguration($saveConfig);
}
echo "XML file Created successfully.";
exit;
?>