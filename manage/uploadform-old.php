<?
include('master.inc.php');
extract($_GET, EXTR_PREFIX_ALL, 'form_'); extract($_POST, EXTR_PREFIX_ALL, 'form_');

switch($form_type){
	case 1:		$_SESSION["uploadTab"] = "photo";break;
	case 2:		$_SESSION["uploadTab"] = "video";break;
	case 3:		$_SESSION["uploadTab"] = "slide";break;
	case 4:		$_SESSION["uploadTab"] = "video";break;	// video thumbnail
}

function showAttachments($code, $type, $id){
	global $form_id;
	$session = session_id();
	switch($type){
		case 1:
			if($id != 0)
				$results1 = dbQuery("SELECT content_photo_id, content_photo_thumb, content_photo_title FROM content_photo WHERE content_photo_content=$form_id");
			else
				$results2 = dbQuery("SELECT attachment_id, attachment_thumb, attachment_title FROM attachment WHERE attachment_code=$code AND attachment_key='$session' AND attachment_type=1");
			$edit = "uploadform.php?action=edit&id=$form_id&code=$code&type=1";
			$delete = "uploadform.php?action=delete&id=$form_id&code=$code&type=1";
			break;
		case 2:
			if($id != 0)
				$results1 = dbQuery("SELECT content_video_id, content_video_thumb, content_video_title FROM content_video WHERE content_video_content=$form_id");
			else
				$results2 = dbQuery("SELECT attachment_id, attachment_thumb, attachment_title FROM attachment WHERE attachment_code=$code AND attachment_key='$session' AND attachment_type=2");
			$edit = "uploadform.php?action=edit&id=$form_id&code=$code&type=2";
			$delete = "uploadfrom.php?action=delete&id=$form_id&code=$code&type=2";
			break;
		case 3: 
			if($id != 0)
				$results1 = dbQuery("SELECT content_slide_id, content_slide_thumb, content_slide_title FROM content_slide WHERE content_slide_content=$form_id");
			else
				$results2 = dbQuery("SELECT attachment_id, attachment_thumb, attachment_title FROM attachment WHERE attachment_code=$code AND attachment_key='$session' AND attachment_type=1");
			$edit = "uploadform.php?action=edit&id=$form_id&code=$code&type=3";
			$delete = "uploadform.php?action=delete&id=$form_id&code=$code&type=3";
			break;
	}
	echo "<table>";
	$row = 0;
	$col = 9999;
	if($id != 0){
		while($rec = dbFetchArray($results1)){
			if($col > 6){
				if($row > 0)
					echo "</tr>";
				echo "<tr valign='bottom'>";
				$col = 0;
			}
			if($rec[1] == "")
				$rec[1] = "no-thumb.jpg";
			echo "<td><a href='$edit&aid=0&pid=$rec[0]'><img src='../upload/$rec[1]'><br>$rec[2]</a><a href='$delete&aid=0&pid=$rec[0]'><img title='Delete Content Page Photo' height='14' src='images/del.png' border='0'/></a></td>\n";
		}
	}
	else{
		while($rec = dbFetchArray($results2)){
			if($col > 6){
				if($row > 0)
					echo "</tr>";
				echo "<tr valign='bottom'>";
				$col = 0;
			}
			if($rec[1] == "")
				$rec[1] = "no-thumb.jpg";
			echo "<td><a href='$edit&pid=0&aid=$rec[0]'><img border='0' src='../upload/$rec[1]'><br>$rec[2]</a><a href='$delete&pid=0&aid=$rec[0]'><img title='Delete Content Page Photo' height='14' src='images/del.png' border='0'/></a></td>\n";
		}
	}
	echo "</tr></table>";
}

