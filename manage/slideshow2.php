<?php

extract($_GET, EXTR_PREFIX_ALL, 'form_'); extract($_POST, EXTR_PREFIX_ALL, 'form_');
include "master.inc.php";
include "fckeditor/fckeditor.php";
$_SESSION["cp_active_tab"] = "video.php";
define('defaultTable', 'slideshow');

if($form_action == "add")
	$selmenu = "slideshow.php?action=add";

/// BEGIN CALL DEBUG - Write info to http://www.dirtondirt.com/dev/upload/test.txt
// $now = time();
// $test = "Called at $now $form_action, {$_FILES["Filedata"]["name"]} params: ($form_id,$form_code,$form_type)";
// $zzz = fopen(SITE_PATH."upload/{$_FILES["Filedata"]["name"]}.txt", "w");
// fwrite($zzz, $test);
/// END CALL DEBUG at end of program and inside uploadAdd();

//if($form_action != "uploadFiles"){
	if (!isLoggedIn()){
		$_SESSION["loginreturn"] = this_php;
		location("login.php");
	}
	if(!checkAccess(this_php)){
		addMessage("No permission for working with the slideshows");
		location("index.php");
		exit(0);
	}
//}

function upload(){
	$file = $_FILES["Filedata"];
	if($file[name] != ""){
		$img = false;
		$fname = $file["name"];
		$desc = $fname;

//		uploadFile("Filedata", "$code.flv", "$code.flv", false, "upload/upload/");
		
		$now = time();
		$sql = "INSERT INTO slideshow VALUES(0, '$desc', '', $now)";
		dbQuery($sql);

		$cid = dbLastInsertID("slideshow");
		$fields["slideshow_src"] = uploadPhoto("slide", $cid, "Filedata", "upload/slide/");
		
		dbPerform("slideshow", $fields, 'update', "slideshow_id=$cid");
		mkthumb($fields["slideshow_src"], $cid);
//		$cid = dbLastInsertID("video");
//		dbPerform("video", $fields2, 'update', "content_video_id=$cid");
	}
}

function cancelAction(){
	global $form_mode;
	switch($form_mode){
		case "page":
			addMessage("Thank you for working with the slideshows");
			location("index.php");
			break;
		default:
			addMessage("Cancelled $form_mode");
			location(this_php);
			break;
	}
	exit();
}

function showRecords(){
	$result = dbRows(defaultTable, "%created", "DESC");
	
	echo "<h2>Your Slideshow List</h2>";
	$num = dbNumRows($result);
	if($num == 0)
		echo "There are no slideshows";
	else{
		if($num == 1)
			echo "There is 1 slideshow";
		else
			echo "There are $num slideshows";
		echo "<table cellspacing=\"5\">";
		$row = 0;
		$col = 0;
		while($rec = dbFetchArray($result)){
			if($col == 5){
			echo "</tr>";
			echo "<tr>";
			$col = 0;
		}
			if($rec["slideshow_src"] == "")
			$rec["slideshow_src"] = "no-thumb.jpg";
			$sid = $rec["slideshow_id"];
			$result2 = dbQuery("SELECT * FROM slideshow_slide WHERE slideshow_slide_slideshow=$sid LIMIT 0,1");
			$slide = dbFetchArray($result2);
			if($slide == false)
				$img = "../upload/no-thumb.jpg";
			else
				$img = "../upload/slide/t0_" . $slide["slideshow_slide_src"];
		echo "<td style='font-size:10px' align='center'><a href='slideshow.php?action=edit&id=$sid'>
		<img src='$img'><a/><br>
		<a href='slideshow.php?action=edit&id=$sid' style='font-size:10px'>[ edit ]</a> | <a href='slideshow.php?action=delete&id=$sid' style='font-size:10px'>[ delete ]</a></td>\n";
		$col++;
		}
		echo "</tr></table>";
	}
	printButtonLink("Done", this_php."?action=cancel&mode=page", 'cpButton');
	printButtonLink("Add Slideshow", this_php."?action=add", 'cpButton');
}

function uploadForm(){
	echo <<<EOF
<h2>Upload Slides</h2>
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
<a href="slideshow.php">Done uploading</a>
EOF;
}

