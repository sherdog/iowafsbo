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
		$error = "Your account password has been flagged for resetting, please follow the instructions in the email sent.";
					
	} else {
		$error = "Could not find that email address in the database.";	
	}
}
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
 <div id="logo"><img src="images/cpLogo.jpg" width="304" height="89" /></div>
</div>
<div id="massheadshadow">&nbsp;</div>
</div>
<div id="content">
	<div id="loginbox">
    <h3 style="margin-bottom:5px;">Client Login</h3>
<?
printMessage();
printErrors();
?>
   	<br /> 
	<form action='forgot.php' method=post>
	<input type=hidden name=passsent value=yes>
		<table width=100% height=100%><tr><td align=center valign=middle style="vertical-align:middle">
			<table width=300 bgcolor=#cccccc align=center style='border:2px solid #AAAAAA;;padding:10px;'>
				<tr>
					<td colspan=3 align=center><h1 style="border-bottom: 3px double black"><?=COMPANY?></h1><h2><nobr>Reset Your Password</h2><br></td>
				</tr>
				<? if (isset($error)) echo "<tr><td colspan=2 class=error align=center>$error<br><br></td></tr>" ?>
	
				<tr>
					<td align=center class=normal>Email:</td><td align=right><input name=email size=30></td>
				</tr>
				<tr>
					<td colspan=2 align=right>
						<input style='padding:1px;font-size:10px' type=submit value='Submit'>
					</td>
				</tr>
			</table>
		</td></tr></table>
	</form>

</body>
</html>
