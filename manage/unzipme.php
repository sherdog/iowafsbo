<?
include('master.inc.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>

<body>
<? 

function showForm() { 
	echo "<form action=\"unzipme.php\" method=\"post\" enctype=\"multipart/form-data\">\n";
	echo "<table width=\"500\" cellpadding=\"5\" cellspacing=\"0\">\n";
	echo "<tr>\n";
	echo "<td valign=\"top\">File</td>\n";
	echo "<td valign=\"top\"><input type=\"file\" name=\"file\"></td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "<td colspan=\"2\"><input type=\"Submit\" name=\"Submit\" value=\"Submit\"></td>\n";
	echo "</tr>\n";
	echo "</table>\n";
	echo "</form>\n";
}

if(!isset($_POST['Submit'])){
showForm();
}else{
//open zip and pretend we are unzipping!
//k get file
if($_FILES['file']['name'] != "") {
	//function unzip($src_file, $dest_dir=false, $create_zip_name_dir=true, $overwrite=true)
	uploadZip('file');
}
}
?>
</body>
</html>
