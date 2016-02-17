
/*******************************************************************************************
 Common Google Maps Drawing
 License: proprietary license product, please read license.txt before using this product.
 Author: Trofimov Alexander (kolimarfey@gmail.com)
*******************************************************************************************/


/*******************************************************************************************
*******************************************************************************************/

function GMKDrawing (map, isReadOnly, sImagesBaseUrl, sLoadDataUrl, sSaveDataUrl) 
{
    this.GMKPlacesDrawingControl = function (sImagesBaseUrl) {
        this._sImagesBaseUrl = sImagesBaseUrl;
    }

    this.GMKPlacesDrawingControl.prototype = new GControl();

    this.GMKPlacesDrawingControl.prototype.initialize = function(map) {
      var container = $('<div><a class="gmk_gd_na" href="javascript:void(0);"><img style="margin-right:3px;" src="'+this._sImagesBaseUrl+'gd_na_active.png" /></a><a class="gmk_gd_pl" href="javascript:void(0);"><img style="margin-right:3px;" src="'+this._sImagesBaseUrl+'gd_pl.png" /></a><a class="gmk_gd_pg" href="javascript:void(0);"><img src="'+this._sImagesBaseUrl+'gd_pg.png" /></a></div>').get(0);
      map.getContainer().appendChild(container);
      return container;
    }

    this.GMKPlacesDrawingControl.prototype.getDefaultPosition = function() {
        return new GControlPosition(G_ANCHOR_TOP_LEFT, new GSize(50, 7));
    }


    this.GMKPlacesDrawingControl.prototype.setButtonStyle_ = function(button) {

    }

    this._v = '1.0a';
	this._map = map;

    this._isReadOnly = isReadOnly;

    this._sImagesBaseUrl = sImagesBaseUrl;
    this._sUrlLoadData = sLoadDataUrl;
    this._sUrlSaveData = sSaveDataUrl;

    this._encoderNumLevels = 18;
    this._encoderVerySmall = 0.00001;
    this._encoderForceEndpoints = true;

    this._isDrawingPolygon = false;
    this._isDrawingPOlyline = false;

    this._aFigures = [];
    this._iCurrentIndex = -1;
    this._oDrawOptions = {
        'polygon' : {
            'color' : "#0000FF",
            'weight' : 5,            
            'opacity' : 0.5
        },
        'polyline' : {
            'color' : "#0000FF",
            'weight' : 5,
            'opacity' : 0.5
        }
    };

    if (!this._isReadOnly) {

        map.addControl(new this.GMKPlacesDrawingControl(this._sImagesBaseUrl));

        this._btnNormal = $(map.getContainer()).find('a.gmk_gd_na > img');
        this._btnPolygon = $(map.getContainer()).find('a.gmk_gd_pg > img');
        this._btnPolyline = $(map.getContainer()).find('a.gmk_gd_pl > img');

        var $this = this;    

        this.callbackEndLine = function (overlay) {        
            GEvent.addListener(overlay, "click", function() {
                $this._openInfoWindow (this);
            });
            GEvent.addListener(overlay, "lineupdated", function () {
                $this.callbackLineUpdated(this);
            });
            $this._openInfoWindow (overlay);
            $this._disableDrawing();
        }

        this.callbackMouseOver = function (overlay) {
            overlay.enableEditing();
        }

        this.callbackMouseOut = function (overlay) {
            overlay.disableEditing();
        }

        this.callbackLineUpdated = function (overlay) {
            $this.save();
        }

        this.callbackDrawDisable = function (ev) {
            $this._disableDrawing();
        }
        $(map.getContainer()).find('a.gmk_gd_na').bind('click', this.callbackDrawDisable);

        this.callbackDrawPolygon = function (ev) {
            $this._disableDrawing();
            var polygon = new GPolygon([], $this._oDrawOptions.polygon.color, $this._oDrawOptions.polygon.weight, $this._oDrawOptions.polygon.opacity);
            polygon.gmk_type = 'polygon';
            polygon.gmk_desc = 'polygon';
            $this._map.addOverlay(polygon);
            $this._addFigure (polygon, true);
            GEvent.addListener(polygon, "endline", function () {
                $this.callbackEndLine(polygon);
            });
            GEvent.addListener(polygon, "mouseover", function () {
                $this.callbackMouseOver(polygon);
            });
            GEvent.addListener(polygon, "mouseout", function () {
                $this.callbackMouseOut(polygon);
            });        
            $this._btnNormal.attr('src', $this._sImagesBaseUrl + 'gd_na.png');
            $this._btnPolyline.attr('src', $this._sImagesBaseUrl + 'gd_pl.png');
            $this._btnPolygon.attr('src', $this._sImagesBaseUrl + 'gd_pg_active.png');
        }
        $(map.getContainer()).find('a.gmk_gd_pg').bind('click', this.callbackDrawPolygon);

        this.callbackDrawPolyline = function (ev) {
            $this._disableDrawing();
            var polyline = new GPolyline([], $this._oDrawOptions.polyline.color, $this._oDrawOptions.polyline.weight, $this._oDrawOptions.polyline.opacity);
            polyline.gmk_type = 'polyline';
            polyline.gmk_desc = 'polyline';
            $this._map.addOverlay(polyline);
            $this._addFigure (polyline, true);
            GEvent.addListener(polyline, "endline", function () {
                $this.callbackEndLine(polyline);
            });
            GEvent.addListener(polyline, "mouseover", function () {
                $this.callbackMouseOver(polyline);
            });
            GEvent.addListener(polyline, "mouseout", function () {
                $this.callbackMouseOut(polyline);
            });        
            $this._btnNormal.attr('src', $this._sImagesBaseUrl + 'gd_na.png');
            $this._btnPolyline.attr('src', $this._sImagesBaseUrl + 'gd_pl_active.png');
            $this._btnPolygon.attr('src', $this._sImagesBaseUrl + 'gd_pg.png');
        }
        $(map.getContainer()).find('a.gmk_gd_pl').bind('click', this.callbackDrawPolyline);
    }

    this.load();
}

