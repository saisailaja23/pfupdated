
/*******************************************************************************************
 Common Google Maps file v.2.2
 License: proprietary license product, please read license.txt before using this product.
 Author: Trofimov Alexander (kolimarfey@gmail.com)
*******************************************************************************************/

function GMKPlacesBase (id, lat, lon, zoom, type)
{
	this._iSaveLocationId = 0; // override this
	this._sLoadingId = '';

	this._iId = id;
	this._fLat = lat.length ? parseFloat (lat) : 0;
	this._fLon = lon.length ? parseFloat (lon) : 0;
	this._iZoom = zoom.length ? parseInt (zoom) : 1;

	this._map = null;

	this._iMapType = type.length ? parseInt(type) : 0;
	this._iMapControl = 1;
	this._isTypeControl = true;
	this._isScaleControl = true;
	this._isOverviewControl = true;
    this._isLocalSearchControl = true;
	this._isDragable = true;
	this._sCustomType = 'all';

    this._iSaveMapType = this._iMapType;
    this._iSaveZoom = this._iZoom;
    this._fSaveLat = this._fSaveLng = 0;

    this._iClickable = 0;

    this._oDrawing = null;
    this._isDrawingMode = false;
    this._oDrawingOptions = 
    {
        read_only : false,
        url_images_base : '',
        url_load_data : '', 
        url_save_data: ''
    }

    this.sLangPositionSaved = 'Location has been succesfully saved';
    this.sLangPositionSaveFailed = 'Location saving failed';
    this.sLangGeocodeFailed = "Can not geocode following location:\n";
    this.sLangSelectLocation = "Please select location first";

    this.sActionsFile = 'override this';
    this.sActionShowLocations = 'override this';
}

GMKPlacesBase.prototype.init = function ()
{	
    google.load("maps", '2.x');    
    if (this._isLocalSearchControl) 
        google.load("search", '1');

	var $this = this;

	var h = function ()
	{
		if (!GBrowserIsCompatible()) {
			alert ("Sorry your browser is incompatible with Google Maps");
			return;
		}	

        $this.preload();

		glGMKPlacesBase_MAP = new GMap2(document.getElementById($this._iId));
		$this._map = glGMKPlacesBase_MAP;		

		$this._iMapControl = parseInt($this._iMapControl);
		switch ($this._iMapControl)
		{
			case 2: $this._map.addControl(new GLargeMapControl()); break;
			case 1: $this._map.addControl(new GSmallMapControl()); break;
		}

		if ($this._isTypeControl) $this._map.addControl(new GMapTypeControl());
		if ($this._isScaleControl) $this._map.addControl(new GScaleControl());
		if ($this._isOverviewControl) $this._map.addControl(new GOverviewMapControl ());        

		$this._map.setCenter(new GLatLng($this._fLat, $this._fLon), $this._iZoom);

		if (!$this._isDragable)
			$this._map.disableDragging();


		switch ($this._iMapType) 
		{
			case 1: $this._map.setMapType(G_SATELLITE_MAP); break;
			case 2: $this._map.setMapType(G_HYBRID_MAP); break;
			default: $this._map.setMapType(G_NORMAL_MAP); break;
		}

        if (!$this._iClickable)
		    $this.showLocations ();

		$this._map._gmk = $this;

        if (!$this._iClickable && !$this._isDrawingMode) 
		GEvent.addListener($this._map, "dragend", function() { 
			this._gmk.showLocations();
		});

        if (!$this._iClickable && !$this._isDrawingMode)
		GEvent.addListener($this._map, "zoomend", function(oldLevel,  newLevel) { 
			if (newLevel < oldLevel)
				this._gmk.showLocations();
		});

		
        if ($this._iClickable)        
            $this.initClickable();

        if ($this._isDrawingMode)
            $this.enableDrawing($this._oDrawingOptions.read_only, $this._oDrawingOptions.url_images_base, $this._oDrawingOptions.url_load_data, $this._oDrawingOptions.url_save_data);

        if ($this._isLocalSearchControl) 
        {
            $.getScript ("http://www.google.com/uds/solutions/localsearch/gmlocalsearch.js", function() 
            {   
                $this._map.addControl(new google.maps.LocalSearch(), new GControlPosition(G_ANCHOR_BOTTOM_RIGHT, new GSize(10,20)));
            });
        }

		$this.onload();
	}
    
	google.setOnLoadCallback(h);
	
}

