<?php
include "master.inc.php";

$from = "From: no-reply@nobody.com";
	
if (isset($_GET["uid"]) && isset($_GET["confirmation"])) {
	$query = "SELECT * FROM cp_user WHERE cp_user_id=".$_GET["uid"]." AND cp_user_password='".$_GET["confirmation"]."'";
	$check = dbQuery($query);
	
	if (dbNumRows($check)==1) {
		$me = dbFetchArray($check);
		
		$newpass = randomCode();
		$query = "UPDATE cp_user SET cp_user_password='".md5($newpass)."' WHERE cp_user_id=".$_GET["uid"];
		dbQuery($query);
		
		mail($me["cp_user_email"], "New ".COMPANY." Password", "
		
Your ".COMPANY." password has been reset. Please log in and promptly change it.
Your new password: $newpass
");
		
		location("login.php?error=reset");
		
	} else {
		$error = "The confirmation key was invalid.";
	}
}

if (isset($_POST["passsent"])) {
	$query = "SELECT * FROM cp_user WHERE cp_user_email='{$_POST["email"]}'";
	$check = dbQuery($query);
	
	if (dbNumRows($check)==1) {
		$me = dbFetchArray($check);
		
//		$ok = mail($_POST["email"], "test", "test");
//		echo "OK=$ok - {$_POST["email"]}";
	
		$ok = mail($_POST["email"], COMPANY." password reset request", "
Your ".COMPANY." account has had a
password reset requested. If you do not recall
requesting a reset, please ignore this message,
otherwise, please visit the following address to
reset your password, and it will be emailed to
you.

".SITE_URL."passReset.php?uid={$me["cp_user_id"]}&confirmation={$me["cp_user_password"]}", $from);
			
//		echo "OK=$ok";
		addMessage("Your account password has been changed, the password is in the email sent.", "email");
					
	} else {
		addMessage("That email/password was not found.");
	}
}
//addMessage("Welcome " . COMPANY);

 ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?=SITE_TITLE?></title>
<link rel="stylesheet" href="styles.css" />
</head>

<body>
<div id="wrapper">
<div id="masshead">
 <div id="logo"><img src="images/cplogo.jpg" width="244" height="57" /></div>
</div>
<div id="massheadshadow">&nbsp;</div>
</div>
<div id="content">
	<div id="loginbox">
    <h3 style="margin-bottom:5px;">Reset Your Password</h3>
<?
printMessage();
printErrors();
?>
   	<br /> 
	<form action='forgot.php' method=post>
		<input type=hidden name=passsent value=yes>
	   	<label >Email Address<br />
    	<input type="text" name="email" style="width:300px;" id="formfield" /></label><br />
    	<br/>
		<input type="submit" name="login" value="Reset Password" class="formbutton" /> <a href="login.php">Login</a>
    </form>
    <a style="float:right" href="<?=SITE_URL?>"><small>Return to <?=COMPANY?></small></a> 
    </div>
</div>
</div>
</body>
</html>
