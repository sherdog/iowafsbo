<?php

extract($_GET, EXTR_PREFIX_ALL, 'form_'); extract($_POST, EXTR_PREFIX_ALL, 'form_');
include "master.inc.php";
include "fckeditor/fckeditor.php";
$_SESSION["cp_active_tab"] = "trackAccommodations.php";
define('defaultTable', 'track');

if (!isLoggedIn()){
	$_SESSION["loginreturn"] = this_php;
	location("login.php");
}

if(!checkAccess(this_php)){
	addMessage("No permission for working with the tracks");
	location("index.php");
	exit(0);
}

function cancelAction(){
	global $form_mode;
	switch($form_mode){
		case "page":
			addMessage("Thank you for working with the tracks");
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
	echo "<thead><tr><th>Track Name</th><th>City</th><th>State</th><th><br/></th><th><br/></th></tr></thead>";
}

function showRecord($rec){
	global $row_num;
	$class = "row" . $row_num % 2;
	$row_num++;
	$link = "href='".this_php."?action=edit&id={$rec["track_id"]}'";
	$rec = getFields("", $rec, "SHOW");
	$tdl = "<td title='Edit Track'><a $link>";
	$tdr = "<td title='Edit Track' align='right'><a $link>";
	$tde = "</a></td>\n";
//	$fdate = formatDate($rec["track_updated"]);
	echo "<tr class='$class'>$tdl{$rec["track_title"]}$tde
	$tdl{$rec["track_city"]}$tde
    $tdl{$rec["track_state"]}$tde
		<td><a class='edit' $link>Edit$tde
		<td><a href='".this_php."?action=delete&id={$rec["track_id"]}'><img title='Delete Track' height='14' src='images/del.png' border='0'/>$tde</tr>
	";
}

function showRecords(){
	//$result = dbRows(defaultTable, "%state");
	global $form_page;
	$resultsPerPage = 30;
//	$result = dbRows(defaultTable, "%start_date", "DESC");
	
//	$result = dbQuery("SELECT COUNT(*) FROM schedule, content_category, schedule_division, track WHERE schedule_region=content_category_id AND schedule_division=schedule_division_id AND schedule_track=track_id ORDER BY schedule_start_date DESC LIMIT 0, $resultsPerPage");
	$num = dbNumRecords("track");
	$pages = ceil($num / $resultsPerPage); 
	$recStart = $form_page * $resultsPerPage;

	$result = dbQuery("SELECT * FROM track ORDER BY track_state, track_city, track_title LIMIT $recStart, $resultsPerPage");
	
	echo "<h2>Your Track List</h2>";
//	$num = dbNumRows($result);
	if($num == 0)
		echo "There are no tracks";
	else{
		if($num == 1)
			echo "There is 1 track";
		else
			echo "There are $num tracks";
			
 		if($num > $resultsPerPage){
 			$page = $form_page + 1;
 			echo "<form action='".this_php."'>
	 		Page $page of $pages pages, change page:
			 <select name='page' onchange='this.parentNode.submit()'>";
 			for($a = 0,$b = 1;$a < $pages;$a++,$b++){
 				$sel = "";
 				if($a == $form_page)
 					$sel = " selected";
 				echo "<option$sel value='$a'>$b</option>";
 			}
 			echo "</select></form>";
 		}
			
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
	printButtonLink("Add New Track", this_php."?action=add");
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
	echo "<h2>$mode Track</h2>";
	if(numErrors() > 0){
//		printErrors();
		$rec = getFields(defaultTable, $_POST, "SHOW");
	}
	echo "<form action='".this_php."' method='post'>
			<input type='hidden' name='id' value='$id'/>
			<input type='hidden' name='action' value='{$mode}Record'/>
		<table class='form' width='100%'><tbody>
	";
	printTextField("Title", $rec, "%title", 80, 80);
	printHTMLField("Description", $rec, "%desc", "Basic");
	printCityStateZip($rec, "");
	echo "	</tbody>
		<tfoot>
			<tr class='bar'>
			<td colspan='2'>
				<input style='float: left' class='submitButton' type='submit' value='$mode Track'/>
				<input style='float: right' class='cancelButton' type='button' value='Cancel' onclick=\"location.href='".this_php."?action=cancel&mode=$mode'\">
			</td></tr>
		</tfoot></table>
		</form>
	";
	if($mode == "Edit"){
		printButtonLinkSeperator(TRUE, "Other Actions");
		printButtonLink("Delete This Track", this_php."?action=delete&id=$id");
		printButtonLink("Add a New Track", this_php."?action=add");
	}
}

function askDelete($id){
	$rec = getFields("", dbRow(defaultTable, $id), "SHOW");
	echo "<h2>Delete Track</h2>
		Are you sure you want to delete <font class=highlighted>{$rec["track_title"]}</font>?
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
	printButtonLink("Edit This Track", this_php."?action=edit&id=$id");
	printButtonLink("Add a New Track", this_php."?action=add");
}

function validate($fields){
	validateTextField($fields, "track_title", NULL, TRUE, 1);
	validateTextField($fields, "track_city", NULL, TRUE, 1);
	return numErrors();
}

function addRecord(){
	global $form_action;
	$fields = getFields(defaultTable, $_POST, "SAVE");
	if(validate($fields,0) == 0){
//		$fields["track_expires"] = strtotime($fields["track_expires"]);
//		$fields["track_created"] = time();
		dbPerform(defaultTable, $fields, 'insert');
		addMessage("Added the track");
		$fields["track_id"] = dbLastInsertID(defaultTable);
		location(this_php);
	}
	else
		$form_action = "add";
}

function editRecord($id){
	global $form_action, $announce;
	$fields["track_id"] = $id;
	$fields = getFields(defaultTable, $_POST, "SAVE");
	if(validate($fields,$id) == 0){
//		$fields["track_expires"] = strtotime($fields["track_expires"]);
//		$fields["track_created"] = time();
//		$fields["track_updated"] = time();
		dbPerform(defaultTable, $fields, 'update', "track_id=$id");
		addMessage("The track was edited");
		location(this_php);
	}
	else
		$form_action = "edit";
}

function deleteRecord(){
	global $form_id;
	dbDeleteRecord(defaultTable, $form_id);
	addMessage("The track was deleted");
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
