<?
extract($_GET, EXTR_PREFIX_ALL, 'form_'); 
extract($_POST, EXTR_PREFIX_ALL, 'form_');

include "master.inc.php";

$_SESSION["cp_active_tab"] = "listings.php";
if (!isLoggedIn()){
	$_SESSION["loginreturn"] = this_php;
	location("login.php");
}

define('defaultTable', 'listing');

if(!checkAccess(this_php)){
	addMessage("No permission for working with the listings");
	location("listings.php");
	exit(0);
}


function askDelete($id){
	$rec = getFields("", dbRow(defaultTable, $id), "SHOW");
	echo "<h2>Delete Series</h2>
		Are you sure you want to delete <font class=highlighted>{$rec["schedule_division_title"]}</font>?
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
}


function showSearch() {
	echo "<table width=\"100%\" cellspacing=\"0\">\n";
	echo "<tr>\n";
	echo "<td align=\"right\">Search: </td>\n";
	echo "<td width=\"100\" nowrap><form method=\"GET\" action=\"".$_SERVER['PHP_SELF']."\"><input type=\"text\" name=\"keyword\" value=\"".$_GET['keyword']."\" style=\"width:170px;\"> <select name=\"type\" style=\"width:150px;\"><option>MLS #</option><option>Address</option></select></td>\n";
	echo "<tr>\n";
	echo "</table>\n";
}

function showRecords($orderBy='listing_first_posting_date', $dir='DESC') {
	global $form_page;
	$resultsPerPage = 30;

	$numResults = dbQuery('SELECT * FROM listing ORDER BY '.$orderBy. ' ' . $dir);
	$num1 = dbNumRows($numResults);
	$pages = ceil($num1 / $resultsPerPage); 
	$recStart = $form_page * $resultsPerPage;
	
	$results = dbQuery('SELECT * FROM listing ORDER BY '.$orderBy.' ' . $dir . ' LIMIT '.$recStart.', ' .$resultsPerPage);
	$num = dbNumRows($results);
	showSearch();//Show Search for MLS
	
	$pagetext = "Page $page of $pages pages";
	
	echo "<h2>Your Listings</h2>";
	
	if($num == 0)
		echo "No records returned";
		
		if($num1 > $resultsPerPage){
 			$page = $form_page + 1;
 			echo "<form action='".this_php."'>
	 		change page:
			 <select name='page' onchange='this.form.submit()'>";
 			for($a = 0,$b = 1;$a < $pages;$a++,$b++){
 				$sel = "";
 				if($a == $form_page)
 					$sel = " selected";
 				echo "<option$sel value='$a'>$b</option>";
 			}
 			echo "</select></form>";
 		}
		
		echo "<table class='listings' cellpadding='5' cellspacing='0' width='100%'>";
		
		printHeader($num, $num1, $resultsPerPage);
		$row_num = 0;
		while($rec = dbFetchArray($results)){
			$class = "row" . $row_num % 2;
			$link = "href='".this_php."?action=edit&id={$rec["listing_id"]}'";
			$rec = getFields("", $rec, "SHOW");
			$tdl = "<td class='".$class."' title='Edit Listing'><a $link>";
			$tdl2 = "<td class='".$class."' title='Edit Listing'>";
			$tdr = "<td class='".$class."' title='Edit Listing'><a $link>";
			$tde = "</a></td>\n";
			$tde2 = "</td>\n";
			
			$listingDate = formatDate($rec["listing_first_posting_date"]);
			
			echo "<tr>\n";
			
			echo $tdl.$rec["listing_number"].$tde;
			echo $tdl2.$listingDate.$tde2;
			echo $tdl2.$rec["listing_street"].$tde2;
			echo $tdl2.$rec['listing_city'].$tde2;
		
			echo "<td class='".$class."'><a class=\"editLink\"".$link.">Edit </a> | <a href=\"".this_php."?action=delete&id=".$rec["listing_id"]."\"><img title='Delete Listing' height='14' src='images/del.png' border='0'/>".$tde;
			echo "</tr>\n";
			
		$row_num++;
		}
		echo "</table>";
	}
	
	
function printHeader(){
	
	
	echo "<tr>\n";
	echo "<td class=\"listingColHeader\" valign=\"top\">MLS #</td>\n";
	echo "<td class=\"listingColHeader\" valign=\"top\">Posting Date</td>\n";
	echo "<td class=\"listingColHeader\" valign=\"top\">Street Address</td>\n";
	echo "<td class=\"listingColHeader\" valign=\"top\">Location</td>\n";
	echo "<td class=\"listingColHeader\" valign=\"top\">&nbsp;</td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "<td colspan=\"5\" class=\"listingColHeaderSub\">$pagetext</td>\n";
	echo "</tr>\n";
}

