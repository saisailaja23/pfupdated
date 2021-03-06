
/*******************************************************************************************
 Places v.1.0
 License: proprietary license product, please read license.txt before using this product.
 Author: Trofimov Alexander (kolimarfey@gmail.com)
*******************************************************************************************/

function GMKPlacesEdit (id, lat, lon, zoom, type)
{
	this._iSaveLocationId = 0;
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
	this._isDragable = true;
	this._sCustomType = 'all';

    this._iSaveMapType = this._iMapType;
    this._iSaveZoom = this._iZoom;
    this._fSaveLat = this._fSaveLng = 0;

    this._iClickable = 0;

    this.sLangPositionSaved = 'Location has been succesfully saved';
    this.sLangPositionSaveFailed = 'Location saving failed';
    this.sLangGeocodeFailed = "Can not geocode following location:\n";
    this.sLangSelectLocation = "Please select location first";

    this.sActionsFile = 'places.php/gmk_actions';
    this.sActionShowLocations = 'get';

    this._isLocationsDisplayed = 0;
}

GMKPlacesEdit.prototype = GMKPlacesBase.prototype;

GMKPlacesEdit.prototype.showLocations = function ()
{
    if (0 == this._fLat && 0 == this._fLon) return; 
    if (1 == this._isLocationsDisplayed) return;
    var point = new GLatLng(this._fLat, this._fLon);
	this._map.addOverlay(this.createMarker(point, '', ''));
    this._isLocationsDisplayed = 1;
}

GMKPlacesEdit.prototype.onsavemaptypezoomafter = function (iMapType, fZoom)
{
    document.getElementById('gmk_zoom').value = this._iSaveZoom;
    document.getElementById('gmk_type').value = this._iSaveMapType;
}

GMKPlacesEdit.prototype.onclickafter = function (market, point)
{
    document.getElementById('gmk_lat').value = this._fSaveLat;
    document.getElementById('gmk_lng').value = this._fSaveLng;
}

GMKPlacesEdit.prototype.onfindlocationsuccesscomplete = function (response, sAddress)
{
    document.getElementById('gmk_lat').value = this._fSaveLat;
    document.getElementById('gmk_lng').value = this._fSaveLng;
}

