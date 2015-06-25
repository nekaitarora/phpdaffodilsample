/*Update Field Value*/
function updateValue(val,val2){
    var contentStr = $("#filecontent").val();
    var currentIndex = val2;
    var prevIndex = val2 - 1;
    var locationVal;
    var prevVal;
    var k;
    for(k=0;k<=50;k++){
        locationVal = $("#location"+k).val();
        if(k != prevIndex && currentIndex==k){
            if(locationVal != ''){
                var res = contentStr.substr(locationVal);
                $("#value"+k).val(res);
                // previous value calculate
                prevVal = $("#location"+prevIndex).val();
                var diff = locationVal - prevVal;
                var res2 = contentStr.substr(prevVal,diff);
                if(diff >= 0){
                    $("#value"+prevIndex).val(res2);
                }
                return false;
            }
        }
    }
}
/*Add Field*/
var counter = 1;
function addField(){
    var param = "'" + 'TextBoxDiv' + counter +"'";
    var elm = '<div id="TextBoxDiv'+ counter +'"><div style="padding-top: 5px;"><input type="text" onchange="updateValue(this.value,'+ counter +')" value="" placeholder="Location" class="location" id="location'+ counter +'" name="location[]" />  <input type="text" value="" placeholder="Field Name" id="field'+ counter +'" name="field[]" />  <input type="text" value="" readonly="readonly" id="value'+ counter +'" name="value'+ counter +'" />  <a class="ax-clear ax-button" title="Remove Field"><span class="ax-text" onclick="removeField('+ param +')">Remove field</span></a></div></div>';
    $(elm).appendTo("#TextBoxesGroup");
    counter++;
}
/*Remove Field*/
function removeField(fId){
    // remove field
    $("#"+fId).remove();
}
/*Tab Navigation for Watchfolder and Fixed To XML*/
function switchTask(div_id){
    if(div_id == 'watchFolder'){
        $("#watchFolder").show();
        $("#fixedToXML").hide();
        $("#propertiesTab").hide();
    } else if(div_id == 'fixedToXML'){
        $("#fixedToXML").show();
        $("#watchFolder").hide();
        $("#propertiesTab").hide();
    } else if(div_id == 'fixedToXMLCross'){
        $("#fixedToXML").hide();
        $("#propertiesTab").show();
    } else if(div_id == 'watchFolderCross'){
        $("#watchFolder").hide();
        $("#propertiesTab").show();
    }
}