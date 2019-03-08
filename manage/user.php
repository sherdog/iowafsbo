<?php

extract($_GET, EXTR_PREFIX_ALL, 'form_'); 
extract($_POST, EXTR_PREFIX_ALL, 'form_');
include "master.inc.php";

$_SESSION["cp_active_tab"] = "user.php";

define('defaultTable', 'cp_user');
define('table_1l', 'control panel user');
define('table_1u', 'Control Panel User');
define('table_n', table_1l.'s');
if($form_action == "edit")
	$selmenu = "user.php?action=edit";
elseif($form_action == "add")
	$selmenu = "user.php?action=add";

if (!isLoggedIn()){
	$_SESSION["loginreturn"] = this_php;
	location("login.php");
}

if (!checkAccess("Administrator") && $form_action != "edit"){
	addMessage("No permission for control panel users");
	location("index.php");
}

function cancelAction(){
	global $form_mode;
	switch($_GET['mode']){
		case "page":
			addMessage("Thank you for working with the ".table_n);
			location("index.php");
			break;
		default:
			addMessage("Cancelled $form_mode");
			location(this_php);
			break;
	}
	exit();
}

function validateFields($fields, $id){
	if($id == 0)
		validateTextField($fields, "cp_user_password", NULL, TRUE, 1);
	else
		validateTextField($fields, "cp_user_password", NULL, FALSE, 1);
	validateTextField($fields, "cp_user_name", NULL, TRUE, 1);
	validateEmail($fields, "cp_user_email", TRUE, "SELECT * FROM cp_user WHERE cp_user_email='{$fields["cp_user_email"]}' AND cp_user_id != {$fields["cp_user_id"]}");
	return numErrors();
}

function showRecord($rec){
	global $row_num;
	$style = "row" . $row_num % 2;
	$row_num++;
	$link = "href='".this_php."?action=edit2&id={$rec["cp_user_id"]}'";
	$rec = getFields("", $rec, "SHOW");
	$tdl = "<td title='Edit ".table_1u."' style='$style'><a $link>";
	$tdr = "<td title='Edit ".table_1u."' style='$style' align='right'><a $link>";
	$tde = "</a></td>\n";
	echo "<tr class='$style'>$tdl{$rec["cp_user_email"]}$tde
		$tdl{$rec["cp_user_name"]}$tde
		<td><a class='edit' $link>Edit$tde
		<td style='$style'><a href='".this_php."?action=delete&id={$rec["cp_user_id"]}'><img title='Delete ".table_1u."' height='14' src='images/del.png' border='0'/>$tde</tr>
	";
}

function showRecords(){
	$result = dbRows(defaultTable, "%email");
	
	echo "<h2>Your ".table_1u." List</h2>";
	$num = dbNumRows($result);
	if($num == 0)
		echo "There are no ".table_n;
	else{
		if($num == 1)
			echo "There is 1 ".table_1l;
		else
			echo "There are $num ".table_n;
		echo "<table class='records' cellpadding='5' cellspacing='2'>";
		echo "<thead><tr><th>E-Mail Address</th><th>Name</th><th><br/></th><th><br/></th></tr></thead>";
		echo "<tbody>";
		while($rec = dbFetchArray($result)){
			showRecord($rec);
		}
		echo "</tbody></table>";
	}
	printButtonLinkSeperator(false);
	printButtonLink("Done", this_php."?action=cancel&mode=page");
	printButtonLink("Add New ".table_1u, this_php."?action=add");
	printButtonLink("Setup Groups", "access.php");
}

function printGroupsField($name, $fields, $field){
	echo "
		<tr >
			<td class='fieldLabel' class='name'><label class='formLabelText'>$name</label></td>
			<td>
	";
	$selections = getSelections($fields["$field"]);
	$rows = dbRows('cp_access','cp_access_desc');
	while ($row = dbFetchArray($rows)) {
		echo "<label><input type='checkbox' name='cp_user_groups[]' value='{$row["cp_access_id"]}'";
		if (isset($selections[$row["cp_access_id"]]))
			echo " checked";
		echo ">{$row["cp_access_desc"]}</label><br>\n";
	}
	echo "			</td>
		</tr>
	";
}

