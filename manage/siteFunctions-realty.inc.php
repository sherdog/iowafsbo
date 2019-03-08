<?php


function getListings($sql, $display='grid', $page='index', $listingTitle=''){
	
	global $params; //querystring stuff
	global $form_page;
	global $num;
	global $pages;
	global $recStart;
	global $resultsPerPage;
	global $reqString;
	
	if ($page=='index')
	{
		$cols = 3;
	}
	else
	{
		$cols = 4;
	}
	
	if ($page != 'index') 
	{
		echo "<form action=\"listings.php\" method=\"GET\">\n";
		//hidden vals used to sent as well!
		echo "<input type=\"hidden\" name=\"search\" valule=\"true\">\n";
		echo "<input type=\"hidden\" name=\"city\" value=\"".$_GET['city']."\">\n";
		echo "<input type=\"hidden\" name=\"type\" value=\"".$_GET['type']."\">\n";
		echo "<input type=\"hidden\" name=\"beds\" value=\"".$_GET['beds']."\">\n";
		echo "<input type=\"hidden\" name=\"baths\" value=\"".$_GET['baths']."\">\n";
		echo "<input type=\"hidden\" name=\"price\" value=\"".$_GET['price']."\">\n";
		echo "<input type=\"hidden\" name=\"page\" value=\"".$_GET['page']."\">\n";

		echo "<b>Order By:</b> <select style=\"float:right:\" name=\"orderby\" onChange=\"this.form.submit();\">\n";
		echo "<option value=\"priceasc\"";
			if($_GET['orderby'] == 'priceasc') echo " selected";
		echo ">Price Lowest to Highest</option>\n";
		echo "<option value=\"pricedesc\"";
			if($_GET['orderby'] == 'pricedesc') echo " selected";
		echo ">Price Hightest to Lowest</option>\n";
		echo "<option value=\"dateasc\"";
			if($_GET['orderby'] == 'dateasc') echo " selected";
		echo ">Date Oldest to Newest</option>\n";
		echo "<option value=\"datedesc\"";
			if($_GET['orderby'] == 'datedesc') echo " selected";
		echo ">Date Newest to Oldest</option>\n";
		echo "</select>\n";
		echo "</form>\n";
		
		//print_r(buildQuery());
		//echo "Num: " . print_r($url) . "<br>";
		//echo "RPP: " . $resultsPerPage . "<br>";
		if($num > $resultsPerPage)
		{
		
		
			echo "<div id=\"pagination\" class=\"tan12\">";
			$page = $form_page + 1;
			//echo "<form action='".this_php."'>\n";
			echo "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">\n";
			
			echo "<tr>\n";
			echo "<td class=\"tan12\">Page: ";
			
			//echo "<select name='page' onchange='this.form.submit()'>";
			for($a = 0,$b = 1;$a < $pages;$a++,$b++)
			{
				$sel = "";
				if($a == $form_page)
						$sel = " style=\"font-size:12px; font-weight:bold; border-bottom:1px solid #333; padding:4px; background:#8E897A; \" ";
				echo "<a href=\"listings.php".$params."page=$a\" class=\"listing\" ".$sel.">$b</a> ";
				//<option$sel value='$a'>$b</option>";
			}
			//echo "</select>\n";
			echo "</td>\n";
			echo "<td align=\"right\" class=\"tan12\" style=\"font-weight;bold;\">Page $page of $pages</td>\n";
			echo "</tr>\n";
			echo "</table>\n";
			//	echo "</form>";
			echo "</div>\n"; 
		}
	} 

	if($num)
	{
		
		$i = 0;
		
		while($rec = dbFetchArray($sql))
		{
		
			$imgSQL = 'SELECT * FROM listing_images WHERE listing_images_filename = "'.$rec['listing_number'].'.jpg"';
			$imgResults = dbQuery($imgSQL);

			if(dbNumRows($imgResults))
			{
				$img = dbFetchArray($imgResults);
				$imageName = $img['listing_images_filename'];	
			}
			else
			{
				//no image... show noimage.jpg
				$imageName = 'noimage.jpg';	
			}
				
				if ($page == 'index')
				{
					?>
					 <div class="col-lg-3 col-md-6 mb-4">
				        <div class="card h-100">
				        	<a href="mls<?php echo $rec['listing_number']; ?>.html" title="<?php echo $rec['listing_street']; ?> <?php echo $rec['listing_city']; ?> <?php echo $rec['listing_state']; ?>">
				        		<img class="card-img-top" src="http://placehold.it/500x325" alt="">
				          		<!--<img class="card-img-top" src="<?php echo SITE_URL.UPLOAD_DIR . get_thumbnail_filename($imageName, 'small'); ?>" /> -->
				          	</a>
				          	<div class="card-body">
				            	<h4 class="card-title"><?php echo $rec['listing_street'].", " . $rec['listing_city'] . " "; ?></h4>
				            	<p class="card-text">
				            	<?php echo $rec['listing_total_bdrms']." bdr, " . $rec['listing_total_baths'] . " bth"; ?> <br />
				            	1,230 sq. ft.<br
				            	<br />
				            	<h3>$<?php echo number_format($rec['listing_price']); ?></h3>
				            	</p>
				            	<p class="card-text"></p>
				          	</div>
				          	<div class="card-footer">
				            	<a href="mls<?php echo $rec['listing_number']; ?>.html" class="btn btn-primary">View</a>
				        	</div>
				    	</div>
				    </div>
				    <?php
				}
				else
				{
					?>
					 <div class="col-lg-3">
				        <div class="card h-100">
				        	<a href="mls<?php echo $rec['listing_number']; ?>.html" title="<?php echo $rec['listing_street']; ?> <?php echo $rec['listing_city']; ?> <?php echo $rec['listing_state']; ?>">
				        		<img class="card-img-top" src="http://placehold.it/500x325" alt="">
				          		<!--<img class="card-img-top" src="<?php echo SITE_URL.UPLOAD_DIR . get_thumbnail_filename($imageName, 'small'); ?>" /> -->
				          	</a>
				          	<div class="card-body">
				            	<h4 class="card-title"><?php echo $rec['listing_street'].", " . $rec['listing_city'] . " "; ?></h4>
				            	<p class="card-text">
				            	<?php echo $rec['listing_total_bdrms']." bdr, " . $rec['listing_total_baths'] . " bth"; ?> <br />
				            	1,230 sq. ft.<br
				            	<br />
				            	<h3>$<?php echo number_format($rec['listing_price']); ?></h3>
				            	</p>
				            	<p class="card-text"></p>
				          	</div>
				          	<div class="card-footer">
				            	<a href="mls<?php echo $rec['listing_number']; ?>.html" class="btn btn-primary">View</a>
				        	</div>
				    	</div>
				    </div>
				    <?php
				}
				$i++;
		}//end while

	} 
	else 
	{
		echo "<h2> Error: No results returned</h2>";
	}
	

	if($page != 'index'){
	if($num > $resultsPerPage){
		
			$page = $form_page + 1;
			//echo "<form action='".this_php."'>\n";
			echo "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">\n";
			echo "<tr>\n";
			echo "<td class=\"tan12\">Page:";
			
			//echo "<select name='page' onchange='this.form.submit()'>";
			for($a = 0,$b = 1;$a < $pages;$a++,$b++)
			{
				$sel = "";
				//echo $form_page;
				if($a == $form_page) {
					$sel = " style=\"font-size:12px; font-weight:bold; border-bottom:1px solid #333; padding:4px; background:#8E897A; \" ";
				}
				echo "<a href=\"listings.php".$params."page=$a\" class=\"listing\" ".$sel.">$b</a> ";
				//<option$sel value='$a'>$b</option>";
			}
			//echo "</select>\n";
			echo "</td>\n";
			echo "<td align=\"right\" class=\"tan12\" style=\"font-weight;bold;\">Page $page of $pages</td>\n";
			
			echo "</tr>\n";
			echo "</table>\n";
		//	echo "</form>";
		}
	}
}

