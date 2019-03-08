<?php

extract($_GET, EXTR_PREFIX_ALL, 'form_'); extract($_POST, EXTR_PREFIX_ALL, 'form_');
include "master.inc.php";
include "fckeditor/fckeditor.php";
$_SESSION["cp_active_tab"] = "staff.php";
define('defaultTable', 'staff');

if (!isLoggedIn()){
	$_SESSION["loginreturn"] = this_php;
	location("login.php");
}

if(!checkAccess(this_php)){
	addMessage("No permission for working with the staff members");
	location("index.php");
	exit(0);
}

function cancelAction(){
	global $form_mode;
	switch($form_mode){
		case "page":
			addMessage("Thank you for working with the staff members");
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
	echo "<thead><tr><th>Last name</th><th>First name</th><th>Title</th><th><br/></th><th><br/></th></tr></thead>";
}

function showRecord($rec){
	global $row_num;
	$class = "row" . $row_num % 2;
	$row_num++;
	$link = "href='".this_php."?action=edit&id={$rec["staff_id"]}'";
	$rec = getFields("", $rec, "SHOW");
	$tdl = "<td title='Edit Staff Member'><a $link>";
	$tdr = "<td title='Edit Staff Member' align='right'><a $link>";
	$tde = "</a></td>\n";
//	$fdate = formatDate($rec["staff_updated"]);
	echo "<tr class='$class'>$tdl{$rec["staff_last_name"]}$tde$tdl{$rec["staff_first_name"]}$tde$tdl{$rec["staff_title"]}$tde<td><a class='edit' $link>Edit$tde<td><a href='".this_php."?action=delete&id={$rec["staff_id"]}'><img title='Delete Staff Member' height='14' src='images/del.png' border='0'/>$tde</tr>";
}

function showRecords(){
	$result = dbRows(defaultTable, "%last_name");
	
	echo "<h2>Your Staff Member List</h2>";
	$num = dbNumRows($result);
	if($num == 0)
		echo "There are no staff members";
	else{
		if($num == 1)
			echo "There is 1 staff member";
		else
			echo "There are $num staff members";
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
	printButtonLink("Add New Staff Member", this_php."?action=add");
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
	echo "<h2>$mode Staff Member</h2>";
	if(numErrors() > 0){
//		printErrors();
		$rec = getFields(defaultTable, $_POST, "SHOW");
	}
	echo "<form action='".this_php."' method='post' enctype='multipart/form-data'>
			<input type='hidden' name='id' value='$id'/>
			<input type='hidden' name='action' value='{$mode}Record'/>
		<table class='form' width='100%'><tbody>
	";
	printTextField("Title", $rec, "%title", 40, 40);
	printTextField("First Name", $rec, "%first_name", 40, 40);
	printTextField("Last Name", $rec, "%last_name", 40, 40);
	printTextField("Order", $rec, "%sort_order", 4, 4);
	printHTMLField("Description", $rec, "%desc", "Basic");
	printFileUploadField("Photo", $rec, "Filedata");
	printImageField("Current Image", $rec, "%photo", "t0_", "../upload/staff/");
	echo "	</tbody>
		<tfoot>
			<tr class='bar'>
			<td colspan='2'>
				<input style='float: left' class='submitButton' type='submit' value='$mode Staff Member'/>
				<input style='float: right' class='cancelButton' type='button' value='Cancel' onclick=\"location.href='".this_php."?action=cancel&mode=$mode'\">
			</td></tr>
		</tfoot></table>
		</form>
	";
	if($mode == "Edit"){
		printButtonLinkSeperator(TRUE, "Other Actions");
		printButtonLink("Delete This Staff Member", this_php."?action=delete&id=$id");
		printButtonLink("Add a New Staff Member", this_php."?action=add");
	}
}

function askDelete($id){
	$rec = getFields("", dbRow(defaultTable, $id), "SHOW");
	echo "<h2>Delete Staff Member</h2>
		Are you sure you want to delete <font class=highlighted>{$rec["staff_title"]}</font>?
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
	printButtonLink("Edit This Staff Member", this_php."?action=edit&id=$id");
	printButtonLink("Add a New Staff Member", this_php."?action=add");
}

function validate($fields){
	validateTextField($fields, "staff_title", NULL, TRUE, 1);
	validateTextField($fields, "staff_first_name", NULL, TRUE, 1);
	validateTextField($fields, "staff_last_name", NULL, TRUE, 1);
	validateTextField($fields, "staff_desc", NULL, TRUE, 1);
	return numErrors();
}

function mkthumb($name, $id){
	makeThumb($name, "t0_$name", 150, 100, "Filedata", "upload/staff/");
	makeThumb($name, "t1_$name", 100, 999, "Filedata", "upload/staff/");
	makeThumb($name, "t2_$name", 470, 999, "Filedata", "upload/staff/");
}


function addRecord(){
	global $form_action;
	$fields = getFields(defaultTable, $_POST, "SAVE");
	if(validate($fields,0) == 0){
//		$fields["staff_expires"] = strtotime($fields["staff_expires"]);
//		$fields["staff_created"] = time();
		dbPerform(defaultTable, $fields, 'insert');
		$cid = dbLastInsertID(defaultTable);
		$z["staff_photo"] = uploadPhoto("staff", $cid, "Filedata", "upload/staff/");
		mkthumb($z["staff_photo"], $cid);
		dbPerform(defaultTable, $z, 'update', "staff_id=$cid");
		addMessage("Added the staff member");
		$fields["staff_id"] = dbLastInsertID(defaultTable);
		location(this_php);
	}
	else
		$form_action = "add";
}

function editRecord($id){
	global $form_action, $announce;
	$fields["staff_id"] = $id;
	$fields = getFields(defaultTable, $_POST, "SAVE");
	if(validate($fields,$id) == 0){
//		$fields["staff_expires"] = strtotime($fields["staff_expires"]);
//		$fields["staff_created"] = time();
//		$fields["staff_updated"] = time();
		$fields["staff_photo"] = uploadPhoto("staff", $id, "Filedata", "upload/staff/");
		mkthumb($fields["staff_photo"], $id);
		if($fields["staff_photo"] == false)
			unset($fields["staff_photo"]);
		dbPerform(defaultTable, $fields, 'update', "staff_id=$id");
		addMessage("The staff member was edited");
		location(this_php);
	}
	else
		$form_action = "edit";
}

function deleteRecord(){
	global $form_id;
	dbDeleteRecord(defaultTable, $form_id);
	addMessage("The staff member was deleted");
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
