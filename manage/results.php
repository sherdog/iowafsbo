<?php



extract($_GET, EXTR_PREFIX_ALL, 'form_'); extract($_POST, EXTR_PREFIX_ALL, 'form_');

include "master.inc.php";

include "fckeditor/fckeditor.php";

include "jscalendar/calendar.php";

$jscalendar = new DHTML_Calendar('jscalendar/', "en", 'calendar-win2k-2', false);

$_SESSION["cp_active_tab"] = "schedules.php";

define('defaultTable', 'result');



if (!isLoggedIn()){

	$_SESSION["loginreturn"] = this_php;

	location("login.php");

}



if(!checkAccess(this_php)){

	addMessage("No permission for working with the results");

	location("index.php");

	exit(0);

}



function cancelAction(){

	global $form_mode;

	switch($form_mode){

		case "page":

			addMessage("Thank you for working with the results");

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

	echo "<thead><tr><th>Date</th><th>Track</th><th>State</th><th><br/></th><th><br/></th></tr></thead>";

}



function showRecord($rec){

	global $row_num;

	$class = "row" . $row_num % 2;

	$row_num++;

	$link = "href='".this_php."?action=edit&id={$rec["result_id"]}'";

	$rec = getFields("", $rec, "SHOW");

	$tdl = "<td title='Edit Result'><a $link>";

	$tdr = "<td title='Edit Result' align='right'><a $link>";

	$tde = "</a></td>\n";

	$fdate = formatDate($rec["result_date"]);



	$fdate = formatDate($rec["result_date"]);

	echo "<tr class='$class'>$tdl$fdate$tde

		$tdl{$rec["track_title"]}$tde

		$tdl{$rec["track_state"]}$tde

		<td><a class='edit' $link>Edit$tde

		<td><a href='".this_php."?action=delete&id={$rec["result_id"]}'><img title='Delete Result' height='14' src='images/del.png' border='0'/>$tde</tr>

	";

}



function showRecords(){

//	$result = dbRows(defaultTable, "%date");

	global $form_page;

	$resultsPerPage = 30;

	$num = dbNumRecords("result");

	$pages = ceil($num / $resultsPerPage); 

	$recStart = $form_page * $resultsPerPage;



	$result = dbQuery("SELECT * FROM result, track WHERE result_track=track_id ORDER BY result_date DESC  LIMIT $recStart, $resultsPerPage");

	

	echo "<h2>Your Result List</h2>";

//	$num = dbNumRows($result);

	if($num == 0)

		echo "There are no results";

	else{

		if($num == 1)

			echo "There is 1 result";

		else

			echo "There are $num results";

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

	printButtonLink("Add New Result", this_php."?action=add");

}



function showForm($id){

	$mode = "Edit";

	if($id == 0){

		$rec[] = "";

		$mode = "Add";

		$rec["result_date"] = time();

	}

	else{

		$rec = getFields("", dbRow(defaultTable, $id), "SHOW");

	}

	echo "<h2>$mode Result</h2>";

	if(numErrors() > 0){

//		printErrors();

		$rec = getFields(defaultTable, $_POST, "SHOW");

	}

	echo "<form action='".this_php."' method='post'>

			<input type='hidden' name='id' value='$id'/>

			<input type='hidden' name='action' value='{$mode}Record'/>

		<table class='form' width='100%'><tbody>

	";

	if($mode == "Add")

		$rec["result_date"] = time();



	printTrackSelect("Track", $rec, "%track");

	printHTMLField("Top", $rec, "%top", "Basic");

	printHTMLField("Notes", $rec, "%notes", "Basic");

	printDateField("Date", $rec, "%date");

//	printTextAreaField("Contents", $rec, "%contents", 10, 80);

	echo "	</tbody>

		<tfoot>

			<tr class='bar'>

			<td colspan='2'>

				<input style='float: left' class='submitButton' type='submit' value='$mode Result'/>

				<input style='float: right' class='cancelButton' type='button' value='Cancel' onclick=\"location.href='".this_php."?action=cancel&mode=$mode'\">

			</td></tr>

		</tfoot></table>

		</form>

	";

	if($mode == "Edit"){

		printButtonLinkSeperator(TRUE, "Other Actions");

		printButtonLink("Delete This Result", this_php."?action=delete&id=$id");

		printButtonLink("Add a New Result", this_php."?action=add");

	}

}



function askDelete($id){

	$rec = getFields("", dbRow(defaultTable, $id), "SHOW");

	echo "<h2>Delete Result</h2>

		Are you sure you want to delete <font class=highlighted>{$rec["result_title"]}</font>?

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

	printButtonLink("Edit This Result", this_php."?action=edit&id=$id");

	printButtonLink("Add a New Result", this_php."?action=add");

}



function validate($fields){

	validateTextField($fields, "result_top", NULL, TRUE, 1);

	validateTextField($fields, "result_notes", NULL, FALSE, 1);

	return numErrors();

}



function addRecord(){

	global $form_action;

	$fields = getFields(defaultTable, $_POST, "SAVE");

	if(validate($fields,0) == 0){

//		$fields["result_expires"] = strtotime($fields["result_expires"]);

//		$fields["result_created"] = time();

		$z = addTrack();

		if($z > 0)

			$fields["content_track"] = $z;

		dbPerform(defaultTable, $fields, 'insert');

		addMessage("Added the result");

		$fields["result_id"] = dbLastInsertID(defaultTable);

		location(this_php);

	}

	else

		$form_action = "add";

}



function editRecord($id){

	global $form_action, $announce;

	$fields["result_id"] = $id;

	$fields = getFields(defaultTable, $_POST, "SAVE");
	$fields['result_date'] = strtotime($fields['result_date']);
	if(validate($fields,$id) == 0){

//		$fields["result_expires"] = strtotime($fields["result_expires"]);

//		$fields["result_created"] = time();

//		$fields["result_updated"] = time();

		$z = addTrack();

		if($z > 0)

			$fields["content_track"] = $z;
		
		
		dbPerform(defaultTable, $fields, 'update', "result_id=$id");

		addMessage("The result was edited");

		location(this_php);

	}

	else

		$form_action = "edit";

}



function deleteRecord(){

	global $form_id;

	dbDeleteRecord(defaultTable, $form_id);

	addMessage("The result was deleted");

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