function buildQuery() {
	global $reqString;
	
	$str = explode("?", $reqString);
	$substr = explode("&", $str[1]);
	return($reqString);
	
}

function printRecordNavigation2($pageNumber, $numRecords, $link, $cutOffWidth=3){
	
	global $resultsPerPage;
	
	$numPages = ceil($numRecords / $resultsPerPage);
	$skipped = false;
	$prevPage = $pageNumber - 1;
	$nextPage = $pageNumber + 1;
	$pageNumber++;
	if($numRecords <= recordsPerPage)
		return;
	
	echo "  <td width='10%' class='mainText'><span class='mainText' style='padding-right:3px;'><span class='mainText' style='padding-right:3px;'>Page&nbsp;$pageNumber&nbsp;of&nbsp;$numPages</span></span></td>
			<td width='90%' align='right'><table border='0' cellspacing='0' cellpadding='0'>
			<tr>
	";

	if($prevPage >= 0)
		echo "<td><span class='mainText' style='padding-right:3px;'><a class='prevArrow' href='{$link}page=$prevPage'><img src='images/b_prev.gif' alt='Prev' name='prev' width='49' height='23' border='0'/></a></span></td><td><span class='mainText' style='padding-right:3px;'>";
	else
		echo "<td><span class='mainText' style='padding-right:3px;'><img src='images/b_prev.gif' alt='Prev' name='prev' width='49' height='23' border='0'/></span></td><td><span class='mainText' style='padding-right:3px;'>";
	
	if($numPages < $cutOffWidth){
		for($ipage = 1, $pagelink = 0;$pagelink < $numRecords;$ipage++, $pagelink += recordsPerPage){
			if($ipage != $pageNumber)
				echo "<a class='pagination' href='{$link}page=" . ($ipage-1) . "''>$ipage</a> ";
			else
				echo "$ipage ";
		}
	} else {
		$start = $pageNumber - ($cutOffWidth / 2);
		if($start < 1)
			$start = 1;
		else if($start + $cutOffWidth > $numPages)
			$start = $numPages - $cutOffWidth + 1;
		for($a = 0, $ipage = $start, $pagelink = 0;$a < $cutOffWidth;$a++, $ipage++, $pagelink += recordsPerPage){
			if($ipage != $pageNumber)
				echo "<a class='pagination' href='{$link}page=" . ($ipage-1) . "''>$ipage</a> ";
			else
				echo "$ipage ";
		}
	}
	//next button
	if($nextPage < $numPages)
		echo "</span></td><td><span class='mainText' style='padding-right:3px;'><a href='{$link}page=$nextPage'><img class='nextArrow' src='images/b_next.gif' name='next' width='49' height='23' border='0'/></a></span></td></tr></table></td>\n";
	else
		echo "</span></td><td><span class='mainText' style='padding-right:3px;'><img src='images/b_next.gif' name='next' width='49' height='23' border='0'/></span></td></tr></table></td>\n";

}

