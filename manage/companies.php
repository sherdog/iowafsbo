<?php

extract($_GET, EXTR_PREFIX_ALL, 'form_'); extract($_POST, EXTR_PREFIX_ALL, 'form_');
include "master.inc.php";
$_SESSION["cp_active_tab"] = "companies.php";
define('defaultTable', 'company');

if (!isLoggedIn()){
	$_SESSION["loginreturn"] = this_php;
	location("login.php");
}

if(!checkAccess(this_php)){
	addMessage("No permission for working with the industry directory");
	location("index.php");
	exit(0);
}

function cancelAction(){
	global $form_mode;
	switch($form_mode){
		case "page":
			addMessage("Thank you for working with the industry directory");
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
	echo "<thead><tr><th>Title</th><th>Website</th><th><br/></th><th><br/></th></tr></thead>";
}

function showRecord($rec){
	global $row_num;
	$class = "row" . $row_num % 2;
	$row_num++;
	$link = "href='".this_php."?action=edit&id={$rec["company_id"]}'";
	$rec = getFields("", $rec, "SHOW");
	$tdl = "<td title='Edit Industry Directory'><a $link>";
	$tdr = "<td title='Edit Industry Directory' align='right'><a $link>";
	$tde = "</a></td>\n";
//	$fdate = formatDate($rec["company_updated"]);
	echo "<tr class='$class'>$tdl{$rec["company_title"]}$tde$tdl{$rec["company_href"]}$tde<td><a class='edit' $link>Edit$tde<td><a href='".this_php."?action=delete&id={$rec["company_id"]}'><img title='Delete Industry Directory' height='14' src='images/del.png' border='0'/>$tde</tr>";
}

function showRecords(){
	$result = dbRows(defaultTable, "%title");
	
	echo "<h2>Your Industry Directory List</h2>";
	$num = dbNumRows($result);
	if($num == 0)
		echo "There are nothing in the industry directory";
	else{
		if($num == 1)
			echo "There is 1 industry directory";
		else
			echo "There are $num industry directory items";
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
	printButtonLink("Add New Industry Directory Item", this_php."?action=add");
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
	echo "<h2>$mode Industry Directory Item</h2>";
	if(numErrors() > 0){
//		printErrors();
		$rec = getFields(defaultTable, $_POST, "SHOW");
	}
	echo "<form action='".this_php."' method='post' enctype='multipart/form-data'>
			<input type='hidden' name='id' value='$id'/>
			<input type='hidden' name='action' value='{$mode}Record'/>
		<table class='form' width='100%'><tbody>
	";
	printDBRecordSelect("Category", $rec, "%category", "SELECT company_category_id, company_category_title FROM company_category ORDER BY company_category_title", "companyCategories.php");
	printTextField("Title", $rec, "%title", 80, 80);
	printTextField("Website", $rec, "%href", 80, 80);
	printTextField("Phone Number", $rec, "%phone", 20, 20);
	printTextField("Address", $rec, "%address", 80, 80);
	printCityStateZip($rec, "");
	printFileUploadField("Logo", $rec, "Filedata");
	printImageField("Current Image", $rec, "%logo", "t0_", "../upload/logo/");

//	printTextAreaField("Contents", $rec, "%contents", 10, 80);
	echo "	</tbody>
		<tfoot>
			<tr class='bar'>
			<td colspan='2'>
				<input style='float: left' class='submitButton' type='submit' value='$mode Industry Directory Item'/>
				<input style='float: right' class='cancelButton' type='button' value='Cancel' onclick=\"location.href='".this_php."?action=cancel&mode=$mode'\">
			</td></tr>
		</tfoot></table>
		</form>
	";
	if($mode == "Edit"){
		printButtonLinkSeperator(TRUE, "Other Actions");
		printButtonLink("Delete This Industry Directory Item", this_php."?action=delete&id=$id");
		printButtonLink("Add a New Industry Directory Item", this_php."?action=add");
	}
}

function askDelete($id){
	$rec = getFields("", dbRow(defaultTable, $id), "SHOW");
	echo "<h2>Delete Industry Directory</h2>
		Are you sure you want to delete <font class=highlighted>{$rec["company_title"]}</font>?
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
	printButtonLink("Edit This Industry Directory Item", this_php."?action=edit&id=$id");
	printButtonLink("Add a New Industry Directory Item", this_php."?action=add");
}

function validate($fields){
	validateTextField($fields, "company_title", NULL, TRUE, 1);
	validateTextField($fields, "company_href", NULL, TRUE, 1);
	validateTextField($fields, "company_address", NULL, TRUE, 1);
	validateTextField($fields, "company_city", NULL, TRUE, 1);
	validateZipCode($fields, "company_zip", NULL, TRUE);
	return numErrors();
}


function mkthumb($name, $id){
	makeThumb($name, "t0_cphoto_$id.jpg", 150, 100, "Filedata", "upload/logo/");
	makeThumb($name, "t1_cphoto_$id.jpg", 100, 999, "Filedata", "upload/logo/");
	makeThumb($name, "t2_cphoto_$id.jpg", 470, 999, "Filedata", "upload/logo/");
}


function addRecord(){
	global $form_action;
	$fields = getFields(defaultTable, $_POST, "SAVE");
	if(validate($fields,0) == 0){
//		$fields["company_expires"] = strtotime($fields["company_expires"]);
//		$fields["company_created"] = time();
		dbPerform(defaultTable, $fields, 'insert');
		$cid = dbLastInsertID(defaultTable);
		$z["company_logo"] = uploadPhoto("cphoto", $cid, "Filedata", "upload/logo/");
//		$z["company_logo_thumb"] = makeThumb($z["company_logo"], "cphoto_t$cid.jpg", 80, 80);
		mkthumb($z["company_logo"], $cid);
		dbPerform(defaultTable, $z, 'update', "content_photo_id=$cid");
		addMessage("Added the industry directory");
		location(this_php);
	}
	else
		$form_action = "add";
}

function editRecord($id){
	global $form_action, $announce;
	$fields["company_id"] = $id;
	$fields = getFields(defaultTable, $_POST, "SAVE");
	if(validate($fields,$id) == 0){
//		$fields["company_expires"] = strtotime($fields["company_expires"]);
//		$fields["company_created"] = time();
//		$fields["company_updated"] = time();
		$fields["company_logo"] = uploadPhoto("cphoto", $id, "Filedata", "upload/logo/");
		if($fields["company_logo"] == false)
			unset($fields["company_logo"]);
//		else
//			$fields["company_logo_thumb"] = makeThumb($fields["company_logo"], "cphoto_t$id.jpg", 80, 80);
			mkthumb($fields["company_logo"], $id);
		dbPerform(defaultTable, $fields, 'update', "company_id=$id");
		addMessage("The industry directory was edited");
		location(this_php);
	}
	else
		$form_action = "edit";
}

function deleteRecord(){
	global $form_id;
	dbDeleteRecord(defaultTable, $form_id);
	addMessage("The industry directory was deleted");
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
include "footer.inc.php"
?>
