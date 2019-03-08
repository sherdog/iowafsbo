<?php
include('manage/master.inc.php');
			  
  $mlsNumber = $_GET['mls'];
   $results = dbQuery('SELECT * FROM listing WHERE listing_number = "'.$mlsNumber.'" LIMIT 1');
   if(dbNumRows($results)>0){
 // dump(dbFetchArray($results));
 	$row = dbFetchArray($results);
	}else{
	exit("There was an error processing your request, contact the webmaster");
	}
 
 
 	//get listing image
	$imgResults = dbQuery('SELECT * FROM listing_images WHERE listing_images_filename = "'.$row['listing_number'].'.jpg"');
	if(dbNumRows($imgResults)){
		$img = dbFetchArray($imgResults);
		$imgFilename = $img['listing_images_filename'];
	}else{
		$imgFilename = 'noimage.jpg';
	}
 	
	//Process forms!
	if(isset($_POST['action'])){
		switch($_POST['action']) {
		
		case 'schedule':
			//we are scheduling a viewing
			$date = trim($_POST['schedule_date']);
			$time = trim($_POST['schedule_time']);
			$name = trim($_POST['schedule_name']);
			$email = trim($_POST['schedule_email_address']);
			$phone = trim($_POST['schedule_phone']);
			$comments = trim(strip_tags($_POST['schedule_comments']));
			$scode = trim($_POST['security_code']);
			$mls = $_POST['mls'];
			//print_r($settings);
			$to = $settings['Contact Email'];//[Contact Email] 
			$subject = "website inquiry [schedule viewing]";
			$fromaddress = $email;
			$fromname = $name;
			
			$message = $name . " has requested a viewing on MLS# " . $mls ." below are their details\n\n";
			$message .= "Date/Time: " . $date . " @ ".$time."\n";
			$message .= "Name: " . $name . "\n";
			$message .= "Email: " . $email . "\n";
			$message .= "Phone: " . $phone . "\n";
			$message .=  "\n\n";
			//echo $to." ".$message." ".$fromname." ".$fromaddress." ".$subject."<br>";
			sendNotification($fromname, $fromaddress, $to, $message, $subject);
			
			addMessage("Your request was processed successfully");
		break;
		case 'request':
			//we are requesting more information
			$name = trim($_POST['request_name']);
			$email = trim($_POST['request_email']);
			$phone = trim($_POST['request_phone']);
			$comments = trim(strip_tags($_POST['request_comments']));
			$scode = trim($_POST['security_code']);
			$mls = $_POST['mls'];
			//print_r($settings);
			$to = $settings['Contact Email'];
			$subject = "website inquiry [more informatin]";
			$fromname = $name;
			$fromaddress = $email;
			
			$message = $name . " has requested more information on MLS# " . $mls ." below are their details\n\n";
			
			$message .= "Name: " . $name . "\n";
			$message .= "Email: " . $email . "\n";
			$message .= "Phone: " . $phone . "\n";
			$message .= "Comments: " . $comments."\n";
			$message .=  "\n\n";
			//echo $to." ".$message." ".$fromname." ".$fromaddress." ".$subject."<br>";
			sendNotification($fromname, $fromaddress, $to, $message, $subject);
			
			addMessage("Your request was processed successfully");
			
		break;
		default:
		
		break;
		}
	}


?>
<!DOCTYPE html>
<html lang="en">
<head>

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

  <title><?php echo SITE_TITLE; ?></title>

  <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="css/heroic-features.css" rel="stylesheet">

  <script language="javascript" src="javascript.js" type="text/javascript"></script>

