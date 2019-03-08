<div id="searchtop">
  <form action="listings.php" method="GET" name="searchtop" id="searchtop" style="margin:0px; padding:0px;">
	<input type="hidden" name="orderby" value="<?=$_GET['orderby']?>">
    <table width="100%" border="0" cellspacing="0" cellpadding="3">
      <tr>
        <td valign="middle" align="right"><strong>City:</strong> 
        <?php
		$cityResults = dbQuery('SELECT DISTINCT listing_city FROM listing ORDER BY listing_city ASC');
			
		?>
        <select name="city" id="city">
        	<option  value="-1">No Preference</option>
            <? 
			
				while($city = dbFetchArray($cityResults)){
					if($city['listing_city'] != '') {
						if($city['listing_city'] != ""){
							echo "<option value=\"".stripslashes($city['listing_city'])."\"";
							if($_GET['city'] == $city['listing_city']) echo ' selected';
							echo ">".stripslashes($city['listing_city'])."</option>\n";
						}
					}
				}

		?>
        </select>
          <strong>Beds:</strong>
          <select name="beds" id="beds">
          <option  value="-1">Any</option>
             <?php
			 for($i=1; $i<=$settings['Max Bedrooms']; $i++){
			 	echo "<option value=\"".$i."\"";
				 if($i == $_GET['beds']) echo ' selected';
				echo ">".$i."</option>\n";
			 }
			 ?>
          </select>
          <strong>Bath:</strong>
          <select name="baths" id="baths">
          <option value="-1">Any</option>
          <?php
			 for($i=1; $i<=$settings['Max Bathrooms']; $i++){
			 	echo "<option value=\"".$i."\"";
				 if($i == $_GET['baths']) echo ' selected';
				echo ">".$i."</option>\n";
			 }
		   ?>
          </select>
          <strong>Style:</strong>
          <select name="type" id="type">
            <option value="-1">No Preference</option>
            <?
			$typeResults = dbQuery('SELECT DISTINCT listing_house_style FROM listing ORDER BY listing_house_style ASC');
			while($type = dbFetchArray($typeResults)){
				if($type['listing_house_style'] != ""){
					echo "<option value=\"".html_entity_decode(stripslashes($type['listing_house_style']))."\"";
					if($_GET['type'] == $type['listing_house_style']) echo ' selected';
					echo ">".html_entity_decode(stripslashes($type['listing_house_style']))."</option>\n";
				}
			}
			?>
          </select>
          <strong>Price:</strong>
          <select name="price" id="price">
            <option selected="selected" value="-1" <? if($_GET['price'] == 'noMax') echo ' selected'; ?>>No  Maximum </option>
            <option>------------ </option>
            <option value="25000" <? if($_GET['price'] == '25000') echo ' selected'; ?>>$25,000 </option>
            <option value="50000" <? if($_GET['price'] == '50000') echo ' selected'; ?>>$50,000 </option>
            <option value="100000" <? if($_GET['price'] == '100000') echo ' selected'; ?>>$100,000 </option>
            <option value="125000" <? if($_GET['price'] == '125000') echo ' selected'; ?>>$125,000 </option>
            <option value="150000" <? if($_GET['price'] == '150000') echo ' selected'; ?>>$150,000 </option>
            <option value="175000" <? if($_GET['price'] == '175000') echo ' selected'; ?>>$175,000 </option>
            <option value="200000" <? if($_GET['price'] == '200000') echo ' selected'; ?>>$200,000 </option>
            <option value="250000" <? if($_GET['price'] == '250000') echo ' selected'; ?>>$250,000 </option>
            <option value="300000" <? if($_GET['price'] == '300000') echo ' selected'; ?>>$300,000 </option>
            <option value="350000" <? if($_GET['price'] == '350000') echo ' selected'; ?>>$350,000 </option>
            <option value="500000" <? if($_GET['price'] == '500000') echo ' selected'; ?>>$500,000 </option>
            <option value="750000" <? if($_GET['price'] == '750000') echo ' selected'; ?>>$750,000 </option>
            <option value="1000000" <? if($_GET['price'] == '1000000') echo ' selected'; ?>>$1,000,000 </option>
            <option>------------ </option>
          </select> 
          <input type="hidden" name="search" value="true" />
          </strong>
         
       </td>
       <td> <input type="image" name="imageField" id="imageField" src="images/b_search_small.jpg" style=" position:top" /></td>
      </tr>
    </table>
  </form>
</div>