function showRecord() {
	global $row_num;
	
	
	
}

function importResults($data, $action='replace'){
 //dump($data);
	if($state == "State" || $track == "Track" || $track == "track" || $date == "Date"){
		addMessage("Line $lineNum was skipped because it appears to be a header");
		return false;
	}

		$fields['listing_id'] 				= NULL;
		$fields['listing_number'] 			= str_replace('"', '', $data[0]);
		$fields['listing_type'] 			= $data[1];
		$fields['listing_street']			= $data[2];
		$fields['listing_city']				= $data[3];
		$fields['listing_state']			= $data[4];
		$fields['listing_zip_code']			= $data[5];
		$fields['listing_elem_sch']			= $data[6];
		$fields['listing_mdljh_school']		= $data[7];
		$fields['listing_sr_high_school']	= $data[8];
		$fields['listing_fuel_type']		= $data[9];
		$fields['listing_region']			= $data[10];
		$fields['listing_sewer_type']		= $data[11];
		$fields['listing_water_type']		= $data[12];
		$fields['listing_ownership']		= $data[13];
		$fields['listing_house_style']		= $data[14];
		$fields['listing_roof']				= $data[15];
		$fields['listing_ac_type']			= $data[16];
		$fields['listing_basement']			= $data[17];
		$fields['listing_exterior']			= $data[18];
		$fields['listing_fireplaces']		= $data[19];
		$fields['listing_heat_type']		= $data[20];
		$fields['listing_trim_type']		= $data[21];
		$fields['listing_walk_out']			= $data[22];
		$fields['listing_water_softener']	= $data[23];
		$fields['listing_total_bdrms']		= $data[24];
		$fields['listing_total_baths']		= $data[25];
		$fields['listing_total_sq_ft']		= $data[26];
		$fields['listing_yr_built']			= $data[27];
		$fields['listing_garage_stalls']	= $data[28];
		$fields['listing_type']				= $data[29];
		$fields['listing_current_status']	= $data[30];
		$fields['listing_change_date']		= strtotime($data[31]);
		$fields['listing_price']			= $data[32];
		$fields['listing_selling_price']	= $data[33];
		$fields['listing_first_posting_date']= strtotime($data[34]);
		$fields['listing_sale_date']		= strtotime($data[35]);
		$fields['listing_days_on_market']	= $data[36];
		$fields['listing_first_name']		= $data[37];
		$fields['listing_last_name']		= $data[38];
		$fields['listing_office_phone']		= $data[39];
		$fields['listing_voice_mail']		= $data[40];
		$fields['listing_name']				= $data[41];
		$fields['listing_phone_number']		= $data[42];
		$fields['listing_first_name2']		= $data[43];
		$fields['listing_last_name2']		= $data[44];
		$fields['listing_office_phone2']	= $data[45];
		$fields['listing_voice_mail2']		= $data[46];
		$fields['listing_name2']			= $data[47];
		$fields['listing_phone_number2']	= $data[48];
		$fields['listing_mls_area']			= $data[49];
		$fields['listing_lot_size']			= $data[50];
		$fields['listing_total_taxes']		= $data[51];
		$fields['listing_tax_year']			= $data[52];
		$fields['listing_feature_1']		= $data[53];
		$fields['listing_feature_2']		= $data[54];
		$fields['listing_feature_3']		= $data[55];
		$fields['listing_feature_4']		= $data[56];
		$fields['listing_feature_5']		= $data[57];
		$fields['listing_feature_6']		= $data[58];
		$fields['listing_feature_7']		= $data[59];
		$fields['listing_feature_8']		= $data[60];
		$fields['listing_feature_9']		= $data[61];
		$fields['listing_feature_10']		= $data[62];
		$fields['listing_feature_11']		= $data[63];
		$fields['listing_feature_12']		= $data[64];
		$fields['listing_feature_13']		= $data[65];
		$fields['listing_feature_14']		= $data[66];
		$fields['listing_feature_15']		= $data[67];
		$fields['listing_comments']			= $data[68];
		
		dbPerform('listing', $fields, 'insert');
}

function addRecord(){
	global $form_action;
	$fields = getFields(defaultTable, $_POST, "SAVE");
//	if(validate($fields,0) == 0){
//		$fields["schedule_division_expires"] = strtotime($fields["schedule_division_expires"]);
//		$fields["schedule_division_created"] = time();
		dbPerform(defaultTable, $fields, 'insert');
		addMessage("Add the listing");
		$fields["schedule_division_id"] = dbLastInsertID(defaultTable);
		location(this_php);
//}
	//else
		//$form_action = "add";
}

