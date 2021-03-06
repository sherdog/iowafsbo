<?

function printHiddenField($arr, $prefix){
	if(gettype($arr) == "array"){
		foreach($arr as $name=>$value){
			if(strncmp($name, $prefix, strlen($prefix)) == 0){
				echo "<input type='hidden' name='$name' value=\"$value\"/>\n";
			}
		}
	}
	else
		echo "<input type='hidden' name='$arr' value=\"$prefix\"/>";
}

function printNumberField($name, $fields, $fname, $msg=""){
	$fname = str_replace("%", defaultTable."_"	, $fname);
	echo "<tr><td class='fieldLabel'><label for='$fname' class='formLabelText'>$name:</label></td>	<td><input id='$fname' title='$name' type='text' name='$fname' value='{$fields[$fname]}' size='4' maxlength='8'/>$msg</td></tr>\n";
}

function printTextField($name, $fields, $fname, $size=40, $max=80, $onchange=null, $rowid="", $rowclass=""){
	$fname = str_replace("%", defaultTable."_"	, $fname);
	echo "<tr";
	if($rowid != "")
		echo " id='$rowid'";
	if($rowclass != "")
		echo " class='$rowclass'";
	echo "><td class='fieldLabel'><label for='$fname' class='formLabelText'>$name:</label></td>	<td><input id='$fname' title='$name' type='text' name='$fname' value='{$fields[$fname]}' size='$size' maxlength='$max'";
	if($onchange != null)
		echo " onchange=\"$onchange\"";
	echo "/></td></tr>\n";
}

function printCheckboxField($name, $fields, $field, $value, $msg=""){
	$field = str_replace("%", defaultTable."_"	, $field);
	$chk = "";
	if($fields[$field] == $value)
		$chk = "checked";
	echo "<tr><td class='fieldLabel'><label for='$field' class='formLabelText'>$name:</label></td>	<td><label><input id='$field' title='$name' type='checkbox' $chk name='$field' value='$value'> $msg</label></td></tr>\n";
}

function printFileUploadField($name, $fields, $fname){
	$fname = str_replace("%", defaultTable."_"	, $fname);
	echo "<tr><td class='fieldLabel'><label for='$fname' class='formLabelText'>$name:</label></td>	<td><input id='$fname' title='$name' type='file' name='$fname'></td></tr>\n";
}

function printTextAreaField($name, $fields, $fname, $rows=40, $cols=80){
	$fname = str_replace("%", defaultTable."_"	, $fname);
	echo "<tr><td class='fieldLabel' valign='top'><label for='$fname' class='formLabelText'>$name:</label></td>	<td><textarea id='$fname' title='$name' type='text' name='$fname' rows='$rows' cols='$cols'>{$fields[$fname]}</textarea></td></tr>\n";
}

function printHTMLField($name, $fields, $field, $toolbar='ControlPanel', $width=0, $height=0){
	global $issetFCKeditor;
	$field = str_replace("%", defaultTable."_"	, $field);
	echo "<tr><td class='fieldLabel'><label for='$field' class='formLabelText'>$name:</label></td>	<td>";
	if(class_exists("FCKeditor")){
		$sBasePath = "fckeditor/";
//		$sBasePath = substr( $sBasePath, 0, strpos( $sBasePath, "_samples" ) ) ;
		$oFCKeditor = new FCKeditor($field) ;
		$oFCKeditor->BasePath	= $sBasePath ;
		$oFCKeditor->Value = html_entity_decode($fields[$field]);
		$oFCKeditor->ToolbarSet = $toolbar;
		if($width != 0){
			$oFCKeditor->Width = $width;
		}
		if($height != 0)
			$oFCKeditor->Height = $height;
		$oFCKeditor->Create() ;
	}
	else{
		echo "<h1>FCK editor not setup</h1>
			<textarea id='$field' title='$name' type='text' name='$field' rows='6' cols='40'>{$fields[$field]}</textarea>
		";
	}
	echo "</td></tr>\n";
}

