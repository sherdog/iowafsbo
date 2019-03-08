<?
//function used to validate the subscriber
function validateSubscriber(){
	if(isset($_SESSION['loginsubscriber']) && $_SESSION['loginsubscriber']['subscriber_id'] > 0) {
		return true;
	} else {
		return false;
	}
}

//function used to grab db listing fields rturns array
function getListingFields() { 
	$results = dbQuery('SELECT listing_fields_title FROM listing_fields ORDER BY listing_fields_id ASC');
	while($row = dbFetchArray($results)){
		$fields[] = $row['listing_fields_title'];
	}

	return $fields;
}

function getFieldType($field) {
	$results = dbQuery('SELECT * FROM listing_fields WHERE listing_fields_title = "'.$field.'"');
	$row = dbFetchArray($results);
	return $row['listing_fields_type'];
}

function getStates(){
	$states[] = "|Unknown";
	$states[] = "AL|Alabama";
	$states[] = "AK|Alaska";
	$states[] = "AZ|Arizona";
	$states[] = "AR|Arkansas";
	$states[] = "CA|California";
	$states[] = "CO|Colorado";
	$states[] = "CT|Connecticut";
	$states[] = "DE|Delaware";
	$states[] = "FL|Florida";
	$states[] = "GA|Georgia";
	$states[] = "HI|Hawaii";
	$states[] = "ID|Idaho";
	$states[] = "IL|Illinois";
	$states[] = "IN|Indiana";
	$states[] = "IA|Iowa";
	$states[] = "KS|Kansas";
	$states[] = "KY|Kentucky";
	$states[] = "LA|Louisiana";
	$states[] = "ME|Maine";
	$states[] = "MD|Maryland";
	$states[] = "MA|Massachusetts";
	$states[] = "MI|Michigan";
	$states[] = "MN|Minnesota";
	$states[] = "MS|Mississippi";
	$states[] = "MO|Missouri";
	$states[] = "MT|Montana";
	$states[] = "NE|Nebraska";
	$states[] = "NV|Nevada";
	$states[] = "NH|New Hampshire";
	$states[] = "NJ|New Jersey";
	$states[] = "NM|New Mexico";
	$states[] = "NY|New York";
	$states[] = "NC|North Carolina";
	$states[] = "ND|North Dakota";
	$states[] = "OH|Ohio";
	$states[] = "OK|Oklahoma";
	$states[] = "OR|Oregon";
	$states[] = "PA|Pennsylvania";
	$states[] = "RI|Rhode Island";
	$states[] = "SC|South Carolina";
	$states[] = "SD|South Dakota";
	$states[] = "TN|Tennessee";
	$states[] = "TX|Texas";
	$states[] = "UT|Utah";
	$states[] = "VT|Vermont";
	$states[] = "VA|Virginia";
	$states[] = "WA|Washington";
	$states[] = "WV|West Virginia";
	$states[] = "WI|Wisconsin";
	$states[] = "WY|Wyoming";
	
	return $states;
}

function returnShortState($state) { 
	$states['Iowa'] = 'IA';
	$states['Alabama'] = "AL";
	$states['Arizona'] = "AZ";
	$states['Arkansas'] = "AR";
	$states['California'] = "CA";
	$states['Colorado'] = "CO";
	$states['Connecticut'] = "CT";
	$states['Delaware'] = "DE";
	$states['Florida'] = "FL";
	$states['Georgia'] = "GA";
	$states['Hawaii'] = "HI";
	$states['Idaho'] = "ID";
	$states['Illinois'] = "IL";
	$states['Indiana'] = "IN";
	$states['Iowa'] = "IA";
	$states['Kansas'] = "KS";
	$states['Kentucky'] = "KY";
	$states['Louisiana'] = "LA";
	$states['Maine'] = "ME";
	$states['Maryland'] = "MD";
	$states['Massachusetts'] = "MA";
	$states['Michigan'] = "MI";
	$states['Minnesota'] = "MN";
	$states['Mississippi'] = "MS";
	$states['Missouri'] = "MO";
	$states['Montana'] = "MT";
	$states['Nebraska'] = "NE";
	$states['Nevada'] = "NV";
	$states['New Hampshire'] = "NH";
	$states['New Jersey'] = "NJ";
	$states['New Mexico'] = "NM";
	$states['New York'] = "NY";
	$states['North Carolina'] = "NC";
	$states['North Dakota'] = "ND";
	$states['Ohio'] = "OH";
	$states['Oregon'] = "OR";
	$states['Pennsylvania'] = "PA";
	$states['Rhode Island'] = "RI";
	$states['South Carolina'] = "SC";
	$states['South Dakota'] = "SD";
	$states['Tennessee'] = "TN";
	$states['Texas'] = "TX";
	$states['Utah'] = "UT";
	$states['Vermont'] = "VT";
	$states['Virginia'] = "VA";
	$states['Washington'] = "WA";
	$states['West Virginia'] = "WV";
	$states['Wisconsin'] = "WI";
	$states['Wyoming'] = "WY";
	
	return $states[$state];
	
}