function editRecord($id){
	global $form_action, $announce;
	$fields["listing_id"] = $id;
	$fields = getFields(defaultTable, $_POST, "SAVE");
	//if(validate($fields,$id) == 0){
//		$fields["schedule_division_expires"] = strtotime($fields["schedule_division_expires"]);
//		$fields["schedule_division_created"] = time();
//		$fields["schedule_division_updated"] = time();
		dbPerform(defaultTable, $fields, 'update', defaultTable."_id=$id");
		addMessage("The listing was edited");
		location(this_php);
	//}
	//else
		//$form_action = "edit";
}

function changeComma($str) { 
	$returnString = str_replace(',', '<comma>', $str);
	return $returnString;
}

function parseCSV(){
	$lines = file($_FILES['Filedata']['tmp_name']);
	//dump($lines);
	//echo count($lines);
	//ok so we are going delete all listings and reimport everything
		$del = dbQuery('DELETE FROM listing');
		if($del){
			//delete images!
			//run through upload dir remove all images from db.
			$imgResults = dbQuery('SELECT * FROM listing_images');
			
			while($img = dbFetchArray($imgResults)){
				if(file_exists(SITE_URL.UPLOAD_DIR.$img['listing_images_filename'])){
					unlink(SITE_URL.UPLOAD_DIR.$img['listing_images_filename']);
				}
			}
			
			$delImages = dbQuery('DELETE FROM listing_images');
		}
		
		
	
	
	if(count($lines) > 0 ){
		//$list = file($_FILES['file']['tmp_name']);
		$i = 0;
		foreach($lines as $line){
		//	echo $line."<br><br><br>";
			if($i > 0){
				$data = explode('", "', $line);
			//	dump($data);
				importResults($data);
				//print_r($data)."<BR><BR><BR>";
				
					if(numErrors() > 10){
						addMessage("Import stopped on line $i due to too many errors");
						fclose($handle);
						return;
					}
			
			}
			$i++;
		}
	
		addMessage("Imported " . $i . " records");
		header('location: listings.php?action=manage');
	
	} else {
		addMessage("Nothing to import");
	}
	
		
}

function showBulkImageUpload() {
	//we just going to show old school one
	echo "<form action=\"listings.php\" method=\"POST\" enctype=\"multipart/form-data\">\n";
	echo "<input type=\"hidden\" name=\"action\" value=\"bulkUpload\" />";
	echo "<table width=\"100%\" cellpadding=\"5\" cellspacing=\"0\" id=\"bulkupload\">\n";
	echo "<tr>\n";
	echo "<td class=\"fieldLabel\">Upload ZIP</td>\n";
	echo "<td><input type=\"file\" name=\"bulk\" /></td>\n";
	echo "</tr>\n";
	echo "</table>\n";
	echo "</form>\n";
}

function unzipFile($id) {
	//grab file info
	$results = dbQuery('SELECT * FROM zipfiles WHERE zipfiles_id = ' . $id);
	$zip = dbFetchArray($results);
	
	$file = MANAGE_PATH.UPLOAD_DIR_ZIP.$zip['zipfiles_filename'];
	if( unzip($file, SITE_PATH.UPLOAD_DIR, false, true) ){
		addMessage("Extracted images successfully from " . $zip['zipfiles_filename']);
		
	}
	
}

function showCurrentFiles() { 
	//get zipfiles 
	
	echo "<table width=\"500\">\n";
	echo "<tr>\n";
	echo "	<td class=\"listingColHeader\">Filename</td>\n";
	echo "	<td class=\"listingColHeader\">Date added</td>\n";
	echo "	<td class=\"listingColHeader\">&nbsp;</td>\n";
	echo "</tr>\n";
	$results = dbQuery("SELECT * FROM zipfiles ORDER BY zipfiles_timestamp DESC");
	$row_num = 1;
	while($row = dbFetchArray($results)) { 
		$class = "row" . $row_num % 2;
		echo "<tr>\n";
		echo "	<td class=\"".$class."\">".stripslashes($row['zipfiles_filename'])."</td>\n";
		echo "	<td class=\"".$class."\">".date('F j, Y, g:i a', $row['zipfiles_timestamp'])."</td>\n";
		echo "	<td class=\"".$class."\"><a href=\"".this_php."?action=unzipFile&id=".$row['zipfiles_id']."\">Unzip</a> | <a href=\"".this_php."?action=deleteZipFile&id=".$row['zipfiles_id']."\">Delete</a></td>\n";
		echo "</tr>\n";
	$row_num++;
	}
	
	echo "</table>\n";
	echo "<br>";
	echo "<hr noshade color=\"#F5F5F5\" />";
	echo "<br>";
}

