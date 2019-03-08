<?php

extract($_GET, EXTR_PREFIX_ALL, 'form_'); extract($_POST, EXTR_PREFIX_ALL, 'form_');
include "master.inc.php";
include "fckeditor/fckeditor.php";
include "jscalendar/calendar.php";
$jscalendar = new DHTML_Calendar('jscalendar/', "en", 'calendar-win2k-2', false);
//$page = 'article';
if($form_maxage == "")
	$form_maxage = "2 month";
if($form_minage == "")
	$form_minage = "today";
else
	$form_minage.=" ago";	
$form_maxage.=" ago";

if($form_action == "add")
	$selmenu = "content.php?action=add";

$javascript = <<<SCRIPT
function unhide(name){
	elem = document.getElementById(name);
	if(elem)
		elem.className = '';
}

function checkall(){
	a = 0;
	document.getElementById('z_region_all').checked = true;
	for(a = 1;a < 1000;a++){
		elem = document.getElementById('z_region_'+a)
		if(elem)
			elem.checked = false;
		else
			break;
	}
}
function uncheckall(){
	document.getElementById("z_region_all").checked = false;
}
SCRIPT;


$_SESSION["cp_active_tab"] = "content.php";
define('defaultTable', 'content');

if (!isLoggedIn()){
	$_SESSION["loginreturn"] = this_php;
	location("login.php");
}

if(!checkAccess(this_php)){
	addMessage("No permission for working with the content pages");
	location("index.php");
	exit(0);
}

// function subrecContentPhoto($rec){
// 	global $row_num2, $col_num2;
// 	$col_num++;
// 	if($col_num2 > 4){
// 		$col = 0;
// 		if($row_num2 > 0)
// 			echo "</tr>";
// 		$style = "row" . $row_num2 % 2;
// 		$row_num2++;
// 		echo "<tr $class='$style'>";
// 	}
// 	$link = "href='contentphoto.php?referer=content.php&action=edit&cid={$rec["content_photo_content"]}&id={$rec["content_photo_id"]}'";
// 	$rec = getFields("", $rec, "SHOW");
// 	$tdl = "<td title='Change Photo' align='center'><a $link>";
// 	$tde = "</a></td>\n";
// 	echo "$tdl<img border='0' src='../upload/{$rec["content_photo_thumb"]}'><br>{$rec["content_photo_title"]}</a><a href='contentphoto.php?referer=content.php&action=delete&cid={$rec["content_photo_content"]}&id={$rec["content_photo_id"]}'><img title='Delete Photo' height='14' src='images/del.png' border='0'/>$tde";
// }

