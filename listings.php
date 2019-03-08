<?php
include('manage/master.inc.php');
extract($_GET, EXTR_PREFIX_ALL, 'form_'); 
extract($_POST, EXTR_PREFIX_ALL, 'form_');

function addSearchString($searchParam) {
	global $sqlString;
	
	if(strlen($sqlString ) > 0 ){
		//means we add an AND
		$sqlString .= " AND " . $searchParam;
	}else{
		//means we just started so we add WHERE
		$sqlString .= " WHERE " . $searchParam;
	}
	
}


//$start++;

if(!isset($_GET['search']) )
{
	//get all
	$table = 'SELECT * FROM listing';
}
else
{
	//echo $sqlwithlimit;
	//we start our search!
	$table = 'SELECT * FROM listing';

	if(isset($_GET['city']) && $_GET['city'] > '0') 
	{
		addSearchString("listing_city = '".$_GET['city']."'");
	}
	if(isset($_GET['beds']) && $_GET['beds'] > '0')
	{
		addSearchString('listing_total_bdrms > ' . $_GET['beds']);
	}
	if(isset($_GET['baths']) && $_GET['baths'] > '0')
	{
		addSearchString('listing_total_baths > ' . $_GET['baths']);
	}
	if(isset($_GET['price']) && $_GET['price'] > '0')
	{
		addSearchString('listing_price < ' . $_GET['price']);
	}
	if(isset($_GET['type']) && $_GET['type'] > '0')
	{
		addSearchString('listing_house_style = "' . $_GET['type'] . '"');
	}
	
	
	
}
		switch($_GET['orderby']) 
		{
			case 'pricedesc':
				$sqlString .= " ORDER BY listing_price DESC";
			break;
			case 'priceasc':
				$sqlString .= " ORDER BY listing_price ASC";
			break;
			case 'dateasc':
				$sqlString .= " ORDER BY listing_first_posting_date ASC";
			break;
			case  'datedesc':
			
				$sqlString .= " ORDER BY listing_first_posting_date DESC";
			break;
			default:
				$sqlString .= " ORDER BY listing_price ASC";
			break;
			
		}


### PAGINATION STUFF 

$resultsPerPage = $settings['Listings per page'];
$recStart = $_GET['page'] * $resultsPerPage;


### PAGINATION STUFF 
$num = dbNumRows(dbQuery($table.$sqlString));
$sqlString .= " LIMIT " . $recStart.", ".$resultsPerPage;
$reqString =  $_SESSION['REQUEST_URI'];
//echo $table.$sqlString;

$listingResults = dbQuery($table. $sqlString);//get total number of rows
$pages = ceil($num / $resultsPerPage); 

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

<?php include('header.inc.php'); ?>


<div class="container" style="margin-top:100px;">
    <div class="row">
    	<?php include('search.inc.php'); ?>
    </div>
</div>
<hr>
<div class="container">
    <div class="row">


      	<!-- Post Content Column -->
	    <?php

		$params = "?search=".$_GET['search']."&city=".$_GET['city']."&price=".$_GET['price']."&type=".$_GET['type']."&beds=".$_GET['beds']."&baths=".$_GET['baths']."&orderby=".$_GET['orderby']."&";
		
		if ($num == 0)
		{
			echo "<h2>No results returned</h2>";
		}
		else
		{
			//get results, in an array
			while($rec = dbFetchArray($listingResults))
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
					$imageName = 'noimage.jpg';	
				}

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
		}
		?>
	</div>
</div>
      <!-- END TABULAR DATA -->      
<? include('footer.inc.php'); ?>