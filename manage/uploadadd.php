<?php
include('master.inc.php');
extract($_GET, EXTR_PREFIX_ALL, 'form_'); extract($_POST, EXTR_PREFIX_ALL, 'form_');

/// BEGIN CALL DEBUG - Write info to http://www.dirtondirt.com/dev/upload/test.txt
$now = time();
$test = "Called at $now, {$_FILES["Filedata"]["name"]} params: ($form_id,$form_code,$form_type)";
$zzz = fopen(SITE_PATH."upload/test.txt", "w");
fwrite($zzz, $test);
/// END CALL DEBUG at end of program and inside uploadAdd();

uploadAdd($form_code, $form_type, $form_id, $form_desc);

// END DEBUG
fwrite($zzz, " done");
fclose($zzz);
switch($form_type){
	case 1:		location("upload-photos.php?code=$form_code&id=$form_id");break;	// Called by a form
//	case 2:		location("upload-videos.php?code=$form_code&id=$form_id");break;	// Called by swfuploader, no need to redirect
//	case 3:		location("upload-slides.php?code=$form_code&id=$form_id");break;	// Called by swfuploader, no need to redirect
}

?>
