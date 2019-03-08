<?php

extract($_GET, EXTR_PREFIX_ALL, 'form_'); extract($_POST, EXTR_PREFIX_ALL, 'form_');
include "../master.inc.php";

define('defaultTable', '');
define('defaultParams', "?fid=$form_fid");
define('table_1l', 'template');
define('table_1u', ucwords(table_1l));
define('table_n', table_1l.'s');

function printHeader(){
	echo "<table class='records' cellpadding='2' cellspacing='0'>";
	echo "<thead><tr><th>Title</th><th>Filename</th></thead>";
	echo "<tbody>";
}

function showTemplateRecord($file){
	global $row_num;
	$style = "row" . $row_num % 2;
	$row_num++;
	$title = str_replace(".php", "", $file);
	$title = ucwords(str_replace("_", " ", $title));
	$link = "href='".this_php."?action=edit&id=$file'";
	$tdl = "<td title='Select This ".table_1u."' style='$style'><a $link>";
	$tde = "</a></td>\n";
	echo "<tr class='$style'>$tdl$title$tde$tdl$file$tde</tr>";
}
function selectTemplate(){
	$dir = glob("*.php");
	echo "<div class='h1'>Select a program template</div>";
	$num = count($dir) - 2;
	if($num == 0)
		echo "There are no ".table_n;
	else{
		if($num == 1)
			echo "There is 1 ".table_1l;
		else
			echo "There are $num ".table_n;
		printHeader();
		foreach($dir as $file){
			if($file != "index.php" && $file != "login.php")
				showTemplateRecord($file);
		}
		echo "</tbody></table>";
	}
}

function showForm($id){
	global $zzTable1, $form_referer;
	echo "<div class='h1'>Fill in ".table_1u."</div>";
	if(numErrors() > 0){
		printErrors();
		$rec = getFields(defaultTable, $_POST, "SHOW");
	}
	echo "<form action='".this_php."' method='post'>
			<input type='hidden' name='id' value='$id'/>
			<input type='hidden' name='action' value='create'/>
		<table class='form'><tbody>";

	@include "${id}.fields";

	echo "	</thead><tfoot>
			<tr class='bar'><td><input type='button' value='Cancel' onclick=\"location.href='".this_php.defaultParams."&action=cancel&mode=$mode'\"></td>
			<td><input type='submit' value='$mode ".table_1u."'/></td></tr>
		</tfoot></table>
		</form>
	";
	if($mode == "Edit"){
		printButtonLinkSeperator(TRUE, "Other Actions");
		printButtonLink("Delete This ".table_1u, this_php.defaultParams."&action=delete&id=$id");
		printButtonLink("Add a New ".table_1u, this_php.defaultParams."&action=add");
	}
}

//if (!check_access("Administrator")
//	deny_access();
function cancelAction(){
	global $form_mode;
	switch($form_mode){
		case "page":
			addMessage("Thank you for working with ".table_n);
			location("index.php");
			break;
		default:
			addMessage("Cancelled $form_mode");
			location("index.php");
			break;
	}
	exit();
}

function getTableData(){
	$table = $_POST["((table))"];
	$results = dbQuery("DESCRIBE $table");
	while($fieldRec = dbFetchArray($results)){
		$field = $fieldRec["Field"];
		$a = explode("(", $fieldRec["Type"]);
		$type = $a[0];
		$a = explode(")", $a[1]);
		$a = explode(",", $a[0]);
		$size1 = $a[0];
		$size2 = $a[1];
		$rec[$field]["name"] = $field;
		$rec[$field]["type"] = $type;
		$rec[$field]["size1"] = $size1;
		$rec[$field]["size2"] = $size2;
		$field2 = ucwords($field);
		$form1 = "'$field2', \$rec, '$field'"; 
		if($type == "int"){
			if($field == "{$table}_id");
			else{
				$form .= "printNumberField($form1);\n";
				$verify .= "verifyNumber()\n";
			}
		}
		elseif($type == "varchar"){
			if(preg_match("/_email$/", $field)){		// Email field
				$form .= "printEmailField($form1, true);\n";
				$verify .= "verifyEmail()\n";
			}
			elseif(preg_match("/_phone$/", $field)){		// Phone number
				$form .= "printTextField($form1, true, 20, 20);\n";
				$verify .= "verifyPhoneNumber()\n";
			}
			elseif(preg_match("/_city$/", $field)){		// Phone number
				$form .= "printCityStateZipField('');\n";
				$verify .= "verifyPhoneNumber()\n";
			}
			elseif(preg_match("/_state$/", $field));
			elseif(preg_match("/_zip$/", $field));
			else{		// Generic
				$form .= "printTextField($form1, true, 40, $size1);\n";
				$verify .= "verifyPhoneNumber()\n";
			}
		}
	}
	echo "<pre>";
	echo $form;
	exit;
	return $rec;
}

function createProgram($id){
	header("Content-type: text/plain");
	$file = file_get_contents($id);
	foreach($_POST as $key=>$field){
		if($key[0] == "("){
			$rep_field[] = $key;
			$rep_to[] = $field;
		}
	}
	if(isset($_POST["((recdesc))"])){
			$rep_field[] = "((table_1l))";
			$rep_to[] = strtolower($_POST["((recdesc))"]);
			$rep_field[] = "((table_1u))";
			$rep_to[] = ucwords($_POST["((recdesc))"]);
			$rep_field[] = "((table_n))";
			$rep_to[] = strtolower($_POST["((recdesc))"]) . "s";
	}
	$file = str_replace($rep_field, $rep_to, $file);
	echo $file;
}

function deleteRecord(){
	global $form_id;
	dbDeleteRecord(defaultTable, $form_id);
	addMessage("The ".table_1l." was deleted");
	location(return_php);
}

///////////////////////////////////////////////////////////////////////////////////////////////////////////
switch($form_action){
	case "create":
//		getTableData();
		createProgram($form_id);
		exit;
		break;
	case "cancel":
		cancelAction();
		break;
}

///////////////////////////////////////////////////////////////////////////////////////////////////////////
echo "<html><head>
	<link href='../style.css' rel='stylesheet' type='text/css'>
	<style>
		body {
			padding: 10px;
			background-color: white;
		}
	</style>
	<title>Templates</title>
	</head>
	<body>
";
///////////////////////////////////////////////////////////////////////////////////////////////////////////

//	echo "Action: $form_action<br>";
showMessage();
switch($form_action) {
	case "edit":
		showForm($form_id);
		break;
	default:
		selectTemplate();
}
///////////////////////////////////////////////////////////////////////////////////////////////////////////

echo "</body></html>";