function showUploadFrame($uploadFile = 'uploadmanager.php') {
	
    if($uploadFile != 'uploadmanager.php'){
		
		showCurrentFiles();
		
	} 
	echo "<table width=\"100%\" cellpadding=\"6\" cellspacing=\"0\">";
			echo "<tr><td class=\"text\">
			<strong>Once all files are upload a Finish button will appear</strong></td></tr>";
			//<td width=\"40%\">";
			//we just going to show old school one
			//	echo "<fieldset>\n";
			//	echo "<legend style=\"font-size:11px; font-weight:bold;\">Bulk Image Upload (.zip)</legend>\n";
			//	echo "<form action=\"listings.php\" method=\"POST\" enctype=\"multipart/form-data\">\n";
			//	echo "<input type=\"hidden\" name=\"action\" value=\"bulkUpload\" />";
			//	echo "<table width=\"100%\" cellpadding=\"5\" cellspacing=\"0\" id=\"bulkupload\" style=\"margin:10px\">\n";
			//	echo "<tr>\n";
			//	echo "<td class=\"fieldLabel\">Upload ZIP</td>\n";
			//	echo "<td><input type=\"file\" name=\"bulk\" /></td>\n";
			//	echo "</tr>\n";
			//	echo "<tr>\n";
			//	echo "<td colspan=\"2\"><input type=\"Submit\" name=\"Submit\" value=\"Update\"></td>\n";
			//	echo "</tr>\n";
			//	echo "</table>\n";
			//	echo "</form>\n";
			//echo "</td>
			//</tr>
			echo "<tr>
				<td ><iframe frameborder=\"0\" src=\"loader/".$uploadFile."?id=<?=$galleryID?>\" style=\"height:500px; width:100%;\"></iframe></td>
			</tr>
			<tr>
				<td>&nbsp;</td>
			</tr>
			</table>";

}

function showForm($id){
$mode = 'edit';

if($id == 0){
	$mode = 'add';
	$tablerow[] = '';
}else{
	$tablerow = getFields("", dbRow('listing', $id), "SHOW");
}
//dump($tablerow);
	echo "<h2>".ucfirst($mode)." Listings</h2>";
	echo "<form action=\"".this_php."\" method=\"POST\">\n";
	if($mode == 'edit') echo "<input type=\"hidden\" name=\"id\" value=\"".$id."\">\n";
	echo "<input type='hidden' name='action' value='{$mode}Record'/>\n";
	echo "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"5\" id=\"listings\">\n";
	foreach ( getListingFields() as $fieldName ) {
		echo "<tr>\n";
		echo "<td nowrap width=\"200\" valign=\"top\" class=\"fieldLabel\">".ucfirst(str_replace("_", " ", $fieldName))."</td>\n";
		echo "<td>";
		
		switch(getFieldType($fieldName)){
		case 'textarea':
			echo "<textarea name=\"".$fieldName['listing_fields_title']."\" style=\"width:400px; height:100px;\">".$tablerow[$fieldName]."</textarea>\n";
		break;
		case 'select':
		
		break;
		case 'text':
		default:
		if(stristr($fieldName, 'date')) {
			$tablerow[$fieldName] = date('m/d/y', $tablerow[$fieldName]);
		}
			echo "<input type=\"text\" name=\"".$fieldName."\" value=\"".$tablerow[$fieldName]."\" class=\"listingField\" style=\"width:200px;\">";
		break;
		}
		
		echo "</td>\n";
		echo "</tr>\n";
	}
	echo "</table>\n";
	if($mode == 'edit'){
		echo "<input type=\"Submit\" name=\"Edit\" value=\"Save Changes\" class=\"cpButton\">\n";
	}else{
		echo "<input type=\"Submit\" name=\"Add\" value=\"Add Listing\" class=\"cpButton\">\n";
	}
	echo "</form>\n";
}
function deleteZipForm($id){
	$rec = getFields("", dbRow("zipfiles", $id), "SHOW");
	echo "<h2>Delete Result</h2>

		Are you sure you want to delete <font class=highlighted>{$rec["zipfiles_filename"]}</font>?
		<br><br>
		<form action='".this_php."' method=post>
			<input type='hidden' name='action' value='deleteZipRecord'>
			<input type='hidden' name='id' value='$id'>
			<table><tr>
				<td><input type='submit' class='delete' value='Yes -- Delete'></td>
				<td><input type='button' class='nodelete' value='No -- Cancel' onclick=\"location.href='".this_php."?action=cancel&mode=Delete'\"></td>
			</tr></table>
		</form>
	";
}

