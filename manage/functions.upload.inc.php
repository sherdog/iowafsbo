<?php

define("defaultParams", "?code=$form_code&type=$form_type&id=$form_id");

function showAttachments($code, $type, $id){
	global $form_id;
	$session = session_id();
	switch($type){
		case 1:
			if($id != 0)
				$results1 = dbQuery("SELECT content_photo_id, content_photo_src, content_photo_title FROM content_photo WHERE content_photo_content=$form_id");
			else
				$results2 = dbQuery("SELECT attachment_id, attachment_thumb, attachment_title FROM attachment WHERE attachment_code=$code AND attachment_key='$session' AND attachment_type=1");
			$edit = "upload-photos.php?action=edit&id=$form_id&code=$code&type=1";
			$delete = "upload-photos.php?action=delete&id=$form_id&code=$code&type=1";
			$thumb = "articlephoto/t0_";
			break;
		case 2:
			if($id != 0)
				$results1 = dbQuery("SELECT content_video_id, content_video_thumb, content_video_title FROM content_video WHERE content_video_content=$form_id");
			else
				$results2 = dbQuery("SELECT attachment_id, attachment_thumb, attachment_title FROM attachment WHERE attachment_code=$code AND attachment_key='$session' AND attachment_type=2");
			$edit = "upload-videos.php?action=edit&id=$form_id&code=$code&type=2";
			$delete = "upload-videos.php?action=delete&id=$form_id&code=$code&type=2";
			$thumb = "";
			break;
		case 3: 
			if($id != 0)
				$results1 = dbQuery("SELECT content_slide_id, content_slide_src, content_slide_title FROM content_slide WHERE content_slide_content=$form_id");
			else
				$results2 = dbQuery("SELECT attachment_id, attachment_thumb, attachment_title FROM attachment WHERE attachment_code=$code AND attachment_key='$session' AND attachment_type=3");
			$edit = "upload-slideshow.php?action=edit&id=$form_id&code=$code&type=3";
			$delete = "upload-slideshow.php?action=delete&id=$form_id&code=$code&type=3";
			$thumb = "articleslide/t0_";
			break;
		case 4: 
			if($id != 0)
				$results1 = dbQuery("SELECT slideshow_slide_id, slideshow_slide_src, slideshow_slide_title FROM slideshow_slide WHERE slideshow_slide_slideshow=$form_id");
			else
				$results2 = dbQuery("SELECT attachment_id, attachment_thumb, attachment_title FROM attachment WHERE attachment_code=$code AND attachment_key='$session' AND attachment_type=4");
			$edit = "upload-slideshow2.php?action=edit&id=$form_id&code=$code&type=4";
			$delete = "upload-slideshow2.php?action=delete&id=$form_id&code=$code&type=4";
			$thumb = "slide/t0_";
			break;
	}
	echo "<table>";
	$row = 0;
	$col = 9999;
	if($id != 0){
		while($rec = dbFetchArray($results1)){
			if($col >= 5){
				if($row > 0)
					echo "</tr>";
				echo "<tr valign='bottom'>";
				$col = 0;
				$row++;
			}
			if($rec[1] == "")
				$rec[1] = "no-thumb.jpg";
			else
				$rec[1] = "$thumb$rec[1]";
			echo "<td><a href='$edit&aid=0&pid=$rec[0]'><img src='../upload/$rec[1]'><br>".substr($rec[2],0, 30)."</a><a href='$delete&aid=0&pid=$rec[0]'><img title='Delete Content Page Photo' height='14' src='images/del.png' border='0'/></a></td>\n";
			$col++;
		}
	}
	else{
		while($rec = dbFetchArray($results2)){
			if($col >= 5){
				if($row > 0)
					echo "</tr>";
				echo "<tr valign='top'>";
				$col = 0;
				$row++;
			}
			if($rec[1] == "")
				$rec[1] = "no-thumb.jpg";
			echo "<td><a href='$edit&pid=0&aid=$rec[0]'><img border='0' src='../upload/$rec[1]'><br>$rec[2]</a><a href='$delete&pid=0&aid=$rec[0]'><img title='Delete Content Page Photo' height='14' src='images/del.png' border='0'/></a></td>\n";
			$col++;
		}
	}
	echo "</tr></table>";
}