//uploadZip uploads zip into upload dir, then we extract the contents of the zip into a specific directory. 
function uploadZip($file, $filename='') {
	if($filename == '') $filename = $_FILES[$file]['name'];  
	move_uploaded_file($_FILES[$file]["tmp_name"], MANAGE_PATH.UPLOAD_DIR_ZIP.$filename);
	//$theFile = $_FILES[$file]['name'];
	//unzip(MANAGE_PATH.UPLOAD_DIR_ZIP.$theFile, false, false, true);
	///@unlink(SITE_PATH.UPLOAD_DIR.$theFile);
}




/**
 * Unzip the source_file in the destination dir
 *
 * @param   string      The path to the ZIP-file.
 * @param   string      The path where the zipfile should be unpacked, if false the directory of the zip-file is used
 * @param   boolean     Indicates if the files will be unpacked in a directory with the name of the zip-file (true) or not (false) (only if the destination directory is set to false!)
 * @param   boolean     Overwrite existing files (true) or not (false)
 * 
 * @return  boolean     Succesful or not
 // Extract C:/zipfiletest/zip-file.zip to C:/zipfiletest/zip-file/ and overwrites existing files
unzip("C:/zipfiletest/zip-file.zip", false, true, true);

// Extract C:/zipfiletest/zip-file.zip to C:/another_map/zipfiletest/ and doesn't overwrite existing files. NOTE: It doesn't create a map with the zip-file-name!
unzip("C:/zipfiletest/zip-file.zip", "C:/another_map/zipfiletest/", true, false);
 */
 
 
