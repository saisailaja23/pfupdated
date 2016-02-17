checked=false;
function checkedAll (theform) {
	var aa= document.getElementById(theform);
	 if (checked == false)
          {
           checked = true
          }
        else
          {
          checked = false
          }
	for (var i =0; i < aa.elements.length; i++) 
	{
		aa.elements[i].checked = checked;
	}
}
function LoadLang(val) {
	window.location.href="?r=deanos_tools/administration/&se=sc&LangID="+val;
}
function LoadPHP(val) {
	window.location.href="?r=deanos_tools/administration/&se=pe&phpbid="+val;
}

