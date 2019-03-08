<?
function validateUploadImage($fields, $field){
	if($_FILES[$field]["name"] != ""){
		if(file_exists($_FILES[$field]["tmp_name"])){
			$mime = explode("/", $_FILES[$field]["type"]);
			if($mime[0] == "image"){
				switch($mime[1]){
					case "jpeg":case "jpg":
					case "gif":
					case "png":
						break;
					default:
						AddError("The file you supplied was not an image", $field);
						break;
				}
			}
			else
				AddError("The file you supplied was not an image", $field);
			if($_FILES[$field]["size"] == 0)
				AddError("The file you supplied is blank", $field);
			else if(numErrors() == 0)
				return true;
		}
	}
	return false;
}

function validateRequiredField($fields, $field, $name=NULL){
	$verb = "is";
	if(gettype($fields) == "array")
		$validate = $fields[$field];
	else
		$validate = $fields;
	if($name == NULL){
		$name = str_replace("_", " ", strstr($field,"_"));
		if(substr($name, strlen($name) - 1) == "s")
			$verb = "are";
	}
	if($validate == "")
		addError("The $name $verb blank", $field);
}

function validateTextField($fields, $field, $name=NULL, $req=TRUE, $minlength=0){
	$verb = "is";
	if(gettype($fields) == "array")
		$validate = $fields[$field];
	else
		$validate = $fields;
	if($name == NULL){
		$name = str_replace("_", " ", strstr($field,"_"));
		if(substr($name, strlen($name) - 1) == "s")
			$verb = "are";
	}
	if($validate == ""){
		if($req)
			addError("The $name $verb blank", $field);
	}
	else{
		if(strlen(trim($validate)) < $minlength)
			addError("The $name {$verb}n't long enough", $field);
	}
}

function validateNumberRange($fields, $field, $name=NULL, $min, $max){
	$verb = "is";
	if($name == NULL){
		$name = str_replace("_", " ", strstr($field,"_"));
		if(substr($name, strlen($name) - 1) == "s")
			$verb = "are";
	}
	if(!isset($fields[$field]))
		addError("The $name was not entered", $field);
	elseif($fields[$field] < $min)
		addError("The $name $verb less than $min", $field);
	elseif($fields[$field] > $max)
		addError("The $name $verb greater than $max", $field);
}

function validateDateRange($fields, $field, $name=NULL, $past=0, $future=7){
	$verb = "is";
	if(gettype($fields) == "array")
		$validate = $fields[$email];
	else
		$validate = $fields;
	if($name == NULL){
		$name = str_replace("_", " ", strstr($field,"_"));
		if(substr($name, strlen($name) - 1) == "s")
			$verb = "are";
	}
	if(!isset($validate))
		addError("The $name was not entered", $field);
		
	$now = strtotime(date("Y/m/d"));
	$pasttime = $now - ($past * 86400);
	$futuretime = $now + ($future * 86400);
	$dt = strtotime($validate);
#	echo "DEBUG: $validate=$dt (todaysdate=$now, $past $pasttime,$future $futuretime) (now=".time().")<br>";
	
	if($dt < $pasttime)
		addError("The $name is too far into the past");
	elseif($dt > $futuretime)
		addError("The $name is too far into the future");
}

function validateIndex($fields, $field, $name=NULL){
	$verb = "is";
	if($name == NULL){
		$name = str_replace("_", " ", strstr($field,"_"));
		if(substr($name, strlen($name) - 1) == "s")
			$verb = "are";
	}
	if($fields[$field] == "")
		addError("The $name $verb blank", $field);
	elseif($fields[$field] == 0)
		addError("The $name $verb zero", $field);
	elseif($fields[$field] < 0)
		addError("The $name $verb less than zero", $field);
}

function validateDBIndex($fields, $field, $table, $name=NULL){
	$verb = "is";
	if(gettype($fields) == "array")
		$validate = $fields[$email];
	else
		$validate = $fields;

	if($name == NULL){
		$name = str_replace("_", " ", strstr($field,"_"));
		if(substr($name, strlen($name) - 1) == "s")
			$verb = "are";
	}
	if($validate == "")
		addError("The $name $verb blank", $field);
	elseif($validate == 0)
		addError("The $name $verb zero", $field);
	elseif($validate < 0)
		addError("The $name $verb less than zero", $field);
	elseif(dbRow($table, $validate) == false)
		addError("The $name $verb is not a valid index");
}

