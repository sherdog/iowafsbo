<?php
include('../master.inc.php');
if($_GET['action'] == 'upload') {
	//upload pics =)
	//$galleryId = $_GET['id'];
	
	//$filename = time()."-".fixname($_FILES['Filedata']['name']);
		$filename = $_FILES['Filedata']['name'];//image we are about to upload
	
	
		//move_uploaded_file($_FILES['Filedata']['tmp_name'], SITE_PATH.UPLOAD_DIR.$_FILES['Filedata']['name']);
		move_uploaded_file($_FILES['Filedata']['tmp_name'], MANAGE_PATH.UPLOAD_DIR_ZIP.$filename);
		//uploadZip($_FILES, $filename);
		$fields['zipfiles_timestamp'] = time();
		$fields['zipfiles_filename'] = $filename;
		
		
		$update = dbPerform('zipfiles', $fields, 'insert'); 
	
}


?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<script type="text/javascript" src="jscripts/SWFUpload/SWFUpload.js"></script>
<script type="text/javascript" src="jscripts/example_callbacks.js"></script>
<script language="javascript">
<!--
var swfu;

window.onload = function() {

	swfu = new SWFUpload({
		
		upload_script : "../../zipUploadManager.php?id=<?=$_GET['id']?>&action=upload",
		target : "SWFUploadTarget",
		flash_path : "jscripts/SWFUpload/SWFUpload.swf",
		allowed_filesize : 300000000,	// 300 MB
		allowed_filetypes : "*.*",
		allowed_filetypes_description : "Images",
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
		debug: false,
		auto_upload : false	
	});
	
	swfu.loadUI(); 
	
};

function uploadError(code, file, msg) {
  alert(msg);
  document.getElementById('queueinfo').innerHTML = code+" "+file+" "+msg;
}

function uploadQueueComplete(file) {
window.parent.location.reload();
//	var div = document.getElementById("queueinfo");
//	div.innerHTML = "All files uploaded... <a href='../listings.php' style='color:white; background:red; font-weight:bold; font:verdana; font-size:12px;' target='_parent'>Back to listings</a>"
//	document.getElementById("cancelqueuebtn").style.display = "none";
}

//-->
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
		
		#fileContainer { }
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
</style>
</head>

<body>
<h2 style="font-family:Arial, Helvetica, sans-serif; font-size:18px; border-bottom:1px solid #CCC;">Upload</h2>

<div id="SWFUploadTarget">
<input name="briwse" type="button" id="SWFUpload_0BrowseBtn" value="Browse" />

</div>
<h4 class="style1" id="queueinfo">Queue is empty</h4>
<div id="fileContainer">
<div id="SWFUploadFileListingFiles">

</div>
</div>
<br class="clr"/>
<a id="SWFUpload_0UploadBtn" class="swfuploadbtn uploadbtn" href="#">Upload queue</a>
<a class="swfuploadbtn" id="cancelqueuebtn" href="javascript:cancelQueue();">Cancel queue</a>


</body>
</html>