function printDateField($name, $fields, $field){
	global $jscalendar;
	$field = str_replace("%", defaultTable."_"	, $field);
	$date = strftime("%m/%d/%Y", $fields[$field]);
	echo "<tr><td class='fieldLabel'><label for='$field' class='formLabelText'>$name:</label></td>	<td>";
	// calendar options go here; see the documentation and/or calendar-setup.js
	if(isset($jscalendar)){
		$jscalendar->make_input_field(array('firstDay'       => 0, // show Monday first
			'showsTime'      => false,
			'showOthers'     => true,
			'ifFormat'       => '%m/%d/%Y',
			'weekNumbers'	=> false),
		// field attributes go here
		array('style'       => 'width: 7em; border: 1px solid #000',
			'name'        => $field,
			'value'       => $date));
	}
	else{
		echo "<h1>JS Calendar not setup</h1>
			<input id='$field' title='$name' type='text' name='$field' value='$date' size='10'>
		";
	}
	echo " <span class='subtext'>(format: mm/dd/yyyy)</span></td></tr>\n";
}
function printDateTimeField($name, $fields, $field){
	global $jscalendar;
	$field = str_replace("%", defaultTable."_"	, $field);
	$date = strftime("%m/%d/%Y %H:%M", $fields[$field]);
	echo "<tr><td class='fieldLabel'><label class='formLabelText' for='$field'>$name:</label></td>	<td>";
	// calendar options go here; see the documentation and/or calendar-setup.js
	if(isset($jscalendar)){
		$jscalendar->make_input_field(array('firstDay'       => 0, // show Monday first
			'showsTime'      => true,
			'showOthers'     => true,
			'ifFormat'       => '%m/%d/%Y %H:%M',
			'weekNumbers'	=> false),
		// field attributes go here
		array('style'       => '',
			'name'        => $field,
			'value'       => $date));
	}
	else{
		echo "<h1>JS Calendar not setup</h1>
			<input id='$field' title='$name' type='text' name='$field' value='$date' size='16'>
		";
	}
	echo " <span class='subtext'>(format: mm/dd/yyyy hh:mm)</span></td></tr>\n";
}
function printDateInfoField($name, $fields, $field){
	$field = str_replace("%", defaultTable."_"	, $field);
	$info = strftime("%m/%d/%Y %I:%M %P - ", $fields[$field]) . timeAgo($fields[$field]);
	echo "<tr><td class='fieldLabel'><label class='formLabelText'>$name:</label></td><td>$info</td></tr>\n";
}

function printTableSeperator($title){
	echo "</tbody></table><div class='h2 sep'>$title</div>\n<table class='form'><tbody>";
}

function printListField($name, $fields, $field, $list){
	$field = str_replace("%", defaultTable."_"	, $field);

	echo "<tr><td class='fieldLabel'><label class='formLabelText' for='$field'>$name:</label></td>	<td><select id='$field' title='$name' name='$field'>\n";
	foreach($list as $itemz){
		$list = explode("|", $itemz);
		$sel = "";
		if($list[0] == $fields[$field])
			$sel = " selected";
		echo "<option$sel value='$list[0]' title='$list[1]'>$list[1]</option>\n";
	}
	echo "</select></td></tr>\n";
}