function editForm($code, $type, $pid, $aid){
	global $form_id;
	if($aid != 0){
		$rec = getFields("", dbRow("attachment", $aid), "SHOW");
		$name = "attachment";
	}
	else{
		switch($type){
			case 1:
				$rec = getFields("", dbRow("content_photo", $pid), "SHOW");
				break;
			case 2:
				$rec = getFields("", dbRow("content_video", $pid), "SHOW");
				break;
			case 3:
				$rec = getFields("", dbRow("content_slide", $pid), "SHOW");
				break;
			case 4:
				$rec = getFields("", dbRow("slideshow_slide", $pid), "SHOW");
				break;
		}
	}
	switch($type){
		case 1:
			$name = "photo";
			$name2 = "photos";
			break;
		case 2:
			$name = "video";
			$name2 = "videos";
			break;
		case 3:
			$name = "slide";
			$name2 = "slideshow";
			break;
		case 4:
			$name = "slide";
			$name2 = "slideshow2";
			break;
	}
	echo "<h2>Edit $name</h2>";
	if(numErrors() > 0){
		printErrors();
		$rec = getFields(defaultTable, $_POST, "SHOW");
	}
	echo "<form action='upload-$name2.php' method='post' enctype='multipart/form-data'>
			<input type=hidden name=id value='$form_id'>
			<input type=hidden name=pid value='$pid'>
			<input type=hidden name=aid value='$aid'>
			<input type=hidden name=code value='$code'>
			<input type=hidden name=type value='$type'>
			<input type='hidden' name='action' value='EditRecord'/>
		<table class='form'><tbody>";

	if($aid != 0){
		printTextField("Title", $rec, "attachment_title", 40, 80);
		switch($type){
			case 2:
				printFileUploadField("Video Thumbnail", $rec, "Filedata");
				break;
			default:
				printFileUploadField("Change Attachment", $rec, "Filedata");
				break;
		}
	}
	else{
		switch($type){
			case 1:
				printTextField("Photo Title", $rec, "content_photo_title", 40, 80);
				printFileUploadField("Change Photo", $rec, "Filedata");
				break;
			case 2:
				printTextField("Video Title", $rec, "content_video_title", 40, 80);
				printFileUploadField("Video Thumbnail", $rec, "Filedata");
				break;
			case 3:
				//printTextField("Slide Title", $rec, "content_slide_title", 40, 80);
				
				printHTMLField("Slide Title", $rec, "content_slide_title", "Basic");
				printFileUploadField("Change Slide", $rec, "Filedata");
				break;
			case 4:
				printHTMLField("Slide Title", $rec, "slideshow_slide_title", "Basic");
				printFileUploadField("Change Slide", $rec, "Filedata");
				break;
		}
	}

	echo "	</thead>
		<tfoot>
			<tr class='bar'>
			<td colspan='2'>
				<input style='float: left' class='submitButton' type='submit' value='Edit $name'/>
				<input style='float: right' class='cancelButton' type='button' value='Cancel' onclick=\"location.href='".this_php.defaultParams."'\">
			</td></tr>
		</tfoot></table>
		</form>
	";
}
function editRecord($code, $type, $pid, $aid){
	if($aid != 0){
		$fields = getFields("attachment", $_POST, "SAVE");
		if($_FILES["Filedata"]["name"] != ""){
			if($type == 2){	// Thumbnail of video
				$fields["attachment_thumb"] = uploadFile("Filedata", "", "", true, "upload/");
			}
			else {	// change file
				$fields["attachment_name"] = uploadFile("Filedata", "", "", true, "upload/");
				$fields["attachment_thumb"] = makeThumb($fields["attachment_name"], "thumb_{$fields["attachment_name"]}", 80, 80);
			}
		}
		dbPerform("attachment", $fields, 'update', "attachment_id=$aid");
	}
	else{
		switch($type){
			case 1:
				$fields = getFields("content_photo", $_POST, "SAVE");
				if($_FILES["Filedata"]["name"] != ""){
					$fields["content_photo_src"] = uploadPhoto("cphoto", $pid, "Filedata", "upload/articlephoto/");
//					makeThumb($fields["content_photo_src"], "cphoto_t$pid.jpg", 80, 80, "Filedata", "upload/articlephoto/");
					makeThumb($fields["content_photo_src"], "t0_cphoto_$pid.jpg", 150, 100, "Filedata", "upload/articlephoto/");
					makeThumb($fields["content_photo_src"], "t1_cphoto_$pid.jpg", 100, 999, "Filedata", "upload/articlephoto/");
					makeThumb($fields["content_photo_src"], "t2_cphoto_$pid.jpg", 470, 999, "Filedata", "upload/articlephoto/");
					
				}
				dbPerform("content_photo", $fields, 'update', "content_photo_id=$pid");
				break;
			case 2:
				$fields = getFields("content_video", $_POST, "SAVE");
				if($_FILES["Filedata"]["name"] != ""){
					$fields["content_video_thumb"] = uploadPhoto("cvideo", $pid, "Filedata", "upload/");
//					makeThumb($fields["content_video_thumb"], "t0_cvideo_$pid.jpg", 150, 100, "Filedata", "upload/");
//					makeThumb($fields["content_video_thumb"], "t1_cvideo_$pid.jpg", 100, 999, "Filedata", "upload/");
//					makeThumb($fields["content_video_thumb"], "t2_cvideo_$pid.jpg", 470, 999, "Filedata", "upload/");
		
				}
				dbPerform("content_video", $fields, 'update', "content_video_id=$pid");
				break;
			case 3:
				$fields = getFields("content_slide", $_POST, "SAVE");
				if($_FILES["Filedata"]["name"] != ""){
					$fields["content_slide_src"] = uploadPhoto("cslide", $pid, "Filedata", "upload/articleslide/");
					$fields["content_slide_thumb"] = makeThumb($fields["content_slide_src"], "cslide_t$pid.jpg", 80, 80, "Filedata", "upload/articleslide/");
				}
				dbPerform("content_slide", $fields, 'update', "content_slide_id=$pid");
				break;
			case 4:
				$fields = getFields("slideshow_slide", $_POST, "SAVE");
				if($_FILES["Filedata"]["name"] != ""){
					$fields["slideshow_slide_src"] = uploadPhoto("slide", $pid, "Filedata", "upload/slide/");
//					$fields["slideshow_slide_thumb"] = makeThumb($fields["content_slide_src"], "cslide_t$pid.jpg", 80, 80, "Filedata", "upload/articleslide/");
					makeThumb($fields["slideshow_slide_src"], "t0_slide_$pid.jpg", 150, 100, "Filedata", "upload/slide/");
					makeThumb($fields["slideshow_slide_src"], "t1_slide_$pid.jpg", 100, 999, "Filedata", "upload/slide/");
					makeThumb($fields["slideshow_slide_src"], "t2_slide_$pid.jpg", 470, 999, "Filedata", "upload/slide/");
				}
				$fields["slideshow_slide_created"] = time();
				dbPerform("slideshow_slide", $fields, 'update', "slideshow_slide_id=$pid");
				break;
		}
	}
}

