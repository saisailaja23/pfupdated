<?php 
$action	= $_POST['action'];

if ($_POST['action'] == "listAlbum"){
	
	if(isset($_POST['ID'])){
		$albumID = $_POST['ID'];
		//echo $_POST['ID'];
		?>
		<span class="control">
        	<!-- BTN Upload Files -->
        	<a href="processors/uploadFiles.php?albumID=<? echo $albumID; ?>&iframe=true&width=80%&height=420" data-id="<?php echo $albumID; ?>" rel="prettyPhoto" class="btn btn-primary tooltip" data-title="Upload files." title="Upload files."><i class="fa fa-upload"></i> Upload Files</a>
        	
            <!-- BTN Cover Letter -->
	        <a href="processors/coverLetter.php?albumID=<? echo $albumID; ?>&iframe=true&width=80%&height=420" rel="prettyPhoto" class="btn btn-primary tooltip" data-title="Cover Letter." title="Cover Letter."><i class="fa fa-file-text"></i> Cover Letter</a>
        	
            <!-- BTN Unpublish -->
            <a href="#" class="btn btn-warning tooltip right publish" data-id="<?php echo $albumID; ?>" data-title="Unpublish Lifebook." title="Unpublish Lifebook."><i class="fa fa-arrow-circle-o-down"></i> Unpublish Lifebook</a>
            
            <!-- BTN Publish -->
            <a href="#" class="btn btn-success tooltip right publish" data-id="<?php echo $albumID; ?>" data-title="Publish Lifebook." title="Publish Lifebook."><i class="fa fa-arrow-circle-o-up"></i> Publish Lifebook</a>
            
            <!-- BTN Delete -->
            <a href="#" class="btn btn-danger tooltip right delete" data-id="<?php echo $albumID; ?>" data-title="Delete Lifebook." title="Delete Lifebook."><i class='fa fa-trash-o tooltip' data-title='Delete Lifebook.'></i> Delete Lifebook</a>
        </span>
		<p class="edit">Lifebook description</p>
		<?
		echo "<ul class='albums'>";
		?>
				<li>
                	<i class="fa fa-youtube-play fa-4x"></i>
                	<a href='images/10/video.flv?width=864&amp;height=486' id='id_docx' rel='prettyPhoto[Album]' title='Title text VIDEO'>
                    	<img src='images/10/video.jpg' />
					</a>
                    <span class='tp-info'>
                    	<span class='edit'>Foto title text .VIDEO</span>
					</span>
					<div class='tools'>
                    	<i class="fa fa-play-circle-o">&nbsp;Video</i>
                    	<i class='fa fa-comments tooltip' data-title='Comments!'>3&nbsp;&nbsp;</i>
                        <i class='fa fa-eye tooltip' data-title='Views.'>20&nbsp;&nbsp;</i>
                        <i class='fa fa-trash-o tooltip delete' data-title='Delete doc.'>&nbsp;&nbsp;</i>
                        <i class='fa fa-arrows tooltip' data-title='Sort doc.'></i>
					<div>
				</li>

                <li>
                	<a href='http://docs.google.com/gview?url=http://cdmap01.myadoptionportal.com/modules/childconnect/images/10/Report%20Childconnect.doc&embedded=true&iframe=true&width=90%&height=90%' id='id_docx' rel='prettyPhoto[Album]' title='Title text .DOCX'>
                    	<img src='images/docx.jpg' />
					</a>
                    <span class='tp-info'>
                    	<span class='edit'>Foto title text .DOCX</span>
					</span>
					<div class='tools'>
                    	<i class='fa fa-comments tooltip' data-title='Comments!'>3&nbsp;&nbsp;</i>
                        <i class='fa fa-eye tooltip' data-title='Views.'>20&nbsp;&nbsp;</i>
                        <i class='fa fa-trash-o tooltip delete' data-title='Delete doc.'>&nbsp;&nbsp;</i>
                        <i class='fa fa-arrows tooltip' data-title='Sort doc.'></i>
					<div>
				</li>

                <li>
<!--                	<a href='http://docs.google.com/gview?url=http://cdmap01.myadoptionportal.com/modules/childconnect/images/10/Integration_Sample.pdf&embedded=true&iframe=true&width=90%&height=90%' id='id_pdf' rel='prettyPhoto[Album]' title='Title text .PDF'>-->
                    <a href='http://cdmap01.myadoptionportal.com/modules/childconnect/images/10/Integration_Sample.pdf?iframe=true&width=90%&height=90%' id='id_pdf' rel='prettyPhoto[Album]' title='Title text .PDF'>
                    	<img src='images/pdf.jpg' />
					</a>
                    <span class='tp-info'>
                    	<span class='edit'>Foto title text .PDF</span>
					</span>
					<div class='tools'>
                    	<i class='fa fa-comments tooltip' data-title='Comments!'>3&nbsp;&nbsp;</i>
                        <i class='fa fa-eye tooltip' data-title='Views.'>20&nbsp;&nbsp;</i>
                        <i class='fa fa-trash-o tooltip delete' data-title='Delete PDF.'>&nbsp;&nbsp;</i>
                        <i class='fa fa-arrows tooltip' data-title='Sort PDF.'></i>
					<div>
				</li>
		<?
		for ($x=0; $x<=$albumID; $x++){
//		for ($i = 0; $i > $albumID; $i++){
			$rand_value = rand(1,9);
			$rand_photo = rand(1,999);
			echo "<li><a href='images/10/img_0".$rand_value.".jpg' id='".$x."_".$rand_photo."' rel='prettyPhoto[Album]' title='Title text ".$rand_value."'><img src='images/10/img_0".$rand_value."_thumb.jpg?". time() ."' /></a><span class='tp-info'><span class='edit'>Foto title text ".$rand_value."</span></span><div class='tools'><i class='fa fa-comments tooltip' data-title='Comments!'>3&nbsp;&nbsp;</i><i class='fa fa-eye tooltip' data-title='Views.'>20&nbsp;&nbsp;</i><i class='fa fa-pencil-square-o tooltip' data-title='Edit image.'></i>&nbsp;&nbsp;<i class='fa fa-trash-o tooltip delete' data-title='Delete photo.'></i>&nbsp;&nbsp;<i class='fa fa-arrows tooltip' data-title='Sort image.'></i><div></li>";
			
		}
		echo "<ul>";
	}

?>
<?php
}else{
?>
					<ul class="filter-menu">
						<li class="btn"><a href="#" rel="all">All</a></li>
						<li><a href="#" rel="published">Published</a></li>
						<li><a href="#" rel="saved">Saved</a></li>
						<li><a href="#" rel="pending">Pending Review</a></li>
					</ul>
                    <div id="lifebooksCont"><span>6</span> Lifebook(s)</div>
                    
                    <a href="#" class="btn btn-primary tooltip addAlbum" data-title="Create Lifebook." title="Create Lifebook."><i class="fa fa-plus"></i> Create Lifebook</a>
                    
					<ul id="lb-grid">
						<li rel="saved">
							<span class="tp-info"><span class="edit">Holliday in the Zoo</span>
                               	<span class="lb-views">23</span>
							</span>
							<img class="lb-thumb" id="23" src="images/10/img_01_thumb.jpg" alt="Holliday in the Zoo" />
                            <i class='fa fa-eye'>10</i>
						</li>
						
                        <li rel="published">
							<span class="tp-info">Holliday
                               	<span class="lb-views">150</span>
							</span>
							<img class="lb-thumb" id="150" src="images/10/img_02_thumb.jpg" alt="Holliday"/>
                            <i class='fa fa-eye'>25</i>
						</li>
						
                        <li rel="published">
							<span class="tp-info">Holliday test
								<span class="lb-views">200</span>
							</span>
							<img class="lb-thumb" id="200" src="images/10/img_03_thumb.jpg" alt="Holliday test"/>
                            <i class='fa fa-eye'>20</i>
						</li>
						
                        <li rel="published">
							<span class="tp-info"><span class="edit">Holliday in the Zoo</span>
                               	<span class="lb-views">30</span>
							</span>
							<img class="lb-thumb" id="30" src="images/10/img_04_thumb.jpg" alt="Test Holliday" />
                            <i class='fa fa-eye'>33</i>
						</li>
						
                        <li rel="saved">
							<span class="tp-info">Fest II
                               	<span class="lb-views">33</span>
							</span>
							<img class="lb-thumb" id="33" src="images/10/img_05_thumb.jpg" alt="Fest II" />
                            <i class='fa fa-eye'>24</i>
						</li>
						
                        <li rel="pending">
							<span class="tp-info"><span class="edit">Fest IV</span>
                               	<span class="lb-views">43</span>
							</span>
							<img class="lb-thumb" id="43" src="images/10/img_05_thumb.jpg" alt="Fest III" />
                            <i class='fa fa-eye'>51</i>
						</li>
                        
					</ul>
<?php }?>