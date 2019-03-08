<?php


include "functions.validate.inc.php";
include "functions.printform.inc.php";

function dump($arr, $header=true){
	if(!isset($arr))
		echo "Not set<br>";
	switch(gettype($arr)){
		case "array":
			if($arr == false)
				echo "Array is empty<br>";
			else{
				echo "<table border='1'>\n";
				if($header){
					echo "<thead><tr><th>Name</th><th>Type</th><th>Len</th><th>Value</th>\n";
					if(count($arr) > 5)
						echo "<th style='width:16px'></th></tr></thead><tbody style='height: 100px;overflow:auto'>\n";
					else
						echo "</tr></thead><tbody>\n";
				}
				else
					echo "<tbody>\n";
		
				foreach($arr as $f=>$v){
					echo "<tr valign='top'><td>$f</td>";
					// Print Type
					echo "<td>".gettype($v)."</td>";
					// Print Size
					switch(gettype($v)){
						case "boolean":
							echo "<td align='right'>1</td>";break;
						case "string":
							echo "<td align='right'>".strlen($v)."</td>";break;
						case "array":
							echo "<td align='right'>".count($v)."</td>";break;
						default:
							echo "<td><br></td>";break;
					}
					// Print Value
					switch(gettype($v)){
						case "boolean":
							echo "<td>";
							if($v == false)
								echo "false";
							else
								echo "true";
							echo "</td>";
							break;
						case "array":
							if($f === "GLOBALS")
								echo "<td>$v (loop)</td></tr>\n";
							else{
								echo "<td>";
								dump($v, false);
								echo "</td></tr>\n";
							}
							break;
						default:
							echo "<td>$v</td></tr>\n";break;
					}
				}
				echo "</tbody></table>";
			}
			break;
		default:
			echo "$arr";break;
	}
}

function getUserLoggedIn(){
	return dbRow("cp_user", $_SESSION["userid"]);
}

function isLoggedIn() { 
	return isset($_SESSION["cp_user"]);
}

function checkAccess($type) {
//	dump($_SESSION["cp_access"]);
//	echo $type;
	return isset($_SESSION["cp_access"][$type]) || isset($_SESSION["cp_access"]["Administrator"]);
}

function denyAccess() {
	echo "You do not have access to this page. <a href='login.php'>click here to login</a>";
	exit;
}

function is_client_logged_in() {
	return isset($_SESSION["clientid"]) && $_SESSION["clientid"]>0;
}

//added 3/14/2007 for real racing wheels JB
function is_customer_logged_in() {
	return isset($_SESSION["customerData"]["customerID"]) && $_SESSION["customerData"]["customerID"]>0;
}

// function getUser($email, $password) {
// 	$query = "SELECT * FROM cp_user WHERE cp_user_email='$email' AND cp_user_password='$password'";
// 	echo "done";
// 	$results = dbQuery($query);
// 
// 	if (dbNumRows($results) >= 1) {
// 		$row = dbFetchArray($results);
// //		return $row["user_id"];
// 		return $row;
// 	}
// 	else
// 		return FALSE;
// }

function getSelections($selections) {
	$rows = explode(",",$selections);
	$return = Array();
	if ($selections != "") {
		foreach($rows as $id) {
			$return[$id] = $id;
		}
	}
	return $return;
}

// Prints a partial credit card number 
function printCreditCardNumber($num){
	$len = strlen($num);
	echo "XXXX XXXX XXXX " . substr($num, $len - 4);
}

// Original PHP code by Chirp Internet: www.chirp.com.au
function truncateString($string, $limit, $break=".", $pad="...") {
	// return with no change if string is shorter than $limit
 	if(strlen($string) <= $limit)
	 	return $string;
 	
	// is $break present between $limit and the end of the string?
	if(false !== ($breakpoint = strpos($string, $break, $limit))) {
		if($breakpoint < strlen($string) - 1)
			$string = substr($string, 0, $breakpoint) . $pad;
	}
	return $string;
}
	 