function editForm($code, $type, $pid, $aid){
	global $form_id;
	if($aid != 0){
		$rec = getFields("", dbRow("attachment", $aid), "SHOW");
		$name = "attachment";
	}
	else{
		switch($type){
			case 1:
				$rec = getFields("", dbRow("content_photo", $pid), "SHOW");
				$name = "photo";
				break;
			case 2:
				$rec = getFields("", dbRow("content_video", $pid), "SHOW");
				$name = "video";
				break;
			case 3:
				$rec = getFields("", dbRow("content_slide", $pid), "SHOW");
				$name = "slide";
				break;
		}
	}
	echo "<h2>Edit $name</h2>";
	if(numErrors() > 0){
		printErrors();
		$rec = getFields(defaultTable, $_POST, "SHOW");
	}
	echo "<form action='uploadform.php' method='post' enctype='multipart/form-data'>
			<input type=hidden name=id value='$form_id'>
			<input type=hidden name=pid value='$pid'>
			<input type=hidden name=aid value='$aid'>
			<input type=hidden name=code value='$code'>
			<input type=hidden name=type value='$type'>
			<input type='hidden' name='action' value='EditRecord'/>
		<table class='form'><tbody>";

	if($aid != 0){
		printTextField("Title", $rec, "attachment_title", 40, 80);
		printFileUploadField("Change Attachment", $rec, "Filedata");
	}
	else{
		switch($type){
			case 1:
				printTextField("Photo Title", $rec, "content_photo_title", 40, 80);
				printFileUploadField("Change Photo", $rec, "Filedata");
				break;
			case 2:
				printTextField("Video Title", $rec, "content_video_title", 40, 80);
				printFileUploadField("Video Thumbnail", $rec, "Filedata");
				break;
			case 3:
				printTextField("Slide Title", $rec, "content_slide_title", 40, 80);
				printFileUploadField("Change Slide", $rec, "Filedata");
				break;
		}
	}

	echo "	</thead>
		<tfoot>
			<tr class='bar'>
			<td colspan='2'>
				<input style='float: left' class='submitButton' type='submit' value='Edit $name'/>
				<input style='float: right' class='cancelButton' type='button' value='Cancel' onclick=\"location.href='".this_php.defaultParams."'\">
			</td></tr>
		</tfoot></table>
		</form>
	";
}
function editRecord($code, $type, $pid, $aid){
	if($aid != 0){
		$fields = getFields("attachment", $_POST, "SAVE");
		if($_FILES["Filedata"]["name"] != ""){
			$fields["attachment_name"] = uploadFile("Filedata", "", "", true, "upload/");
			$fields["attachment_thumb"] = makeThumb($fields["attachment_name"], "thumb_{$fields["attachment_name"]}", 80, 80);
		}
		dbPerform("attachment", $fields, 'update', "attachment_id=$aid");
	}
	else{
		switch($type){
			case 1:
				$fields = getFields("content_photo", $_POST, "SAVE");
				if($_FILES["Filedata"]["name"] != ""){
					$fields["content_photo_src"] = uploadPhoto("cphoto", $pid);
					$fields["content_photo_thumb"] = makeThumb($fields["content_photo_src"], "cphoto_t$pid.jpg", 80, 80);
				}
				dbPerform("content_photo", $fields, 'update', "content_photo_id=$pid");
				break;
			case 3:
				$fields = getFields("content_slide", $_POST, "SAVE");
				if($_FILES["Filedata"]["name"] != ""){
					$fields["content_slide_src"] = uploadPhoto("cslide", $pid);
					$fields["content_slide_thumb"] = makeThumb($fields["content_slide_src"], "cslide_t$pid.jpg", 80, 80);
				}
				dbPerform("content_slide", $fields, 'update', "content_slide_id=$pid");
				break;
		}
	}
}

function askDelete($code, $type, $pid, $aid){
	global $form_id;
	if($aid != 0){
		$rec = getFields("", dbRow("attachment", $aid), "SHOW");
		$name = "attachment";
		$title = $rec["attachment_title"];
	}
	else{
		switch($type){
			case 1:
				$rec = getFields("", dbRow("content_photo", $pid), "SHOW");
				$name = "photo";
				$title = $rec["content_photo_title"];
				break;
			case 3:
				$rec = getFields("", dbRow("content_slide", $pid), "SHOW");
				$name = "slide";
				$title = $rec["content_slide_title"];
				break;
		}
	}
	echo "<h2>Delete $name</h2>Are you sure you want to delete <font class=highlighted>$title</font>?
		<br><br>
		<form action='".this_php."' method=post>
			<input type=hidden name=action value='confirmdelete'>
			<input type=hidden name=id value='$form_id'>
			<input type=hidden name=pid value='$pid'>
			<input type=hidden name=aid value='$aid'>
			<input type=hidden name=code value='$code'>
			<input type=hidden name=type value='$type'>
			<table><tr>
				<td><input type=submit class='delete' value='Yes -- Delete'></td>
				<td><input type=button class='nodelete' value='No -- Cancel' onclick=\"location.href='".this_php.defaultParams."'></td>
			</tr></table>
		</form>
	";
}
function deleteRecord($code, $type, $pid, $aid){
	if($aid != 0)
		dbDeleteRecord("attachment", $aid);
	else{
		switch($type){
			case 1:
				dbDeleteRecord("content_photo", $pid);
				break;
			case 2:
				dbDeleteRecord("content_video", $pid);
				break;
			case 3:
				dbDeleteRecord("content_slide", $pid);
				break;
		}
	}
}