GMKDrawing.prototype.getCurentFigure = function () {
    if (this._iCurrentIndex > -1)
        return this.getFigure (this._iCurrentIndex);
    else
        return null;
}

GMKDrawing.prototype.getFigure = function (index) {
    if (index > -1 && index < this._aFigures.length && this._aFigures[index] != undefined)
        return this._aFigures[index];
    else
        return null;
}

GMKDrawing.prototype.serialize = function () {
    var polylineEncoder = new PolylineEncoder(this._encoderNumLevels, 2, this._encoderVerySmall, this._encoderForceEndpoints);
    var aRet = [];
    var $this = this;
    for (var i in this._aFigures) {
        var oFigure = this.getFigure(i);
        if (i == null) 
            continue;
        var res = polylineEncoder.dpEncode(this._getFigurePoints(i));        
        var s;
        if (oFigure.gmk_type == 'polyline')
            s = oFigure.gmk_type+':'+oFigure.color+':'+oFigure.weight+':'+oFigure.opacity+':'+res.encodedPoints+':'+res.encodedLevels+':'+encodeURIComponent(oFigure.gmk_desc)+':'+this._map.getZoom();
        else
            s = oFigure.gmk_type+':'+oFigure.color+':'+$this._oDrawOptions.polygon.weight+':'+oFigure.opacity+':'+res.encodedPoints+':'+res.encodedLevels+':'+encodeURIComponent(oFigure.gmk_desc)+':'+this._map.getZoom();
        aRet.push(s);
    }
    return aRet;
}

GMKDrawing.prototype._getFigurePoints = function (index) {
    var aFigure = this._aFigures[index];
    var l = aFigure.getVertexCount();
    var a = []
    for (var i=0; i<l ; ++i)
        a.push(aFigure.getVertex(i));
    return a;
}

GMKDrawing.prototype.removeFigure = function (oFigure) {

    var aFiguresNew = [];
    for (var i in this._aFigures) {
        if (this._aFigures[i] == oFigure)
            continue;
        aFiguresNew.push(this._aFigures[i]);
    }
    this._aFigures = aFiguresNew;

    this._map.removeOverlay(oFigure);

    this.save();
}

GMKDrawing.prototype._addFigure = function (overlay, enableDrawing) {

    var l = this._aFigures.push(overlay);
    if (enableDrawing)
    {
        this._iCurrentIndex = l - 1;
        overlay.enableDrawing();
    }
}

GMKDrawing.prototype._disableDrawing = function () {

    for (var i in this._aFigures)
        this._aFigures[i].disableEditing();

    this._iCurrentIndex = -1;

    this._btnNormal.attr('src', this._sImagesBaseUrl + 'gd_na_active.png');
    this._btnPolyline.attr('src', this._sImagesBaseUrl + 'gd_pl.png');
    this._btnPolygon.attr('src', this._sImagesBaseUrl + 'gd_pg.png');
}