/*function uploadFile($formfile,$filename="") {
	if ($filename == "")
		$filename = $formfile['name'];
	if(is_writable(SITE_PATH.UPLOAD_DIR)){
		move_uploaded_file($formfile['tmp_name'], SITE_PATH.UPLOAD_DIR.$filename);
	}
	else{//attempt to chmod it
		@chmod(SITE_PATH.UPLOAD_DIR.$filename, 0777);
		move_uploaded_file($formfile['tmp_name'], UPLOAD_DIR.$filename);
	}
} */

function isNumber($text){
	if (preg_match("/^([0-9]+)$/" , trim($text)))
		return true;
	elseif (preg_match("/^-([0-9]+)$/" , trim($text)))
		return true;
	else
		return false;
}

function moneyFormat($num){
	return number_format($num, 2, ".", ",");
}

/*function uploadBanner($formfile,$filename="") {
	if ($filename == "")
		$filename = $formfile['name'];
	move_uploaded_file($formfile['tmp_name'], SITE_PATH.BANNER_DIR.$filename);
}*/
		
// function upload_xml($formfile,$filename="") {
// 	if ($filename=="")
// 		$filename = $formfile['name'];
// 	move_uploaded_file($formfile['tmp_name'], SITE_PATH.XML_DIR.$filename);
// }

/*function upload_xmlpic($formfile,$filename="") {
	if ($filename=="")
		$filename = $formfile['name'];
	move_uploaded_file($formfile['tmp_name'], SITE_PATH.XML_DIR.$filename);
	make_thumbnail($filename,XML_DIR,175);
	unlink(XML_DIR.$filename);
}*/
		
// function get_filetype($name) {
// 	return substr($name, strrpos($name,".")+1);
// }

// returns a string of random gibberish of a certain length
function randomCode($length=10){
   $retVal = "";
   while(strlen($retVal) < $length){
       $nextChar = mt_rand(0, 61); // 10 digits + 26 uppercase + 26 lowercase = 62 chars
       if(($nextChar >=10) && ($nextChar < 36)){ // uppercase letters
           $nextChar -= 10; // bases the number at 0 instead of 10
           $nextChar = chr($nextChar + 65); // ord('A') == 65
       }
       else if($nextChar >= 36){ // lowercase letters
           $nextChar -= 36; // bases the number at 0 instead of 36
           $nextChar = chr($nextChar + 97); // ord('a') == 97
       }
       else { // 0-9
           $nextChar = chr($nextChar + 48); // ord('0') == 48
       }
       $retVal .= $nextChar;
	}
	return $retVal;
}

function parse_text($text) {
	$newtext = str_replace("\n", "<br>\n", $text);
	return $newtext;
}

function thumb($filename, $postfix="") {
	return get_thumbnail_filename($filename, $postfix);
}

function get_thumbnail_filename($filename, $postfix="") {
	return substr($filename, 0, strrpos($filename, ".")).".thumb$postfix.jpg";
}



// returns the filename minus any letters that may cause problems later
function fixName($name) {
	$name = strtolower($name);
	$name = str_replace("'","",$name);
	$name = str_replace("\"","",$name);
	$name = str_replace(" ","_",$name);
	$name = str_replace("%20","",$name);
	return $name;
}

function upload_file($formfile,$filename="") {

	if ($filename=="")
		$filename = $formfile['name'];
	move_uploaded_file($formfile, SITE_PATH.UPLOAD_DIR.$filename);

}


function make_thumbnail($filename, $location, $width="", $height="", $postfix="") {

			$filetype = substr($filename, strrpos($filename, ".")+1);

			switch($filetype) {
				case "jpg":
				case "jpeg":
					$image = imagecreatefromjpeg($location.$filename);
					break;
				case "gif":
					$image = imagecreatefromgif($location.$filename);
					break;
				case "png":
					$image = imagecreatefrompng($location.$filename);
					break;
				default:
					return FALSE;
			}
			list($imwidth, $imheight) = getimagesize($location.$filename);

			if ($width=="" && $height=="") {
				$newwidth=$imwidth;
				$newheight=$imheight;
			}
			else if ($width!="" && $height=="") {
				$newwidth=$width;
				$newheight=$imheight*($width/$imwidth);
			}
			else if ($width=="" && $height!="") {
				$newwidth=$imwidth*($height/$imheight);
				$newheight=$height;
			}
			else {
				$newwidth=$width;
				$newheight=$height;
			}

			$thumbimage = imagecreatetruecolor($newwidth, $newheight);
			imagecopyresampled($thumbimage, $image, 0, 0, 0, 0, $newwidth, $newheight, $imwidth, $imheight);

			imagejpeg($thumbimage, $location.get_thumbnail_filename($filename, $postfix));

			return TRUE;
		}