function printDBRecordSelect($name, $fields, $field, $query, $edit=NULL, $button=NULL, $field2=NULL, $none=FALSE){
	$field = str_replace("%", defaultTable."_"	, $field);

	echo "<tr><td class='fieldLabel'><label for='$field' class='formLabelText'>$name:</label></td>	<td><select id='$field' title='$name' name='$field' style='width:200px;'>\n";
	if($none)
		echo "<option value=0>None</option>\n";
	$results = dbQuery($query);
	while($rec = dbFetchArray($results)){
		$id = $rec[0];
		$val = $rec[1];
		$sel = "";
		if($id == $fields[$field])
			$sel = " selected";
		echo "<option$sel value='$id' title=\"$val\">$val</option>\n";
	}
	echo "</select>";
	if($edit != NULL)
		echo " <a href='$edit'>Edit List</a>";
	if($button != NULL){
		$code = randomCode(10);
		$value = $_REQUEST[$field2];
		if($value == ""){
			echo " <input type='button' onclick=\"this.style.display='none';zelem = document.getElementById('$code');zelem.style.display='inline';zelem.focus()\" value='$button'/>\n";
			echo " <input id='$code' type='text' style='display:none' name='$field2' value='$value'/>\n";
		}
		else
			echo " <input id='$code' type='text' name='$field2' value='$value'/>\n";

	}
	echo "</td></tr>\n";
}

function printStateField($name, $fields, $fname, $onchange=null, $rowid="", $rowclass=""){
	$fname = str_replace("%", defaultTable."_"	, $fname);
	echo "<tr";
	if($rowid != "")
		echo " id='$rowid'";
	if($rowclass != "")
		echo " class='$rowclass'";
	echo "><td class='fieldLabel'><label for='$fname' class='formLabelText'>$name:</label></td>	<td><select id='$fname' title='$name' name='$fname'";
	if($onchange != null)
		echo " onchange=\"$onchange\"";
	echo ">";
	$states = getStates();
	foreach($states as $state){
		$state = explode("|", $state);
		$sel = "";
		if($fields[$fname] == $state[0])
			$sel = " selected";
		echo "<option$sel value='$state[0]'>$state[1]</option>\n";
	}
	echo "</select></td></tr>\n";
}

function printSelectionField($name, $fields, $fname, $choices, $onchange=null, $rowid="", $rowclass=""){
	$fname = str_replace("%", defaultTable."_"	, $fname);
//	dump($fields);
	echo "<tr";
	if($rowid != "")
		echo " id='$rowid'";
	if($rowclass != "")
		echo " class='$rowclass'";
	echo "><td class='fieldLabel'><label for='$fname' class='formLabelText'>$name:</label></td>	<td><select id='$fname' title='$name' name='$fname'";
	if($onchange != null)
		echo " onchange=\"$onchange\"";
	echo ">";
	$states = explode("^", $choices);
	foreach($states as $state){
		$state = explode("|", $state);
		$sel = "";
		if($fields[$fname] == $state[0])
			$sel = " selected";
		echo "<option$sel value='$state[0]'>$state[1]</option>\n";
	}
	echo "</select></td></tr>\n";
}

function printImageField($name, $fields, $fname, $prefix="", $path="", $fullurl=false){
	$fname = str_replace("%", defaultTable."_"	, $fname);
	echo "<tr";
	if($rowid != "")
		echo " id='$rowid'";
	echo "><td class='fieldLabel'><label for='$fname' class='formLabelText'>$name:</label></td><td>";
	$img = $path . $prefix . $fields[$fname];
	if($fullurl)
		echo "<img src='$img'/>";
	else{
		$img2 = SITE_PATH . "manage/" . $img;
 		if(file_exists($img2))
 			echo "<img src='$img'/>";
 		else
 			echo "Does not exist <!-- $img -->";
	}
	echo "</td></tr>\n";
}