GMKPlacesBase.prototype.saveLocation = function ()
{
    var oPos = this._map.getCenter();        
    this.saveLocationFull ('save_location', "&id=" + this.getLocationId(), oPos.lat(), oPos.lng());
}

GMKPlacesBase.prototype.saveLocationFull = function (sAction, sParams, fLat, fLng)
{
    var $this = this;
	var request = GXmlHttp.create();
	var iMapType = 0;
	var fZoom = this._map.getZoom();
	switch (this._map.getCurrentMapType())
	{
		case G_SATELLITE_MAP: iMapType = 1; break;
		case G_HYBRID_MAP: iMapType = 2; break;
	};	

    if (undefined == sParams) sParams = '';

    if (undefined == fLat) fLat = this._fSaveLat;
    if (undefined == fLng) fLng = this._fSaveLng;

    if ('save_location' != sAction && ((undefined == fLat || undefined == fLng) || (0 == fLat && 0 == fLng)))
    {
        alert (this.sLangSelectLocation);
        return;
    }    

    if (!this.onsavelocation (fLat, fLng, fZoom, iMapType, sParams)) return false;

	request.open("GET", this.sActionsFile + "?action=" + sAction + "&lat=" + fLat + "&lng=" + fLng + "&zoom=" + fZoom + "&type=" + iMapType + sParams, true);

	request.onreadystatechange = function() 
	{
		if (request.readyState == 4) 
		{
			if (request.responseText == '<ret>1</ret>')
            {                
				alert ($this.sLangPositionSaved);
                $this.onsavelocationsuccess (fLat, fLng, fZoom, iMapType, sParams);
            }
			else
            {
				alert ($this.sLangPositionSaveFailed);
                $this.onsavelocationfailed (fLat, fLng, fZoom, iMapType, sParams);
            }
		}
	}	

	request.send(null);
}

GMKPlacesBase.prototype.min = function (x, y)
{
    return x < y ? x : y;
}

GMKPlacesBase.prototype.max = function (x, y)
{
    return x > y ? x : y;
}

GMKPlacesBase.prototype.showLocations = function ()
{
	var bounds = this._map.getBounds();
	var southWest = bounds.getSouthWest();
	var northEast = bounds.getNorthEast();
	var span = bounds.toSpan();
	var request = GXmlHttp.create();
	var $this = this;

    if (!this.onshowlocationsbegin(southWest, northEast, span)) return;

	this._map.clearOverlays();

    var minLat = southWest.lat() <  northEast.lat() ? southWest.lat() : northEast.lat();
    var maxLat = minLat + ( southWest.lat() <  northEast.lat() ? span.lat() : -span.lat() );

    var minLng = southWest.lng() <  northEast.lng() || minLat < 0 ? southWest.lng() : northEast.lng();
    var maxLng = minLng + ( southWest.lng() <  northEast.lng() || minLat < 0 ? span.lng() : -span.lng() );
	request.open("GET", this.sActionsFile + "?action=" + this.sActionShowLocations + "&type="+this._sCustomType+"&minLat=" + this.min(minLat,maxLat) + "&maxLat=" + this.max(minLat,maxLat) + "&minLng=" + this.min(minLng,maxLng) + "&maxLng=" + this.max(minLng,maxLng), true);

	request.onreadystatechange = function() 
	{
		if (request.readyState == 4) 
		{

			var xml = GXml.parse(request.responseText);
			var aProfiles = xml.documentElement.getElementsByTagName("loc");
			for (var i = 0; i < aProfiles.length; i++) 
			{
				var point = new GLatLng(parseFloat(aProfiles[i].getAttribute("lat")), parseFloat(aProfiles[i].getAttribute("lng")));
				var e = aProfiles[i];
				var sData = e.textContent ? e.textContent : e.nodeValue;
				if (null == sData) sData = e.firstChild.nodeValue;				
				$this._map.addOverlay($this.createMarker(point, sData, aProfiles[i].getAttribute("icon")));
			}
			$this.loading(0);
            $this.onshowlocations(aProfiles);
		}
	}
	this.loading(1);
	request.send(null);
}

