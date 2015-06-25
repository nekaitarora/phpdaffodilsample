<?php
include_once("../model/xmltoxml_model.php");
include_once('../../config/include.php');
 //Create conversion class object
$conversionConfig = new XmlConversionModel();

/*
 * PHP SimpleXML
 * Loading a XML from a file, adding new elements and editing elements
 */

  // Xml conversion fields
if (isset($_REQUEST['xmlsub'])) {
    
  // check for select configuration
    if($_REQUEST['newconfig1']=='newcon'){
    $data['xmlconfig'] = $_REQUEST['xmlconfig'];
    }
    else if($_REQUEST['newconfig1']=='existcon'){
        $data['xmlconfig'] = $_REQUEST['exisconfig'];
     } 
     $data['xmltoxml']='2';
    $xmlObj= new XmlConversionModel();
    $saveData = $xmlObj->addConfig($data);
    
    
    //check for source path
    $saveConfig['save_sch_id']     = 1;
    $saveConfig['xmlconfig'] = $saveData;
    if($_REQUEST['prev']=='prev1'){
        $saveConfig['folderpath'] = $_REQUEST['selectsource'];
    }
    else if($_REQUEST['prev']=='prev2'){
      $saveConfig['folderpath'] = $_REQUEST['folderpath'];
    }
    
    //check for destination part
    if($_REQUEST['next']=='next1'){
        $saveConfig['destfolderpath'] = $_REQUEST['selectdes'];
    }
    else if($_REQUEST['next']=='next2'){
        $saveConfig['destfolderpath'] = $_REQUEST['destfolderpath'];
    }
 // SCHEDULE details
$saveCron = $xmlObj->saveSchedule($saveConfig);
if($saveCron){
    echo "Schedule created successfully.";
    die;
} else {
    echo "Something went wrong, please try again.";
    die;
}
}



//$uploaddir = '../../public/uploaded_xmlfiles/';
//$uploadfile = $uploaddir . basename($_FILES['xmlfile']['name']);
//
//if (move_uploaded_file($_FILES['xmlfile']['tmp_name'], $uploadfile)) {
//    echo "File is valid, and was successfully uploaded.\n";
//} else {
//  echo "Possible file upload attack!\n";
//}
/*
$upload='../../public/uploaded_xmlfiles/'.$_REQUEST['filename'];
$filedata = file_get_contents('../../public/uploaded_xmlfiles/'.$_REQUEST['filename']);
echo "<pre>";

$array = explode("\n",$filedata);


if (file_exists($upload)) {
    //loads the xml and returns a simplexml object
    $xml = simplexml_load_file('../../public/uploaded_xmlfiles/'.$_REQUEST['filename']);
    $domtree = new DOMDocument('1.0', 'UTF-8');
 
    //transforming the object in xml format
    $xmlFormat = $xml->asXML();$i=0;
    //displaying the element in proper format
   /* echo '<u><b>This is the xml code from test2.xml:</b></u>
     <br /><br />
     <pre>' . htmlentities($xmlFormat, ENT_COMPAT | ENT_HTML401, "ISO-8859-1") . '</pre><br /><br />';
*/ /*
     // Extracting string row from file
        $expFields = explode(' ', $v);
        $fieldsFilter = array_filter($expFields);
        $strContent = $fieldsFilter[0];
        $strData = $v;
        $locationArr = $_REQUEST['location'];
        $dataArrField    = $_REQUEST['field'];
            $dataArrLocation = $_REQUEST['location'];
             $j=1;
    //adding new child to the xml
        $newChild = $xml->addChild($tableName);
              // Loop through fields to render in XML file
        foreach ($dataArrField as $key => $value) { $i=1;
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
                 $newChild->addChild($fieldValue,$subStrVal); 
            }
            $j++;
        }
    //$newChild->addChild('name', 'Mamaliga');
   // $newChild->addChild('price', '100 $');

    //transforming the object in xml format
    $xmlFormat = $xml->asXML();
    //displaying the element in proper format
    echo '<u><b>This is the xml code from test2.xml with new elements added:</b></u>
     <br /><br />
     <pre>' . htmlentities($xmlFormat, ENT_COMPAT | ENT_HTML401, "ISO-8859-1") . '</pre>';

    //changing the nodes values
    //in this case we are changing the value 
    //of all children called <name>
    foreach ($xml->children() as $child)
        $child->name = "CHANGED";
    //displaying the element in proper format
    echo '<br /><u><b>This is the xml code from test2.xml with new elements added:</b></u>
     <br /><br />
     <pre>' . htmlentities($xml->asXML(), ENT_COMPAT | ENT_HTML401, "ISO-8859-1") . '</pre>';
   
     $myfile_terms = fopen("../../public/xmltoxml_files/".$i.'_'.$_REQUEST['filename'], "w") or die("Unable to open file !");
        fwrite($myfile_terms, $domtree->saveXML());
        fclose($myfile_terms);
        $i++;
} else {
    exit('Failed to open xml file.');
}
*/