function LoadPage(val) {
	if (val == '')
	{
		window.location.href="?r=deano_meta/administration/";
	} 
	else
	{
		window.location.href="?r=deano_meta/administration/&page="+val;
	}
}
function LoadBlocks(val) {
	document.cookie = 'dbblocka' + "=" + val;
	window.location.href="?r=block_tools/administration/&tab=cb";
}

function AjaxLoad(url,target) {
    // native XMLHttpRequest object
    document.getElementById(target).innerHTML = 'Loading...';
    if (window.XMLHttpRequest) {
        req = new XMLHttpRequest();
        req.onreadystatechange = function() {AjaxLoadDone(target);};
        req.open("GET", url, true);
        req.send(null);
    // IE/Windows ActiveX version
    } else if (window.ActiveXObject) {
        req = new ActiveXObject("Microsoft.XMLHTTP");
        if (req) {
            req.onreadystatechange = function() {AjaxLoadDone(target);};
            req.open("GET", url, true);
            req.send();
        }
    }
}    

function AjaxLoadDone(target) {
    // only if req is "loaded"
    if (req.readyState == 4) {
        // only if "OK"
        if (req.status == 200) {
            results = req.responseText;
            document.getElementById(target).innerHTML = results;
        } else {
            document.getElementById(target).innerHTML="Error:\n" +
                req.statusText;
        }
    }
}

function LoadPHP(val,val2) {
	window.location.href="?r=block_tools/administration/&tab=pb&phpbid="+val+"&emode="+val2;
}

function LoadHTML(val,val2) {
	window.location.href="?r=block_tools/administration/&tab=hb&htmlbid="+val+"&emode="+val2;
}

function switchMode(tab,mode) {
	window.location.href="?r=block_tools/administration/&tab="+tab+"&emode="+mode;
}