GMKPlacesBase.prototype.createMarker = function (point, html, pic) 
{
    var $this = this;
    var marker;
    var icon = new GIcon();

    if (!pic || undefined == pic || 0 == pic.length)
        pic = '0.png';

    icon.iconSize = new GSize(24, 24);
    icon.iconAnchor = new GPoint(12, 12);
    icon.infoWindowAnchor = new GPoint(12, 0);    
    icon.image = 'places/application/icons/' + pic;
    marker = new GMarker(point, icon);

    if (html.length)
	GEvent.addListener(marker, "click", function() { if (!$this.onshowinfowindow(marker, html)) return; marker.openInfoWindowHtml(html); });
    return marker;
}

GMKPlacesBase.prototype.setMapType = function (iMapType) 
{
	this._iMapType = iMapType;
}

GMKPlacesBase.prototype.setMapControl = function (iMapControl) 
{
	this._iMapControl = iMapControl;
}

GMKPlacesBase.prototype.setTypeControl = function (isTypeControl) 
{
	this._isTypeControl = isTypeControl == 'on' ? true : false;
}

GMKPlacesBase.prototype.setScaleControl = function (isScaleControl) 
{
	this._isScaleControl = isScaleControl == 'on' ? true : false;
}
	
GMKPlacesBase.prototype.setOverviewControl = function (isOverviewControl) 
{
	this._isOverviewControl = isOverviewControl == 'on' ? true : false;
}
	
GMKPlacesBase.prototype.setLocalSearchControl = function (b) 
{
	this._isLocalSearchControl = b == 'on' ? true : false;
}

GMKPlacesBase.prototype.enableDrawing = function (isReadOnly, sImagesBaseUrl, sLoadDataUrl, sSaveDataUrl) 
{
	this._oDrawing = new GMKDrawing(this._map, isReadOnly, sImagesBaseUrl, sLoadDataUrl, sSaveDataUrl);
}

GMKPlacesBase.prototype.disableDrawing = function () 
{
	this._oDrawing = null;
}

GMKPlacesBase.prototype.setDragable = function (isDragable) 
{
	this._isDragable = isDragable == 'on' ? true : false;
}

GMKPlacesBase.prototype.setCustomType = function (s) 
{
	this._sCustomType = s;
}
	
GMKPlacesBase.prototype.setLocationId = function (i) 
{
	this._iSaveLocationId = i;
}

GMKPlacesBase.prototype.getLocationId = function () 
{
	return this._iSaveLocationId;
}

GMKPlacesBase.prototype.setLoadingId = function (sId)
{
	this._sLoadingId = sId;
}

GMKPlacesBase.prototype.loading = function (bShow)
{
	var e = document.getElementById(this._sLoadingId);
	if (!e) return;
	e.style.display = bShow ? 'block' : 'none';
}

function gmk_move_prev (id)
{
	var iCurrent = parseInt(document.getElementById(id).innerHTML);
	if (!document.getElementById(id+(iCurrent-1))) return;
	document.getElementById(id+iCurrent).style.display = 'none';
	--iCurrent;
	document.getElementById(id+iCurrent).style.display = 'block';
	document.getElementById(id).innerHTML = iCurrent;
}

function gmk_move_next (id)
{
	var iCurrent = parseInt(document.getElementById(id).innerHTML);
	if (!document.getElementById(id+(iCurrent+1))) return;
	document.getElementById(id+iCurrent).style.display = 'none';
	++iCurrent;
	document.getElementById(id+iCurrent).style.display = 'block';
	document.getElementById(id).innerHTML = iCurrent;
}

GMKPlacesBase.prototype.setClickable = function () 
{
    this._iClickable = 1;
}

