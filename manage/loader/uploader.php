<?php
include('../master.inc.php');
//$id = $_GET['id'];
if($_FILES['Filedata']['size'] > 0) {
    //
   	//move_uploaded_file($_FILES['Filedata']['tmp_name'], $path);
	//$filename = time()."-".fixname($_FILES['Filedata']['name']);
	//upload_file($_FILES['Filedata']['tmp_name'], $filename);
	 
		//$filename = time()."-".$_FILES['Filedata']['name'];
		//upload_file($_FILES['Filedata']['tmp_name'], $filename);
		$path = 'repository/'.$_FILES['Filedata']['name'];
		move_uploaded_file($_FILES['Filedata']['tmp_name'], $path);
		//make_thumbnail($filename, UPLOAD_DIR, 150, "", "small");
		//make_thumbnail($filename, UPLOAD_DIR, 760, "", "main") ;
		//make_thumbnail($filename, UPLOAD_DIR, 225, "", "thumb");
		//make_thumbnail($filename, UPLOAD_DIR, 100, "", "tiny");
		
	//	$imgfield['filename'] = $filename;
		//$imgfield['GalleryID'] = $lastid;
		//$update = db_perform('tmp', $imgfield, 'insert');
		//$imgsql = "INSERT INTO Images ('GalleryID', 'ImagesFilename', 'ImagesCaption') VALUES ('".$lastid."', '".$filename."', '".$imgfield['ImagesCaption']."')";
		//echo $imgsql;
	///	$updateimg = db_query($imgsql);
		//$update_image  = db_query('Images', $imgfield, 'insert');
	/*	if($action == 'add'){
		$lastimgid = db_insert_id();
		
			if($i==0){//set this as the default image 
				$updatefield['GalleryID'] = $lastid;
				$updatefield['GalleryDefaultImage'] = $lastimgid;
				
					$update_default = db_query('UPDATE Gallery SET GalleryDefaultImage="'.$lastimgid.'" WHERE GalleryID='.$lastid);
				
			}
		}//end if add
		
	
	$path = 'repository/'.$_FILES['Filedata']['name'];
   
    move_uploaded_file($_FILES['Filedata']['tmp_name'], $path);
*/
//echo $_FILES['Filedata']['size']."<br>";
}
?>

