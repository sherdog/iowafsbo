<?php

extract($_GET, EXTR_PREFIX_ALL, 'form_'); extract($_POST, EXTR_PREFIX_ALL, 'form_');
include "master.inc.php";
$_SESSION["cp_active_tab"] = "trackAccommodations.php";
define('defaultTable', 'track_accommodation');

if (!isLoggedIn()){
	$_SESSION["loginreturn"] = this_php;
	location("login.php");
}

if(!checkAccess(this_php)){
	addMessage("No permission for working with the track accommodations");
	location("index.php");
	exit(0);
}

function cancelAction(){
	global $form_mode;
	switch($form_mode){
		case "page":
			addMessage("Thank you for working with the track accommodations");
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
	echo "<thead><tr><th>Title</th><th>City</th><th>State</th><th><br/></th><th><br/></th></tr></thead>";
}

function showRecord($rec){
	global $row_num;
	$class = "row" . $row_num % 2;
	$row_num++;
	$link = "href='".this_php."?action=edit&id={$rec["track_accommodation_id"]}'";
	$rec = getFields("", $rec, "SHOW");
	$tdl = "<td title='Edit Track Accommodation'><a $link>";
	$tdr = "<td title='Edit Track Accommodation' align='right'><a $link>";
	$tde = "</a></td>\n";
//	$fdate = formatDate($rec["track_accommodation_updated"]);
	echo "<tr class='$class'>$tdl{$rec["track_accommodation_title"]}$tde
	$tdl{$rec["track_accommodation_city"]}$tde
	$tdl{$rec["track_accommodation_state"]}$tde
		<td><a class='edit' $link>Edit$tde
		<td><a href='".this_php."?action=delete&id={$rec["track_accommodation_id"]}'><img title='Delete Track Accommodation' height='14' src='images/del.png' border='0'/>$tde</tr>
	";
}

function showRecords(){
	$result = dbRows(defaultTable, "%state");
	
	echo "<h2>Your Track Accommodation List</h2>";
	$num = dbNumRows($result);
	if($num == 0)
		echo "There are no track accommodations";
	else{
		if($num == 1)
			echo "There is 1 track accommodation";
		else
			echo "There are $num track accommodations";
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
	printButtonLink("Add New Track Accommodation", this_php."?action=add");
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
	echo "<h2>$mode Track Accommodation</h2>";
	if(numErrors() > 0){
		$rec = getFields(defaultTable, $_POST, "SHOW");
	}
	echo "<form action='".this_php."' method='post' enctype='multipart/form-data'>
			<input type='hidden' name='id' value='$id'/>
			<input type='hidden' name='action' value='{$mode}Record'/>
		<table class='form' width='100%'><tbody>
	";
	printTrackSelect("Track", $rec, "%track");
	printTextField("Title", $rec, "%title", 80, 80);
	printTextField("Phone", $rec, "%phone", 20, 20);
	printTextField("Email", $rec, "%email", 80, 255);
	printTextField("Website", $rec, "%website", 80, 255);
	printTextField("Address 1", $rec, "%address", 80, 255);
	printTextField("Address 2", $rec, "%address2", 80, 255);
	printCityStateZip($rec, "");
	printFileUploadField("Photo/Logo", $rec, "Filedata");
	printImageField("Current Image", $rec, "%logo", "t0_", "../upload/accommodation/");
	echo "	</tbody>
		<tfoot>
			<tr class='bar'>
			<td colspan='2'>
				<input style='float: left' class='submitButton' type='submit' value='$mode Track Accommodation'/>
				<input style='float: right' class='cancelButton' type='button' value='Cancel' onclick=\"location.href='".this_php."?action=cancel&mode=$mode'\">
			</td></tr>
		</tfoot></table>
		</form>
	";
	if($mode == "Edit"){
		printButtonLinkSeperator(TRUE, "Other Actions");
		printButtonLink("Delete This Track Accommodation", this_php."?action=delete&id=$id");
		printButtonLink("Add a New Track Accommodation", this_php."?action=add");
	}
}

function askDelete($id){
	$rec = getFields("", dbRow(defaultTable, $id), "SHOW");
	echo "<h2>Delete Track Accommodation</h2>
		Are you sure you want to delete <font class=highlighted>{$rec["track_accommodation_title"]}</font>?
		<br><br>
		<form action='".this_php."' method=post>
			<input type='hidden' name='action' value='confirmdelete'>
			<input type='hidden' name='id' value='$id'>
			<table><tr>
				<td><input type='submit' class='delete' value='Yes -- Delete'></td>
				<td><input type='button' class='nodelete' value='No -- Cancel' onclick=\"location.href='".this_php."?action=cancel&mode=Delete'\"></td>
			</tr></table>
		</form>
	";
	printButtonLinkSeperator(true, "Other Actions");
	printButtonLink("Edit This Track Accommodation", this_php."?action=edit&id=$id");
	printButtonLink("Add a New Track Accommodation", this_php."?action=add");
}

function validate($fields){
	validateTextField($fields, "track_accommodation_title", NULL, TRUE, 1);
	validateEmail($fields, "track_accommodation_email", FALSE);
	validatePhoneNumber($fields, "track_accommodation_phone", FALSE);
	validateTextField($fields, "track_accommodation_city", NULL, TRUE, 1);
	return numErrors();
}

function mkthumb($name, $id){
	makeThumb($name, "t0_logo_$id.jpg", 150, 100, false, "upload/accommodation/");
	makeThumb($name, "t1_logo_$id.jpg", 100, 999, false, "upload/accommodation/");
	makeThumb($name, "t2_logo_$id.jpg", 470, 999, false, "upload/accommodation/");
}

function addRecord(){
	global $form_action;
	$fields = getFields(defaultTable, $_POST, "SAVE");
	if(validate($fields,0) == 0){
		$fields["track_accommodation_created"] = time();
		dbPerform(defaultTable, $fields, 'insert');
		addMessage("Added the track accommodation");
		$cid = dbLastInsertID(defaultTable);
//		$z["track_accommodation_logo"] = applyPhoto($cid);
		$z["track_accommodation_logo"] = uploadPhoto("logo", $cid, "Filedata", "upload/accommodation/");
		mkthumb($z["track_accommodation_logo"], $cid);

//		$z["track_accommodation_logo_thumb"] = makeThumb($z["track_accommodation_logo"], "cphoto_t$cid.jpg", 80, 80);
		dbPerform(defaultTable, $z, 'update', "track_accommodation_id=$cid");
		addTrack();
		location(this_php);
	}
	else
		$form_action = "add";
}

function editRecord($id){
	global $form_action, $announce;
	$fields["track_accommodation_id"] = $id;
	$fields = getFields(defaultTable, $_POST, "SAVE");
	if(validate($fields,$id) == 0){
//		$fields["track_accommodation_created"] = time();
//		$fields["track_accommodation_logo"] = applyPhoto($id);
		
		$fields["track_accommodation_logo"] = uploadPhoto("logo", $cid, "Filedata", "upload/accommodation/");
		mkthumb($fields["track_accommodation_logo"], $id);
		if($fields["track_accommodation_logo"] == false)
			unset($fields["track_accommodation_logo"]);
// 		else
// 			$fields["track_accommodation_thumb"] = makeThumb($fields["track_accommodation_logo"], "talogo_t$id.jpg", 80, 80);
		dbPerform(defaultTable, $fields, 'update', "track_accommodation_id=$id");
		addMessage("The track accommodation was edited");
		addTrack();
		location(this_php);
	}
	else
		$form_action = "edit";
}

function deleteRecord(){
	global $form_id;
	dbDeleteRecord(defaultTable, $form_id);
	addMessage("The track accommodation was deleted");
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
