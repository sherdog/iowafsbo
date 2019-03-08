<?php

extract($_GET, EXTR_PREFIX_ALL, 'form_'); extract($_POST, EXTR_PREFIX_ALL, 'form_');
include "master.inc.php";
$_SESSION["cp_active_tab"] = "companies.php";
define('defaultTable', 'company_category');

if (!isLoggedIn()){
	$_SESSION["loginreturn"] = this_php;
	location("login.php");
}

if(!checkAccess(this_php)){
	addMessage("No permission for working with the company categories");
	location("index.php");
	exit(0);
}

function cancelAction(){
	global $form_mode;
	switch($form_mode){
		case "page":
			addMessage("Thank you for working with the company categories");
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
	echo "<thead><tr><th>Title</th><th><br/></th><th><br/></th></tr></thead>";
}

function showRecord($rec){
	global $row_num;
	$class = "row" . $row_num % 2;
	$row_num++;
	$link = "href='".this_php."?action=edit&id={$rec["company_category_id"]}'";
	$rec = getFields("", $rec, "SHOW");
	$tdl = "<td title='Edit Company Category'><a $link>";
	$tdr = "<td title='Edit Company Category' align='right'><a $link>";
	$tde = "</a></td>\n";
//	$fdate = formatDate($rec["company_category_updated"]);
	echo "<tr class='$class'>$tdl{$rec["company_category_title"]}$tde
		<td><a class='edit' $link>Edit$tde
		<td><a href='".this_php."?action=delete&id={$rec["company_category_id"]}'><img title='Delete Company Category' height='14' src='images/del.png' border='0'/>$tde</tr>
	";
}

function showRecords(){
	$result = dbRows(defaultTable, "%title");
	
	echo "<h2>Your Company Category List</h2>";
	$num = dbNumRows($result);
	if($num == 0)
		echo "There are no company categories";
	else{
		if($num == 1)
			echo "There is 1 company category";
		else
			echo "There are $num company categories";
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
	printButtonLink("Add New Company Category", this_php."?action=add");
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
	echo "<h2>$mode Company Category</h2>";
	if(numErrors() > 0){
//		printErrors();
		$rec = getFields(defaultTable, $_POST, "SHOW");
	}
	echo "<form action='".this_php."' method='post'>
			<input type='hidden' name='id' value='$id'/>
			<input type='hidden' name='action' value='{$mode}Record'/>
		<table class='form' width='100%'><tbody>
	";
	printTextField("Title", $rec, "%title", 80, 128);
//	printTextAreaField("Contents", $rec, "%contents", 10, 80);
	echo "	</tbody>
		<tfoot>
			<tr class='bar'>
			<td colspan='2'>
				<input style='float: left' class='submitButton' type='submit' value='$mode Company Category'/>
				<input style='float: right' class='cancelButton' type='button' value='Cancel' onclick=\"location.href='".this_php."?action=cancel&mode=$mode'\">
			</td></tr>
		</tfoot></table>
		</form>
	";
	if($mode == "Edit"){
		printButtonLinkSeperator(TRUE, "Other Actions");
		printButtonLink("Delete This Company Category", this_php."?action=delete&id=$id");
		printButtonLink("Add a New Company Category", this_php."?action=add");
	}
}

function askDelete($id){
	$rec = getFields("", dbRow(defaultTable, $id), "SHOW");
	echo "<h2>Delete Company Category</h2>
		Are you sure you want to delete <font class=highlighted>{$rec["company_category_title"]}</font>?
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
	printButtonLink("Edit This Company Category", this_php."?action=edit&id=$id");
	printButtonLink("Add a New Company Category", this_php."?action=add");
}

function validate($fields){
	validateTextField($fields, "company_category_title", NULL, TRUE, 1);
	return numErrors();
}

function addRecord(){
	global $form_action;
	$fields = getFields(defaultTable, $_POST, "SAVE");
	if(validate($fields,0) == 0){
//		$fields["company_category_expires"] = strtotime($fields["company_category_expires"]);
//		$fields["company_category_created"] = time();
		dbPerform(defaultTable, $fields, 'insert');
		addMessage("Added the company category");
		$fields["company_category_id"] = dbLastInsertID(defaultTable);
		location(this_php);
	}
	else
		$form_action = "add";
}

function editRecord($id){
	global $form_action, $announce;
	$fields["company_category_id"] = $id;
	$fields = getFields(defaultTable, $_POST, "SAVE");
	if(validate($fields,$id) == 0){
//		$fields["company_category_expires"] = strtotime($fields["company_category_expires"]);
//		$fields["company_category_created"] = time();
//		$fields["company_category_updated"] = time();
		dbPerform(defaultTable, $fields, 'update', "company_category_id=$id");
		addMessage("The company category was edited");
		location(this_php);
	}
	else
		$form_action = "edit";
}

function deleteRecord(){
	global $form_id;
	dbDeleteRecord(defaultTable, $form_id);
	addMessage("The company category was deleted");
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