function printCityStateZip($fields, $type, $onchange=""){
	$states = getStates();

// 	$states[] = "|Unknown";
// 	$states[] = "AL|Alabama";
// 	$states[] = "AK|Alaska";
// 	$states[] = "AZ|Arizona";
// 	$states[] = "AR|Arkansas";
// 	$states[] = "CA|California";
// 	$states[] = "CO|Colorado";
// 	$states[] = "CT|Connecticut";
// 	$states[] = "DE|Delaware";
// 	$states[] = "DC|Washington D.C.";
// 	$states[] = "FL|Florida";
// 	$states[] = "GA|Georgia";
// 	$states[] = "HI|Hawaii";
// 	$states[] = "ID|Idaho";
// 	$states[] = "IL|Illinois";
// 	$states[] = "IN|Indiana";
// 	$states[] = "IA|Iowa";
// 	$states[] = "KS|Kansas";
// 	$states[] = "KY|Kentucky";
// 	$states[] = "LA|Louisiana";
// 	$states[] = "ME|Maine";
// 	$states[] = "MD|Maryland";
// 	$states[] = "MA|Massachusetts";
// 	$states[] = "MI|Michigan";
// 	$states[] = "MN|Minnesota";
// 	$states[] = "MS|Mississippi";
// 	$states[] = "MO|Missouri";
// 	$states[] = "MT|Montana";
// 	$states[] = "NE|Nebraska";
// 	$states[] = "NV|Nevada";
// 	$states[] = "NH|New Hampshire";
// 	$states[] = "NJ|New Jersey";
// 	$states[] = "NM|New Mexico";
// 	$states[] = "NY|New York";
// 	$states[] = "NC|North Carolina";
// 	$states[] = "ND|North Dakota";
// 	$states[] = "OH|Ohio";
// 	$states[] = "OK|Oklahoma";
// 	$states[] = "OR|Oregon";
// 	$states[] = "PA|Pennsylvania";
// 	$states[] = "RI|Rhode Island";
// 	$states[] = "SC|South Carolina";
// 	$states[] = "SD|South Dakota";
// 	$states[] = "TN|Tennessee";
// 	$states[] = "TX|Texas";
// 	$states[] = "UT|Utah";
// 	$states[] = "VT|Vermont";
// 	$states[] = "VA|Virginia";
// 	$states[] = "WA|Washington";
// 	$states[] = "WV|West Virginia";
// 	$states[] = "WI|Wisconsin";
// 	$states[] = "WY|Wyoming";

	if($type == "")
		$field = defaultTable . "_";
	else
		$field = defaultTable . "_{$type}_";
	$city = $fields["{$field}city"];
	$state = $fields["{$field}state"];
	$zip = $fields["{$field}zip"];
	if($onchange != "")
		$onchange = "onchange=\"$onchange\"";
	
	echo "<tr><td class='fieldLabel'><label for='{$field}city' class='formLabelText'>City/State/Zip:</label></td>	<td><input id='{$field}city' title='City' type='text' name='{$field}city' value='$city' size='20' maxlength='80' $onchange/>&nbsp;<select id='{$field}state' title='State' name='{$field}state' $onchange>";
	foreach($states as $statez){
		$astate = explode("|", $statez);
		$sel = "";
		if($astate[0] == $state)
			$sel = " selected";
		echo "<option$sel value='$astate[0]' title='$astate[1]'>$astate[0]</option>\n";
	}
	echo "</select>&nbsp;<input id='{$field}zip' title='Zip' type='text' size='5' maxlength='10' name='{$field}zip' value='$zip' $onchange/></td></tr>\n";
}

function printButtonLinkSeperator($line=TRUE, $msg=NULL){
	if($line)
		echo "<hr>";
	else
		echo "<br>";
	if($msg != NULL)
		echo "<div class='h2'>$msg</div>";
}

function printButtonLink($label, $link, $class=""){
	if($class != "")
		$class = " $class";
	echo "<input type='button' class='button$class' value='$label'	onclick=\"location.href='$link'\"> ";
}

function printBackButton($label='Back', $steps=-1, $class=""){
	if($class != "")
		$class = " $class";
	echo "<input type='button' class='button$class' value='$label'	onclick=\"history.go($steps)\">";
}