// returns the date, but formatted
function formatDate($timestamp) {
	if (date("H:i:s",$timestamp)=="00:00:00")
		return date("m/d/Y",$timestamp);
	else
		return date("m/d/Y, h:i a",$timestamp);
}

function fixPrice($price) {
	$nprice = str_replace(",","",$price);
	$nprice = number_format($nprice, 2);
	return $nprice;
}

function formatPrice($price){
	return "$" . $price;
}

function numPrice($price) {
	$nprice = str_replace(",","",$price);
	return $nprice;
}

// function fixPost($name) {
// 	$new = str_replace(" ","_",$name);
// 	return $new;
// }

// function php4_file_put_contents($file,$data) {
// 	$fl = fopen($file,"w");
// 	fwrite($fl,$data);
// 	fclose($fl);
// }
// 
// function php4_file_get_contents($filename) {
// 	$data = "";
// 	$fl = fopen($filename,"r");
// 	if(!$f1){
// 		while($chunk = fread($f1, 1024))
// 			$data .= $chunk;
// 		fclose($fl);
// 	}
// 	return $data;
// }

//from php.net  3/14/07
/*function html2txt($document){
$search = array('@<script[^>]*?'.'>.*?</script>@si',  // Strip out javascript $search = array('@<script[^>]*<?php
               '@<[\/\!]*?[^<>]*?'.'>@si',            // Strip out HTML tags
               '@<style[^>]*?'.'>.*?</style>@siU',    // Strip style tags properly
               '@<![\s\S]*?--[ \t\n\r]*>@'        // Strip multi-line comments including CDATA
);
$text = preg_replace($search, '', $document);
return $text;
}*/	

function addMessage($msg){
	if(session_id() == "")
	    return;
	if(!isset($_SESSION["message"]) || $_SESSION["message"] == "")
		$_SESSION["message"] = "<p>" . $msg . "</p>";
 	else
		$_SESSION["message"] .= "<p>" . $msg . "</p>";
}
function showMessage(){
	if($_SESSION["message"] != ""){
	    print "<div class='alertBox'>" . $_SESSION["message"] . "</div>\n";
	    $_SESSION["message"] = "";
	}
}
function printMessage(){
	showMessage();
}

function location($url){
	session_write_close();
	header("Location: $url");
	exit(0);
}

function addError($msg, $field=NULL){
	global $error_messages;
	if(field == NULL)
		$error_messages[] = $msg;
	else
		$error_messages[] = "<label for='$field'>$msg</label>";
}

function numErrors(){
	global $error_messages;
	return count($error_messages);
}

function printErrors(){
	global $error_messages;
	if(count($error_messages) > 0){
		echo "<div class='errorBox'><b>Errors:</b><ul>\n";
		foreach($error_messages as $msg)
			echo "<li>$msg</li>\n";
		echo "</ul></div>";
	}
}

function getFields($name, $arr, $mode){
	$ret = Array();
	if(gettype($arr) == "array"){
		foreach($arr as $field=>$value){
			if(strncmp($name, $field, strlen($name)) == 0){
				if(is_string($value)){
					if($mode == "SHOW")
						$ret[$field] = htmlentities(stripslashes($value),ENT_QUOTES);
					else if($mode == "SAVE"){
						$ret[$field] = html_entity_decode($value);
					}
					else if($mode == "SAVE2"){
						$ret[$field] = addslashes($value);
					}
				}
				else{
					$ret[$field] = $value;
				}
			}
		}
	}
	return $ret;
}