function showForm($id){
	$mode = "Edit";
	if($id == 0){
		$rec[] = "";
		$mode = "Add";
	}
	else{
		$rec = getFields("", dbRow(defaultTable, $id), "SHOW");
	}
	echo "<h2>$mode Slideshow</h2>";
	if(numErrors() > 0){
//		printErrors();
		$rec = getFields(defaultTable, $_POST, "SHOW");
	}
	echo "<form action='".this_php."' method='post' enctype='multipart/form-data'>
			<input type='hidden' name='id' value='$id'/>
			<input type='hidden' name='action' value='{$mode}Record'/>
		<table class='form' width='100%'><tbody>
	";
	printTextField("Title", $rec, "%title", 80, 128);
	printHTMLField("Caption", $rec, "%caption", "Basic");
	printCheckboxField("Featured", $rec, "%featured", "1");
//	printFileUploadField("Slide", $rec, "Filedata");
//	printImageField("Current Image", $rec, "%src", "t0_", "../upload/slide/");
	echo "<tr><td class='fieldLabel'><label class='formLabelText'>Attachments:<br>DO NOT USE</label></td><td style='height: 200px'>";
		echo "<iframe width='100%' style='height:100%' src=\"upload-slideshow2.php?code=2&id=$id\" id=\"uploading\" frameborder=\"0\" name=\"uploading\"></iframe>";
	echo "</td></tr>";
	echo "	</tbody>
		<tfoot>
			<tr class='bar'>
			<td colspan='2'>
				<input style='float: left' class='submitButton' type='submit' value='$mode Slideshow'/>
				<input style='float: right' class='cancelButton' type='button' value='Cancel' onclick=\"location.href='".this_php."?action=cancel&mode=$mode'\">
			</td></tr>
		</tfoot></table>
		</form>
	";
	if($mode == "Edit"){
		printButtonLinkSeperator(TRUE, "Other Actions");
		printButtonLink("Delete This Slideshow", this_php."?action=delete&id=$id");
		printButtonLink("Add a new Slideshow", this_php."?action=add");
	}
}

function askDelete($id){
	$rec = getFields("", dbRow(defaultTable, $id), "SHOW");
	echo "<h2>Delete Slideshow</h2>
		Are you sure you want to delete <font class=highlighted>{$rec["slideshow_title"]}</font>?
		<br><br>
		<form action='".this_php."' method=post >
			<input type='hidden' name='action' value='confirmdelete'>
			<input type='hidden' name='id' value='$id'>
			<table><tr>
				<td><input type='submit' class='delete' value='Yes -- Delete'></td>
				<td><input type='button' class='nodelete' value='No -- Cancel' onclick=\"location.href='".this_php."?action=cancel&mode=Delete'\"></td>
			</tr></table>
		</form>
	";
	printButtonLinkSeperator(true, "Other Actions");
	printButtonLink("Edit This Slideshow", this_php."?action=edit&id=$id");
	printButtonLink("Add a new Slideshow", this_php."?action=add");
}

function validate($fields){
	validateTextField($fields, "slideshow_title", NULL, TRUE, 1);
	return numErrors();
}

function applySlideshowFeatured(){
	$results = dbQuery("SELECT * FROM slideshow WHERE slideshow_featured!=0 ORDER BY slideshow_featured DESC");
	for($a = 0;$a < 3;$a++)	// Don't update the 1st 3 records 
		dbFetchArray($results);
	while($rec = dbFetchArray($results))
		dbQuery("UPDATE slideshow SET slideshow_featured=0 WHERE slideshow_id={$rec["slideshow_id"]}");
}

function mkthumb($name, $id){
	makeThumb($name, "t0_slide_$id.jpg", 150, 100, "Filedata", "upload/slide/");
	makeThumb($name, "t1_slide_$id.jpg", 100, 999, "Filedata", "upload/slide/");
	makeThumb($name, "t2_slide_$id.jpg", 470, 999, "Filedata", "upload/slide/");
}

function applyAttachments($cid){
	$session = session_id();
	$results = dbQuery("SELECT * FROM attachment WHERE attachment_key='$session' AND attachment_code=2 AND attachment_type=4");	// Slides
	while($rec = dbFetchArray($results)){
		$fields1["slideshow_slide_title"] = $rec["attachment_title"];
		$fields1["slideshow_slide_slideshow"] = $cid;
		dbPerform("slideshow_slide", $fields1, 'insert');
		$cpid = dbLastInsertID("slideshow_slide");
		$fields2["slideshow_slide_src"] = "slide_$cpid.jpg";
		rename(SITE_PATH."upload/{$rec["attachment_name"]}", SITE_PATH."upload/slide/slide_$cpid.jpg");
//		echo "makeThumb({$fields2["content_slide_src"]}, 't0_cslide_$cpid.jpg', 150, 100, false, 'upload/articleslide/')";
		makeThumb($fields2["slideshow_slide_src"], "t0_slide_$cpid.jpg", 150, 100, false, "upload/slide/");
		makeThumb($fields2["slideshow_slide_src"], "t1_slide_$cpid.jpg", 100, 999, false, "upload/slide/");
		makeThumb($fields2["slideshow_slide_src"], "t2_slide_$cpid.jpg", 470, 999, false, "upload/slide/");
		dbPerform("slideshow_slide", $fields2, 'update', "slideshow_slide_id=$cpid");
	}
	dbQuery("DELETE FROM attachment WHERE attachment_key='$session' AND attachment_code=2");	// delete all
}

