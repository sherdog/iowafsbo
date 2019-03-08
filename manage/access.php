<?php

extract($_GET, EXTR_PREFIX_ALL, 'form_'); extract($_POST, EXTR_PREFIX_ALL, 'form_');
include "master.inc.php";
$_SESSION["cp_active_tab"] = "user.php";
define('defaultTable', 'cp_access');

if(!checkAccess(this_php)){
	addMessage("No permission for working with the control panel access types");
	location("index.php");
	exit(0);
}

function cancelAction(){
	global $form_mode;
	switch($form_mode){
		case "page":
			addMessage("Thank you for working with the control panel access types");
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
	echo "<thead><tr><th>Title/Page</th><th>Desc</th><th><br/></th></tr></thead>";
}

function showRecord($rec){
	global $row_num;
	$class = "row" . $row_num % 2;
	$row_num++;
	$link = "href='".this_php."?action=edit&id={$rec["cp_access_id"]}'";
	$rec = getFields("", $rec, "SHOW");
	$tdl = "<td title='Edit Control Panel Access Type'><a $link>";
	$tdr = "<td title='Edit Control Panel Access Type' align='right'><a $link>";
	$tde = "</a></td>\n";
//	$fdate = formatDate($rec["cp_access_updated"]);
	echo "<tr class='$class'>$tdl{$rec["cp_access_title"]}$tde$tdl{$rec["cp_access_desc"]}$tde<td><a href='".this_php."?action=delete&id={$rec["cp_access_id"]}'><img title='Delete Control Panel Access Type' height='14' src='images/del.png' border='0'/>$tde</tr>";
}

function showRecords(){
	$result = dbRows(defaultTable, "%title");
	
	echo "<h2>Your Control Panel Access Type List</h2>";
	$num = dbNumRows($result);
	if($num == 0)
		echo "There are no control panel access types";
	else{
		if($num == 1)
			echo "There is 1 control panel access type";
		else
			echo "There are $num control panel access types";
		echo "<table class='records' cellpadding='2' cellspacing='0'>";
		printHeader();
		echo "<tbody>";
		while($rec = dbFetchArray($result)){
			showRecord($rec);
		}
		echo "</tbody></table>";
	}
	printButtonLinkSeperator(false);
	printButtonLink("Done", this_php."?action=cancel&mode=page");
	printButtonLink("Add New Control Panel Access Type", this_php."?action=add");
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
	echo "<h2>$mode Control Panel Access Type</h2>";
	if(numErrors() > 0){
		printErrors();
		$rec = getFields(defaultTable, $_POST, "SHOW");
	}
	echo "<form action='".this_php."' method='post'>
			<input type='hidden' name='id' value='$id'/>
			<input type='hidden' name='action' value='{$mode}Record'/>
		<table class='form'><tbody>
	";
	printTextField("Title/Page", $rec, "%title", 40, 100);
	printTextField("Desc", $rec, "%desc", 40, 100);
	echo "	</tbody>
		<tfoot>
			<tr class='bar'><td><input type='button' value='Cancel' onclick=\"location.href='".this_php."?action=cancel&mode=$mode'\"></td>
			<td><input type='submit' value='$mode Control Panel Access Type'/></td></tr>
		</tfoot></table>
		</form>
	";
	if($mode == "Edit"){
		printButtonLinkSeperator(TRUE, "Other Actions");
		printButtonLink("Delete This Control Panel Access Type", this_php."?action=delete&id=$id");
		printButtonLink("Add a New Control Panel Access Type", this_php."?action=add");
	}
}

function askDelete($id){
	$rec = getFields("", dbRow(defaultTable, $id), "SHOW");
	echo "<h2>Delete Control Panel Access Type</h2>Are you sure you want to delete <font class=highlighted>{$rec["cp_access_title"]}</font>?
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
	printButtonLink("Edit This Control Panel Access Type", this_php."?action=edit&id=$id");
	printButtonLink("Add a New Control Panel Access Type", this_php."?action=add");
}

function validateFields($fields){
	validateTextField($fields, "cp_access_title", NULL, TRUE, 1);
	validateTextField($fields, "cp_access_desc", NULL, TRUE, 1);
	return numErrors();
}

function addRecord(){
	global $form_action;
	$fields = getFields(defaultTable, $_POST, "SAVE");
	if(validateFields($fields,0) == 0){
		dbPerform(defaultTable, $fields, 'insert');
		addMessage("Added the control panel access type");
		$fields["cp_access_id"] = dbLastInsertID(defaultTable);
		location(this_php);
	}
	else
		$form_action = "add";
}

function editRecord($id){
	global $form_action, $announce;
	$fields["cp_access_id"] = $id;
	$fields = getFields(defaultTable, $_POST, "SAVE");
	if(validateFields($fields,$id) == 0){
//		$fields["cp_access_updated"] = time();
		dbPerform(defaultTable, $fields, 'update', "cp_access_id=$id");
		addMessage("The control panel access type was edited");
		location(this_php);
	}
	else
		$form_action = "edit";
}

function deleteRecord(){
	global $form_id;
	dbDeleteRecord(defaultTable, $form_id);
	addMessage("The control panel access type was deleted");
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
