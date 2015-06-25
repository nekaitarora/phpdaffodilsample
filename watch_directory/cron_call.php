<?php
include_once("application/model/watch_directory_configuration.php");
include_once('config/include.php');

// Save configuration details
$conversionConfiguration = new ConversionConfiguration();
$scheduleList = $conversionConfiguration->getScheduleList();

/* create a dom document with encoding utf8 */
$domtreeBatch = new DOMDocument('1.0', 'UTF-8');
/* create the root element of the xml tree */
$xmlRootBatch = $domtreeBatch->createElement("xml");
/* append it to the document created */
$xmlRootBatch = $domtreeBatch->appendChild($xmlRootBatch);

foreach ($scheduleList as $key => $value) {
    $dir = $value['source_dir_path'];
    $destin_dir = $value['destin_dir_path'];
    $table = $value['table'];
    $conversionType = $value['conversion'];
    if(empty($table)){
        $table = 'table';
    }
    if(empty($value['field'])){ continue; }
    // Open a directory, and read its contents
    $folderContent = array();
    if (is_dir($dir)){
      if ($dh = opendir($dir)){
        while (($file = readdir($dh)) !== false){
            if(!is_dir($file)){
               // Get file extension
               $ext = pathinfo($file, PATHINFO_EXTENSION);
               if($ext == 'txt'){
                   if(!empty($file)){
                       // Get Text File Data and Loop through the data
                        $filedata = file_get_contents($dir.'/'.$file);
                        $array = explode("\n",$filedata);
                       if($conversionType == 'individual'){
                            $i = 1;
                            // Loop through data from text file
                            foreach(array_filter($array) as $k=>$v){
                                /* create a dom document with encoding utf8 */
                                $domtree = new DOMDocument('1.0', 'UTF-8');
                                /* create the root element of the xml tree */
                                $xmlRoot = $domtree->createElement("xml");
                                /* append it to the document created */
                                $xmlRoot = $domtree->appendChild($xmlRoot);
                                $currentTrack = $domtree->createElement($table);
                                $currentTrack = $xmlRoot->appendChild($currentTrack);
                                // Extracting string row from file
                                $expFields = explode(' ', $v);
                                $fieldsFilter = array_filter($expFields);
                                $strContent = $fieldsFilter[0];
                                $strData = $v;
                                $j=1;
                                // Get configuration record
                                $dataArrField    = $value['field'];
                                $dataArrLocation = $value['location'];
                                // Loop through fields to render in XML file
                                foreach ($dataArrField as $key => $valuen) {
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
                                        if(empty($valuen)){
                                            $fieldValue = 'field'.$j;
                                        } else {
                                            $fieldValue = $valuen;
                                        }
                                        // Creating XML fields.
                                        $currentTrack->appendChild($domtree->createElement($fieldValue,$subStrVal)); 
                                    }
                                    $j++;
                                }
                                // Creating XML files.
                                $myfile_terms = fopen($destin_dir."/".$file.'_'.$i.time().".xml", "w") or die("Unable to open file !");
                                if(fwrite($myfile_terms, $domtree->saveXML())){
                                    unlink($dir."/".$file);
                                }
                                fclose($myfile_terms);
                                ++$i;
                            }
                       } elseif($conversionType = 'batch') {
                            // Loop through data from text file
                            foreach(array_filter($array) as $k=>$v){
                                $currentTrack = $domtreeBatch->createElement($table);
                                $currentTrack = $xmlRootBatch->appendChild($currentTrack);
                                // Extracting string row from file
                                $expFields = explode(' ', $v);
                                $fieldsFilter = array_filter($expFields);
                                $strContent = $fieldsFilter[0];
                                $strData = $v;
                                $j=1;
                                // Get configuration record
                                $dataArrField    = $value['field'];
                                $dataArrLocation = $value['location'];
                                // Loop through fields to render in XML file
                                foreach ($dataArrField as $key => $valuen) {
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
                                        if(empty($valuen)){
                                            $fieldValue = 'field'.$j;
                                        } else {
                                            $fieldValue = $valuen;
                                        }
                                        // Creating XML fields.
                                        $currentTrack->appendChild($domtreeBatch->createElement($fieldValue,$subStrVal)); 
                                    }
                                    $j++;
                                }
                            }
                            // Creating XML files.
                            $myfile_terms = fopen($destin_dir."/".$file.'_batch'.".xml", "w") or die("Unable to open file !");
                            if(fwrite($myfile_terms, $domtreeBatch->saveXML())){
                                unlink($dir."/".$file);
                            }
                            fclose($myfile_terms);
                       }   
                   }
               }
            }
        }
        closedir($dh);
      }
    };
}
echo "success";
die;
?>