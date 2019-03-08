<?php
include('manage/master.inc.php');




$mls = $_GET['mls'];
$listingResults = dbQuery('SELECT listing_street, listing_city, listing_state, listing_zip_code FROM listing WHERE listing_number = "'.$mls.'"');


?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo SITE_TITLE; ?></title>
<link rel="stylesheet" href="styles.css" />
<script language="javascript" src="javascript.js"></script>
<style>
<!--
.chatbottom-online { background-image:url(images/chat-online-bottom.jpg); background-repeat:no-repeat; background-position:top right; }
-->
</style>
<script type="text/javascript">
<!--
function MM_validateForm() { //v4.0
  if (document.getElementById){
    var i,p,q,nm,test,num,min,max,errors='',args=MM_validateForm.arguments;
    for (i=0; i<(args.length-2); i+=3) { test=args[i+2]; val=document.getElementById(args[i]);
      if (val) { nm=val.id; if ((val=val.value)!="") {
        if (test.indexOf('isEmail')!=-1) { p=val.indexOf('@');
          if (p<1 || p==(val.length-1)) errors+='- '+nm+' must contain an e-mail address.\n';
        } else if (test!='R') { num = parseFloat(val);
          if (isNaN(val)) errors+='- '+nm+' must contain a number.\n';
          if (test.indexOf('inRange') != -1) { p=test.indexOf(':');
            min=test.substring(8,p); max=test.substring(p+1);
            if (num<min || max<num) errors+='- '+nm+' must contain a number between '+min+' and '+max+'.\n';
      } } } else if (test.charAt(0) == 'R') errors += '- '+nm+' is required.\n'; }
    } if (errors) alert('The following error(s) occurred:\n'+errors);
    document.MM_returnValue = (errors == '');
} }
//-->
</script>
</head>

<body>
<?php

if(isset($_POST['send'])){

	$fromname = trim($_POST['yname']);
	$fromemail = trim($_POST['yemail']);
	$subject = "Found this for you";
	$message = stripslashes(trim($_POST['msg']));
	
	
	
	foreach($_POST['email'] as $key=>$val){
		//send email if not blank
		if($_POST['email'] != '')
			sendNotification($fromname, $fromemail, $val, $message, $subject);
	}
	$message = stripslashes($_POST['msg']);
	//$security_code = trim($_POST['security_code']);
	
	//sendNotification($fromname, $fromaddress, $to, $body, $subject, $attachments=false)
	?>
    <div class="alertBox">
      <div align="center">
        <p><br />
        Thank you, your reccomendation has been sent succesfully<br />
        <br />
        <a href="javascript:window.close()" class="listing" style="color:black;">Click here to close this window</a></p>
        <p><br />
        </p>
      </div>
    </div>
    <?
	
	
	

}else{
?>
<form action="<?=$_SERVER['PHP_SELF']?>" method="post" name="form1" id="form1" onsubmit="MM_validateForm('Your Name','','R','Your Email Address','','RisEmail','Email','','RisEmail');return document.MM_returnValue">
  <br />
  <table width="95%" border="0" align="center" cellpadding="5" cellspacing="0" bgcolor="#5A5448" style="border:3px solid #8E897A;">
    <tr>
      <td colspan="3" class="tan12">Please enter your name and your email address and your friends.<strong class="required"> * required<br />
        <br />
      </strong></td>
    </tr>
    <tr>
      <td width="50%" class="tan12"><div align="right"><span class="required"><strong>*</strong></span><strong>Your name:</strong></div></td>
      <td width="1" rowspan="8"><img src="images/sep.jpg" width="1" height="300" /></td>
      <td width="50%"><input name="yname" type="text" class="textDark" id="Your Name" style="width:250px;" /></td>
    </tr>
    <tr>
      <td class="tan12"><div align="right"><span class="required"><strong>*</strong></span><strong>Your email address:</strong></div></td>
      <td><input name="yemail" type="text" class="textDark" id="Your Email Address" style="width:250px;" /></td>
    </tr>
    <tr>
      <td class="tan12"><div align="right"></div></td>
      <td>&nbsp;</td>
    </tr>
    
    <tr>
      <td class="tan12"><div align="right"><span class="required"><strong>*</strong></span><strong>Friends Email:</strong></div></td>
      <td><input name="email[]" type="text" class="textDark" id="Email" style="width:250px;" /></td>
    </tr>
     <tr>
      <td class="tan12"><div align="right"><strong>Friends Email:</strong></div></td>
      <td><input name="email[]" type="text" class="textDark" id="Email" style="width:250px;" /></td>
    </tr>
     <tr>
      <td class="tan12"><div align="right"><strong>Friends Email:</strong></div></td>
      <td><input name="email[]" type="text" class="textDark" id="Email" style="width:250px;" /></td>
    </tr>
    <tr>
      <td class="tan12"><div align="right"></div></td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td valign="top" class="tan12"><div align="right"><strong>Message</strong></div></td>
      <td valign="top"><textarea name="msg" cols="45" rows="5" class="textDark" id="msg" style="width:250px; height:100px;">Hey i found a listing you'd like on <?=COMPANY?> here is the link.
     
     <?=SITE_URL.$_GET['mls']?></textarea></td>
    </tr>
    
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td><input type="image" name="imageField" id="imageField" src="images/b_submit.jpg" /><input type="hidden" name="send" value="true" /></td>
    </tr>
</table>
</form>
<p>&nbsp;</p>
<p>
  <?php
}

?>
  
</p>
</body>
</html>
