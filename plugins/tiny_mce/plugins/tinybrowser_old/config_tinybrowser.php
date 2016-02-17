<?php
/*
TinyBrowser 1.41 - A TinyMCE file browser (C) 2008  Bryn Jones
(author website - http://www.lunarvis.com)

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.
    
*/

/*[DeeEmm Web Technologies]===================================

    This file modified by DeeEmm AKA Mick Percy on 18/03/2010 18:11
    
    - Modified to integrate with Boonex Dolphin version 6.1.6 
    
--[Support]---------------------------------------------------

    For installation instructions please refer to the README file
    supplied with this modification. For support please contact 
    support@deeemm.com or visit our helpdesk / forums
    
=====================================[http://www.deeemm.com]*/

// switch off error handling, to use custom handler
error_reporting(0); 

// set script time out higher, to help with thumbnail generation
set_time_limit(240);

$tinybrowser = array();

// Session control and security check - to enable please uncomment
//if(isset($_GET['sessidpass'])) session_id($_GET['sessidpass']); // workaround for Flash session bug
//session_start();
//$tinybrowser['sessioncheck'] = 'authenticated_user'; //name of session variable to check

// Random string used to secure Flash upload if session control not enabled - be sure to change!
$tinybrowser['obfuscate'] = 's0mer4nd0mjunk!!!111';

// Set default language (ISO 639-1 code)
$tinybrowser['language'] = 'en';

// Set the integration type (TinyMCE is default)
$tinybrowser['integration'] = 'tinymce'; // Possible values: 'tinymce', 'fckeditor'

// Default is rtrim($_SERVER['DOCUMENT_ROOT'],'/') (suitable when using absolute paths, but can be set to '' if using relative paths)
$tinybrowser['docroot'] = rtrim($_SERVER['DOCUMENT_ROOT'],'/');

// Folder permissions for Unix servers only
$tinybrowser['unixpermissions'] = 0777;


//<!--[DeeEmm Dolphin Integration START]-->

$bUseNickName = TRUE;
$sDolUserDir = 0 ;
$iDolUserID = 0;

//Get member ID from cookie
$iDolUserID = (int)$_COOKIE['memberID'];

if ($bUseNickName == TRUE) {

//Get database details
include( '../../../../inc/header.inc.php' );

mysql_connect($db['host'],$db['user'],$db['passwd']);
mysql_select_db($db['db']);

//Get Nick from DB using member ID 
$result = mysql_query( "SELECT * FROM `Profiles` WHERE `ID` = '$iDolUserID' " );
while($aArr = mysql_fetch_array($result))
{
	$sDolUserDir = $aArr['NickName'];
}

//Check we have a username else use default id
if (!$sDolUserDir) {
	$sDolUserDir = '0';
}

} else {

//not using nicknames so use userid instead
$sDolUserDir = $iDolUserID;

}

// File upload paths (set to absolute by default)
$tinybrowser['path']['image'] = '/media/users/' . $sDolUserDir . '/images/'; // Image files location - also creates a '_thumbs' subdirectory within this path to hold the image thumbnails
$tinybrowser['path']['media'] = '/media/users/' . $sDolUserDir . '/media/'; // Media files location
$tinybrowser['path']['file']  = '/media/users/' . $sDolUserDir . '/files/'; // Other files location

//<!--[DeeEmm Dolphin Integration END]-->

// File link paths - these are the paths that get passed back to TinyMCE or your application (set to equal the upload path by default)
$tinybrowser['link']['image'] = $tinybrowser['path']['image']; // Image links
$tinybrowser['link']['media'] = $tinybrowser['path']['media']; // Media links
$tinybrowser['link']['file']  = $tinybrowser['path']['file']; // Other file links

// File upload size limit (0 is unlimited)
$tinybrowser['maxsize']['image'] = 0; // Image file maximum size
$tinybrowser['maxsize']['media'] = 0; // Media file maximum size
$tinybrowser['maxsize']['file']  = 0; // Other file maximum size

// Image automatic resize on upload (0 is no resize)
$tinybrowser['imageresize']['width']  = 800;
$tinybrowser['imageresize']['height'] = 600;

// Image thumbnail source (set to 'path' by default - shouldn't need changing)
$tinybrowser['thumbsrc'] = 'path'; // Possible values: path, link

// Image thumbnail size in pixels
$tinybrowser['thumbsize'] = 80;

// Image and thumbnail quality, higher is better (1 to 99)
$tinybrowser['imagequality'] = 80; // only used when resizing or rotating
$tinybrowser['thumbquality'] = 80;

// Date format, as per php date function
$tinybrowser['dateformat'] = 'd/m/Y H:i';

// Permitted file extensions
$tinybrowser['filetype']['image'] = '*.jpg, *.jpeg, *.gif, *.png'; // Image file types
$tinybrowser['filetype']['media'] = '*.swf, *.dcr, *.mov, *.qt, *.mpg, *.mp3, *.mp4, *.mpeg, *.avi, *.wmv, *.wm, *.asf, *.asx, *.wmx, *.wvx, *.rm, *.ra, *.ram'; // Media file types
$tinybrowser['filetype']['file']  = '*.*'; // Other file types

// Prohibited file extensions
$tinybrowser['prohibited'] = array('php','php3','php4','php5','phtml','htm','html','asp','aspx','ascx','jsp','cfm','cfc','pl','bat','exe','dll','reg','cgi', 'sh', 'py','asa','asax','config','com','inc');

// Default file sort
$tinybrowser['order']['by']   = 'name'; // Possible values: name, size, type, modified
$tinybrowser['order']['type'] = 'asc'; // Possible values: asc, desc

// Default image view method
$tinybrowser['view']['image'] = 'thumb'; // Possible values: thumb, detail

// File Pagination - split results into pages (0 is none)
$tinybrowser['pagination'] = 0;

// TinyMCE dialog.css file location, relative to tinybrowser.php (can be set to absolute link)
$tinybrowser['tinymcecss'] = '../../themes/advanced/skins/default/dialog.css';

// TinyBrowser pop-up window size
$tinybrowser['window']['width']  = 770;
$tinybrowser['window']['height'] = 480;

// Assign Permissions for Upload, Edit, Delete & Folders
$tinybrowser['allowupload']  = true;
$tinybrowser['allowedit']    = true;
$tinybrowser['allowdelete']  = true;
$tinybrowser['allowfolders'] = true;

// Clean filenames on upload
$tinybrowser['cleanfilename'] = true;

// Set default action for edit page
$tinybrowser['defaultaction'] = 'rename'; // Possible values: delete, rename, move

// Set delay for file process script, only required if server response is slow
$tinybrowser['delayprocess'] = 0; // Value in seconds

//error_reporting(E_ALL);
//ini_set(display_errors,"1"); 

?>