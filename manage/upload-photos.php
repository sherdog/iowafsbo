<?
extract($_GET, EXTR_PREFIX_ALL, 'form_'); extract($_POST, EXTR_PREFIX_ALL, 'form_');
include('master.inc.php');
include('functions.upload.inc.php');
$form_type = 1;

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
<li class="active"><a id="photoTab" href="#" class="active">Photos</a></li>
<li><a id="videoTab" href="upload-videos.php?id=<?=$form_id?>&code=<?=$form_code?>">Videos</a></li>
<li><a id="slideTab" href="upload-slideshow.php?id=<?=$form_id?>&code=<?=$form_code?>">Slideshow</a></li>
</ul></div><br>
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
		echo <<<EOF
<form action='upload-photos.php' method='post' enctype='multipart/form-data'>
		<input type='hidden' name='action' value='upload'/>
		<input type='hidden' name='code' value='$form_code'/>
		<input type='hidden' name='type' value='1'/>
		<input type='hidden' name='id' value='$form_id'/>
		<table><tr valign='top'><td>
		<input type='file'  name="Filedata" value="Upload"/>
		Desc:</td><td><textarea rows="1" onfocus="this.rows=5" onblur="this.rows=1" cols="20" type='text' name="desc">$form_desc</textarea>
		</td><td><input type='submit' value="Add Photo"/>
		</td></tr>
<tr><td colspan=2 align="right"><span style="font-size:9px">Max 255 Letters</span></td></tr>
</table>
</form>
EOF;
		showAttachments($form_code, 1, $form_id);
		break;
}
?>

</body>
</html>
