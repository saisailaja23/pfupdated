<?php
/* This section can be removed if you would like to reuse the PHP example outside of this PHP sample application */
require_once("lib/config.php"); 
require_once("lib/common.php");

$configManager = new Config();
if($configManager->getConfig('admin.password')==null){
	$url = 'setup.php';
	header("Location: $url");
	exit;
}

$viewMode = 'html,flash';
if($_GET["ro"])
{
	$viewMode = $_GET["ro"];
}

?>

<!doctype html>
    <head> 
        <title>FlexPaper AdaptiveUI PHP Example</title>                
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="initial-scale=1,user-scalable=no,maximum-scale=1,width=device-width" />

        <style type="text/css" media="screen"> 
			html, body	{ height:100%; }
			body { margin:0; padding:0; overflow:auto; }   
			#flashContent { display:none; }
			
        </style> 
		
		<link rel="stylesheet" type="text/css" href="css/flexpaper.css" />
		<script type="text/javascript" src="js/jquery.min.js"></script>
		<script type="text/javascript" src="js/jquery.extensions.min.js"></script>
		<script type="text/javascript" src="js/flexpaper.js"></script>
		<script type="text/javascript" src="js/flexpaper_handlers.js"></script>
    </head> 
    <body>  
		<div id="documentViewer" class="flexpaper_viewer" style="position:absolute;width:100%;height:99%"></div>
	        
	        <script type="text/javascript">   

				
		        function getDocumentUrl(document){
				return "services/view.php?doc={doc}&format={format}&page={page}".replace("{doc}",document);     
		        }
		        
		        function getDocQueryServiceUrl(document){
		        	return "services/swfsize.php?doc={doc}&page={page}".replace("{doc}",document);
		        }
		        
		        var startDocument = "<?php if(isset($_GET["doc"])){echo $_GET["doc"];}else{?>Paper.pdf<?php } ?>";
				<?php
				
				if($_GET["ro"] == "html5,html,flash")
				{
					?>
					$('#documentViewer').FlexPaperViewer(
					{ config : {
							//PDFFile : '../../../pf/PDFTemplates/user/' + startDocument
                                                        
                                                  PDFFile : '../../../PDFTemplates/user/' + startDocument
                                                  
							,Scale : <?php echo $_GET["scale"];?>
							,RenderingOrder : 'html5'
							,key : '<?php echo $configManager->getConfig('licensekey') ?>'
							,ZoomTransition : 'easeOut'
							,ZoomTime : 0.5
							,ZoomInterval : 0.1
							,FitPageOnLoad : false
							,FitWidthOnLoad : false
							,FullScreenAsMaxWindow : true
							,ProgressiveLoading : true
							,MinZoomSize : 0.2
							,MaxZoomSize : 5
							,SearchMatchAll : false
							,InitViewMode : 'Portrait'
							,ViewModeToolsVisible : false
							,ZoomToolsVisible : false
							,NavToolsVisible : false
							,CursorToolsVisible : false
							,SearchToolsVisible : false
							
					}});
					<?php
				}
				
				else
				{
					?>
					$('#documentViewer').FlexPaperViewer(
					{ 
						config :
						{
							 
							DOC : escape(getDocumentUrl(startDocument)),
							Scale : <?php echo $_GET["scale"];?>, 
							ZoomTransition : 'easeOut',
							ZoomTime : 0.5, 
							ZoomInterval : 0.1,
							FitPageOnLoad : false,
							FitWidthOnLoad : false, 
							FullScreenAsMaxWindow : false,
							ProgressiveLoading : true,
							MinZoomSize : 0.2,
							MaxZoomSize : 5,
							SearchMatchAll : false,
							InitViewMode : 'Portrait',
							RenderingOrder : '<?php echo $viewMode;?>',
							
							ViewModeToolsVisible : false,
							ZoomToolsVisible : false,
							NavToolsVisible : false,
							CursorToolsVisible : false,
							SearchToolsVisible : false,
							
							//UIConfig : "UI_Zine.xml",						
	
							//DocSizeQueryService : 'services/swfsize.php?doc=' + startDocument,
							//JSONDataType : 'jsonp',
							key : '<?php echo $configManager->getConfig('licensekey') ?>',
	
							WMode : 'transparent',
							localeChain: 'en_US'
						}
					});
					<?php
				}
				?>
				
				
				
	            
				
				jQuery('#documentViewer').bind('onDocumentLoaded',function( e, totalPages )
				{
					try
					{
						window.parent.FlexPaperComponent.progressOff( '<?php echo $_GET["uid"];?>' );	
					}catch(e)
					{
						
					}
				});
				
	        </script>
   </body> 
</html> 