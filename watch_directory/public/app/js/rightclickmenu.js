var mouse_x = 0;
var mouse_y = 0;
var distance_to_right_edge = 0;
var distance_to_bottom = 0;

var dirmenu			= null;
var filemenu		= null;
var refreshmenu		= null;

addLoadEvent(onstart);

function onstart()
{
	addGenericEvent(document, 'mousemove', mousemove);
	addGenericEvent(document, 'click', hide_contextmenu);
	
	dirmenu		= document.getElementById('dirmenu');
	filemenu	= document.getElementById('filemenu');
	refreshmenu	= document.getElementById('refreshmenu');
}

function context_menuitem_highlight(element, color)
{
	element.className = 'highlight';
}

function context_menuitem_unhighlight(element)
{
	element.className = '';
}

function show_contextmenu(event, menu)
{
	hide_contextmenu();
	get_page_boundaries();
	menu.style.left	= mouse_x+"px";
	menu.style.top	= mouse_y+"px";
	menu.style.visibility = "visible";
	
	// adjust menu if near window edge
	if (distance_to_right_edge < menu.offsetWidth)
		menu.style.left	= 2+mouse_x - menu.offsetWidth+"px";			// The 2+ is not some dumb kludge - 
	if (distance_to_bottom < menu.offsetHeight)                  		// it places the menu just under the pointer,
		menu.style.top	= 2+mouse_y - menu.offsetHeight+"px";			// instead of just outside
	
	try
	{
		window.getSelection().collapseToStart(); 	// try to compensate for tendency to treat right-clicking as text selection
	} catch (e) {}									// do nothing
	
	// prevent the event from bubbling up and causing the regular browser context menu to appear.
	event.cancelBubble = true;
	if (event.stopPropagation) event.stopPropagation(); 
	if (event.preventDefault) event.preventDefault();
	
	return false;
}

function hide_contextmenu()
{
	dirmenu.style.visibility		= "hidden";
	filemenu.style.visibility		= "hidden";
	refreshmenu.style.visibility	= "hidden";
}

function addLoadEvent(func)
{
	if (window.addEventListener)
		window.addEventListener("load", func, false);
	else if (document.addEventListener)
		document.addEventListener("load", func, false);
	else if (window.attachEvent)
		window.attachEvent("onload", func);
	else if (document.attachEvent)
		document.attachEvent("onload", func);
}

function addGenericEvent(source, trigger, func)
{
	if (source.addEventListener)
		source.addEventListener(trigger, func, false);
	else if (source.attachEvent)
		source.attachEvent("on"+trigger, func);
}

function window_x()
{
	if (window.screenX)
		return window.screenX
	else if (window.screenLeft)
		return window.screenLeft;
}

function window_y()
{
	if (window.screenY)
		return window.screenY
	else if (window.screenTop)
		return window.screenTop;
}

function mousemove(e)
{ 
	if (e && e.clientX && typeof(window.scrollY) == 'number')							// Moz
	{
		mouse_x = e.clientX + window.scrollX;
		mouse_y = e.clientY + window.scrollY;
		event_target = e.target;
	}
	else if (window.event)																// IE
	{
		if (document.documentElement)													// Explorer 6 Strict
		{
			mouse_x = window.event.clientX + document.documentElement.scrollLeft - 4;
			mouse_y = window.event.clientY + document.documentElement.scrollTop - 4;
		}
		else if (document.body)															// all other Explorers
		{
			mouse_x = window.event.clientX + document.body.scrollLeft-4;
			mouse_y = window.event.clientY + document.body.scrollTop-4;
		}
		
		mouse_window_x = window.event.clientX;
		mouse_window_y = window.event.clientY;
	}
}

function get_page_boundaries()
{
	if (window.innerWidth)
	{
		distance_to_right_edge = window.innerWidth-(mouse_x - window.scrollX)
		distance_to_bottom = window.innerHeight-(mouse_y - window.scrollY);
		
		//alert(window.innerHeight+' '+mouse_y);
	}
	else if (document.body.clientWidth)
	{
		distance_to_right_edge = document.body.clientWidth-mouse_x;
		distance_to_bottom = document.body.clientHeight-mouse_y;
	}
}