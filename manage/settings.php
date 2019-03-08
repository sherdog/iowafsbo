<?
include('master.inc.php');
extract($_GET, EXTR_PREFIX_ALL, 'form_'); extract($_POST, EXTR_PREFIX_ALL, 'form_');
include "master.inc.php";
define('defaultTable', 'settings');

$_SESSION["cp_active_tab"] = "settings.php";
if (!isLoggedIn()){
	$_SESSION["loginreturn"] = this_php;
	location("login.php");
}



if(!checkAccess(this_php)){
	addMessage("No permission for working with the listings");
	location("index.php");
	exit(0);
}
if (!isLoggedIn()){
	$_SESSION["loginreturn"] = this_php;
	location("login.php");
}

function addRecord(){
	global $form_action;
	$fields = getFields(defaultTable, $_POST, "SAVE");
//	if(validate($fields,0) == 0){
//		$fields["schedule_division_expires"] = strtotime($fields["schedule_division_expires"]);
//		$fields["schedule_division_created"] = time();
		dbPerform(defaultTable, $fields, 'insert');
		addMessage("Added the setting");
		//$fields["schedule_division_id"] = dbLastInsertID(defaultTable);
		location(this_php);
//}
	//else
		//$form_action = "add";
}

function editRecord($id){
	global $form_action, $announce;
	$fields[defaultTable."_id"] = $id;
	$fields = getFields(defaultTable, $_POST, "SAVE");
	$fields["settings_value"] = trim($fields["settings_value"]);
	//if(validate($fields,$id) == 0){
//		$fields["schedule_division_expires"] = strtotime($fields["schedule_division_expires"]);
//		$fields["schedule_division_created"] = time();
//		$fields["schedule_division_updated"] = time();
		dbPerform(defaultTable, $fields, 'update', defaultTable."_id=$id");
		addMessage("The setting was edited");
		location(this_php);
	//}
	//else
		//$form_action = "edit";
}


function showRecords($orderBy='listing_first_posting_date', $dir='DESC') {
	
	$results = dbQuery('SELECT * FROM '.defaultTable);
	$num = dbNumRows($results);
	//showSearch();//Show Search for MLS
	echo "<h2>Your Settings</h2>";
	
	if($num == 0)
		echo "No records returned";
		
		echo "<table class='listings' cellpadding='5' cellspacing='0' width='100%'>";
		
		printHeader($num);
		$row_num = 0;
		while($rec = dbFetchArray($results)){
			$class = "row" . $row_num % 2;
			$link = "href=\"".this_php."?action=edit&id=".$rec[defaultTable."_id"]."\"";
			$rec = getFields("", $rec, "SHOW");
			$tdl = "<td class='".$class."' title='Edit Setting'><a $link>";
			$tdl2 = "<td class='".$class."' title='Edit Setting'>";
			$tdr = "<td class='".$class."' title='Edit Setting'><a $link>";
			$tde = "</a></td>\n";
			$tde2 = "</td>\n";
			
			if(strlen($rec[defaultTable."_value"]) > 50) {
				$snippet = substr($rec[defaultTable.'_value'], 0, 50)."...";
			}else{
				if($rec[defaultTable.'_value'] == 'text')
					$snippet = $rec[defaultTable.'_value'];
			}
			
			
			echo "<tr>\n";
			
			echo $tdl.$rec[defaultTable."_title"].$tde;
			echo $tdl2.$snippet.$tde2;
		
			echo "<td class='".$class."'><a class=\"editLink\"".$link.">Edit </a>";
			// | <a href=\"".this_php."?action=delete&id=".$rec["listing_id"]."\"><img title='Delete' height='14' src='images/del.png' border='0'/>".$tde
			echo "</tr>\n";
			
		$row_num++;
		}
		echo "</table>";
	}
	
	
function printHeader($num){
	
	echo "<tr>\n";
	echo "<td class=\"listingColHeader\" valign=\"top\">Setting Title</td>\n";
	echo "<td class=\"listingColHeader\" valign=\"top\">Setting Value</td>\n";
	echo "<td class=\"listingColHeader\" valign=\"top\">&nbsp;</td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "<td colspan=\"5\" class=\"listingColHeaderSub\">Displaying $num of $num records</td>\n";
	echo "</tr>\n";
}

function showForm($id){
$mode = 'edit';

if($id == 0){
	$mode = 'add';
	$tablerow[] = '';
}else{
	$tablerow = getFields("", dbRow(defaultTable, $id), "SHOW");
}
//dump($tablerow);
	echo "<h2>".ucfirst($mode)." Settings</h2>";
	echo "<div class=\"alertBox\">".html_entity_decode($tablerow[defaultTable."_description"])."</div>\n";
	echo "<form action=\"".this_php."\" method=\"POST\">\n";
	if($mode == 'edit') echo "<input type=\"hidden\" name=\"id\" value=\"".$id."\">\n";
	echo "<input type='hidden' name='action' value='{$mode}Record'/>\n";
	echo "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"5\" id=\"listings\">\n";
	echo "<tr>\n";
	echo "<td class=\"fieldLabel\" valign=\"top\">".$tablerow[defaultTable."_title"]."<input type=\"hidden\" name=\"".$tablerow[defaultTable."_title"]."\" value=\"".$tablerow[defaultTable."_title"]."\"></td>\n";
	
	switch($tablerow[defaultTable."_type"]){
	case 'textarea':
		echo "<td><textarea name=name=\"".defaultTable."_value"."\" style=\"width:400px; height:200px;\">".$tablerow[defaultTable."_value"]."</textarea>\n";
	break;
	default:
		echo "<td><input type=\"text\" name=\"".defaultTable."_value"."\" value=\"".$tablerow[defaultTable."_value"]."\" /></td>\n";
	break;
	}
	
	
	
	echo "</tr>\n";
	
	
	
		
		echo "</td>\n";
		echo "</tr>\n";
//	}
	echo "</table>\n";
	if($mode == 'edit'){
		echo "<input type=\"Submit\" name=\"Edit\" value=\"Save Changes\" class=\"cpButton\">\n";
	}else{
		echo "<input type=\"Submit\" name=\"Add\" value=\"Add Setting\" class=\"cpButton\">\n";
	}
	echo "</form>\n";
}

switch($form_action) {
	case 'editRecord':
	//echo defaultTable;
		editRecord($form_id);
	break;
	case 'addRecord':
		addRecord();
	break;
}
include('header.inc.php');

///////////////////////////////////////////////////////////////////////////////////////////////////////////

//	echo "Action: $form_action<br>";
showMessage();

switch($form_action){
	case 'import':
		showImportForm();
	break;
	case 'add':
		showForm(0);
	break;
	case 'edit':
		showForm($_GET['id']);
	break;
	
	case 'upload':
		showUploadFrame();
	break;
	case 'manage':
	default:
		showRecords();
	break;
}


include('footer.inc.php');
?>