function deleteForm($id){

	$rec = getFields("", dbRow(defaultTable, $id), "SHOW");
	echo "<h2>Delete Result</h2>

		Are you sure you want to delete <font class=highlighted>{$rec["listing_title"]}</font>?
		<br><br>
		<form action='".this_php."' method=post>
			<input type='hidden' name='action' value='deleteRecord'>
			<input type='hidden' name='id' value='$id'>
			<table><tr>
				<td><input type='submit' class='delete' value='Yes -- Delete'></td>
				<td><input type='button' class='nodelete' value='No -- Cancel' onclick=\"location.href='".this_php."?action=cancel&mode=Delete'\"></td>
			</tr></table>
		</form>
	";

}


function deleteRecord(){
	
	global $form_id;
	dbDeleteRecord(defaultTable, $_GET['id']);
	addMessage("The record was deleted;");
	location(this_php);

}

function deleteZipRecord(){
	
	
	//get filename
	$results = dbQuery('SELECT * FROM zipfiles WHERE zipfiles_id = ' . $_GET['id']);
	$zip = dbFetchArray($results);
	
	@unlink(MANAGE_PATH.UPLOAD_ZIP_DIR.$zip['zipfiles_filename']);
	
	dbDeleteRecord("zipfiles", $form_id);
	addMessage("The record was deleted;");
	
	location(this_php);
}

function showImportForm(){

	global $content, $form_referer;
	$mode = "Edit";
	if($id == 0){
		$mode = "Add";
	}

	else{
		$rec = getFields("", dbRow(defaultTable, $id), "SHOW");
	}

	echo "<h2>Import listings</h2>";
	if(numErrors() > 0){
		printErrors();
		$rec = getFields(defaultTable, $_POST, "SHOW");
	}

	echo "<form action='".this_php."' method='post' enctype='multipart/form-data'>
			<input type='hidden' name='action' value='parse'/>
		<table class='form'><tbody>";
			printFileUploadField("Results CSV", $rec, "Filedata");
			echo "<tr>\n";
			echo "<td valign=\"top\" class=\"fieldLabel\">Import Option</td>\n";
			echo "<td valign=\"top\">\n";
			echo "<table width=\"100%\" cellpadding=\"5\" cellspacing=\"5\">\n";
			echo "<tr><td><label><input type=\"radio\" name=\"importaction\" value=\"replace\" checked>Delete existing & relace</label></td></tr>\n";
			echo "<tr><td><label><input type=\"radio\" name=\"importaction\" value=\"replace\">Append</label></td></tr>\n";
			echo "<tr><td><label><input type=\"radio\" name=\"importaction\" value=\"replace\">Update</label></td></tr>\n";
			//echo "<td><label>Delete existing & relace<input type=\"radio\" name=\"importaction\" value=\"replace\"></label>\n";
			echo "</table>\n";
			echo "</td>\n";
			echo "</tr>\n";
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

switch($_GET['action']) {
	case 'parse':
		parseCSV();
	break;
	case 'editRecord':
	//echo defaultTable;
		editRecord($form_id);
	break;
	case 'addRecord':
		addRecord();
	break;
	case 'bulkUpload':
		unzipFile();
	break;
	case 'deleteRecord':
		deleteRecord($form_id);
	break;
	case 'deleteZipFile':
		deleteZipRecord();
	break;
}
///////////////////////////////////////////////////////////////////////////////////////////////////////////

include "header.inc.php";

///////////////////////////////////////////////////////////////////////////////////////////////////////////

//	echo "Action: $form_action<br>";
showMessage();

switch($_GET['action']){
	case 'import':
		showImportForm();
	break;
	case 'add':
		showForm(0);
	break;
	case 'edit':
		showForm($_GET['id']);
	break;
	case 'bulkupload':
	 showBulkImageUpload();
	break;
	case 'bulkZipUpload':
	 showUploadFrame('zipUploadManager.php');
	break;
	case 'upload':
		showUploadFrame();
	break;
	case 'delete':
	 deleteForm($_GET['id']);
	break;
	case 'unzipFile':
		unzipFile($_GET['id']);
	break;
	case 'deleteZipFile':
		deleteZipForm($_GET['id']);
	break;
	case 'manage':
	default:
		showRecords();
	break;
}



include "footer.inc.php"

?>

