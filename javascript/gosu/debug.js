var debugWindow = null;

function debug(s, name) {
    if (!debugWindow) {
        debugWindow = window.open("", "debugWindow", "width=400,height=500,scrollbars=yes,resizable=yes");
        debugWindow.document.write("<pre>");
    }
    
    if (name) {
        debugWindow.document.write('<div style="font: 12px sans-serif; font-weight: bold;">'+name+'</div>');
    }
    
    debugWindow.document.write(s + "\n");
}

function debugObject(obj, name) {
    var s = typeof obj;
    for (var i in obj) {
    	var prop = obj[i];
    	if (prop) {
    		s += "\nProperty." + i + "=";
	        if ((typeof prop == "object" || typeof prop == "function") && prop.toString) {
	            s += prop.toString().replace(/\n/g, "");
	        } else {
	            s += prop;
	        }
    	}
    }

    debug(s, name);
}

function debugArray(arr, name) {
    var s = '';
    for (var i = 0; i < arr.length; ++i) {
        s += "Array[" + i + "]=" + arr[i] + "\n";
    }
    
    debug(s, name);
}