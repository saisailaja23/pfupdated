<?php 

$albumID = $_GET['albumID'];

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Cover Letter</title>

<script type="text/javascript" src="../js/jquery-1.9.1.js"></script>
<script type="text/javascript" src="../js/tinymce/tinymce.min.js"></script>
<script type="text/javascript">
tinymce.init({
    selector: "textarea",
	height : 295,
	theme : "modern",
    plugins: [
        "save advlist autolink lists link image charmap print preview anchor",
        "searchreplace visualblocks code",
        "insertdatetime media table contextmenu paste spellchecker"
    ],
    toolbar: "save | insertfile undo redo | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media | spellchecker | print preview",
	save_enablewhendirty: true,
    save_onsavecallback: function() {
		var ed = tinyMCE.get('content');
        ed.setProgressState(1); // Show progress
		$.post( "saveCoverLetter.php", { "albumID" : <?php echo $albumID; ?>, "content": ed.getContent() })
  		.done(function( data ) {
			ed.setProgressState(0); // Hide progress
		})
	},
	menubar: false,
	spellchecker_rpc_url: "rpc.php"
});
</script>

</head>

<body>
<form method="post">
    <textarea name="content" style="width:100%; height:100%;"><?php echo "Content..." ?></textarea>
</form>
</body>
</html>