function cancelAction(){
	global $form_mode;
	switch($form_mode){
		case "page":
			addMessage("Thank you for working with the content pages");
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
	echo "<tr><td class=\"tableHeader\">Posted</td><td class=\"tableHeader\">Title</td><td class=\"tableHeader\">Category</td><td class=\"tableHeader\">Author</td><td class=\"tableHeader\">&nbsp;</td><td class=\"tableHeader\">&nbsp;</td></tr>";
}

function showRecord($rec){
	global $row_num;
	$class = "row" . $row_num % 2;
	$row_num++;
	$link = "href='".this_php."?action=edit&id={$rec["content_id"]}'";
	$rec = getFields("", $rec, "SHOW");
	$tdl = "<td>";
	$tdr = "<td>";
	$tde = "</td>\n";
//	$fdate = formatDate($rec["content_updated"]);
	//$post = formatDate($rec["content_posted"]);
$postDate = date('m/d/Y', $rec['content_posted']);
$postTime = date('h:i:s', $rec['content_posted']);
$post = "<span class=\"text10\">".$postDate . '<br>' . $postTime."</span>";
	echo "<tr class='$class'>$tdl$post$tde$tdl{$rec["content_title"]}$tde<td>".getCategoryName($rec['content_category'])."</td><td>".$rec['content_author']."</td><td align=\"center\"><a class='edit' href='".this_php."?action=edit&id={$rec["content_id"]}'>edit</a>$tde <td align=\"center\"><a href='".this_php."?action=delete&id={$rec["content_id"]}'><img title='Delete Content Page' height='14' src='images/del.png' border='0'/></a>$tde</tr>";
}

function showRecords(){
	global $form_maxage, $form_minage, $form_filter1;
	$mindate = strtotime($form_minage, time());
	$maxdate = strtotime($form_maxage, time());
	
	$result = dbRows("content_category", "%order");
	echo "<div style='float: right'><form action='content.php'>Filter by Region: <select name='filter1' style='width: 200px' onchange='this.parentNode.submit()'>\n<option value='0'>Every Region</option>\n";
	while($rec = dbFetchArray($result)){
		$sel = "";
		if($form_filter1 == $rec["content_category_id"])
			$sel = " selected";
		echo "<option$sel value='{$rec["content_category_id"]}'>{$rec["content_category_desc"]} ({$rec["content_category_title"]})</option>\n";
	}
	echo "</select></form></div>\n\n";

	if(isset($form_filter1) && $form_filter1 != 0)
		$result = dbQuery("SELECT * FROM content WHERE content_category=$form_filter1");
	else
		$result = dbRows(defaultTable, "%posted", "desc", "WHERE content_posted<$mindate AND content_posted>$maxdate");
		echo "$form_filter1";
	
 	echo "<h2>Posted Articles</h2>";
	$num = dbNumRows($result);
	if($num == 0)
		echo "There are no content pages";
	else{
		echo "<table class='records' cellpadding='5' cellspacing='2' width=\"100%\">";
		printHeader();
		//echo "<tbody>";
		while($rec = dbFetchArray($result)){
			showRecord($rec);
		}
		echo "</table>";
	}
	echo "<div id=\"subButtons\">\n";
	//printButtonLink("Show year", this_php."?maxage=1 year");
	//printButtonLink("Show 40 years", this_php."?minage=1+year&maxage=40+years");
	

//printButtonLinkSeperator(false);
	printButtonLink("Done", this_php."?action=cancel&mode=page", 'cpButton');
echo "&nbsp;";
printButtonLink("Add Artcle", this_php."?action=add", 'cpButton');

echo "</div>";
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
	echo "<h2>$mode Article</h2>";
	if(numErrors() > 0){
		$rec = getFields(defaultTable, $_POST, "SHOW");
	}
	echo "<form action='".this_php."' method='post'>
			<input type='hidden' name='id' value='$id'/>
			<input type='hidden' name='action' value='{$mode}Record'/>
		<table class='form'><tbody>
	";
	printTextField("Title", $rec, "%title", 80, 128);
	//printTextField("Sub Title", $rec, "%subtitle", 80, 255);
//	printDBRecordSelect("Primary Region", $rec, "%category", "SELECT content_category_id, content_category_title FROM content_category ORDER BY content_category_order", "contentcategory.php");
//	printSelectionField("Primary Region Display", $rec, "%primary_display", "1|Breaking News^2|Secondary News^3|Latest Dirt");
//	printSelectionField("Homepage Display", $rec, "%homepage_display", "0|None^1|Breaking News^2|Secondary News^3|Latest Dirt");

	$results = dbQuery("SELECT * FROM content_category_list WHERE content_category_list_content=$id AND content_category_list_category=0");
	$chk="";
	if(dbNumRows($results) == 1)
		$chk=" checked";
// 	echo "\n\n<tr valign='top'><td class='fieldLabel'><label class='formLabelText' for='z_region_all'>Secondary Regions:<br><a href='contentcategory.php'>Edit List</a></label></td><td>\n\n
// 		<input type='checkbox' id='z_region_all' name='z_region[]' value='0'$chk onclick='checkall(this)'/> Every Region<br>";
// 
// 	$results = dbQuery("SELECT * FROM content_category ORDER BY content_category_order");
// 	$pno = 1;
// 	while($rec2 = dbFetchArray($results)){
// 		$results2 = dbQuery("SELECT * FROM content_category_list WHERE content_category_list_content=$id AND content_category_list_category={$rec2["content_category_id"]}");
// 		$chk="";
// 		if(dbNumRows($results2) == 1)
// 			$chk=" checked";
// 		echo "<label><input type='checkbox'$chk id='z_region_$pno' name='z_region[]' value='{$rec2["content_category_id"]}' onclick='uncheckall(this)'> {$rec2["content_category_desc"]} ({$rec2["content_category_title"]})</label><br>\n";
// 		$pno++;
// 	} 
// 	echo "</td></tr>\n\n"; 
		
	if($mode == 'Edit'){
		printTextField("Author", $rec, "%author", 30, 128);
	}
	else{
		printDBRecordSelect("Author", $rec, "%author", "SELECT CONCAT(staff_first_name, ' ', staff_last_name), CONCAT(staff_first_name, ' ', staff_last_name) FROM staff ORDER BY staff_last_name", "staff.php", "Other Author", "other_author");
	}
//	printDBRecordSelect("Track", $rec, "%track", "SELECT track_id, concat(track_title, ' ', track_state) FROM track ORDER BY track_title", "track.php", "Quick Add Track", "add_track");
//	printTrackSelect("Track", $rec, "%track");
//	printTextField('Tagline', $rec, '%tagline', 30, 128);
	//printDBRecordSelect("Tagline", $rec, "%tagline", "SELECT content_tagline_id, content_tagline_title FROM content_tagline ORDER BY content_tagline_title", "contenttagline.php");
	printHTMLField("Contents", $rec, "content_contents", "Basic");
//	printHTMLField("Grey Box Contents", $rec, "content_greybox", "Basic");


//	printDateField("Posted", $rec, "%posted");
	if($mode == "Add")
		$rec["content_posted"] = time();
	printDateTimeField("Posted", $rec, "%posted");
//	printCheckboxField("Premium Content", $rec, "%premium", 1, "Premium Content");
//	printCheckboxField("Breaking News", $rec, "%breaking", 1, "BREAKING NEWS");
//	printCheckboxField("Homepage Feature", $rec, "%featured", 1, "Add to latest Dirt");
//	printCheckboxField("Homepage Secondary", $rec, "%secondary", 1, "Add to Secondary News");
	//if($mode == "Edit")
	//	printDbDataField("Pictures", "SELECT * FROM content_photo WHERE content_photo_content=$id", "subrecContentPhoto", "contentphoto.php?referer=content.php&action=add&cid=$id", false);
	//echo "<tr><td class='fieldLabel'><label class='formLabelText'>Attachments:</label></td><td style='height: 200px'>";
	//	echo "<iframe width='100%' style='height:100%' src=\"upload-photos.php?code=1&id=$id\" id=\"uploading\" frameborder=\"0\" name=\"uploading\"></iframe>";
//	echo "</td></tr>";

	echo "	</tbody>
		</table>";
	echo "<br style=\"clear:both;\">\n";
	
	if($mode == 'Add') $mode="Publish"; else $mode="Save";
	echo "<div id=\"subButtons\">\n";
	echo "<input style='float: left' class='cpButton' type='submit' value='$mode Article' style=\"margin-left:240px;\" />";
	echo "<input style='float: right' class='cancelButton' type='button' value='Cancel' onclick=\"location.href='".this_php."?action=cancel&mode=$mode'\">";
	echo "</div>\n";
	echo "</form>";
	echo "<br style='clear:left'/>\n";
	echo "<br><br>";
	echo "<br><br>";
	//if($mode == "Edit"){
		//printButtonLinkSeperator(TRUE, "Other Actions");
		//printButtonLink("Delete This Content Page", this_php."?action=delete&id=$id");
		//printButtonLink("Add a New Content Page", this_php."?action=add");
	//}
}

function askDelete($id){
	$rec = getFields("", dbRow(defaultTable, $id), "SHOW");
	echo "<h2>Delete Article</h2>
		Are you sure you want to delete <span class=highlighted>{$rec["content_title"]}</span>?
		<br><br>
		<form action='".this_php."' method=post>
			<input type='hidden' name='action' value='confirmdelete'>
			<input type='hidden' name='id' value='$id'>
			<table><tr>
				<td><input type='submit' class='cpButton' value='Yes -- Delete'></td>
				<td><input type='button' class='cpButton' value='No -- Cancel' onclick=\"location.href='".this_php."?action=cancel&mode=Delete'\"></td>
			</tr></table>
		</form>
	";
	echo "<div id=\"subButtons\">\n";

	printButtonLink("Edit This Article", this_php."?action=edit&id=$id", 'cpButton');
	printButtonLink("Add New Article", this_php."?action=add" , 'cpButton');
	echo "</div>\n";
}

function validate($fields){
	validateTextField($fields, "content_title", NULL, TRUE, 1);
	validateTextField($fields, "content_contents", NULL, TRUE, 1);
	return numErrors();
}

function breakingNews($fields){
	if(($fields["content_breaking"]) == 1){
		dbQuery("UPDATE content SET content_breaking=0 WHERE content_breaking=1");	// remove old breaking content
	} 
}

function applyRegions($id){
	$regions = $_POST["z_region"];
	if(!isset($regions))
		$regions[] = 0;	// Default to all
	dbQuery("DELETE FROM content_category_list WHERE content_category_list_content=$id");
	foreach($regions as $regionid){
		dbQuery("INSERT INTO content_category_list VALUES(0, $id, $regionid)");
	}
}

function applyAttachments($cid){
	$session = session_id();
	$results = dbQuery("SELECT * FROM attachment WHERE attachment_key='$session' AND attachment_code=1 AND attachment_type=1");	// Photos
	while($rec = dbFetchArray($results)){
		$fields1["content_photo_title"] = $rec["attachment_title"];
		$fields1["content_photo_content"] = $cid;
		dbPerform("content_photo", $fields1, 'insert');
		$cpid = dbLastInsertID("content_photo");
		$fields2["content_photo_src"] = "cphoto_$cpid.jpg";
//		$fields2["content_photo_thumb"] = "cphoto_t$cpid.jpg";
		rename(SITE_PATH."upload/{$rec["attachment_name"]}", SITE_PATH."upload/articlephoto/cphoto_$cpid.jpg");
//		echo "makeThumb({$fields2["content_photo_src"]}, 't0_cphoto_$cpid.jpg', 150, 100, 'Filedata', 'upload/articlephoto/')<br>";
		makeThumb($fields2["content_photo_src"], "t0_cphoto_$cpid.jpg", 150, 100, false, "upload/articlephoto/");
		makeThumb($fields2["content_photo_src"], "t1_cphoto_$cpid.jpg", 100, 999, false, "upload/articlephoto/");
		makeThumb($fields2["content_photo_src"], "t2_cphoto_$cpid.jpg", 470, 999, false, "upload/articlephoto/");
		dbPerform("content_photo", $fields2, 'update', "content_photo_id=$cpid");
	}
	unset($fields1);
	unset($fields2);
	$results = dbQuery("SELECT * FROM attachment WHERE attachment_key='$session' AND attachment_code=1 AND attachment_type=2");	// Videos
	while($rec = dbFetchArray($results)){
		$fields1["content_video_title"] = $rec["attachment_title"];
		$fields1["content_video_content"] = $cid;
		dbPerform("content_video", $fields1, 'insert');
		$cpid = dbLastInsertID("content_video");
		$fields2["content_video_src"] = "cvideo_$cpid.flv";
		rename(SITE_PATH."upload/{$rec["attachment_name"]}", SITE_PATH."upload/cvideo_$cpid.flv");
		if($rec["attachment_thumb"] != ""){
			$fields2["content_video_thumb"] = "cvideo_$cpid.jpg";
			rename(SITE_PATH."upload/{$rec["attachment_thumb"]}", SITE_PATH."upload/cvideo_$cpid.jpg");
		}
		dbPerform("content_video", $fields2, 'update', "content_video_id=$cpid");
	}
	unset($fields1);
	unset($fields2);
	$results = dbQuery("SELECT * FROM attachment WHERE attachment_key='$session' AND attachment_code=1 AND attachment_type=3");	// Slides
	while($rec = dbFetchArray($results)){
		$fields1["content_slide_title"] = $rec["attachment_title"];
		$fields1["content_slide_content"] = $cid;
		dbPerform("content_slide", $fields1, 'insert');
		$cpid = dbLastInsertID("content_slide");
		$fields2["content_slide_src"] = "cslide_$cpid.jpg";
//		$fields2["content_slide_thumb"] = "cslide_t$cpid.jpg";
		rename(SITE_PATH."upload/{$rec["attachment_name"]}", SITE_PATH."upload/articleslide/cslide_$cpid.jpg");
//		echo "makeThumb({$fields2["content_slide_src"]}, 't0_cslide_$cpid.jpg', 150, 100, false, 'upload/articleslide/')";
		makeThumb($fields2["content_slide_src"], "t0_cslide_$cpid.jpg", 150, 100, false, "upload/articleslide/");
		makeThumb($fields2["content_slide_src"], "t1_cslide_$cpid.jpg", 100, 999, false, "upload/articleslide/");
		makeThumb($fields2["content_slide_src"], "t2_cslide_$cpid.jpg", 470, 999, false, "upload/articleslide/");
		dbPerform("content_slide", $fields2, 'update', "content_slide_id=$cpid");
	}
	dbQuery("DELETE FROM attachment WHERE attachment_key='$session' AND attachment_code=1");	// delete all
}

function addRecord(){
	global $form_action;
	$fields = getFields(defaultTable, $_POST, "SAVE");
	if(validate($fields,0) == 0){
		breakingNews($fields);
		if($_REQUEST["other_author"] != "")
			$fields["content_author"] = $_REQUEST["other_author"]; 
		$fields["content_posted"] = strtotime($fields["content_posted"]);
		$fields["content_created"] = time();
		$z = addTrack();
		if($z > 0)
			$fields["content_track"] = $z;
		
		dbPerform(defaultTable, $fields, 'insert');
		$cid = dbLastInsertID(defaultTable);
		applyAttachments($cid);
		applyRegions($cid);
		addMessage("Post Saved");
		location(this_php);
	}
	else
		$form_action = "add";
}

function editRecord($id){
	global $form_action, $announce;
	$fields["content_id"] = $id;
	$fields = getFields(defaultTable, $_POST, "SAVE");
	if(validate($fields,$id) == 0){
		breakingNews($fields);
		$z = addTrack();
		if($z > 0)
			$fields["content_track"] = $z;
		if($_REQUEST["other_author"] != "")
			$fields["content_author"] = $_REQUEST["other_author"]; 
		if(!isset($fields["content_premium"]))
			$fields["content_premium"] = 0;
//		if(!isset($fields["content_secondary"]))
//			$fields["content_secondary"] = 0;
//		if(!isset($fields["content_featured"]))
//			$fields["content_featured"] = 0;
		$fields["content_posted"] = strtotime($fields["content_posted"]);
		$fields["content_created"] = time();
		$fields["content_updated"] = time();
		applyRegions($id);
		dbPerform(defaultTable, $fields, 'update', "content_id=$id");
		addMessage("Post Saved");
		location(this_php);
	}
	else
		$form_action = "edit";
}

function deleteRecord(){
	global $form_id;
	dbDeleteRecord(defaultTable, $form_id);
	dbDeleteRecords("content_category_list", "content_category_list_content=$form_id");
	// TODO delete the attached media files and thumbnails
	dbDeleteRecords("content_photo", "content_photo_content=$form_id");
	dbDeleteRecords("content_video", "content_video_content=$form_id");
	dbDeleteRecords("content_slide", "content_slide_content=$form_id");

	addMessage("Post Deleted");
	location(this_php);
}

///////////////////////////////////////////////////////////////////////////////////////////////////////////
switch($form_action){
	case "cancel":
		cancelAction();
		break;
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
