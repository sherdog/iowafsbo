<?php

extract($_GET, EXTR_PREFIX_ALL, 'form_'); extract($_POST, EXTR_PREFIX_ALL, 'form_');
include "master.inc.php";
include "fckeditor/fckeditor.php";
$_SESSION["cp_active_tab"] = "staff.php";
define('defaultTable', 'page');

if (!isLoggedIn()){
	$_SESSION["loginreturn"] = this_php;
	location("login.php");
}

if(!checkAccess(this_php)){
	addMessage("No permission for working with the web pages");
	location("index.php");
	exit(0);
}

function cancelAction(){
	global $form_mode;
	switch($form_mode){
		case "page":
			addMessage("Thank you for working with the web pages");
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
	echo "<thead><tr><th>Name</th><th>Title</th><th><br/></th></tr></thead>";
}

function showRecord($rec){
	global $row_num;
	$class = "row" . $row_num % 2;
	$row_num++;
	$link = "href='".this_php."?action=edit&id={$rec["page_id"]}'";
	$rec = getFields("", $rec, "SHOW");
	$tdl = "<td title='Edit Web Page'><a $link>";
	$tdr = "<td title='Edit Web Page' align='right'><a $link>";
	$tde = "</a></td>\n";
//	$fdate = formatDate($rec["page_updated"]);
	echo "<tr class='$class'>$tdl{$rec["page_name"]}$tde$tdl{$rec["page_htmltitle"]}$tde
		<td><a class='edit' $link>Edit$tde
		</tr>
	";
}

function showRecords(){
	$result = dbRows(defaultTable, "%name", "", "WHERE page_editable=1");
	
	echo "<h2>Your Editable Web Page List</h2>";
	$num = dbNumRows($result);
	if($num == 0)
		echo "There are no web pages";
	else{
		if($num == 1)
			echo "There is 1 web page";
		else
			echo "There are $num web pages";
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
	echo "<h2>$mode Web Page</h2>";
	if(numErrors() > 0){
//		printErrors();
		$rec = getFields(defaultTable, $_POST, "SHOW");
	}
	echo "<form action='".this_php."' method='post'>
			<input type='hidden' name='id' value='$id'/>
			<input type='hidden' name='action' value='{$mode}Record'/>
		<table class='form' width='100%'><tbody>
	";
	printTextField("Title", $rec, "%htmltitle", 80, 128);
	printHTMLField("Contents", $rec, "%contents", "Basic");
	echo "	</tbody>
		<tfoot>
			<tr class='bar'>
			<td colspan='2'>
				<input style='float: left' class='submitButton' type='submit' value='$mode Web Page'/>
				<input style='float: right' class='cancelButton' type='button' value='Cancel' onclick=\"location.href='".this_php."?action=cancel&mode=$mode'\">
			</td></tr>
		</tfoot></table>
		</form>
	";
}

function validate($fields){
	validateTextField($fields, "page_htmltitle", NULL, TRUE, 1);
	validateTextField($fields, "page_contents", NULL, TRUE, 1);
	return numErrors();
}


function editRecord($id){
	global $form_action, $announce;
	$fields["page_id"] = $id;
	$fields = getFields(defaultTable, $_POST, "SAVE");
	if(validate($fields,$id) == 0){
//		$fields["page_expires"] = strtotime($fields["page_expires"]);
//		$fields["page_created"] = time();
//		$fields["page_updated"] = time();
		dbPerform(defaultTable, $fields, 'update', "page_id=$id");
		addMessage("The web page was edited");
		location(this_php);
	}
	else
		$form_action = "edit";
}


///////////////////////////////////////////////////////////////////////////////////////////////////////////
switch($_GET['action']){
	case "cancel":
		cancelAction();
		break;
	case "EditRecord":
		editRecord($_GET['id']);
		break;
}

///////////////////////////////////////////////////////////////////////////////////////////////////////////
include "header.inc.php";
///////////////////////////////////////////////////////////////////////////////////////////////////////////

//	echo "Action: $form_action<br>";
showMessage();
switch($_GET['action']){
	case "edit":
		showForm($_GET['id']);
		break;
	default:
		showRecords();
}
///////////////////////////////////////////////////////////////////////////////////////////////////////////
include "footer.inc.php" ?>