<body>
<? include('header.inc.php'); ?>

  
   <div class="container" style="margin-top:100px;">
      <div class="row ">
          <div class="col-lg-12">
           <h1><?=ucfirst($row['listing_street']) . ", " . ucfirst($row['listing_city'])?> <?=ucfirst($row['listing_state'])?> <?=ucfirst($row['listing_zip_code'])?></h1>
          </div>
      </div>
   </div>

  <div class="container" >
    <div class="row">

      <!-- Post Content Column -->
      <div class="col-lg-8">

         <!-- Main Container -->
         <img class="img-fluid rounded" src="http://placehold.it/900x600" alt="" title="Property Images">
         <hr>

         <p><strong>$<?=number_format($row['listing_price'])?></strong><br />
          <?=$row['listing_total_bdrms']?> Bedroom<br />
          <?=$row['listing_total_baths']?> Bathroom<br />
          <strong>MLS#: </strong><?=$row['listing_number']?><br />
          Status: <?=$row['listing_current_status']?>

          </p>
          <strong>Property Description:</strong><br />
          <?=ucfirst(strtolower(stripslashes($row['listing_comments'])))?>

          <hr>
          <table width="100%" border="0" cellspacing="2" cellpadding="2">
          <tr>
            <td colspan="4"><h2>Listing Details</h2></td>
            </tr>
          <tr>
            <td width="20%" class="descField"><div align="right"><strong>Price</strong></div></td>
            <td width="34%" class="tan12">$<?=number_format($row['listing_price'])?></td>
            <td width="15%" class="descField"><div align="right"><strong>Style</strong></div></td>
            <td width="31%" class="tan12"><?=$row['listing_house_style']?></td>
          </tr>
          <tr>
            <td class="descField"><div align="right"><strong>Square Feet</strong></div></td>
            <td class="tan12"><?=number_format($row['listing_total_sq_ft'])?></td>
            <td class="descField"><div align="right"><strong>Garage</strong></div></td>
            <td class="tan12"><?=$row['listing_garage_stalls']?></td>
          </tr>
          <tr>
            <td class="descField"><div align="right"><strong>Water</strong></div></td>
            <td class="tan12"><?=$row['listing_water_type']?></td>
            <td class="descField"><div align="right"><strong>Sewer</strong></div></td>
            <td class="tan12"><?=$row['listing_sewer_type']?></td>
          </tr>
          <tr>
            <td class="descField"><div align="right"><strong>Roof</strong></div></td>
            <td class="tan12"><?=$row['listing_roof']?></td>
            <td class="descField"><div align="right"><strong>AC</strong></div></td>
            <td class="tan12"><?=$row['listing_ac_type']?></td>
          </tr>
          <tr>
            <td class="descField"><div align="right"><strong>Basement</strong></div></td>
            <td class="tan12"><?=$row['listing_basement']?></td>
            <td class="descField"><div align="right"><strong>Exterior</strong></div></td>
            <td class="tan12"><?=$row['listing_exterior']?></td>
          </tr>
          <tr>
            <td class="descField"><div align="right"><strong>Fireplaces</strong></div></td>
            <td class="tan12"><? if($row['listing_fireplaces']) echo 'Yes'; else echo "No"; ?></td>
            <td class="descField"><div align="right"><strong>Heat Type</strong></div></td>
            <td class="tan12"><?=$row['listing_heat_type']?></td>
          </tr>
          <tr>
            <td valign="top" class="descField"><div align="right"><strong>Schools</strong></div></td>
            <td valign="top" class="tan12"><strong>Elementary:</strong> <?=$row['listing_elem_sch']?> <br />
                <strong>Middle:</strong> <?=$row['listing_mdljh_school']?> <br />
                <strong>High:</strong> <?=$row['listing_sr_high_school']?>
            </td>
            <td valign="top" >&nbsp;</td>
            <td valign="top" class="tan12">&nbsp;</td>
          </tr>
        </table>

        <hr>

        <strong>Features:</strong><br />
            <?
          for($i=1; $i<=10; $i++){
            if($row['listing_feature_'.$i] != '') $features .= stripslashes($row['listing_feature_'.$i]).", ";
          }
          echo substr($features, 0, -2);
          ?>


      </div>
      <div class="col-md-4">

         <!-- Search Widget -->
        <div class="card my-4">
          <h5 class="card-header">Search</h5>
          <div class="card-body">
            <div class="input-group">
              <input type="text" class="form-control" placeholder="Search for...">
              <span class="input-group-btn">
                <button class="btn btn-secondary" type="button">Go!</button>
              </span>
            </div>
          </div>
        </div>

        <!-- Side Widget -->
         <div class="card my-4">
        <h5 class="card-header">Courtesy of</h5>
          <div class="card-body">
            <div class="row">
              <?=$row['listing_first_name2']?>
              <?=$row['listing_last_name2']?>
              <br />
              <?=stripslashes($row['listing_name'])?>
              <br />
              <?=$row['listing_phone_number']?>
            </div>
          </div>
        </div>

        <!-- Side Widget -->
        <div class="card my-4">
          <h5 class="card-header">Side Widget</h5>
          <div class="card-body">
            You can put anything you want inside of these side widgets. They are easy to use, and feature the new Bootstrap 4 card containers!
          </div>
        </div>


      </div>
    </div>
  </div>
<? include('footer.inc.php'); ?>