function arrayConvert($array, $prefix1, $prefix2){
	foreach($array as $n=>$v){
		if(strncmp($prefix1, $n, strlen($prefix1)) == 0){
			$new = str_replace($prefix1, $prefix2, $n);
			$out[$new] = $v;
		}
	}
	return $out;
}


/*function arrayDiff($from, $to){
	$diff = "";
	foreach($to as $key=>$value){
		if($from[$key] != $to[$key]){
			if($diff != "")
				$diff .= ", ";
			$diff .= "$key changed from \"{$from[$key]}\" to \"{$to[$key]}\"";
		}
	}
	if($diff == "")
		$diff = "changed nothing";
	return $diff;
} */

/*function toHTML($name, $arr){
	$ret = Array();
	foreach($arr as $field=>$value){
	    if(strncmp($name, $field, strlen($name)) == 0)
		    $ret[$field] = htmlentities($value,ENT_QUOTES);
	}
	return $ret;
}
function fromHTML($name, $arr){
	$ret = Array();
	foreach($arr as $field=>$value){
	    if(strncmp($name, $field, strlen($name)) == 0)
		    $ret[$field] = html_entity_decode($value);
	}
	return $ret;
}*/

function addWord($num, $word){
	global $timeagostr;
	if($num > 1)
		$add2 = $word . "s";
	elseif( $num == 1)
		$add2 = $word;
	else
		return "";;
	if($timeagostr == "")
		$timeagostr = "$num $add2";
	else
		$timeagostr = "$timeagostr, $num $add2";
}

function timeAgo($time){
	global $timeagostr;
	if($time == 0)
		return "Never";
	$timeagostr = "";
	$diff = (time() - $time) / 60;
	
	$minutes = $diff % 60;
	$diff = $diff / 60;
	$hours = $diff % 24;
	$diff = floor($diff / 24);

	addWord($diff, "day");
	if($diff < 14){
		addWord($hours, "hour");
		if($diff < 1)
			addWord($minutes, "minute");
	}
	if ( $timeagostr == "")
		$timeagostr = "A few seconds";
	$timeagostr .= " ago";
	return $timeagostr;
}

function fmtDate($date){
	return strftime("%m/%d/%Y %I:%M %P", $date);
}

function fmtFileSize($size){
	$unit = "";
	if($size == 0){
		$unit = "(Empty file)";
	}
	elseif($size >= 1073741824){
		$unit = "Gig";
		$size /= 1073741824;
	}
	elseif($size >= 1048576){
		$unit = "Meg";
		$size /= 1048576.0;
	}
	elseif($size >= 1024){
		$unit = "Kb";
		$size /= 1024.0;
	}
	else
		$unit = "Byte";
	$s2 = floor($size);
	if($s2 > 1)
	    $unit .= "s";
	return sprintf("%.2f %s", $size, $unit);
}

/*function logAdd($action, $msg, $class){
	$fields["logAction"] = $action;
	$fields["logMessage"] = $msg;
	$fields["logClass"] = $class;
	$fields["logDate"] = time();
	if(isset($_SESSION["login"]) && isset($_SESSION["login"]["username"]))
		$fields["logUser"] = $_SESSION["login"]["username"];
	else
		$fields["logUser"] = $_SERVER["REMOTE_ADDR"];

	db_perform('log', $fields, 'insert');
}*/


function uploadFile($field, $prefix, $oldfile=false, $uploadfilename=true, $dir=UPLOAD_DIR){
	if($_FILES[$field]["name"] != ""){
		if($oldfile && file_exists(SITE_PATH.$dir.$oldfile))
			unlink(SITE_PATH.$dir.$oldfile);
		if($uploadfilename)
			$fname = $prefix . $_FILES[$field]["name"];
		else
			$fname = $prefix;
		move_uploaded_file($_FILES[$field]["tmp_name"], SITE_PATH.$dir.$fname);
		return $fname;
	}
	return FALSE;
}