function askDelete($code, $type, $pid, $aid){
	global $form_id;
	if($aid != 0){
		$rec = getFields("", dbRow("attachment", $aid), "SHOW");
		$name = "attachment";
		$title = $rec["attachment_title"];
	}
	else{
		switch($type){
			case 1:
				$rec = getFields("", dbRow("content_photo", $pid), "SHOW");
				$name = "photo";
				$title = $rec["content_photo_title"];
				break;
			case 2:
				$rec = getFields("", dbRow("content_video", $pid), "SHOW");
				$name = "video";
				$title = $rec["content_video_title"];
				break;
			case 3:
				$rec = getFields("", dbRow("content_slide", $pid), "SHOW");
				$name = "slide";
				$title = $rec["content_slide_title"];
				break;
			case 4:
				$rec = getFields("", dbRow("slideshow_slide", $pid), "SHOW");
				$name = "slide";
				$title = $rec["slideshow_slide_title"];
				break;
		}
	}
	echo "<h2>Delete $name</h2>Are you sure you want to delete <font class=highlighted>$title</font>?
		<br><br>
		<form action='".this_php."' method=post>
			<input type=hidden name=action value='confirmdelete'>
			<input type=hidden name=id value='$form_id'>
			<input type=hidden name=pid value='$pid'>
			<input type=hidden name=aid value='$aid'>
			<input type=hidden name=code value='$code'>
			<input type=hidden name=type value='$type'>
			<table><tr>
				<td><input type=submit class='delete' value='Yes -- Delete'></td>
				<td><input type=button class='nodelete' value='No -- Cancel' onclick=\"location.href='".this_php.defaultParams."'></td>
			</tr></table>
		</form>
	";
}
function deleteRecord($code, $type, $pid, $aid){
	if($aid != 0)
		dbDeleteRecord("attachment", $aid);
	else{
		switch($type){
			case 1:
				dbDeleteRecord("content_photo", $pid);
				break;
			case 2:
				dbDeleteRecord("content_video", $pid);
				break;
			case 3:
				dbDeleteRecord("content_slide", $pid);
				break;
			case 4:
				dbDeleteRecord("slideshow_slide", $pid);
				break;
		}
	}
}

?>
