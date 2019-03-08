<?

session_start();
foreach($_SESSION as $f=>$v){
	unset($_SESSION[$f]);
}
header("Location: login.php");
?>