function sendNotification($fromname, $fromaddress, $to, $body, $subject, $attachments=false)
{
  $eol="\r\n";
  $mime_boundary=md5(time());

  # Common Headers
  $headers .= "From: ".$fromname."<".$fromaddress.">".$eol;
  $headers .= "Reply-To: ".$fromname."<".$fromaddress.">".$eol;
  $headers .= "Return-Path: ".$fromname."<".$fromaddress.">".$eol;    // these two to set reply address
  $headers .= "Message-ID: <".time()."-".$fromaddress.">".$eol;
  //$headers .= "X-Mailer: PHP v".phpversion().$eol;          // These two to help avoid spam-filters

  # Boundry for marking the split & Multitype Headers
  //$headers .= 'MIME-Version: 1.0'.$eol.$eol;
 // $headers .= "Content-Type: multipart/mixed; boundary=\"".$mime_boundary."\"".$eol.$eol;

  # Open the first part of the mail
 // $msg = "--".$mime_boundary.$eol;
 
 // $htmlalt_mime_boundary = $mime_boundary."_htmlalt"; //we must define a different MIME boundary for this section
 // # Setup for text OR html -
 // $msg .= "Content-Type: multipart/alternative; boundary=\"".$htmlalt_mime_boundary."\"".$eol.$eol;

  # Text Version
 // $msg .= "--".$htmlalt_mime_boundary.$eol;
 //// $msg .= "Content-Type: text/plain; charset=iso-8859-1".$eol;
 // $msg .= "Content-Transfer-Encoding: 8bit".$eol.$eol;
  $msg .= strip_tags(str_replace("<br>", "\n", substr($body, (strpos($body, "<body>")+6)))).$eol.$eol;

  # HTML Version
 // $msg .= "--".$htmlalt_mime_boundary.$eol;
 // $msg .= "Content-Type: text/html; charset=iso-8859-1".$eol;
 // $msg .= "Content-Transfer-Encoding: 8bit".$eol.$eol;
 // $msg .= $body.$eol.$eol;

  //close the html/plain text alternate portion
 // $msg .= "--".$htmlalt_mime_boundary."--".$eol.$eol;

  if ($attachments !== false)
  {
    for($i=0; $i < count($attachments); $i++)
    {
      if (is_file($attachments[$i]["file"]))
      {  
        # File for Attachment
        $file_name = substr($attachments[$i]["file"], (strrpos($attachments[$i]["file"], "/")+1));
       
        $handle=fopen($attachments[$i]["file"], 'rb');
        $f_contents=fread($handle, filesize($attachments[$i]["file"]));
        $f_contents=chunk_split(base64_encode($f_contents));    //Encode The Data For Transition using base64_encode();
        $f_type=filetype($attachments[$i]["file"]);
        fclose($handle);
       
        # Attachment
        $msg .= "--".$mime_boundary.$eol;
        $msg .= "Content-Type: ".$attachments[$i]["content_type"]."; name=\"".$file_name."\"".$eol;  // sometimes i have to send MS Word, use 'msword' instead of 'pdf'
        $msg .= "Content-Transfer-Encoding: base64".$eol;
        $msg .= "Content-Description: ".$file_name.$eol;
        $msg .= "Content-Disposition: attachment; filename=\"".$file_name."\"".$eol.$eol; // !! This line needs TWO end of lines !! IMPORTANT !!
        $msg .= $f_contents.$eol.$eol;
      }
    }
  }

  # Finished
  //$msg .= "--".$mime_boundary."--".$eol.$eol;  // finish with two eol's for better security. see Injection.
 
  # SEND THE EMAIL
  ini_set(sendmail_from,$fromaddress);  // the INI lines are to force the From Address to be used !
  $mail_sent = mail($to, $subject, $msg, $headers);
 
  ini_restore(sendmail_from);
 
  return $mail_sent;
}


?>