function printDbDataField($name, $query, $func=FALSE, $addlink=FALSE, $header=true, $class="records"){
	$results = dbQuery($query);
	$num = dbNumRows($results);
	
	echo "<tr><td class='fieldLabel'><label for='$field' class='formLabelText'>$name:</label>";
	if($addlink != FALSE && $num > 0){
		echo "<br>";
		printButtonLink("Add Record", $addlink);
	}
	echo "</td><td>";
	if($num == 0){
		echo "There are no records<br>";
		if($addlink != FALSE)
			printButtonLink("Add Record", $addlink);
	}
	else{
	//	echo "<table class='db'>";
		echo "<table class='$class'>";
		for($a = 0;$row = dbFetchArray($results);$a++){
			if($func != FALSE)
				$func($row);
			else{
				if($a == 0){
					//array_dump($row);
					if($header){
						echo "<thead>";
						$b = 1;	
						foreach(array_keys($row) as $v=>$f){
							if($b++ % 2 == 0)
								echo "<th>$f</th>\n";
						}
						echo "</thead>\n";
					}
					echo "<tbody>\n";
				}
				echo "<tr>";
				for($b = 0;isset($row[$b]);$b++){
					echo "<td>$row[$b]</td>\n";
				}
				echo "</tr>";
			}
		}
		echo "</tbody></table>\n";
	}
	echo "</td></tr>";
}

function showUploadForm($area='article') {
 //Show upload area!
 if($area == 'article'){
 
 	echo '<div id="SWFUploadTarget">';
  	echo '<input name="browse" type="button" id="SWFUpload_0BrowseBtn" value="Browse" />';
	echo '</div>';
	echo '<h4 class="style1" id="queueinfo">Queue is empty</h4>';
	echo '<div id="SWFUploadFileListingFiles"></div>';
	echo '<br class="clr"/>';
	echo '<a id="SWFUpload_0UploadBtn" class="swfuploadbtn uploadbtn" href="#">Upload queue</a>';
	echo '<a class="swfuploadbtn" id="cancelqueuebtn" href="javascript:cancelQueue();">Cancel queue</a>';
 
 }else{
 	echo "<table width=\"100%\" cellpadding=\"5\" cellspacing=\"2\">\n";
	 echo "<tr><td class=\"fieldLabel\">File</td><td><input type=\"file\" name=\"articleImage\" class=\"cpButton\"></tr>";
	 echo "<tr><td class=\"fieldLabel\">Title</td><td><input type=\"text\" name=\"articleImageTitle\"></td></tr>\n";
	 echo "<tr><td class=\"fieldLabel\">Description</td><td><textarea name=\"articleImageDesc\" style=\"width:100%; height:50px;\"></textarea></td></tr>";
	 
	 echo "</table>\n";
 }
 
}

function printUploadJS($pathToUploadScript='../../upload-video.php?id=someid', $allowedFileTypes = '*.*') {
	echo <<<EOF
<script language=\"javascript\">
<!--
var swfu;

window.onload = function() {

swfu = new SWFUpload({
		
upload_script : '".$pathToUploadScript."', // This is reletive to the flash file located in /jscripts/SWFUploads/SWFUpload.swf
target : "SWFUploadTarget",
flash_path : "jscripts/SWFUpload/SWFUpload.swf",
allowed_filesize : 80720,	// 30 MB
allowed_filetypes : '$allowedFileTypes',
allowed_filetypes_description : "Flash Video Files",
browse_link_innerhtml : "Browse for files",
upload_link_innerhtml : "Upload queue",
browse_link_class : "swfuploadbtn browsebtn",
upload_link_class : "swfuploadbtn uploadbtn",
flash_loaded_callback : 'swfu.flashLoaded',
upload_file_queued_callback : "fileQueued",
upload_file_start_callback : 'uploadFileStart',
upload_progress_callback : 'uploadProgress',
upload_file_complete_callback : 'uploadFileComplete',
upload_file_cancel_callback : 'uploadFileCancelled',
upload_queue_complete_callback : 'uploadQueueComplete',
upload_error_callback : 'uploadError',
upload_cancel_callback : 'uploadCancel',
auto_upload : false
});
	
swfu.loadUI();
	
};
//-->
</script>
EOF;
}
?>