// function resizeFile($file, $prefix1, $prefix2, $oldfile=false){
// 	if(file_exists(SITE_PATH.UPLOAD_DIR.$file)){
// 		$preffile = str_replace($prefix1, $file);
// 		if($oldfile && file_exists(SITE_PATH.UPLOAD_DIR.$oldfile))
// 			unlink(SITE_PATH.UPLOAD_DIR.$oldfile);
// 		$fname = $prefix . $_FILES[$field]["name"];
// 		// Create new image
// 		return TRUE;
// 	}
// 	return FALSE;
// }

function resizeUploadedImage($filename, $newfile, $width="", $height="", $dir=UPLOAD_DIR) {
 	if($oldfile != $newfile && file_exists(SITE_PATH.$dir.$newfile))
 		unlink(SITE_PATH.$dir.$newfile);
	$filetype = substr($filename, strrpos($filename, ".")+1);
	switch($filetype) {
		case "JPG":
		case "jpg":
		case "jpeg":
			$image = imagecreatefromjpeg(SITE_PATH.$dir.$filename);
			break;
		case "gif":
			$image = imagecreatefromgif(SITE_PATH.$dir.$filename);
			break;
		case "png":
			$image = imagecreatefrompng(SITE_PATH.$dir.$filename);
			break;
		case "bmp":
			$image = imagecreatefrombmp(SITE_PATH.$dir.$filename);
			break;
		default:
			return FALSE;
	}
	list($imwidth, $imheight) = getimagesize(SITE_PATH.$dir.$filename);
//	echo "$width*$height $imwidth*$imheight<br>";
	if ($width == "" && $height == "") {
		$newwidth = $imwidth;
		$newheight = $imheight;
	}
	else if ($width != "" && $height == "") {
		$newwidth = $width;
		$newheight = $imheight*($width/$imwidth);
	}
	else if ($width == "" && $height != "") {
		$newwidth = $imwidth*($height/$imheight);
		$newheight = $height;
	}
	else {
		if($imwidth < $imheight){
//			echo "resize1<br>";
			if($width < $height){
//				echo "resize1a<br>";
				$newwidth = $width;
				$newheight = $imheight*($width/$imwidth);
			}
			else{
//				echo "resize1b<br>";
				$newheight = $height;
				$newwidth = $imwidth*($height/$imheight);
			}
		}
		else{
//			echo "resize2<br>";
			if($width < $height){
//				echo "resize2a<br>";
				$newwidth = $width;
				$newheight = $imheight*($width/$imwidth);
			}
			else{
//				echo "resize2b<br>";
				$newheight = $height;
				$newwidth = $imwidth*($height/$imheight);
			}
		}
	}
//	echo "$newwidth*$newheight $imwidth*$imheight<br><br>";
	$thumbimage = imagecreatetruecolor($newwidth, $newheight);
	imagecopyresampled($thumbimage, $image, 0, 0, 0, 0, $newwidth, $newheight, $imwidth, $imheight);
	imagejpeg($thumbimage, SITE_PATH.$dir.$newfile, 40);
	return TRUE;
}

function getCheckbox($fields, $field, $true=TRUE, $false=FALSE){
	echo $fields[$field] . " ($true) ($false) =";
	if(isset($fields[$field]))
		$fields[$field] = $true;
	else
		$fields[$field] = $false;

	return $fields;
}

function getUPSShippingCost($weight, $tozip, $type="1DM", $fromzip=50644){
//	if(class_exists("upsShipping")){
		if($weight <= 0.0)
			$weight = 0.01;
		$rate = new upsShippping;
		$rate->upsProduct($type);   // See upsProduct() function for codes
		$rate->origin($fromzip, "US"); // Use ISO country codes!
		$rate->dest($tozip, "US");   // Use ISO country codes!
		$rate->rate("RDP");     // See the rate() function for codes
		$rate->container("CP"); // See the container() function for codes
		$rate->weight($weight);
		$rate->rescom("RES");   // See the rescom() function for codes
//		echo "((W=$weight, ZIP=$tozip, T=$type)) ";
		return $rate->getQuote();
//	}
//	else
//		return NULL;
}