function showForm($id){
	global $error_messages;
	$mode = "Edit";
	if($id == 0){
		$rec[] = "";
		$mode = "Add";
	}
	else{
		$rec = getFields("", dbRow(defaultTable, $id), "SHOW");
		$rec["cp_user_password"] = "";
	}
	echo "<h2>$mode ".table_1u."</h2>";
	if(count($error_messages) > 0){
		echo "<div class='errorBox'><b>Errors:</b><ul>\n";
		foreach($error_messages as $msg)
			echo "<li>$msg</li>\n";
		echo "</ul></div>";
		$rec = getFields(defaultTable, $_POST, "SHOW");
	}
	echo "<form action='".this_php."' method=post>
			<input type='hidden' name='id' value='$id'/>
			<input type='hidden' name='cp_user_id' value='$id'/>
			<input type='hidden' name='action' value='{$mode}Record'/>
		<table class='form'><tbody>
	";
	printTextField("Email", $rec, "%email", 40, 100);
	printTextField("Name", $rec, "%name", 40, 100);
	printTextField("Password", $rec, "%password", 40, 100);
	if(checkAccess("Administrator"))
		printGroupsField("Groups", $rec, "cp_user_groups");
	printDateInfoField("Created", $rec, "%created");
	printDateInfoField("Last Login", $rec, "%last_login");
	echo "	</tbody>
		<tfoot>
			<tr class='bar'>
			<td colspan='2'>
				<input style='float: left' class='submitButton' type='submit' value='$mode Control Panel User'/>
				<input style='float: right' class='cancelButton' type='button' value='Cancel' onclick=\"location.href='".this_php."?action=cancel&mode=$mode'\">
			</td></tr>
		</tfoot></table>
		</form>
	";
	if($mode == "Edit"){
		printButtonLinkSeperator(TRUE, "Other Actions");
		printButtonLink("Delete This ".table_1u, this_php."?action=delete&id=$id");
		printButtonLink("Add a New ".table_1u, this_php."?action=add");
	}
}

function askDelete($id){
	$rec = getFields("", dbRow(defaultTable, $id), "SHOW");
	echo "Are you sure you want to delete <font class=highlighted>{$rec["cp_user_name"]}</font>?
		<br><br>
		<form action='".this_php."' method=post>
			<input type=hidden name=action value='confirmdelete'>
			<input type=hidden name=id value='$id'>
			<table><tr>
	";
	echo "		<td><input type=submit class='delete' value='Yes -- Delete'></td>
				<td><input type=button class='nodelete' value='No -- Cancel' onclick=\"location.href='".this_php."?action=cancel&mode=Delete'\"></td>
			</tr></table>
		</form>
	";
	printButtonLinkSeperator(true, "Other Actions");
	printButtonLink("Edit This ".table_1u, this_php."?action=edit&id=$id");
	printButtonLink("Add a New ".table_1u, this_php."?action=add");
}

function getGroups($groups){
	//array_dump($groups);
	$selections = $groups;
	if ($selections != "") {
		$sel = "";
		foreach($selections as $id) {
			$sel .= $id.",";
		}
		//echo $sel;
		return substr($sel, 0, -1);
	}
	else
		return "";
}

function addRecord(){
	global $form_action;
	$fields = getFields(defaultTable, $_POST, "SAVE");
	$fields["cp_user_groups"] = getGroups($fields["cp_user_groups"]);

	if(validateFields($fields,0) == 0){
		if ($fields["cp_user_password"] != "")
			$fields["cp_user_password"] = md5($_POST["cp_user_password"]);
		$fields["cp_user_created"] = time();
		dbPerform(defaultTable, $fields, 'insert');
		addMessage("Added the ".table_1l);
		$fields["cp_user_id"] = dbLastInsertID(defaultTable);
		location(this_php);
	}
	else
		$form_action = "add";
}

function editRecord($id){
	global $form_action, $announce;
	$fields["cp_user_id"] = $id;
	echo "ZZ<br>";
	$fields = getFields(defaultTable, $_POST, "SAVE");
	$fields["cp_user_groups"] = getGroups($fields["cp_user_groups"]);
	if(validateFields($fields,$id) == 0){
		if ($fields["cp_user_password"] != "")
			$fields["cp_user_password"] = md5($_POST["cp_user_password"]);
		else
			unset($fields["cp_user_password"]);
		dbPerform(defaultTable, $fields, 'update', "cp_user_id=$id");
		addMessage("The ".table_1l." was edited");
//		location(this_php);
	}
	else
		$form_action = "edit";
}

function deleteRecord($id){
	dbDeleteRecord(defaultTable, $id);
	addMessage("The ".table_1l." was deleted");
	location(this_php);
}

///////////////////////////////////////////////////////////////////////////////////////////////////////////
switch($_POST['action']){
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
		deleteRecord($form_id);
		break;
}

///////////////////////////////////////////////////////////////////////////////////////////////////////////
include "header.inc.php";
///////////////////////////////////////////////////////////////////////////////////////////////////////////

//	echo "Action: $form_action<br>";
showMessage();
switch($_GET['action']) {
	case "add":
		showForm(0);
		break;
	case "edit2":
		showForm($_GET['id']);
		break;
	case "edit":
		$form_id = $_SESSION["cp_user"]["cp_user_id"];
		showForm($_GET['id']);
		break;
	case "delete":
		askDelete($_GET['id']);
		break;
	default:
		showRecords();
}
///////////////////////////////////////////////////////////////////////////////////////////////////////////

include "footer.inc.php"; exit
?>
