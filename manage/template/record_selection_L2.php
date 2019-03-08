<?php

// 3. Search and replace "((table))_" with "{table name}_"
// 4. Search and replace "((L1_table))" with the 1st table selection.
// 5. Search and replace "((L1id))" with the 1st table id code.
// 4. Delete this list

extract($_GET, EXTR_PREFIX_ALL, 'form_'); extract($_POST, EXTR_PREFIX_ALL, 'form_');
include "controlpanel.inc.php";

define('defaultTable', '((table))');
define('defaultParams', "?referer=$form_referer&((L1id))=$form_((L1id))&((L2id))=$form_((L2id))");
if(isset($form_referer) && $form_referer != ""){
	if($form_referer == "products.php")
		define('return_php', $form_referer."?action=edit&((L1id))=$form_((L1id))&id=$form_((L2id))");
}
else
	define('return_php', this_php.defaultParams);

if(isset($form_((L1id))) && $form_((L1id)) > 0){
	$((L1_table)) = dbRow("((L1_table))", $form_((L1id)));
	if(isset($form_((L2id))) && $form_((L2id)) > 0){
		$((L2_table)) = dbRow("((L2_table))", $form_((L2id)));
		if(!isset($form_action))
			$form_action = "list2";
	}
	if(!isset($form_action))
		$form_action = "list1";
}


if(!checkAccess(this_php)){
	addMessage("No permission for working with ((table_n))");
	location("index.php");
	exit(0);
}

function printHeader(){
	echo "<table class='records' cellpadding='2' cellspacing='0'>";
	echo "<thead><tr><th>((sel_field_desc))</th><th><br/></th></tr></thead>";
	echo "<tbody>";
}

function showRecord($rec){
	global $row_num;
	$style = "row" . $row_num % 2;
	$row_num++;
	$link = "href='".this_php.defaultParams."&action=edit&id={$rec["((table))_id"]}'";
	$rec = getFields("", $rec, "SHOW");
	$tdl = "<td title='Edit ((table_1u))' style='$style'><a $link>";
	$tdr = "<td title='Edit ((table_1u))' align='right' style='$style'><a $link>";
	$tde = "</a></td>\n";
//	$ago = timeAgo($rec["((table))_date"]);
//	$price = formatPrice($rec["((table))_price"]);
	echo "<tr class='$style'>$tdl{$rec["((table))_((sel_field))"]}$tde$tde
		<td style='$style'><a href='".this_php.defaultParams."&action=delete&id={$rec["((table))_id"]}'><img title='Delete ((table_1u))' height='14' src='images/b_drop14.png' border='0'/>$tde</tr>
	";
}
function showRecords($((L1id)), $((L2id))){
	global $((L1_table)), $((L2_table));
	$((L1_table))_title = $((L1_table))["((L1_table))_title"];
	$((L2_table))_title = $((L2_table))["((L2_table))_title"];
	$result = dbRows(defaultTable, "%((sortby))", 'ASC', "WHERE ((table))_product=$pid");
	echo "<div class='h1'>The ((table_1u)) List for $product_title</div>";
	$num = dbNumRows($result);
	if($num == 0)
		echo "There are no ((table_n)) for $((L1_table))_title";
	else{
		if($num == 1)
			echo "There is 1 ((table_1l)) for $((L1_table))_title";
		else
			echo "There are $num ((table_n)) for $((L1_table))_title";
		printHeader();
		while($rec = dbFetchArray($result))
			showRecord($rec);
		echo "</tbody></table>";
	}
	printButtonLinkSeperator(false);
	printButtonLink("Done", this_php."?action=cancel&mode=page");
	printButtonLink("Add New ((table_1u))", this_php.defaultParams."&action=add");
}

function showForm($((L1id)), $((L2id)), $id){
	global $((L1_table)), $form_referer;
	$mode = "Edit";
	if($id == 0){
		$mode = "Add";
	}
	else{
		$rec = getFields("", dbRow(defaultTable, $id), "SHOW");
	}
	echo "<div class='h1'>$mode ((table_1u)) for {$product["product_title"]}</div>";
	if(numErrors() > 0){
		printErrors();
		$rec = getFields(defaultTable, $_POST, "SHOW");
	}
	echo "<form action='".this_php."' method='post'>
			<input type='hidden' name='id' value='$id'/>
			<input type='hidden' name='((L1id))' value='$((L1id))'/>
			<input type='hidden' name='((L2id))' value='$L2pid'/>
			<input type='hidden' name='referer' value='$form_referer'/>
			<input type='hidden' name='((table))_((L1_table))' value='$((L1id))'/>
			<input type='hidden' name='((table))_((L2_table))' value='$((L2id))'/>
			<input type='hidden' name='action' value='{$mode}Record'/>
		<table class='form'><tbody>";
	
	printTextField("Option Title", $rec, "%title", 40, 80);
	printTextField("Option Value", $rec, "%value", 40, 80);
	printTextField("Option Price Adjust", $rec, "%price", 10, 10);

	echo "	</thead><tfoot>
			<tr class='bar'><td><input type='button' value='Cancel' onclick=\"location.href='".this_php.defaultParams."&action=cancel&mode=$mode'\"></td>
			<td><input type='submit' value='$mode ((table_1u))'/></td></tr>
		</tfoot></table>
		</form>
	";
	if($mode == "Edit"){
		printButtonLinkSeperator(TRUE, "Other Actions");
		printButtonLink("Delete This ((table_1u))", this_php.defaultParams."&action=delete&id=$id");
		printButtonLink("Add a New ((table_1u))", this_php.defaultParams."&action=add");
	}
}

