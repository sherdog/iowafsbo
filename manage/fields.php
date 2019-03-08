<?php
include "master.inc.php";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>

<body>
<?php
//Default Fields for Ultraex
		$fields[] = 'listing_number'; 			
		$fields[]='listing_type'; 			
		$fields[]='listing_street';			
		$fields[]='listing_city';				
		$fields[]='listing_state';			
		$fields[]='listing_zip_code';			
		$fields[]='listing_elem_sch';			
		$fields[]='listing_mdljh_school';		
		$fields[]='listing_sr_high_school';
		$fields[]='listing_fuel_type';
		$fields[]='listing_region';		
		$fields[]='listing_sewer_type';		
		$fields[]='listing_water_type';	
		$fields[]='listing_ownership';	
		$fields[]='listing_house_style';		
		$fields[]='listing_roof';	
		$fields[]='listing_ac_type';			
		$fields[]='listing_basement';			
		$fields[]='listing_exterior';		
		$fields[]='listing_fireplaces';		
		$fields[]='listing_heat_type';	
		$fields[]='listing_trim_type';		
		$fields[]='listing_walk_out';		
		$fields[]='listing_water_softener';	
		$fields[]='listing_total_bdrms';
		$fields[]='listing_total_baths';	
		$fields[]='listing_total_sq_ft';	
		$fields[]='listing_yr_built';	
		$fields[]='listing_garage_stalls';	
		$fields[]='listing_type';	
		$fields[]='listing_current_status';
		$fields[]='listing_change_date';
		$fields[]='listing_price';	
		$fields[]='listing_selling_price';	
		$fields[]='listing_first_posting_date'; 
		$fields[]='listing_sale_date';
		$fields[]='listing_days_on_market';	
		$fields[]='listing_first_name';
		$fields[]='listing_last_name';	
		$fields[]='listing_office_phone';		
		$fields[]='listing_voice_mail';	
		$fields[]='listing_name';	
		$fields[]='listing_phone_number';		
		$fields[]='listing_first_name2';	
		$fields[]='listing_last_name2';	
		$fields[]='listing_office_phone2';	
		$fields[]='listing_voice_mail2';	
		$fields[]='listing_name2';	
		$fields[]='listing_phone_number2';	
		$fields[]='listing_mls_area';	
		$fields[]='listing_lot_size';		
		$fields[]='listing_total_taxes';	
		$fields[]='listing_tax_year';
		$fields[]='listing_feature_1';		
		$fields[]='listing_feature_2';		
		$fields[]='listing_feature_3';		
		$fields[]='listing_feature_4';	
		$fields[]='listing_feature_5';		
		$fields[]='listing_feature_6';		
		$fields[]='listing_feature_7';		
		$fields[]='listing_feature_8';	
		$fields[]='listing_feature_9';		
		$fields[]='listing_feature_10';		
		$fields[]='listing_feature_11';		
		$fields[]='listing_feature_12';		
		$fields[]='listing_feature_13';		
		$fields[]='listing_feature_14';		
		$fields[]='listing_feature_15';		
		$fields[]='listing_comments';	
		echo count($fields);
		for($i=0; $i<=count($fields); $i++){	
			//if(!dbNumRows(dbQuery("SELECT * FROM listing_fields WHERE listing_fields_title = '".$fields[$i]."'"))){
				$insert['listing_fields_title'] = $fields[$i];
				dbPerform("listing_fields", $insert, 'insert');
				echo "Added: " . $fields[$i] . "<br>";
			//}
		}
	//ok we finna add these to the fields table, becuase it would be easier to just grab these anytime throughout the site
	


?>
</body>
</html>
