<?

extract($_GET, EXTR_PREFIX_ALL, 'form_'); extract($_POST, EXTR_PREFIX_ALL, 'form_');

include "master.inc.php";



if (!isLoggedIn()){

	$_SESSION["loginreturn"] = this_php;

	location("login.php");

}



if(!checkAccess(this_php)){

	addMessage("No permission for working with the results");

	location("index.php");

	exit(0);

}



function importResults($lineNum, $state, $track, $date, $top="", $notes=""){

	if($state == "State" || $track == "Track" || $track == "track" || $date == "Date"){

		addMessage("Line $lineNum was skipped because it appears to be a header");

		return false;

	}

	$states = getStates();

	$states = end(preg_grep("/$state/", $states));

	$states = explode("|", $states);

	$state = $states[0]; 

	$date = str_replace("  ", "", $date);

	$date = str_replace(".", "", $date);

	$ff = explode(" ", $date);

	$dd = explode("-", $ff[1]);

	if(!isset($dd[1]))

		$dd[1] = $dd[0];

	

	$date1 = strtotime("$ff[0] $dd[0] $year");

	$date2 = strtotime("$ff[0] $dd[1] $year");

	$result = dbQuery("SELECT track_id FROM track WHERE track_title=\"$track\" AND track_state='$state'");

	if(dbNumRows($result) == 0){	// Add Track

		dbQuery("INSERT INTO track SET track_title=\"$track\", track_state=\"$state\"");

		$track_id = dbLastInsertId("track");

	}

	else{

		$rec = dbFetchArray($result);

		$track_id = $rec["track_id"];

	}

	

	$result = dbQuery("SELECT result_id FROM result WHERE result_track=$track_id AND result_date=$date1");

	if(dbNumRows($result) == 0)	// Add Results

		dbQuery("INSERT INTO result SET result_track=$track_id, result_date=\"$date1\", result_top=\"$top\", result_notes=\"$notes\"");

}



function changeComma($str) { 
	$returnString = str_replace(',', '<comma>', $str);
	return $returnString;
}

function parseCSV(){

	$handle = fopen($_FILES["Filedata"]["tmp_name"], "r");

	while (($data = fgetcsv($handle, 8000, ";")) !== FALSE){

    	$row++;

    	importResults($row, $data[0], $data[1],$data[2], $data[3], $data[4]);

		if(numErrors() > 10){

			addMessage("Import stopped on line $row due to too many errors");

			fclose($handle);

			return;

		}

	}

	addMessage("Import done");

	fclose($handle);

}



function showForm(){

	global $content, $form_referer;

	$mode = "Edit";

	if($id == 0){

		$mode = "Add";

	}

	else{

		$rec = getFields("", dbRow(defaultTable, $id), "SHOW");

	}

	echo "<h2>Import Results from CSV file</h2>";

	if(numErrors() > 0){

		printErrors();

		$rec = getFields(defaultTable, $_POST, "SHOW");

	}

	echo "<form action='".this_php."' method='post' enctype='multipart/form-data'>

			<input type='hidden' name='action' value='parse'/>

		<table class='form'><tbody>";

	

	printFileUploadField("Results CSV", $rec, "Filedata");



	echo "	</thead>

		<tfoot>

			<tr class='bar'>

			<td colspan='2'>

				<input style='float: left' class='submitButton' type='submit' value='Import'/>

				<input style='float: right' class='cancelButton' type='button' value='Cancel' onclick=\"location.href='".this_php.defaultParams."&action=cancel&mode=$mode'\">

			</td></tr>

		</tfoot></table>

		</form>

	";

}



if($form_action == "parse")

	parseCSV();



///////////////////////////////////////////////////////////////////////////////////////////////////////////

include "header.inc.php";

///////////////////////////////////////////////////////////////////////////////////////////////////////////



//	echo "Action: $form_action<br>";

showMessage();



showForm();

include "footer.inc.php"

?>

