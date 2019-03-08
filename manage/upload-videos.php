<?
extract($_GET, EXTR_PREFIX_ALL, 'form_'); extract($_POST, EXTR_PREFIX_ALL, 'form_');
include('master.inc.php');
include('functions.upload.inc.php');
$form_type=2;

define("defaultParams", "?code=$form_code&type=$form_type&id=$form_id");

///////////////////////////////////////////////////////////////////////////////////////////////////////////
switch($form_action){
	case "cancel":
		cancelAction();
		break;
	case "uploadFiles":
		uploadAdd($form_code, $form_type, $form_id, $form_desc);
		break;
	case "EditRecord":
		editRecord($form_code, $form_type, $form_pid, $form_aid);
		break;
	case "confirmdelete":
		deleteRecord($form_code, $form_type, $form_pid, $form_aid);
		break;
}

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
<?
if($form_action == "upload"){
	$session = session_id();
?>
<script type="text/javascript">
<!--
var swfu;

window.onload = function() {
	swfu = new SWFUpload({
		upload_script : "/manage/uploadadd.php?action=uploadFiles&code=<?=$form_code?>&id=<?=$form_id?>&type=2&session=<?=$session?>",
		target : "SWFUploadTarget",
		flash_path : "jscripts/SWFUpload/SWFUpload.swf",
		allowed_filesize : 80720,	// 30 MB
		allowed_filetypes : "*.flv",
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
}
-->
</script>
<? } ?>
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
			margin-top:-3px;
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
			list-style:none;
		}

		.SWFUploadFileItem {

			display: block;
			width: 800px; 
			/*height: 50px;
			 float: left;*/
			background: #FFF;
			margin: 0 5px 0px 0;
			padding: 5px;
			clear:both;

		}

		.fileUploading { background:#FCFAC9; }
		.uploadCompleted { background:#BCFDA6; }
		.uploadCancelled { background: #f77c7c; }
		
		.uploadCompleted .uploadCancelled .cancelbtn {
			display: none;
		}
		
		#fileContainer { width: 1000px; height:300px; overflow:auto; }
		.clr { clear:both; }
		.fileListTitle { border-bottom:1px solid #CCC; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:11px; color:#333; float:left; padding:5px; background:#F5F5F5; width:300px }
		.fileListProgressBar { border-bottom:1px solid #CCC; float:left; padding:5px; background:#F5F5F5; color:white; width:230px  }
		.fileListCancelButton { border-bottom:1px solid #CCC; float:left; padding:5px;  background:#F5F5F5; width:100px; }
		
		
		span.progressBar {
			width: 200px;
			display: block;
			font-size: 10px;
			height: 4px;
			margin-top: -1px;
			margin-bottom: 10px;
			background-color: #CCC;
		}
		
	.style1 {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 12px;
}
.style2 {font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 12px; font-weight: bold; }
div.tabContent {
	display: none;
}
</style>
</head>

<body>
<div id="mainNav">
<ul>
<li><a id="photoTab" href="upload-photos.php?id=<?=$form_id?>&code=<?=$form_code?>">Photos</a></li>
<li class="active"><a id="videoTab" class="active" href="#">Videos</a></li>
<li><a id="slideTab" href="upload-slideshow.php?id=<?=$form_id?>&code=<?=$form_code?>">Slideshow</a></li>
</ul>
</div><br>

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
	case "upload": ?>
		<a href='upload-videos.php?id=<?=$form_id?>&code=<?=$form_code?>'>Done uploading</a><br>
		<div id="SWFUploadTarget">
		<input name="browse" type="button" id="SWFUpload_0BrowseBtn" value="Browse" />
		</div>
		<h4 class="style1" id="queueinfo">Queue is empty</h4>
		<div id="SWFUploadFileListingFiles">
		</div>
		<div id="fileContainer">
		<div id="SWFUploadFileListingFiles">
		</div>
		</div> <?
		break;
	default:
		echo "<a href='upload-videos.php?action=upload&id=$form_id&code=$form_code'>Upload videos</a><br>\n";
		showAttachments($form_code, 2, $form_id);
	
}
?>

</body>
</html>
