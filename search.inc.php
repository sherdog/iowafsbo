  <form class="form-inline">

<div class="form-group">
  <label class="mb-2 mr-sm-2" style="margin-bottom: 5rem; margin-right:5px;"><strong>Search:</strong></label>
</div>

  <div class="form-group">
      <label class="" for="inputCity" style="margin-bottom: .5rem; margin-right:5px;">City</label>
      <select id="inputCity" class="form-control mb-2 mr-sm-2">
        <option  value="-1">No Preference</option>
        <? 
            while($city = dbFetchArray($cityResults)){
              if($city['listing_city'] != '') {
                if($city['listing_city'] != "") {
                  echo "<option value=\"".stripslashes($city['listing_city'])."\"";
                  if($_GET['city'] == stripslashes(html_entity_decode($city['listing_city'])) ) echo ' selected';
                  echo ">".stripslashes(html_entity_decode($city['listing_city']))."</option>\n";
                }
              }
            }

        ?>
      </select>
</div>


    
  <div class="form-group">

      <label class="" for="inputPrice" style="margin-bottom: .5rem; margin-right:5px;">Price</label>
  <select id="inputPrice" class="form-control mb-2 mr-sm-2">
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
  </select>


</div>
  <div class="form-group">



  <label class="" for="inputBeds" style="margin-bottom: .5rem; margin-right:5px;">Beds</label>
  <select id="inputBeds" class="form-control mb-2 mr-sm-2">
    
    <?php
     for($i=1; $i<= 8; $i++)
     {
        echo "<option value=\"".$i."\"";
           if($i == $_GET['beds'])
           {
               echo ' selected';
           }
        echo ">".$i."</option>\n";
      }    

   ?>
  </select>


</div>
    
  <div class="form-group">

  <label class="" for="inputBaths" style="margin-bottom: .5rem; margin-right:5px;">Baths</label>
  <select id="inputBaths" class="form-control mb-2 mr-sm-2">
    <?php
       for($i=1; $i<=8; $i++){
        echo "<option value=\"".$i."\"";
         if($i == $_GET['baths']) echo ' selected';
        echo ">".$i."</option>\n";
       }
       ?>
  </select>


</div>
    
  <div class="form-group">

  <label class="" for="inputStyles" style="margin-bottom: .5rem; margin-right:5px;">Style</label>
  <select id="inputStyles" class="form-control mb-2 mr-sm-2">
    <option value="-1">No Preference</option>
            <?
      $typeResults = dbQuery('SELECT DISTINCT listing_house_style FROM listing ORDER BY listing_house_style ASC');
      while($type = dbFetchArray($typeResults)){
        if($type['listing_house_style'] != ""){
          echo "<option value=\"".utf8_encode($type['listing_house_style'])."\"";
          if($_GET['type'] == utf8_encode($type['listing_house_style']) ) echo ' selected';
          echo ">".utf8_encode($type['listing_house_style'])."</option>\n";
        }
      }
      ?>
  </select>

</div>
    
  <div class="form-group">

  <button type="submit" class="btn btn-primary mb-2">Submit</button>
</div>
</form>