function addRecord(){
	global $form_action;
	$fields = getFields(defaultTable, $_POST, "SAVE");
	if(validate($fields,0) == 0){
		dbPerform(defaultTable, $fields, 'insert');
		if(isset($fields["slideshow_featured"]))
			$fields["slideshow_featured"] = time();
		$cid = dbLastInsertID(defaultTable);
		applyAttachments($cid);
		if(isset($fields["slideshow_featured"]))
			applySlideshowFeatured();
		addMessage("Slideshow Saved");
		location(this_php);
	}
	else
		$form_action = "add";
}

function editRecord($id){
	global $form_action, $announce;
	$fields["slideshow_id"] = $id;
	$fields = getFields(defaultTable, $_POST, "SAVE");
	if(validate($fields,$id) == 0){
//		$fields["slideshow_expires"] = strtotime($fields["slideshow_expires"]);
		$fields["slideshow_created"] = time();
		if(isset($fields["slideshow_featured"]))
			$fields["slideshow_featured"] = time();
//		$fields["slideshow_updated"] = time();
//		$fields["slideshow_src"] = uploadPhoto("slide", $id, "Filedata", "upload/slide/");
//		if($fields["slideshow_src"] == false)
//			unset($fields["slideshow_src"]);
//		else
//			mkthumb($fields["slideshow_src"], $id);
		dbPerform(defaultTable, $fields, 'update', "slideshow_id=$id");
		addMessage("The slideshow was edited");
		if(isset($fields["slideshow_featured"]))
			applySlideshowFeatured();
		location(this_php);
	}
	else
		$form_action = "edit";
}

function deleteRecord(){
	global $form_id;
	dbDeleteRecord(defaultTable, $form_id);
	dbDeleteRecords("slideshow_slide", "slideshow_slide_slideshow=$form_id");
	// TODO DELETE THE slide files
	addMessage("The slideshow was deleted");
	location(this_php);
}

///////////////////////////////////////////////////////////////////////////////////////////////////////////
switch($form_action){
	case "cancel":
		cancelAction();
		break;
// 	case "uploadFiles":
// 		upload();
// 		break;
// 	case "upload":
// $javascript = <<<EOF
// var swfu;
// 
// window.onload = function() {
// 	swfu = new SWFUpload({
// 		upload_script : "/manage/slideshow.php?action=uploadFiles",
// 		target : "SWFUploadTarget",
// 		flash_path : "jscripts/SWFUpload/SWFUpload.swf",
// 		allowed_filesize : 80720,	// 30 MB
// 		allowed_filetypes : "*.jpg;*.gif;*.png",
// 		allowed_filetypes_description : "Image Files",
// 		browse_link_innerhtml : "Browse for files",
// 		upload_link_innerhtml : "Upload queue",
// 		browse_link_class : "swfuploadbtn browsebtn",
// 		upload_link_class : "swfuploadbtn uploadbtn",
// 		flash_loaded_callback : 'swfu.flashLoaded',
// 		upload_file_queued_callback : "fileQueued",
// 		upload_file_start_callback : 'uploadFileStart',
// 		upload_progress_callback : 'uploadProgress',
// 		upload_file_complete_callback : 'uploadFileComplete',
// 		upload_file_cancel_callback : 'uploadFileCancelled',
// 		upload_queue_complete_callback : 'uploadQueueComplete',
// 		upload_error_callback : 'uploadError',
// 		upload_cancel_callback : 'uploadCancel',
// 		auto_upload : false	
// 	});
// 	swfu.loadUI();
// }
// EOF;
// 		break;
	case "AddRecord":
		addRecord();
		break;
	case "EditRecord":
		editRecord($form_id);
		break;
	case "confirmdelete":
		deleteRecord();
		break;
}

///////////////////////////////////////////////////////////////////////////////////////////////////////////
include "header.inc.php";
///////////////////////////////////////////////////////////////////////////////////////////////////////////

//	echo "Action: $form_action<br>";
showMessage();
switch($form_action) {
// 	case "upload":
// 		uploadForm();
// 		break;
	case "add":
		showForm(0);
		break;
	case "edit":
		showForm($form_id);
		break;
	case "delete":
		askDelete($form_id);
		break;
	default:
		showRecords();
}
///////////////////////////////////////////////////////////////////////////////////////////////////////////
include "footer.inc.php" ?>
