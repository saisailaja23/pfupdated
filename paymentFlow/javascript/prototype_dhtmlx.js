var windowManagerPrinter, windowPrinter;
dhtmlXGridObject.prototype.toFile=function(json)
{
	
	var before = json.header, after = json.footer;
	
	
	var html = "<style>TD {font-family:Arial;text-align:center;padding-left:2px;padding-right:2px;}\n td.filter input, td.filter select {display:none;}\n </style>";
    var st_hr = null;
    if (this._fake) {
        st_hr = [].concat(this._hrrar);
        for (var i = 0; i < this._fake._cCount; i++) this._hrrar[i] = null;
    }
    html += "<base href='" + document.location.href + "'></base>";
    if (!this.parentGrid) html += (before || "");
    html += '<table width="100%" border="2px" cellpadding="0" cellspacing="0">';
    var row_length = Math.max(this.rowsBuffer.length, this.rowsCol.length);
    var col_length = this._cCount;
    var width = this._printWidth();
    html += '<tr class="header_row_1">';
    for (var i = 0; i < col_length; i++) {
        if (this._hrrar && this._hrrar[i]) continue;
        var hcell = this.hdr.rows[1].cells[this.hdr.rows[1]._childIndexes ? this.hdr.rows[1]._childIndexes[parseInt(i)] : i];
        var colspan = (hcell.colSpan || 1);
        var rowspan = (hcell.rowSpan || 1);
        for (var j = 1; j < colspan; j++) width[i] += width[j];
        html += '<td rowspan="' + rowspan + '" width="' + width[i] + '%" style="background-color:lightgrey;" colspan="' + colspan + '">' + this.getHeaderCol(i) + '</td>';
        i += colspan - 1;
    }
    html += '</tr>';
    for (var i = 2; i < this.hdr.rows.length; i++) {
        if (_isIE) {
            html += "<tr style='background-color:lightgrey' class='header_row_" + i + "'>";
            var cells = this.hdr.rows[i].childNodes;
            for (var j = 0; j < cells.length; j++) if (!this._hrrar || !this._hrrar[cells[j]._cellIndex]) {
                    html += cells[j].outerHTML;
                }
            html += "</tr>";
        } else
            html += "<tr class='header_row_" + i + "' style='background-color:lightgrey'>" + (this._fake ? this._fake.hdr.rows[i].innerHTML : "") + this.hdr.rows[i].innerHTML + "</tr>";
    }
    for (var i = 0; i < row_length; i++) {
        html += '<tr>';
        if (this.rowsCol[i] && this.rowsCol[i]._cntr) {
            html += this.rowsCol[i].innerHTML.replace(/<img[^>]*>/gi, "") + '</tr>';
            continue;
        }
        if (this.rowsCol[i] && this.rowsCol[i].style.display == "none") continue;
        var row_id
        if (this.rowsCol[i]) row_id = this.rowsCol[i].idd;
        else if (this.rowsBuffer[i]) row_id = this.rowsBuffer[i].idd;
        else continue;
        for (var j = 0; j < col_length; j++) {
            if (this._hrrar && this._hrrar[j]) continue;
            if (this.rowsAr[row_id] && this.rowsAr[row_id].tagName == "TR") {
                var c = this.cells(row_id, j);
                if (c._setState) var value = "";
                else if (c.getContent) value = c.getContent();
                else if (c.getImage || c.combo) var value = c.cell.innerHTML;
                else var value = c.getValue();
            } else
                var value = this._get_cell_value(this.rowsBuffer[i], j);
            var color = this.columnColor[j] ? 'background-color:' + this.columnColor[j] + ';' : '';
            var align = this.cellAlign[j] ? 'text-align:' + this.cellAlign[j] + ';' : '';
            var cspan = c.getAttribute("colspan");
            html += '<td style="' + color + align + '" ' + (cspan ? 'colSpan="' + cspan + '"' : '') + '>' + (value === "" ? "&nbsp;" : value) + '</td>';
            if (cspan) j += cspan - 1;
        }
        html += '</tr>';
        if (this.rowsCol[i] && this.rowsCol[i]._expanded) {
            var sub = this.cells4(this.rowsCol[i]._expanded.ctrl);
            if (sub.getSubGrid) html += '<tr><td colspan="' + col_length + '">' + sub.getSubGrid().printView() + '</td></tr>';
            else
                html += '<tr><td colspan="' + col_length + '">' + this.rowsCol[i]._expanded.innerHTML + '</td></tr>';
        }
    }
    if (this.ftr) for (var i = 1; i < this.ftr.childNodes[0].rows.length; i++) html += "<tr style='background-color:lightgrey'>" + ((this._fake) ? this._fake.ftr.childNodes[0].rows[i].innerHTML : "") + this.ftr.childNodes[0].rows[i].innerHTML + "</tr>";
    html += '</table>';
    if (this.parentGrid) return html;
    html += (after || "");
	
	var htmlH = '<html><head></head><body>';
	var htmlB = '</body></html>';
	
	var params = "document=" + escape( htmlH + html + htmlB );
	dhtmlxAjax.post("processors/generate_pdf.pl", params, function(loader)
	{
		try
		{
			var json = eval('(' + loader.xmlDoc.responseText + ')');
			if( json.status == "success" )	
			{
				
				
				windowPrinter = PaymentFlow.window_manager.createWindow("janela_printer", 200, 200, 650, 340);	
				windowPrinter.button("park").hide();
				windowPrinter.setIcon("printer.png", "printer.png");
				windowPrinter.setText("Print");
				
				windowPrinter.centerOnScreen();
				
				windowPrinter.attachURL(""+json.path+"");
				
				
				windowPrinter.attachEvent("onClose", function (win)
				{
					windowPrinter.hide();
					//W2.isLog = false;
				});
				
				
				
				dhtmlx.message( {text : "Signature saved" } );
				
			}
			else
			{
				dhtmlx.message( {type : "error", text : json.response} );
				
			}
		}
		catch(e)
		{
			dhtmlx.message( {type : "error", text : "Fatal error on server side: "+loader.xmlDoc.responseText } );
			
		}
	
	});	
};