<?php



extract($_GET, EXTR_PREFIX_ALL, 'form_'); extract($_POST, EXTR_PREFIX_ALL, 'form_');

include "master.inc.php";

include "jscalendar/calendar.php";

$jscalendar = new DHTML_Calendar('jscalendar/', "en", 'calendar-win2k-2', false);



$_SESSION["cp_active_tab"] = "schedules.php";

define('defaultTable', 'schedule');



if (!isLoggedIn()){

	$_SESSION["loginreturn"] = this_php;

	location("login.php");

}



if(!checkAccess(this_php)){

	addMessage("No permission for working with the events");

	location("index.php");

	exit(0);

}



function cancelAction(){

	global $form_mode;

	switch($form_mode){

		case "page":

			addMessage("Thank you for working with the events");

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

	echo "<thead><tr><th>Begin Date</th><th>Title</th><th><br/></th><th><br/></th></tr></thead>";

}



function showRecord($rec){

	global $row_num;

	$class = "row" . $row_num % 2;

	$row_num++;

	$link = "href='".this_php."?action=edit&id={$rec["schedule_id"]}'";

	$rec = getFields("", $rec, "SHOW");

	$tdl = "<td title='Edit Event'><a $link>";

	$tdr = "<td title='Edit Event' align='right'><a $link>";

	$tde = "</a></td>\n";

	$fdate = formatDate($rec["schedule_start_date"]);

	//$track = getFields("", dbRow("track", $rec["schedule_track"]), "SHOW");

	//$region = getFields("", dbRow("content_category", $rec["schedule_region"]), "SHOW");

	$title = $rec["schedule_title"];

//	$track = getTrackName($rec["schedule_track"]);

	echo "<tr class='$class'>$tdl$fdate$tde

		$tdl$title $tde

		<td><a class='edit' $link>Edit$tde

		<td><a href='".this_php."?action=delete&id={$rec["schedule_id"]}'><img title='Delete Schedule' height='14' src='images/del.png' border='0'/>$tde</tr>

	";

}



function showRecords(){

	global $form_page, $form_filter1, $form_filter2, $form_filter3, $form_filter4;

	$resultsPerPage = 3000;

//	$result = dbRows(defaultTable, "%start_date", "DESC");

	

//	$result = dbQuery("SELECT COUNT(*) FROM schedule, content_category, schedule_division, track WHERE schedule_region=content_category_id AND schedule_division=schedule_division_id AND schedule_track=track_id ORDER BY schedule_start_date DESC LIMIT 0, $resultsPerPage");

	$num = dbNumRecords("schedule");

	$pages = ceil($num / $resultsPerPage);

	$recStart = $form_page * $resultsPerPage;

	

//	$WHERE="WHERE schedule_region=content_category_id AND schedule_division=schedule_division_id AND schedule_track=track_id";

	if($form_filter1){

		$day1 = strtotime($form_filter1);

		$day2 = strtotime("1 month", $day1);

		$WHERE .= " AND ((schedule_start_date >= $day1 AND schedule_start_date < $day2) OR (schedule_end_date >= $day1 AND schedule_end_date < $day2))"; 

	}

	if($form_filter2)

		$WHERE .= " AND schedule_track=$form_filter2";

	if($form_filter3)

		$WHERE .= " AND schedule_region=$form_filter3";

	if($form_filter4)

		$WHERE .= " AND schedule_division=$form_filter4";

		

//	$WHERE .= $WHERE2;

	if($WHERE != "")

		$WHERE = "WHERE ". substr($WHERE, 4);



//	echo $WHERE. "<br>";

//	echo $WHERE2. "<br>";



	$result = dbQuery("SELECT * FROM schedule $WHERE ORDER BY schedule_start_date");

	while($rec = dbFetchArray($result)){

		$date1=strftime("%b %Y", $rec["schedule_start_date"]);

		$date1a=strftime("%Y%m01", $rec["schedule_start_date"]);

		$dates[$date1] = $date1a;

		//$tracks[$rec["schedule_track"]] =1;

	//	$regions[$rec["schedule_region"]] =1;

	//	$series[$rec["schedule_division"]] =1;

	}

////////////// DATE

	echo "<div style='float: right'><form action='schedules.php'>";

	echo "Filter by:";



	echo "<select name='filter1' onchange='this.parentNode.submit()'>\n";

	echo "<option value='0'>All Months</option>\n";

	foreach($dates as $k=>$v){

		$sel = "";

		if($form_filter1 == $v)

			$sel = " selected";

		echo "<option$sel value='$v'>$k</option>\n";

	}

	echo "</select>\n";

///////////////////	

	echo "<a href='schedules.php'>Reset</a></form></div>";

	$result = dbQuery("SELECT * FROM schedule $WHERE ORDER BY schedule_start_date DESC LIMIT $recStart, $resultsPerPage");

	$num2 = dbNumRows($result); 

	echo "<h2>Events</h2>";

//	$num = dbNumRows($result);

	if($num == 0)

		echo "There are no events";

	else{

		if($num == 1)

			echo "There is 1 event";

		else

			echo "There are $num events";

			

		if($num != $num2)

			echo ", $num2 found from filter";

			

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

	printButtonLink("Add New Event", this_php."?action=add");

}



function showForm($id){

	$mode = "Edit";

	if($id == 0){

		$rec[] = "";

		$mode = "Add";

		$rec["schedule_start_date"] = time();

		$rec["schedule_end_date"] = time();

	}

	else{

		$rec = getFields("", dbRow(defaultTable, $id), "SHOW");

	}

	echo "<h2>$mode Event</h2>";

	if(numErrors() > 0){

//		printErrors();

		$rec = getFields(defaultTable, $_POST, "SHOW");

	}

	echo "<form action='".this_php."' method='post'>

			<input type='hidden' name='id' value='$id'/>

			<input type='hidden' name='action' value='{$mode}Record'/>

		<table class='form' width='100%'><tbody>

	";

//	printDBRecordSelect("Track", $rec, "%track", "SELECT track_id, concat(track_title, ' ', track_state) FROM track ORDER BY track_title", "track.php", "Quick Add Track", "add_track");

	//printTrackSelect("Track", $rec, "%track");

	//printDBRecordSelect("Region", $rec, "%region", "SELECT content_category_id, content_category_title FROM content_category ORDER BY content_category_order", "contentcategory.php");

	//printDBRecordSelect("Series", $rec, "%division", "SELECT schedule_division_id, schedule_division_title FROM schedule_division ORDER BY schedule_division_title", "scheduleDivision.php");

	//printTextField("Purse", $rec, "%purse", 80, 128);

	//printTextField("Winner", $rec, "%winner", 80, 128);
	
	printTextField("Title", $rec, "%title", 80, 255);

	printDateField("Start Date", $rec, "%start_date");

	printDateField("End Date", $rec, "%end_date");

    printTextAreaField("Contents", $rec, "%contents", 10, 80);

	echo "	</tbody>

		<tfoot>

			<tr class='bar'>

			<td colspan='2'>

				<input style='float: left' class='submitButton' type='submit' value='$mode Event'/>

				<input style='float: right' class='cancelButton' type='button' value='Cancel' onclick=\"location.href='".this_php."?action=cancel&mode=$mode'\">

			</td></tr>

		</tfoot></table>

		</form>

	";

	if($mode == "Edit"){

		printButtonLinkSeperator(TRUE, "Other Actions");

		printButtonLink("Delete This Event", this_php."?action=delete&id=$id");

		printButtonLink("Add a New Event", this_php."?action=add");

	}

}



function askDelete($id){

	$rec = getFields("", dbRow(defaultTable, $id), "SHOW");

	echo "<h2>Delete Event</h2>

		Are you sure you want to delete <font class=highlighted>{$rec["schedule_title"]}</font>?

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

	printButtonLink("Edit This Event", this_php."?action=edit&id=$id");

	printButtonLink("Add a New Event", this_php."?action=add");

}



function validate($fields){

//	validateTextField($fields, "schedule_purse", NULL, false, 1);

//	validateTextField($fields, "schedule_winner", NULL, false, 1);

	return numErrors();

}



function addRecord(){

	global $form_action;

	$fields = getFields(defaultTable, $_POST, "SAVE");

	if(validate($fields,0) == 0){

		$z = addTrack();

		if($z > 0)

			$fields["content_track"] = $z;

		$fields["schedule_start_date"] = strtotime($fields["schedule_start_date"]);

		$fields["schedule_end_date"] = strtotime($fields["schedule_end_date"]);

//		$fields["schedule_created"] = time();

		dbPerform(defaultTable, $fields, 'insert');

		addMessage("Added the event");

//		$fields["schedule_id"] = dbLastInsertID(defaultTable);

		location(this_php);

	}

	else

		$form_action = "add";

}



function editRecord($id){

	global $form_action, $announce;

	$fields["schedule_id"] = $id;

	$fields = getFields(defaultTable, $_POST, "SAVE");

	if(validate($fields,$id) == 0){

		$z = addTrack();

		if($z > 0)

			$fields["content_track"] = $z;

		$fields["schedule_start_date"] = strtotime($fields["schedule_start_date"]);

		$fields["schedule_end_date"] = strtotime($fields["schedule_end_date"]);

//		$fields["schedule_created"] = time();

//		$fields["schedule_updated"] = time();

		dbPerform(defaultTable, $fields, 'update', "schedule_id=$id");

		addMessage("The event was edited");

		location(this_php);

	}

	else

		$form_action = "edit";

}



function deleteRecord(){

	global $form_id;

	dbDeleteRecord(defaultTable, $form_id);

	addMessage("The event was deleted");

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