function validateEmail($fields, $email="", $req=TRUE, $query=NULL, $name=NULL){
	if(gettype($fields) == "array")
		$validate = $fields[$email];
	else
		$validate = $fields;
	if($name == NULL){
		$name = "email address";
		if(substr($name, strlen($name) - 1) == "s")
			$verb = "are";
	}
	if($validate == ""){
		if($req)
			addError("The $name is blank", $email);
	}
	else{
		if (!preg_match("/^([a-zA-Z0-9])+([a-zA-Z0-9\._-] )*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/", trim($validate)))
			addError("Please enter a valid email address");
		elseif(strlen($validate) < 10)
			addError("The $name is too short", $email);
		elseif($query != NULL){
			$result = dbQuery($query);
			if(dbNumRows($result) > 0)
				addError("That $name is taken", $email);
		}
	}
}

function validatePasswordAgain($field1, $field2, $req=TRUE){
	$str1 = trim(stripslashes($field1));
	$str2 = trim(stripslashes($field2));
	
	if(strlen($str1) < 1)
		addError("Please enter a password");
	if(strlen($str2) < 1)
		addError("Please enter your password again");
	
	if($str1 != $str2)
		addError("The passwords you supplied did not match");
}

function validatePhoneNumber($fields, $field, $req=TRUE, $query=NULL){
	if(gettype($fields) == "array")
		$validate = $fields[$field];
	else
		$validate = $fields;
	if($validate == ""){
		if($req)
			addError("The phone number is blank", $field);
	}
	else{
		if (!preg_match("/^[0-9][0-9][0-9]-[0-9][0-9][0-9]-[0-9][0-9][0-9][0-9] .*$/", trim($validate)) &&
		!preg_match("/^[0-9][0-9][0-9]-[0-9][0-9][0-9]-[0-9][0-9][0-9][0-9]$/", trim($validate)) &&
		!preg_match("/^1-[0-9][0-9][0-9]-[0-9][0-9][0-9]-[0-9][0-9][0-9][0-9]$/", trim($validate)))
			addError("Please enter the phone number in nnn-nnn-nnnn format", $field);
		elseif($query != NULL){
			$result = dbQuery($query);
			if(dbNumRows($result) > 0)
				addError("That phone number is taken", $field);
		}
	}
}

function validateZipCode($fields, $field, $req=TRUE){
	if($fields[$field] == ""){
		if($req)
			addError("The zip code is blank", $field);
	}
	else{
		if (!preg_match("/^[0-9][0-9][0-9][0-9][0-9]$/", trim($fields[$field])) &&
		    !preg_match("/^[0-9][0-9][0-9][0-9][0-9]-[0-9][0-9][0-9][0-9]$/", trim($fields[$field])))
			addError("Please enter the zip code in nnnnn or nnnnn-nnnn format", $field);
	}
}

function validateCityStateZip($city, $state, $zip){
	if(class_exists("upsaddress")){
		$ups = new upsaddress(UPS_CODE, UPS_USERNAME, UPS_PASSWORD);
		$ups->setCity($city);
		$ups->setState($state);
		$ups->setZip($sip);
		$response = $ups->getResponse();
		$num=count($ups->list);
		echo $num;
		dump($ups);
	}
	else
		addMessage("UPS address code was not found");
}

