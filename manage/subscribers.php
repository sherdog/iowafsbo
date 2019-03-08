<?php

extract($_GET, EXTR_PREFIX_ALL, 'form_'); extract($_POST, EXTR_PREFIX_ALL, 'form_');
include "master.inc.php";
include "jscalendar/calendar.php";
$jscalendar = new DHTML_Calendar('jscalendar/', "en", 'calendar-win2k-2', false);
$_SESSION["cp_active_tab"] = "subscribers.php";
define('defaultTable', 'subscriber');

if($form_action == "add")
	$selmenu = "subscribers.php?action=add";

if (!isLoggedIn()){
	$_SESSION["loginreturn"] = this_php;
	location("login.php");
}

if(!checkAccess(this_php)){
	addMessage("No permission for working with the website subscribers");
	location("index.php");
	exit(0);
}

function cancelAction(){
	global $form_mode;
	switch($form_mode){
		case "page":
			addMessage("Thank you for working with the website subscribers");
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
	echo "<thead><tr><th>Last Name</th><th>First Name</th><th>Email</th><th><br/></th><th><br/></th></tr></thead>";
}

function showRecord($rec){
	global $row_num;
	$class = "row" . $row_num % 2;
	$row_num++;
	$link = "href='".this_php."?action=edit&id={$rec["subscriber_id"]}'";
	$rec = getFields("", $rec, "SHOW");
	$tdl = "<td title='Edit Website Subscriber'><a $link>";
	$tdr = "<td title='Edit Website Subscriber' align='right'><a $link>";
	$tde = "</a></td>\n";
//	$fdate = formatDate($rec["subscriber_updated"]);
	echo "<tr class='$class'>$tdl{$rec["subscriber_last_name"]}$tde$tdl{$rec["subscriber_first_name"]}$tde$tdl{$rec["subscriber_email"]}$tde<td><a class='edit' $link>Edit$tde<td><a href='".this_php."?action=delete&id={$rec["subscriber_id"]}'><img title='Delete Website Subscriber' height='14' src='images/del.png' border='0'/>$tde</tr>";
}

function showRecords(){
	$result = dbRows(defaultTable, "%last_name");
	
	echo "<h2>Your Website Subscriber List</h2>";
	$num = dbNumRows($result);
	if($num == 0)
		echo "There are no website subscribers";
	else{
		if($num == 1)
			echo "There is 1 website subscriber";
		else
			echo "There are $num website subscribers";
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
	printButtonLink("Add New Website Subscriber", this_php."?action=add");
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
	echo "<h2>$mode Website Subscriber</h2>";
	if(numErrors() > 0){
		$rec = getFields(defaultTable, $_POST, "SHOW");
	}
	echo "<form action='".this_php."' method='post'>
			<input type='hidden' name='id' value='$id'/>
			<input type='hidden' name='action' value='{$mode}Record'/>
		<table class='form' width='100%'><tbody>
	";
	printTextField("First Name", $rec, "%first_name", 80, 80);
	printTextField("Last Name", $rec, "%last_name", 80, 80);
	printTextField("Email", $rec, "%email", 80, 255);
	printTextField("Phone", $rec, "%phone", 30, 30);
	printTextField("Address 1", $rec, "%address", 80, 128);
	printTextField("Address 2", $rec, "%address2", 80, 128);
	printCityStateZip($rec, "");
	printNumberField("Months", $rec, "%years");
	printDateTimeField("Expires", $rec, "%expires");
	printDateInfoField("Created", $rec, "%created");

	printTableSeperator("Payment");
	printSelectionField("Payment Type", $rec, "%payment_card", "Visa|Visa^Mastercard|Mastercard^Discover|Discover^American Express|American Express^Cash|Cash^Complimentary|Complimentary^Paypal|Paypal");
	printTextField("Payment Amount", $rec, "%payment_amount", 10, 10);
//	printTextField("Payment Type", $rec, "%payment_card", 20, 20);
	printTextField("Name on Card", $rec, "%payment_name", 80, 80);
	printTextField("Card Number", $rec, "%payment_no", 20, 20);
	printTextField("Card Expires", $rec, "%payment_expire", 6, 6);
	printTextField("CVC", $rec, "%payment_cvc", 4, 4);
	echo "	</tbody>
		<tfoot>
			<tr class='bar'>
			<td colspan='2'>
				<input style='float: left' class='submitButton' type='submit' value='$mode Website Subscriber'/>
				<input style='float: right' class='cancelButton' type='button' value='Cancel' onclick=\"location.href='".this_php."?action=cancel&mode=$mode'\">
			</td></tr>
		</tfoot></table>
		</form>
	";
	if($mode == "Edit"){
		printButtonLinkSeperator(TRUE, "Other Actions");
		printButtonLink("Delete This Website Subscriber", this_php."?action=delete&id=$id");
		printButtonLink("Add a New Website Subscriber", this_php."?action=add");
	}
}

function askDelete($id){
	$rec = getFields("", dbRow(defaultTable, $id), "SHOW");
	echo "<h2>Delete Website Subscriber</h2>
		Are you sure you want to delete <font class=highlighted>{$rec["subscriber_first_name"]} {$rec["subscriber_last_name"]}</font>?
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
	printButtonLink("Edit This Website Subscriber", this_php."?action=edit&id=$id");
	printButtonLink("Add a New Website Subscriber", this_php."?action=add");
}

function validate($fields){
	global $form_id;
	validatePhoneNumber($fields, "order_phone", false);
	validateTextField($fields, "subscriber_first_name", null, true, 1);
	validateTextField($fields, "subscriber_last_name", null, true, 1);
	validateTextField($fields, "subscriber_address", "address", true, 1);
	validateTextField($fields, "subscriber_address2", null, false, 1);
	validateTextField($fields, "subscriber_city", null, true, 1);
	validatePhoneNumber($fields, "subscriber_phone");
	validateZipCode($fields, "subscriber_zip", true);
	validateEmail($fields, "subscriber_email", true, "SELECT * FROM subscriber WHERE subscriber_id!=$form_id AND subscriber_email='{$fields["subscriber_email"]}'");
	validateTextField($fields, "subscriber_payment_name", "name on card", true, 1);
	validateCreditCard($fields, "subscriber_payment_no", "subscriber_payment_card");
	validateTextField($fields, "subscriber_payment_cvc", null, true, 1);
	return numErrors();
}

function addRecord(){
	global $form_action;
	$fields = getFields(defaultTable, $_POST, "SAVE");
	if(validate($fields,0) == 0){
		$fields["subscriber_expires"] = strtotime($fields["subscriber_expires"]);
		$fields["subscriber_created"] = time();
		dbPerform(defaultTable, $fields, 'insert');
		addMessage("Added the website subscriber");
		$fields["subscriber_id"] = dbLastInsertID(defaultTable);
		location(this_php);
	}
	else
		$form_action = "add";
}

function editRecord($id){
	global $form_action, $announce;
	$fields["subscriber_id"] = $id;
	$fields = getFields(defaultTable, $_POST, "SAVE");
	if(validate($fields,$id) == 0){
		$fields["subscriber_expires"] = strtotime($fields["subscriber_expires"]);
		$fields["subscriber_created"] = time();
//		$fields["subscriber_updated"] = time();
		dbPerform(defaultTable, $fields, 'update', "subscriber_id=$id");
		addMessage("The website subscriber was edited");
		location(this_php);
	}
	else
		$form_action = "edit";
}

function deleteRecord(){
	global $form_id;
	dbDeleteRecord(defaultTable, $form_id);
	addMessage("The website subscriber was deleted");
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