GMKPlacesBase.prototype.initClickable = function () 
{		
    	var $this = this;

		var hh = function(marker, point) 
		{			
			var iMapType = 0;
			switch ($this._map.getCurrentMapType())
			{
				case G_SATELLITE_MAP: iMapType = 1; break;
				case G_HYBRID_MAP: iMapType = 2; break;
			};	
            if (!$this.onsavemaptypezoombefore (iMapType, $this._map.getZoom())) return;
			$this._iSaveMapType = iMapType;
			$this._iSaveZoom = $this._map.getZoom();
            $this.onsavemaptypezoomafter (iMapType, $this._map.getZoom());
		};

		var h = function(marker, point) 
		{            
            if (!point) return;            
            if (!$this.onclickbefore(marker, point)) return;
    		$this._fSaveLat = point.lat();
	    	$this._fSaveLng = point.lng();
		    $this._map.clearOverlays();
			$this._map.addOverlay($this.createMarker(point, '', ''));
            $this.onclickafter(marker, point);
		};

		GEvent.addListener(this._map, "click", h);
		GEvent.addListener(this._map, "zoomend", hh);
		GEvent.addListener(this._map, "maptypechanged", hh);


        if (this._fLat != 0 && this._fLon != 0)
        {
    		var point = new GLatLng(this._fLat, this._fLon);
	    	this._map.clearOverlays();
		    this._map.addOverlay(this.createMarker(point, '', ''));
            this._fSaveLat = this._fLat;
            this._fSaveLng = this._fLng;
        }
}

GMKPlacesBase.prototype.findLocation = function (sAddress) 
{
	if (undefined == this._geocoder)
		this._geocoder = new GClientGeocoder();

    this.onfindlocation (sAddress);

	$this = this;
	var h = function (response)
	{
		if (!response || 200 != response.Status.code)
		{
			alert ($this.sLangGeocodeFailed + sAddress);
            $this.onfindlocationfailed (response, sAddress);
			return;
		}

        if (!$this.onfindlocationsuccess (response, sAddress)) return;

      	var place = response.Placemark[0];
      	var point = new GLatLng(place.Point.coordinates[1], place.Point.coordinates[0]);

      	$this._map.setCenter(point, 6);
		$this._map.clearOverlays();
        $this._map.addOverlay($this.createMarker(point, '', ''));

		$this._fSaveLat = point.lat();
		$this._fSaveLng = point.lng();
		$this._iSaveMapType = 6;

        $this.onfindlocationsuccesscomplete (response, sAddress);
	}

    this._geocoder.getLocations(sAddress, h);
}

GMKPlacesBase.prototype.preload = function ()
{

}

GMKPlacesBase.prototype.onload = function ()
{

}

GMKPlacesBase.prototype.onshowlocations = function (aProfiles)
{

}

GMKPlacesBase.prototype.onshowlocationsbegin = function (southWest, northEast, span)
{
    return true;
}

GMKPlacesBase.prototype.onshowinfowindow = function (marker, html)
{
    return true;
}

GMKPlacesBase.prototype.onclickbefore = function (market, point)
{
    return true;
}

GMKPlacesBase.prototype.onclickafter = function (market, point)
{

}

GMKPlacesBase.prototype.onsavemaptypezoombefore = function (iMapType, fZoom)
{
    return true;
}

GMKPlacesBase.prototype.onsavemaptypezoomafter = function (iMapType, fZoom)
{

}

GMKPlacesBase.prototype.onsavelocation = function (fLat, fLng, fZoom, iMapType, sParams)
{
    return true;
}

GMKPlacesBase.prototype.onsavelocationsuccess = function (fLat, fLng, fZoom, iMapType, sParams)
{

}

GMKPlacesBase.prototype.onsavelocationfailed = function (fLat, fLng, fZoom, iMapType, sParams)
{

}

GMKPlacesBase.prototype.onfindlocation = function (sAddress)
{

}

GMKPlacesBase.prototype.onfindlocationfailed = function (response, sAddress)
{

}

GMKPlacesBase.prototype.onfindlocationsuccess = function (response, sAddress)
{
    return true;
}

GMKPlacesBase.prototype.onfindlocationsuccesscomplete = function (response, sAddress)
{

}


GMKPlacesBase.prototype.setDrawingOptions = function (isReadOnly, sImagesBaseUrl, sLoadDataUrl, sSaveDataUrl)
{    
    this._oDrawingOptions = 
    {
        read_only : isReadOnly,
        url_images_base : sImagesBaseUrl,
        url_load_data : sLoadDataUrl, 
        url_save_data: sSaveDataUrl
    }
    this._isDrawingMode = true;
}
