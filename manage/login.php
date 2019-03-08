<?
include('master.inc.php');

if(isset($_POST['login'])){
	//check to see if user exists
	$email = trim($_POST['email']);
	$password = md5(trim($_POST['password']));
	$results = dbQuery("SELECT * FROM cp_user WHERE cp_user_email='$email' AND cp_user_password='$password'");
	if(dbNumRows($results) > 0){
		// user/pass are ok
		$user = dbFetchArray($results);
		$_SESSION["cp_user"] = $user;
		$acc = getSelections($user["cp_user_groups"]);
		$uid = $user["cp_user_id"];
		foreach($acc as $id) {
			$access = dbRow("cp_access",$id);
			$_SESSION["cp_access"][$access["cp_access_title"]] = $id;
		}
			
		dbQuery("UPDATE cp_user SET cp_user_last_login='".time()."' WHERE cp_user_id=$uid");
		if(isset($_SESSION["loginreturn"])){
			$ret = $_SESSION["loginreturn"];
			unset($_SESSION["loginreturn"]);
			location($ret);
		}
		else
			location(LOGIN_SUCCESS);
	}
	else{
		addMessage("That email/password was not found.");
	}
 //check to see if user exists
}

//addMessage("Welcome " . COMPANY);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?=SITE_TITLE?></title>
<link rel="stylesheet" href="styles.css" />
<style type="text/css">
<!--
.style1 {color: #FFFFFF}
-->
</style>
</head>

<body>
<div id="wrapper">
<div id="masshead">
 <div id="logo">&nbsp;&nbsp;<img src="images/cplogo.jpg" width="244" height="57" /></div>
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
    <form id="loginform" action="login.php" method="post">
    	<label >Email Address<br />
    	<input type="text" name="email" style="width:300px;" id="formfield" /></label><br />
    	<label>Password<br />
    	<input type="password" name="password" style="width:300px;" id="formfield"  /></label><br /><br />    	
        <label style="margin-top:10px;"><input type="submit" name="login" value="Log in" class="formbutton" /></label> <a href="forgot.php">Forgot Password?</a>
    </form>
   <br /><a style="float:right" href="<?=SITE_URL?>"><small>Return to <?=COMPANY?></small></a>  </div>
</div>
</div>
</body>
</html>
