<?php

extract($_GET, EXTR_PREFIX_ALL, 'form_'); extract($_POST, EXTR_PREFIX_ALL, 'form_');
include "master.inc.php";
include "fckeditor/fckeditor.php";
$_SESSION["cp_active_tab"] = "staff.php";
define('defaultTable', 'region_landing');

if (!isLoggedIn()){
	$_SESSION["loginreturn"] = this_php;
	location("login.php");
}

if(!checkAccess(this_php)){
	addMessage("No permission for working with the region landing pages");
	location("index.php");
	exit(0);
}

function cancelAction(){
	global $form_mode;
	switch($form_mode){
		case "page":
			addMessage("Thank you for working with the region landing pages");
			location("index.php");
			break;
		default:
			addMessage("Cancelled $form_mode");
			location(this_php);
			break;
	}
	exit();
}

function printHeader(){
	echo "<thead><tr><th>Region</th><th>Title</th><th><br/></th></tr></thead>";
}

function showRecord($rec){
	global $row_num;
	$class = "row" . $row_num % 2;
	$row_num++;
	$link = "href='".this_php."?action=edit&id={$rec["region_landing_id"]}'";
	$rec = getFields("", $rec, "SHOW");
	$tdl = "<td title='Edit Region Landing Page'><a $link>";
	$tdr = "<td title='Edit Region Landing Page' align='right'><a $link>";
	$tde = "</a></td>\n";
//	$fdate = formatDate($rec["region_landing_updated"]);
	$region = dbRow("content_category", $rec["region_landing_region"]);
	echo "<tr class='$class'>$tdl{$region["content_category_title"]}$tde$tdl{$rec["region_landing_title"]}$tde
		<td><a class='edit' $link>Edit$tde
		</tr>
	";
}

function showRecords(){
	$result = dbRows(defaultTable, "%title");
	
	echo "<h2>Your Region Landing Page List</h2>";
	$num = dbNumRows($result);
	if($num == 0)
		echo "There are no region landing pages";
	else{
		if($num == 1)
			echo "There is 1 region landing page";
		else
			echo "There are $num region landing pages";
		echo "<table class='records' cellpadding='5' cellspacing='2'>";
		printHeader();
		echo "<tbody>";
		while($rec = dbFetchArray($result)){
			showRecord($rec);
		}
		echo "</tbody></table>";
	}
	printButtonLinkSeperator(false);
	printButtonLink("Done", this_php."?action=cancel&mode=page");
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
	$region = dbRow("content_category", $rec["region_landing_region"]);
	echo "<h2>$mode {$region["content_category_title"]} Landing Page</h2>";
	if(numErrors() > 0){
//		printErrors();
		$rec = getFields(defaultTable, $_POST, "SHOW");
	}
	echo "<form action='".this_php."' method='post' enctype='multipart/form-data'>
			<input type='hidden' name='id' value='$id'/>
			<input type='hidden' name='action' value='{$mode}Record'/>
		<table class='form' width='100%'><tbody>
	";
	printDBRecordSelect("Featured Video", $rec, "%feature_video", "SELECT video_id, video_title FROM video WHERE video_premium=0 ORDER BY video_created desc", "video.php", NULL, NULL, true);
	printTextField("Title", $rec, "%title", 80, 128);
	printHTMLField("Caption", $rec, "%desc", "Basic");
	printFileUploadField("Photo", $rec, "Filedata");
	printImageField("Current Image", $rec, "%image", "t0_", "../upload/region/");
	printDBRecordSelect("Video", $rec, "%video", "SELECT video_id, video_title FROM video WHERE video_premium=0 ORDER BY video_created desc", "video.php", NULL, NULL, true);
	printDBRecordSelect("Slide", $rec, "%slide", "SELECT slideshow_id, slideshow_title FROM slideshow ORDER BY slideshow_created desc", "slideshow.php", NULL, NULL, true);
	$region = $rec["region_landing_region"];
	printDBRecordSelect("Article", $rec, "%content", "SELECT distinct content_id, content_title FROM content, content_category_list WHERE content_id=content_category_list_content AND (content_category=$region OR content_category_list_category=$region OR content_category_list_category=0) ORDER BY content_posted desc", "content.php", NULL, NULL, true);
//	$video = dbRow("video", $rec["region_landing_video"]);
//	printImageField("Current Video", $video, "video_thumb", "", "../upload/");
//	$slide = dbRow("slideshow", $rec["region_landing_slide"]);
//	printImageField("Current Slide", $slide, "slideshow_src", "t0_", "../upload/slide/");
	echo "	</tbody>
		<tfoot>
			<tr class='bar'>
			<td colspan='2'>
				<input style='float: left' class='submitButton' type='submit' value='$mode Region Landing Page'/>
				<input style='float: right' class='cancelButton' type='button' value='Cancel' onclick=\"location.href='".this_php."?action=cancel&mode=$mode'\">
			</td></tr>
		</tfoot></table>
		</form>
	";
}

function validate($fields){
	validateTextField($fields, "region_landing_title", NULL, TRUE, 1);
	validateTextField($fields, "region_landing_desc", NULL, TRUE, 1);
	return numErrors();
}

function mkthumb($name, $id){
	makeThumb($name, "t0_$name", 150, 100, "Filedata", "upload/region/");
	makeThumb($name, "t1_$name", 100, 999, "Filedata", "upload/region/");
	makeThumb($name, "t2_$name", 470, 999, "Filedata", "upload/region/");
}

function editRecord($id){
	global $form_action, $announce;
	$fields["region_landing_id"] = $id;
	$fields = getFields(defaultTable, $_POST, "SAVE");
	if(validate($fields,$id) == 0){
		$fields["region_landing_image"] = uploadPhoto("land", $id, "Filedata", "upload/region/");
		mkthumb($fields["region_landing_image"], $id);
		if($fields["region_landing_image"] == false)
			unset($fields["region_landing_image"]);
		dbPerform(defaultTable, $fields, 'update', "region_landing_id=$id");
		addMessage("The region landing page was edited");
		location(this_php);
	}
	else
		$form_action = "edit";
}

///////////////////////////////////////////////////////////////////////////////////////////////////////////
switch($form_action){
	case "cancel":
		cancelAction();
		break;
	case "EditRecord":
		editRecord($form_id);
		break;
}

///////////////////////////////////////////////////////////////////////////////////////////////////////////
include "header.inc.php";
///////////////////////////////////////////////////////////////////////////////////////////////////////////

//	echo "Action: $form_action<br>";
showMessage();
switch($form_action) {
	case "edit":
		showForm($form_id);
		break;
	default:
		showRecords();
}
///////////////////////////////////////////////////////////////////////////////////////////////////////////
include "footer.inc.php" ?>
