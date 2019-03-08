function openChat() {
	var URL = 'chat.php';
	
	day = new Date();
	id = day.getTime();

	eval("page" + id + " = window.open(URL, '" + id + "', 'toolbar=0,scrollbars=0,location=0,statusbar=0,menubar=0,resizable=0,width=170,height=310');");
}

function submitToWindow(url) {
	
	
	window.open(url,null,'width=450, height=225, status=no, directories=no, toolbar=no, location=no, menubar=no,scrollbars=no, resizable=no')	
}

function openWindow(URL) { 
	win2 = window.open(URL, "Window2", "width=500,height=500,scrollbars=yes");
}