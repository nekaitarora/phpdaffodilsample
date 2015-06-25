function toggle(div_id) {
	var el = document.getElementById(div_id);
	if ( el.style.display == 'none' ) { el.style.display = 'block';}
	else {el.style.display = 'none';}
}
function blanket_size(popUpDivVar) {
	if (typeof window.innerWidth != 'undefined') {
		viewportheight = window.innerHeight;
	} else {
		viewportheight = document.documentElement.clientHeight;
	}
	if ((viewportheight > document.body.parentNode.scrollHeight) && (viewportheight > document.body.parentNode.clientHeight)) {
		blanket_height = viewportheight;
	} else {
		if (document.body.parentNode.clientHeight > document.body.parentNode.scrollHeight) {
			blanket_height = document.body.parentNode.clientHeight;
		} else {
			blanket_height = document.body.parentNode.scrollHeight;
		}
	}
	var blanket = document.getElementById('blanket');
	blanket.style.height = blanket_height + 'px';
	var popUpDiv = document.getElementById(popUpDivVar);
	popUpDiv_height=blanket_height/2-350;//200 is half popup's height
	popUpDiv.style.top = popUpDiv_height + 'px';
        
        popUpDiv.style.left = (screen.width/2)-250 + 'px';
        popUpDiv.style.top = (screen.height/2)-250 + 'px';
}
function window_pos(popUpDivVar) {
	if (typeof window.innerWidth != 'undefined') {
		viewportwidth = window.innerHeight;
	} else {
		viewportwidth = document.documentElement.clientHeight;
	}
	if ((viewportwidth > document.body.parentNode.scrollWidth) && (viewportwidth > document.body.parentNode.clientWidth)) {
		window_width = viewportwidth;
	} else {
		if (document.body.parentNode.clientWidth > document.body.parentNode.scrollWidth) {
			window_width = document.body.parentNode.clientWidth;
		} else {
			window_width = document.body.parentNode.scrollWidth;
		}
	}
	var popUpDiv = document.getElementById(popUpDivVar);
	window_width=window_width/2-200;//200 is half popup's width
	popUpDiv.style.left = window_width + 'px';
}
function popup(windowname,type,wfId) {
        if(type == 'wf'){
            $("#wfname").val('');
            $("#wfdesc").val('');
        }
        if(wfId != '' && type == 'edit'){
            $.post(siteUrl + "application/controller/workflow_controller.php",{
              id:wfId,
              type:'edit'
            },function(data,status){
                var dataq = JSON.parse(data);
                if(status == 'success'){
                    $("#wfname").val(dataq.wf_name);
                    $("#wfdesc").val(dataq.wf_desc);
                } else {
                    alert("Something went wrong, please try again.");
                }
            });
        }
	blanket_size(windowname);
	window_pos(windowname);
	toggle('blanket');
	toggle(windowname);		
}
function changeWindow(dId){
    var dEl = document.getElementById(dId);
    if ( dEl.style.display == 'none' ) { dEl.style.display = 'block';}
	else {dEl.style.display = 'none';}
}
function deleteWF(wfId){
    if(confirm('Are you sure want to delete this record?')){
        $.post(siteUrl + "application/controller/workflow_controller.php",{
            id:wfId,
            type:'delete'
          },function(data,status){
              if(status == 'success'){
                 alert(data);
                 location.reload(true);
              } else {
                 alert("Something went wrong, please try again.");
              }
          });
    }
}
// Delete Task 
function deleteTask(wfId){
    if(confirm('Are you sure want to delete this record?')){
        $.post(siteUrl + "application/controller/task_controller.php",{
            id:wfId,
            type:'delete'
          },function(data,status){
              if(status == 'success'){
                 alert('Task deleted successfully.');
                 location.reload(true);
              } else {
                 alert("Something went wrong, please try again.");
              }
          });
    }
}