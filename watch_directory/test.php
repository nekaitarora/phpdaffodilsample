<?php
error_reporting(0);

$xmlString;
$xmlFilename;
$target;
if (isset($_FILES['xmlfile'])){
	$xmlFilename = basename($_FILES['xmlfile']['name']);
	$target = "_data/" . $xmlFilename;
	if (move_uploaded_file($_FILES['xmlfile']['tmp_name'], $target)){
		$xml = simplexml_load_file($target, null, LIBXML_NOCDATA); // returns valse if xml is invalid
		if ($xml) $xmlString = $xml->asXML();
	}
}
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>SubChild.com - Live XML Editor</title>
<script type="text/javascript" src="public/app/js/ext/jquery-1.4.4.js"></script>
<script type="text/javascript" src="public/app/js/ext/jquery-color.js"></script>
<script type="text/javascript" src="public/app/js/ext/GLR/GLR.js"></script>
<script type="text/javascript" src="public/app/js/ext/GLR/GLR.messenger.js"></script>
<script type="text/javascript" src="public/app/js/ext/xmleditor.js"></script>
<link href="public/app/css/main.css" type="text/css" rel="stylesheet"/>
<script type="text/javascript">
$(document).ready(function(){
	GLR.messenger.show({msg:"Loading XML..."});
	console.time("loadingXML");
	
	xmlEditor.loadXmlFromFile("<?=$target?>", "#xml", function(){
//	xmlEditor.loadXmlFromString($("#xmlString").val(), "#xml", function(){																														 																													 
		console.timeEnd("loadingXML");
		$("#xml").show();
		$("#actionButtons").show();																																				
		xmlEditor.renderTree();
		$("button#saveFile").show().click(function(){
			GLR.messenger.show({msg:"Generating file...", mode:"loading"});
                         alert("suceessful");
                        
			$.post("do/saveXml.php", {xmlString:xmlEditor.getXmlAsString(), xmlFilename:"<?=$xmlFilename?>"}, function(data){
                            
				if (data.error){
                                    GLR.messenger.show({msg:data.error,mode:"error"});
				}
				else {  
                                        GLR.messenger.inform({msg:"Done.", mode:"success"});
					if ($("button#viewFile").length){
						$("<button id='viewFile'>View Updated File</button>")
							.appendTo("#actionButtons div")
							.click(function(){ window.open(data.filename); });
					}
				}
			}, "json");
		});
	});
//	$("#todos, #links").height($("#about").height()+"px");
});
</script>
</head>

<body>
	
  
	<form id="uploadForm" action="#" method="post" enctype="multipart/form-data">
		<label for="xmlfile">Specify XML file to edit:</label>
		<input type="file" name="xmlfile" id="xmlfile"/>
                <input type="submit" id="gobutton"  value="Upload"/>
	</form>
	<div id="xml" style="display:none;"></div>
	<div id="actionButtons" style="">
		<div></div>
		<button id="saveFile">Save XML</button>
	</div>
        <div id="nodePath"></div>
        <? /* if ($xmlString){ ?><textarea style="display:none;" id="xmlString"><?=$xmlString?></textarea><? } */ ?>
  
<script type="text/javascript">
var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
</script>
<script type="text/javascript">
try {
var pageTracker = _gat._getTracker("UA-443787-5");
pageTracker._trackPageview();
} catch(err) {}</script>	
</body>
</html>