function unzip($src_file, $dest_dir=false, $create_zip_name_dir=true, $overwrite=true)
{
  if ($zip = zip_open($src_file))
  {
    if ($zip)
    {
      $splitter = ($create_zip_name_dir === true) ? "." : "/";
      if ($dest_dir === false) $dest_dir = substr($src_file, 0, strrpos($src_file, $splitter))."/";
     
      // Create the directories to the destination dir if they don't already exist
      create_dirs($dest_dir);
	  $fileCount = 1;
      // For every file in the zip-packet
      while ($zip_entry = zip_read($zip))
      {
        // Now we're going to create the directories in the destination directories
       
        // If the file is not in the root dir
        $pos_last_slash = strrpos(zip_entry_name($zip_entry), "/");
        if ($pos_last_slash !== false)
        {
          // Create the directory where the zip-entry should be saved (with a "/" at the end)
          create_dirs($dest_dir.substr(zip_entry_name($zip_entry), 0, $pos_last_slash+1));
        }

        // Open the entry
        if (zip_entry_open($zip,$zip_entry,"r"))
        {
         
          // The name of the file to save on the disk
          $file_name = $dest_dir.zip_entry_name($zip_entry);
          $filename = zip_entry_name($zip_entry);
          // Check if the files should be overwritten or not
          if ($overwrite === true || $overwrite === false && !is_file($file_name))
          {
            // Get the content of the zip entry
            $fstream = zip_entry_read($zip_entry, zip_entry_filesize($zip_entry));

            file_put_contents($file_name, $fstream );
            // Set the rights
           	@chmod($file_name, 0777);
           // echo "save: ".$file_name."<br />";
			
			//***************************** CREATE THUMBS AND SAVE IN DB***************************************
			
			
			$imgcheck = dbQuery('SELECT * FROM listing_images WHERE listing_images_filename = "'.$filename.'"');
			
			if(dbNumRows == 0) { //see if any of the ones we are about to upload exist
			
				//move_uploaded_file($_FILES['Filedata']['tmp_name'], SITE_PATH.UPLOAD_DIR.$_FILES['Filedata']['name']);
				//upload_file($_FILES['Filedata']['tmp_name'], $filename);
				make_thumbnail($filename, '../uploads/', 150, "", "small");
				make_thumbnail($filename, '../uploads/', 760, "", "main") ;
				make_thumbnail($filename, '../uploads/', 225, "", "thumb");
				make_thumbnail($filename, '../uploads/', 100, "", "tiny");
								
				$number = explode('.', $filename);
				
				$fields['listing_images_id'] = $number[0];//grabs name of file (since we are using Ultrex, this will have to be a setting in the cp
				$fields['listing_images_date_added'] = time();
				$fields['listing_images_filename'] = $filename;
				
				$update = dbPerform('listing_images', $fields, 'insert'); 
			
			}
			//***************************** END CREATE THUMBS AND SAVE IN DB***************************************
			
			
          }
         
          // Close the entry
          zip_entry_close($zip_entry);
        }      
      $fileCount++;
	  }
      // Close the zip-file
      zip_close($zip);
    }
  }
  else
  {
    return false;
  }
 
  return $fileCount;
}

define('FILE_APPEND', 1);


/**
 * This function creates recursive directories if it doesn't already exist
 *
 * @param String  The path that should be created
 * 
 * @return  void
 */
function create_dirs($path)
{
  if (!is_dir($path))
  {
    $directory_path = "";
    $directories = explode("/",$path);
    array_pop($directories);
   
    foreach($directories as $directory)
    {
      $directory_path .= $directory."/";
      if (!is_dir($directory_path))
      {
        @mkdir($directory_path);
        @chmod($directory_path, 0777);
      }
    }
  }
}

?>