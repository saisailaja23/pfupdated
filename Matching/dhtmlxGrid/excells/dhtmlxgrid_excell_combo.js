//v.3.6 build 130619

/*
Copyright DHTMLX LTD. http://www.dhtmlx.com
To use this component please contact sales@dhtmlx.com to obtain license
*/
dhtmlXGridObject.prototype._init_point_bcg=dhtmlXGridObject.prototype._init_point;
dhtmlXGridObject.prototype._init_point=function(){if(!window.dhx_globalImgPath)window.dhx_globalImgPath=this.imgURL;this._col_combos=[];for(var b=0;b<this._cCount;b++)this.cellType[b].indexOf("combo")==0&&(this._col_combos[b]=eXcell_combo.prototype.initCombo.call({grid:this},b));if(!this._loading_handler_set)this._loading_handler_set=this.attachEvent("onXLE",function(a,b,e,c){eXcell_combo.prototype.fillColumnCombos(this,c);this.detachEvent(this._loading_handler_set);this._loading_handler_set=null});
this._init_point_bcg&&this._init_point_bcg()};
function eXcell_combo(b){if(b)this.cell=b,this.grid=b.parentNode.grid,this._combo_pre="",this.edit=function(){if(!window.dhx_globalImgPath)window.dhx_globalImgPath=this.grid.imgURL;this.val=this.getValue();var a=this.getText();this.cell._clearCell&&(a="");this.cell.innerHTML="";this.combo=this.cell._brval?this.cell._brval:(this.grid._realfake?this.grid._fake:this.grid)._col_combos[this.cell._cellIndex];this.cell.appendChild(this.combo.DOMParent);this.combo.DOMParent.style.margin="0";this.combo.DOMelem_input.focus();
this.combo.setSize(this.cell.offsetWidth-2);this.combo._xml?this.combo.setComboText(a):this.combo.getIndexByValue(this.cell.combo_value)!=-1?this.combo.selectOption(this.combo.getIndexByValue(this.cell.combo_value)):this.combo.getOptionByLabel(a)?this.combo.selectOption(this.combo.getIndexByValue(this.combo.getOptionByLabel(a).value)):this.combo.setComboText(a);this.combo.openSelect()},this.selectComboOption=function(a,b){b.selectOption(b.getIndexByValue(b.getOptionByLabel(a).value))},this.getValue=
function(){return this.cell.combo_value||""},this.getText=function(){var a=this.cell;this._combo_pre==""&&a.childNodes[1]&&(a=a.childNodes[1]);return _isIE?a.innerText:a.textContent},this.setValue=function(a){if(typeof a=="object"){this.cell._brval=this.initCombo();var b=this.cell._cellIndex,e=this.cell.parentNode.idd;a.firstChild?this.cell.combo_value=a.firstChild.data:(this.cell.combo_value="&nbsp;",this.cell._clearCell=!0);this.setComboOptions(this.cell._brval,a,this.grid,b,e)}else{this.cell.combo_value=
a;var c=null;if((c=this.cell._brval)&&typeof this.cell._brval=="object")a=(c.getOption(a)||{}).text||a;else if(c=this.grid._col_combos[this.cell._cellIndex]||this.grid._fake&&(c=this.grid._fake._col_combos[this.cell._cellIndex]))a=(c.getOption(a)||{}).text||a;if((a||"").toString()._dhx_trim()=="")a=null;a!==null?this.setComboCValue(a):(this.setComboCValue("&nbsp;",""),this.cell._clearCell=!0)}},this.detach=function(){this.cell.removeChild(this.combo.DOMParent);var a=this.cell.combo_value;!this.combo.getComboText()||
this.combo.getComboText().toString()._dhx_trim()==""?(this.setComboCValue("&nbsp;"),this.cell._clearCell=!0):(this.setComboCValue(this.combo.getComboText().replace(/\&/g,"&amp;").replace(/</g,"&lt;").replace(/>/g,"&gt;"),this.combo.getActualValue()),this.cell._clearCell=!1);this.combo._confirmSelection();this.cell.combo_value=this.combo.getActualValue();this.combo.closeAll();this.grid._still_active=!0;this.grid.setActive(1);return a!=this.cell.combo_value}}eXcell_combo.prototype=new eXcell;
eXcell_combo_v=function(b){var a=new eXcell_combo(b);b.style.paddingLeft="0px";b.style.paddingRight="0px";a._combo_pre="<img src='"+(window.dhx_globalImgPath?window.dhx_globalImgPath:this.grid.imgURL)+"combo_select"+(dhtmlx.skin?"_"+dhtmlx.skin:"")+".gif' style='position:absolute;z-index:1;top:0px;right:0px;'/>";return a};
eXcell_combo.prototype.initCombo=function(b){var a=document.createElement("DIV"),d=this.grid.defVal[arguments.length?b:this.cell._cellIndex],e=new dhtmlXCombo(a,"combo",0,d);this.grid.defVal[arguments.length?b:this.cell._cellIndex]="";e.DOMelem.className+=" fake_editable";var c=this.grid;e.DOMelem.onselectstart=function(){return event.cancelBubble=!0};e.attachEvent("onKeyPressed",function(a){if(a==13||a==27)c.editStop(),c._fake&&c._fake.editStop()});dhtmlxEvent(e.DOMlist,"click",function(){c.editStop();
c._fake&&c._fake.editStop()});e.DOMelem.style.border="0px";e.DOMelem.style.height="18px";return e};eXcell_combo.prototype.fillColumnCombos=function(b,a){if(a){b.combo_columns=b.combo_columns||[];columns=b.xmlLoader.doXPath("//column",a);for(var d=0;d<columns.length;d++)if((columns[d].getAttribute("type")||"").indexOf("combo")==0)b.combo_columns[b.combo_columns.length]=d,this.setComboOptions(b._col_combos[d],columns[d],b,d)}};
eXcell_combo.prototype.setComboCValue=function(b,a){this._combo_pre!=""&&(b="<div style='width:100%;position:relative;height:100%;overflow:hidden;line-height:20px'>"+this._combo_pre+"<span>"+b+"</span></div>");arguments.length>1?this.setCValue(b,a):this.setCValue(b)};
eXcell_combo.prototype.setComboOptions=function(b,a,d,e,c){if(convertStringToBoolean(a.getAttribute("xmlcontent"))&&!a.getAttribute("source")){options=a.childNodes;for(var h=[],g=0;g<options.length;g++)if(options[g].tagName=="option"){var i=options[g].firstChild?options[g].firstChild.data:"";h[h.length]=[options[g].getAttribute("value"),i]}b.addOption(h);if(arguments.length==4)d.forEachRowA(function(a){var c=d.cells(a,e);!c.cell._brval&&!c.cell._cellType&&c.cell._cellIndex==e&&(c.cell.combo_value==
""?c.setComboCValue("&nbsp;",""):b.getOption(c.cell.combo_value)?c.setComboCValue(b.getOption(c.cell.combo_value).text):c.setComboCValue(c.cell.combo_value))});else{var f=this.cell?this:d.cells(c,e);a.getAttribute("text")?a.getAttribute("text")._dhx_trim()==""?f.setComboCValue("&nbsp;",""):f.setComboCValue(a.getAttribute("text")):!f.cell.combo_value||f.cell.combo_value._dhx_trim()==""?f.setComboCValue("&nbsp;",""):b.getOption(f.cell.combo_value)?f.setComboCValue(b.getOption(f.cell.combo_value).text):
f.setComboCValue(f.cell.combo_value)}}if(a.getAttribute("source"))if(a.getAttribute("auto")&&convertStringToBoolean(a.getAttribute("auto")))a.getAttribute("xmlcontent")?(f=this.cell?this:d.cells(c,e),a.getAttribute("text")&&f.setComboCValue(a.getAttribute("text"))):d.forEachRowA(function(a){var b=d.cells(a,e);if(!b.cell._brval&&!b.cell._cellType){var c=b.cell.combo_value.toString();if(c.indexOf("^")!=-1){var f=c.split("^");b.cell.combo_value=f[0];b.setComboCValue(f[1])}}}),b.enableFilteringMode(!0,
a.getAttribute("source"),convertStringToBoolean(a.getAttribute("cache")||!0),convertStringToBoolean(a.getAttribute("sub")||!1));else{var k=this,j=arguments.length;b.loadXML(a.getAttribute("source"),function(){if(j==4)d.forEachRow(function(a){var c=d.cells(a,e);if(!c.cell._brval&&!c.cell._cellType)b.getOption(c.cell.combo_value)?c.setComboCValue(b.getOption(c.cell.combo_value).text):(c.cell.combo_value||"").toString()._dhx_trim()==""?(c.setComboCValue("&nbsp;",""),c.cell._clearCell=!0):c.setComboCValue(c.cell.combo_value)});
else{var a=d.cells(c,e);b.getOption(a.cell.combo_value)?a.setComboCValue(b.getOption(a.cell.combo_value).text):a.setComboCValue(a.cell.combo_value)}})}if(!a.getAttribute("auto")||!convertStringToBoolean(a.getAttribute("auto")))a.getAttribute("editable")&&!convertStringToBoolean(a.getAttribute("editable"))&&b.readonly(!0),a.getAttribute("filter")&&convertStringToBoolean(a.getAttribute("filter"))&&b.enableFilteringMode(!0)};
eXcell_combo.prototype.getCellCombo=function(){if(!this.cell._brval)this.cell._brval=this.initCombo();return this.cell._brval};eXcell_combo.prototype.refreshCell=function(){this.setValue(this.getValue())};dhtmlXGridObject.prototype.getColumnCombo=function(b){if(!this._col_combos||!this._col_combos[b]){if(!this._col_combos)this._col_combos=[];this._col_combos[b]=eXcell_combo.prototype.initCombo.call({grid:this},b)}return this._col_combos[b]};
dhtmlXGridObject.prototype.refreshComboColumn=function(b){this.forEachRow(function(a){this.cells(a,b).refreshCell&&this.cells(a,b).refreshCell()})};

//v.3.6 build 130619

/*
Copyright DHTMLX LTD. http://www.dhtmlx.com
To use this component please contact sales@dhtmlx.com to obtain license
*/