GMKDrawing.prototype.load = function () {
    var $this = this;    
    $.getJSON(this._sUrlLoadData + '?_t=' + (new Date()), function(data) {
        $.each(data, function(i, s) {
            var a = s.split(':');            
            var oFigure;
            if (a[0] == 'polyline') {
                oFigure = GPolyline.fromEncoded({
                    color: a[1],
                    weight: a[2],
                    opacity: a[3], 
                    points: a[4], 
                    levels: a[5],
                    zoomFactor: 2, //a[7], //$this._map.getZoom(),
                    numLevels: 18
                });
            } else {

                var oTmp = GPolyline.fromEncoded({
                    color: a[1],
                    weight: a[2],
                    opacity: a[3], 
                    points: a[4], 
                    levels: a[5],
                    zoomFactor: 2, //a[7], //$this._map.getZoom(),
                    numLevels: 18
                });                

                var aTmp = [];
                var iLen = oTmp.getVertexCount();
                for (var j=0 ; j<iLen ; ++j)
                    aTmp.push(oTmp.getVertex(j));

                oFigure = new GPolygon(aTmp, $this._oDrawOptions.polygon.color, $this._oDrawOptions.polygon.weight, $this._oDrawOptions.polygon.opacity);
                aTmp = undefined;
                oTmp = undefined;

/*
                oFigure = GPolygon.fromEncoded({
                    polylines:[{
                        color: a[1],
                        weight: a[2],
                        opacity: $this._oDrawOptions.polygon.opacity, 
                        points: a[4], 
                        levels: a[5],
                        zoomFactor: 2, //a[7], //$this._map.getZoom(),
                        numLevels: 18}],
                    fill: true,
                    color: a[1],
                    opacity: a[3], 
                    outline: true
                });
*/
            }
            oFigure.gmk_type = a[0];
            oFigure.gmk_desc = decodeURIComponent(a[6]);
            $this._map.addOverlay(oFigure);
            $this._addFigure (oFigure, false);

            if (!$this._isReadOnly) {
                GEvent.addListener(oFigure, "endline", function () {
                    $this.callbackEndLine(oFigure);
                });
                GEvent.addListener(oFigure, "mouseover", function () {
                    $this.callbackMouseOver(oFigure);
                });
                GEvent.addListener(oFigure, "mouseout", function () {
                    $this.callbackMouseOut(oFigure);
                });
                GEvent.addListener(oFigure, "lineupdated", function () {
                    $this.callbackLineUpdated(this);
                });
                GEvent.addListener(oFigure, "click", function() {
                    $this._openInfoWindow (this);
                });
            } else {
                GEvent.addListener(oFigure, "click", function () {
                    $this._openInfoWindowReadOnly (this);
                });
/*
                GEvent.addListener(oFigure, "mouseover", function () {
                    $this._openInfoWindowReadOnly (this);
                });
                GEvent.addListener(oFigure, "mouseout", function () {
                    $this._map.closeInfoWindow();
                });
*/
            }

        });
    });

}

GMKDrawing.prototype.save = function () {

    var $this = this;
    var aFigures = this.serialize(); 
    $.post(this._sUrlSaveData, { 'figures[]': aFigures}, function (data) {
        $this.onSaveCompleted ();
    });
}

GMKDrawing.prototype.onSaveCompleted = function () {
}

GMKDrawing.prototype._openInfoWindowReadOnly = function (oFigure) { 
    this._map.openInfoWindowHtml(oFigure.getVertex(0), oFigure.gmk_desc);
}

GMKDrawing.prototype._openInfoWindow = function (aFigure) { 

    var $this = this;

    var eForm = document.createElement('form');
    eForm._oFigure = aFigure;
    eForm.onsubmit = function () {
        this._oFigure.gmk_desc = document.getElementById('gmk_draw_desc').value;
        $this.save();
        $this._map.closeInfoWindow();
        return false;
    }
    eForm.onreset = function () {
        $this.removeFigure (this._oFigure);
        $this._map.closeInfoWindow();
        return false;
    }

    var eInput = document.createElement('input');
    eInput.name = 'desc';
    eInput.id = 'gmk_draw_desc';
    eInput.value = eForm._oFigure.gmk_desc;

    var eSubmit = document.createElement('input');
    eSubmit.type = 'submit';
    eSubmit.value = 'Update';
    eSubmit.style.marginLeft = '5px';

    var eBr = document.createElement('br');

    var eReset = document.createElement('input');
    eReset.type = 'reset';
    eReset.value = 'Remove Figure';
    eReset.style.marginTop = '5px';

    eForm.appendChild (eInput);
    eForm.appendChild (eSubmit);
    eForm.appendChild (eBr);
    eForm.appendChild (eReset);
    
    $this._map.openInfoWindow(eForm._oFigure.getVertex(0), eForm);
}
