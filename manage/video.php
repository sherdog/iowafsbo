<?php
extract($_GET, EXTR_PREFIX_ALL, 'form_'); extract($_POST, EXTR_PREFIX_ALL, 'form_');
include "master.inc.php";
$_SESSION["cp_active_tab"] = "video.php";

if($form_action == "upload")
	$selmenu = "video.php?action=upload";
define("defaultTable", "video");


/// BEGIN CALL DEBUG - Write info to http://www.dirtondirt.com/dev/upload/test.txt
$now = formatDate(time());
$test = "Called at $now, {$_FILES["Filedata"]["name"]} params: ($form_id,$form_code,$form_type)";
echo $test;
$zzz = fopen(SITE_PATH."upload/test2.txt", "w");
fwrite($zzz, $test);
/// END CALL DEBUG at end of program and inside uploadAdd();

define("defaultParams", "?code=$form_code&type=$form_type&id=$form_id");

function upload(){
	global $zzz;
	$file = $_FILES["Filedata"];
	fwrite($zzz, " upload");
	if($file[name] != ""){
		fwrite($zzz, " ok");
		$img = false;
		$fname = $file["name"];
		$fields1["video_title"] = $desc;
		if($desc == "")
			$fields1["video_title"] = $fname;
				fwrite($zzz, " encode");

		do{
			$code = randomCode(12);
		}while(file_exists(SITE_PATH."upload/$code.flv"));	// Attempt to find the rare 12 digit random filename that DOES NOT exist yet.
			fwrite($zzz, " upload($code)");

		uploadFile("Filedata", "$code.flv", "$code.flv", false, "upload/");
			fwrite($zzz, " presql");
		
		$now = time();
		$sql = "INSERT INTO video VALUES(0, 0, '{$fields1["video_title"]}', '{$fields1["video_title"]}', '$code.flv', '', 0, 0, $now, 0)";
			fwrite($zzz, " presql($sql)");
		dbQuery($sql);
	fwrite($zzz, " done");
//		$cid = dbLastInsertID("video");
//		dbPerform("video", $fields2, 'update', "content_video_id=$cid");
	}
}

function showVideos(){
	global $form_id;
	$session = session_id();
	$results1 = dbQuery("SELECT * FROM video ORDER By video_created DESC");
	
	echo "<h2>Your Videos</h2>
			<a href='video.php?action=upload'>Upload videos</a><br />
			<img src=\"images/icon_f.jpg\"> = Featured Video <br />
			<img src=\"images/icon_p.jpg\"> = Premium Video <br />
		<table cellspacing=\"5\" cellpadding=\"3\">";

	$col = 0;
	//echo dbNumRows($results1);
	while($rec = dbFetchArray($results1)){
		if($col == 4){
			echo "</tr>";
			echo "<tr>";
			$col = 0;
		}
		if($rec["video_thumb"] == "")
			$rec["video_thumb"] = "no-thumb.jpg";
		if($rec["video_featured"] != 0)
			$class = " class=''";
		else
			$class = " class=''";
		
		echo "<td$class style='font-size:10px' align='center'><a href='video.php?action=edit&id={$rec["video_id"]}'><img src='../upload/{$rec["video_thumb"]}'></a> <br><a href='video.php?action=edit&id={$rec["video_id"]}' style='font-size:10px;'>[ edit ]</a> | <a href='video.php?action=delete&id={$rec["video_id"]}' style='font-size:10px;'>[ delete ]</a>";
		if($rec['video_premium'] == 1)
			echo " <img src=\"images/icon_p.jpg\" alt=\"Premium Content\" title=\"Premium Content\">\n";
		if($rec['video_featured'] == 1)
			echo " <img src=\"images/icon_f.jpg\" alt=\"Featured Content\" title=\"Featured Content\">\n";
		
		
		echo "</td>\n";
	
	$col++;
	}
	echo "</tr></table>";
}

function uploadForm(){
	echo <<<EOF
	<h2>Upload Videos</h2>
<div id="upload">
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
</div>
<a href="video.php">Done uploading</a>
EOF;
}

function editForm($id){
	global $form_id;
	$rec = getFields("", dbRow("video", $id), "SHOW");
	$name = "video";
	echo "<h2>Edit $name</h2>";
	if(numErrors() > 0){
		printErrors();
		$rec = getFields(defaultTable, $_POST, "SHOW");
	}
	echo "<form action='video.php' method='post' enctype='multipart/form-data'>
			<input type=hidden name=id value='$form_id'>
			<input type=hidden name='fname' value='{$rec["video_src"]}'>
			<input type='hidden' name='action' value='EditRecord'/>
		<table class='form'><tbody>";
	echo "<tr><td class='fieldLabel'><strong>Number of Views:</strong></td><td>".$rec['video_number_views']."</td></tr>";
	printTextField("Video Title", $rec, "video_title", 40, 80);
	printDBRecordSelect("Category", $rec, "%category", "SELECT video_category_id, video_category_title FROM video_category ORDER BY video_category_title", "videoCategories.php");
	printCheckboxField("Premium", $rec, "video_premium", 1);
	printCheckboxField("Featured", $rec, "video_featured", 1);
	printFileUploadField("Video Thumbnail", $rec, "Filedata");
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

function resetPremiumVideo(){
	dbQuery("UPDATE video SET video_featured=0 WHERE video_featured>0");
}

function editRecord($id){
	$fields = getFields("video", $_POST, "SAVE");
	if($_FILES["Filedata"]["name"] != ""){
		$z = uploadPhoto("tmp.jpg", $id);
		$fields["video_thumb"] = makeThumb($z, "{$_REQUEST["fname"]}.jpg", 150, 100);
	}
	if(!isset($fields["video_premium"]))
		$fields["video_premium"] = 0;
	if(isset($fields["video_featured"]))
		resetPremiumVideo();
	dbPerform("video", $fields, 'update', "video_id=$id");
}

function askDelete($id){
	global $form_id;
	$rec = getFields("", dbRow("video", $id), "SHOW");
	$name = "video";
	$title = $rec["video_title"];
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
function deleteRecord($id){
	dbDeleteRecord("video", $id);
}

///////////////////////////////////////////////////////////////////////////////////////////////////////////
switch($form_action){
	case "cancel":
		cancelAction();
		break;
	case "uploadFiles":
		upload();
		break;
	case "upload":
	$javascript = <<<EOF
var swfu;

window.onload = function() {
	swfu = new SWFUpload({
		upload_script : "/dev/manage/video.php?action=uploadFiles",
		target : "SWFUploadTarget",
		flash_path : "jscripts/SWFUpload/SWFUpload.swf",
		allowed_filesize : 51200,	//50 MB
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
	swfu.loadUI();
}
EOF;
break;
	case "EditRecord":
		editRecord($form_id);
		break;
	case "confirmdelete":
		deleteRecord($form_id);
		break;
}

$defaultTab = $_SESSION["uploadTab"];
if($defaultTab == "")
	$defaultTab = "photo";

///////////////////////////////////////////////////////////////////////////////////////////////////////////
include "header.inc.php";
///////////////////////////////////////////////////////////////////////////////////////////////////////////

switch($form_action) {
	case "upload":
		uploadForm();
		break;
	case "edit":
		editForm($form_id);
		break;
	case "delete":
		askDelete($form_id);
		break;
	default:
		showVideos();
}
?>

<!-- ///////////////// V I D E O S /////////////////////////////// -->

<?
include "footer.inc.php";
?>