function askDelete($((L1id)), $((L2id)), $id){
	global $form_referer;
	$rec = getFields("", dbRow(defaultTable, $id), "SHOW");
	echo "Are you sure you want to delete <font class=highlighted>{$rec["((table))_title"]}</font>?
		<br><br>
		<form action='".this_php."' method=post>
			<input type=hidden name=action value='confirmdelete'>
			<input type=hidden name=id value='$id'>
			<input type=hidden name=((L1id)) value='$((L1id))'>
			<input type=hidden name=((L2id)) value='$((L2id))'>
			<input type='hidden' name='referer' value='$form_referer'/>
			<table><tr>
				<td><input type=submit class='delete' value='Yes -- Delete'></td>
				<td><input type=button class='nodelete' value='No -- Cancel' onclick=\"location.href='".this_php.defaultParams."&action=cancel&mode=Delete'\"></td>
			</tr></table>
		</form>
	";
	printButtonLinkSeperator(true, "Other Actions");
	printButtonLink("Edit This ((table_1u))", this_php.defaultParams."&action=edit&id=$id");
	printButtonLink("Add a New ((table_1u))", this_php.defaultParams."&action=add");
}

//if (!check_access("Administrator")
//	deny_access();
function cancelAction(){
	global $form_mode;
	switch($form_mode){
		case "page":
			addMessage("Thank you for working with ((table_n))");
			location("index.php");
			break;
		default:
			addMessage("Cancelled $form_mode");
			location(return_php);
			break;
	}
	exit();
}

function validateFields($field, $id){
	validateTextField($field, "((table))_title", NULL, TRUE, 3);
	validateTextField($field, "((table))_value", NULL, TRUE, 1);
	return numErrors();
}

function addRecord(){
	global $form_action;
	$fields = getFields(defaultTable, $_POST, "SAVE");
	if(validateFields($fields,0) == 0){
		dbPerform(defaultTable, $fields, 'insert');
		addMessage("Added the ((table_1l))");
//		$fields["((table))_id"] = dbLastInsertID(defaultTable);
		location(return_php);
	}
	else
		$form_action = "add";
}

function editRecord($id){
	global $form_action, $announce;
//	$fields["book_id"] = $id;
	$fields = getFields(defaultTable, $_POST, "SAVE");
	if(validateFields($fields,$id) == 0){
		dbPerform(defaultTable, $fields, 'update', "((table))_id=$id");
		addMessage("The ((table_1l)) was edited");
		location(return_php);
	}
	else
		$form_action = "edit";
}

function deleteRecord(){
	global $form_id;
	dbDeleteRecord(defaultTable, $form_id);
	addMessage("The ((table_1l)) was deleted");
	location(return_php);
}

///////////////////////////////////////////////////////////////////////////////////////////////////////////
switch($form_action){
	case "search":
		$searchFound = 0;
		if(isset($_GET["find"])){
			if(isNumber($_GET["find"])){
				$searchResults = dbRowResults(defaultTable, $_GET["find"]);
			}
			else
				$searchResults = dbQuery("SELECT * FROM `".defaultTable."` where ((table))_title LIKE '%{$_GET["find"]}%'");
			$searchFound = dbNumRows($searchResults);
			if($searchFound == 1){ // It found only 1, goto that product's page, otherwise process this later
				$row = dbFetchArray($searchResults);
				location(this_php."?action=edit&id=" . $row["book_id"]);
			}
		}
		break;
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
	case "search":
		finishSearch();
		break;
	case "add":
		$id = 0;
		showForm($form_((L1id)), $form_((L2id)), 0);
		break;
	case "edit":
		showForm($form_((L1id)), $form_((L2id)), $form_id);
		break;
	case "delete":
		askDelete($form_((L1id)), $form_((L2id)), $form_id);
		break;
	case "list2":
		showRecords($form_((L1id)), $form_((L2id)));
		break;
	case "list1":
		select((L2_recname))Records($form_((L1id)), this_php, "?action=list2&((L1id))=$form_((L1id))");
		break;
	default:
		selectRecords("((L1_table))", "SELECT * FROM ((L1_table))", "select((L1_table))SubRecord", "?action=list1");
}
///////////////////////////////////////////////////////////////////////////////////////////////////////////

include "footer.inc.php" ?>