///////////////////////////////////////////////////////////////////////////////////////////////////////////
switch($form_action){
	case "cancel":
		cancelAction();
		break;
	case "upload":
		uploadAdd($form_code, $form_type, $form_id, $form_desc);
		break;
	case "EditRecord":
		editRecord($form_code, $form_type, $form_pid, $form_aid);
		break;
	case "confirmdelete":
		deleteRecord($form_code, $form_type, $form_pid, $form_aid);
		break;
}

$defaultTab = $_SESSION["uploadTab"];
if($defaultTab == "")
	$defaultTab = "photo";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Upload</title>
<link href="styles.css" rel="stylesheet" type="text/css" />
<link href="tabs.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="jscripts/SWFUpload/SWFUpload.js"></script>
<script type="text/javascript" src="jscripts/example_callbacks.js"></script>
<script type="text/javascript">
<!--
var swfu, swfu2;

window.onload = function() {
	swfu = new SWFUpload({
		upload_script : "/dev/manage/uploadadd.php?action=upload&code=<?=$form_code?>&id=<?=$form_id?>&type=2",
		target : "SWFUploadTarget",
		flash_path : "jscripts/SWFUpload/SWFUpload.swf",
		allowed_filesize : 80720,	// 30 MB
		allowed_filetypes : "*.*",
		allowed_filetypes_description : "Flash Video Files",
		browse_link_innerhtml : "Browse for files",
		upload_link_innerhtml : "Upload queue",
		browse_link_class : "swfuploadbtn browsebtn",
		upload_link_class : "swfuploadbtn uploadbtn",
		flash_loaded_callback : 'swfu.flashLoaded',
		upload_file_queued_callback : "fileQueued",
		upload_file_start_callback : 'uploadFileStart',
		upload_progress_callback : 'uploadProgress',
		upload_file_complete_callback : 'uploadFileComplete',
		upload_file_cancel_callback : 'uploadFileCancelled',
		upload_queue_complete_callback : 'uploadQueueComplete',
		upload_error_callback : 'uploadError',
		upload_cancel_callback : 'uploadCancel',
		auto_upload : false	
	});
	swfu.loadUI();
	swfu2 = new SWFUpload({
		upload_script : "/dev/manage/uploadadd.php?action=upload&code=<?=$form_code?>&id=<?=$form_id?>&type=2",
		target : "SWFUploadTarget2",
		flash_path : "jscripts/SWFUpload/SWFUpload.swf",
		allowed_filesize : 80720,	// 30 MB
		allowed_filetypes : "*.*",
		allowed_filetypes_description : "Flash Video Files",
		browse_link_innerhtml : "Browse for files",
		upload_link_innerhtml : "Upload queue",
		browse_link_class : "swfuploadbtn browsebtn",
		upload_link_class : "swfuploadbtn uploadbtn",
		flash_loaded_callback : 'swfu.flashLoaded',
		upload_file_queued_callback : "fileQueued",
		upload_file_start_callback : 'uploadFileStart',
		upload_progress_callback : 'uploadProgress',
		upload_file_complete_callback : 'uploadFileComplete',
		upload_file_cancel_callback : 'uploadFileCancelled',
		upload_queue_complete_callback : 'uploadQueueComplete',
		upload_error_callback : 'uploadError',
		upload_cancel_callback : 'uploadCancel',
		auto_upload : false	
	});
	swfu2.loadUI();
	show(document.getElementById('<?=$defaultTab?>Tab'), '<?=$defaultTab?>');
}
-->
</script>
<style type="text/css">
		
	.swfuploadbtn {
			display: block;
			width: 100px;
			padding: 0 0 0 20px;
		}
		
		.browsebtn { background: url(images/add.png) no-repeat 0 4px; }
		.uploadbtn { 
			display: none;
			background: url(images/accept.png) no-repeat 0 4px; 
		}
		
		.cancelbtn { 
			display: block;
			width: 16px;
			height: 16px;
			float: right;
			background: url(images/cancel.png) no-repeat; 
		}
		
		#cancelqueuebtn {
			display: block;
			display: none;
			background: url(images/cancel.png) no-repeat 0 4px;
			margin: 10px 0;
		}
		
		#SWFUploadFileListingFiles ul {
			margin: 0;
			padding: 0;
			list-style: none;
		}

		.SWFUploadFileItem {

			display: block;
			width: 230px;
			height: 70px;
			float: left;
			background: #eaefea;
			margin: 0 10px 10px 0;
			padding: 5px;

		}

		.fileUploading { background: #fee727; }
		.uploadCompleted { background: #d2fa7c; }
		.uploadCancelled { background: #f77c7c; }
		
		.uploadCompleted .cancelbtn, .uploadCancelled .cancelbtn {
			display: none;
		}
		
		span.progressBar {
			width: 200px;
			display: block;
			font-size: 10px;
			height: 4px;
			margin-top: 2px;
			margin-bottom: 10px;
			background-color: #CCC;
		}
	.style1 {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 12px;
}
.style2 {font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 12px; font-weight: bold; }

.style2 {font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 12px; font-weight: bold; }
div.tabContent {
	display: none;
}
</style>
<script>
var curTab, curTab2;

function show(thiselem, name){
	if(curTab){
		curTab.style.display = "none";
		curTab2.className = "z";
		curTab2.parentNode.className = "z";
	}
//	if(document.getElementById){
		elem = document.getElementById(name);
		if(elem){
			elem.style.display = "block";
			thiselem.className = "active";
			thiselem.parentNode.className = "active";
			curTab = elem;
			curTab2 = thiselem;
		}
//	}
}
</script>
</head>

<body>
<?php
//	echo "Action: $form_action<br>";
showMessage();
switch($form_action) {
	case "edit":
		editForm($form_code, $form_type, $form_pid, $form_aid);
		break;
	case "delete":
		askDelete($form_code, $form_type, $form_pid, $form_aid);
		break;
	default:
?>
<div id="mainNav">
<ul>
<li><a id="photoTab" href="#" onclick="show(this, 'photo')">Photos</a></li>
<li><a id="videoTab" href="#" onclick="show(this, 'video')">Videos</a></li>
<li><a id="slideTab" href="#" onclick="show(this, 'slide')">Slideshow</a></li>
</ul><br><br>


<!-- /////////////////// P H O T O S /////////////////////////////// -->
<div id="photo" class="tabContent">
<form action='<?=this_php?>' method='post' enctype='multipart/form-data'>
		<input type='hidden' name='action' value='upload'/>
		<input type='hidden' name='code' value='<?=$form_code?>'/>
		<input type='hidden' name='type' value='1'/>
		<input type='hidden' name='id' value='<?=$form_id?>'/>
	<input type='file' name="upload" value="Upload"/> Desc: <input type='text' name="desc" value="<?=$form_desc?>"/>
		<input type='submit' value="Add Photo"/>
</form>
<?
showAttachments($form_code, 1, $form_id);
?>
</div>


<!-- ///////////////// V I D E O S /////////////////////////////// -->
<div id="video" class="tabContent">
		
<div id="SWFUploadTarget">
<input name="browse" type="button" id="SWFUpload_0BrowseBtn" value="Browse" />
</div>
<h4 class="style1" id="queueinfo">Queue is empty</h4>
<div id="SWFUploadFileListingFiles">
</div>
<div id="fileContainer">
<div id="SWFUploadTargetFiles">
</div>
</div>
<?
showAttachments($form_code, 2, $form_id);
?>
</div>

<!-- ///////////////// S L I D E S /////////////////////////////// -->
<div id="slide" class="tabContent">
<form action='<?=this_php?>' method='post' enctype='multipart/form-data'>
		<input type='hidden' name='action' value='upload'/>
		<input type='hidden' name='code' value='$form_code'/>
		<input type='hidden' name='id' value='<?=$form_id?>'/>
		<input type='hidden' name='type' value='3'/>
		<input type='file' name="upload" value ="Upload"/> Desc: <input type='text' name="desc" value="<?=$form_desc?>"/>
		<input type='submit' value="Add Slide"/>
</form>
<!-- <div id="SWFUploadTarget2">
<input name="browse" type="button" id="SWFUpload_0BrowseBtn" value="Browse" />
</div>
<h4 class="style1" id="queueinfo">Queue is empty</h4>
<div id="SWFUploadFileListingFiles2">
</div>
<div id="fileContainer">
<div id="SWFUploadTarget2Files">
</div>
</div>-->
<?
showAttachments($form_code, 3, $form_id);
?>
</div>

</div>		
<?
}
?>
</div>
</body>
</html>