function validateCreditCard ($fields, $cardnumber, $cardname) {  // See realwheel.net's code to handle more cards
return true;//no need to validate as PayPal validates for us.

 // Define the cards we support. You may add additional card types.
  
  //  Name:      As in the selection box of the form - must be same as user's
  //  Length:    List of possible valid lengths of the card number for the card
  //  prefixes:  List of possible prefixes for the card
  //  checkdigit Boolean to say whether there is a check digit
  
  // Don't forget - all but the last array definition needs a comma separator!
  
  $cards = array (  array ('name' => 'American Express', 
                          'length' => '15', 
                          'prefixes' => '34,37',
                          'checkdigit' => true
                         ),
                   array ('name' => 'Discover', 
                          'length' => '16', 
                          'prefixes' => '6011',
                          'checkdigit' => true
                         ),
                   array ('name' => 'MasterCard', 
                          'length' => '16', 
                          'prefixes' => '51,52,53,54,55',
                          'checkdigit' => true
                         ),
                   array ('name' => 'Visa', 
                          'length' => '13,16', 
                          'prefixes' => '4',
                          'checkdigit' => true
                         ),
                );

  $ccErrorNo = 0;
  	if(is_array($fields)){
		$cardnumber = $fields[$cardnumber];
  		$cardname = $fields[$cardname];
	}

  $ccErrors[0] = "Unknown <b>Credit Card</b> type: $cardname";
  $ccErrors[1] = "No <b>Credit Card</b> number provided";
  $ccErrors[2] = "Credit card number has invalid format.  Please use this format XXXX XXXX XXXX XXXX";
  $ccErrors[3] = "<b>Credit Card</b> number is invalid";
  $ccErrors[4] = "<b>Credit Card</b> number is wrong length";
               
  // Establish card type
  $cardType = -1;
  for ($i = 0; $i < sizeof($cards); $i++) {

    // See if it is this card (ignoring the case of the string)
    if (strtolower($cardname) == strtolower($cards[$i]['name'])) {
      $cardType = $i;
      break;
    }
  }
  
  // If card type not found, report an error
  if ($cardType == -1) {
     $errornumber = 0;
     $errortext = $ccErrors[$errornumber];
     addError($errortext);
     return $errortext; 
  }
   
  // Ensure that the user has provided a credit card number
  if (strlen($cardnumber) == 0)  {
     $errornumber = 1;
     $errortext = $ccErrors[$errornumber];
     addError($errortext);
      return $errortext; 
  }
  
  // Remove any spaces from the credit card number
  $cardNo = str_replace (' ', '', $cardnumber);  
   
  // Check that the number is numeric and of the right sort of length.
  if (!eregi('^[0-9]{13,19}$',$cardNo))  {
     $errornumber = 2;     
     $errortext = $ccErrors[$errornumber];
     addError($errortext);
      return $errortext; 
  }
       
  // Now check the modulus 10 check digit - if required
  if ($cards[$cardType]['checkdigit']) {
    $checksum = 0;                                  // running checksum total
    $mychar = "";                                   // next char to process
    $j = 1;                                         // takes value of 1 or 2
  
    // Process each digit one by one starting at the right
    for ($i = strlen($cardNo) - 1; $i >= 0; $i--) {
    
      // Extract the next digit and multiply by 1 or 2 on alternative digits.      
      $calc = $cardNo{$i} * $j;
    
      // If the result is in two digits add 1 to the checksum total
      if ($calc > 9) {
        $checksum = $checksum + 1;
        $calc = $calc - 10;
      }
    
      // Add the units element to the checksum total
      $checksum = $checksum + $calc;
    
      // Switch the value of j
      if ($j ==1) {$j = 2;} else {$j = 1;};
    } 
  
    // All done - if checksum is divisible by 10, it is a valid modulus 10.
    // If not, report an error.
    if ($checksum % 10 != 0) {
     $errornumber = 3;     
     $errortext = $ccErrors[$errornumber];
     addError($errortext);
      return $errortext;
    }
  }  

  // The following are the card-specific checks we undertake.

  // Load an array with the valid prefixes for this card
  $prefix = split(',',$cards[$cardType]['prefixes']);
      
  // Now see if any of them match what we have in the card number  
  $PrefixValid = false; 
  for ($i = 0; $i < sizeof($prefix); $i++) {
    $exp = '^' . $prefix[$i];
    if (ereg($exp,$cardNo)) {
      $PrefixValid = true;
      break;
    }
  }
      
  // If it isn't a valid prefix there's no point at looking at the length
  if (!$PrefixValid) {
     $errornumber = 3;     
     $errortext = $ccErrors[$errornumber];
     addError($errortext);
     return false; 
  }
    
  // See if the length is valid for this card
  $LengthValid = false;
  $lengths = split(',',$cards[$cardType]['length']);
  for ($j = 0; $j < sizeof($lengths); $j++) {
    if (strlen($cardNo) == $lengths[$j]) {
      $LengthValid = true;
      break;
    }
  }
  
  // See if all is OK by seeing if the length was valid. 
  if (!$LengthValid) {
     $errornumber = 4;     
     $errortext = $ccErrors[$errornumber];
     addError($errortext);
     return $errortext;
  };   
  
  // The credit card is in the required format.
  //we don't want to return anything if everything is good'
 // return true;
}

function validateStringLetters($fields, $field, $invalid="\"\'%&\$<>", $name=NULL){
	if(gettype($fields) == "array")
		$validate = $fields[$field];
	else
		$validate = $fields;
	if($name == NULL){
		$name = str_replace("_", " ", strstr($field,"_"));
	}
	for($a = 0;$a < strlen($invalid);$a++){
		if(strchr($validate, $invalid[$a]) != FALSE){
			addError("The $name contains invalid letters", $field);
			return false;
		}
	}
	return true;
}
?>
