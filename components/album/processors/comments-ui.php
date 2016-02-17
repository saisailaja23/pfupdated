<?php 
require_once( '../../../inc/header.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'design.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'profiles.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'utils.inc.php' );
require_once( BX_DIRECTORY_PATH_CLASSES . 'BxDolAlbums.php');

$logged = getLoggedId();
$profileSql = "select NickName,Avatar from Profiles where id = $logged";
$result = mysql_query($profileSql);
$row = mysql_fetch_assoc($result);
$name=$row['NickName'];
$avatar=$row['Avatar'];
$fileID = $_POST['fileID'];

//echo "<p class='edit' data-id='". $fileID ."'>Description test</p>\n";
//echo "Comments!!";

?>
<script type="text/javascript" srs="js/comments.js"></script>
<div id="profileData" data-name="<?php echo $name; ?>" data-file="<?php echo $fileID; ?>" data-avatar="<?php echo $avatar; ?>" ></div>
<ul class="comment-box">
	<li class="arrow-box-left">
		<div class="avatar"><img class="avatar-small" src="images/avatar1.jpg"></div>
		<div class="info">
			<span class="name"><strong>Michael</strong></span>
			<span class="time">8 minutes ago</span>
		</div>
		Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.

	</li>

	<li class="arrow-box-right gray">
		<div class="avatar"><img class="avatar-small" src="images/avatar2.jpg"></div>
		<div class="info">
			<span class="name"><strong>Alex</strong></span>
			<span class="time">3 minutes ago</span>
		</div>
		Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
	</li>
	
  <li class="arrow-box-left">
		<div class="avatar"><img class="avatar-small" src="images/avatar1.jpg"></div>
		<div class="info">
			<span class="name"><strong>Michael</strong></span>
			<span class="time">8 minutes ago</span>
		</div>
		Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
	</li>
    
</ul>

<div id="comment-form">
    <textarea placeholder="Type a message here..." rows="1" id="comment"></textarea>
</div>
