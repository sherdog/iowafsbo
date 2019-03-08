<?
include('master.inc.php');

if (!isLoggedIn()){
	$_SESSION["loginreturn"] = this_php;
	location("login.php");
}

include('header.inc.php');
include('footer.inc.php');
