<?php
include('manage/master.inc.php');
$contentResults = dbQuery('SELECT * FROM page WHERE page_name = "index.php"');
$page = dbFetchArray($contentResults);
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

<!-- Page Content -->
  <div class="container" style="margin-top:100px;">

    <!-- Jumbotron Header -->
    <header class="jumbotron my-4" style="margin-top: 20px;">
      <h1 class="display-3">Welcome!</h1>
      <p class="lead">Submit your property FREE on Iowa's largest For Sale by Owner website!. It's easy with no strings attached!</p>
      <a href="sell.php" class="btn btn-primary btn-lg">Sell your property!</a>
    </header>
   <div class="container">
      <div class="row text-center">
        <h1>Featured Properties</h1>
      </div>
   </div>

    <div class="row text-center">
    <?
      echo html_entity_decode(stripslashes($page['page_contents']));
      //get 6 random listings
      $list = 6;

      $listingResults = dbQuery('SELECT * FROM listing ORDER BY RAND() DESC LIMIT ' . $list);
      $num = 6;
      //  echo dbNumRows($listingResults);s
      global $params; //querystring stuff
      global $form_page;
      global $num;
      global $pages;
      global $recStart;
      global $resultsPerPage;
      global $reqString;
      // dump($records);
      getListings($listingResults, 'grid', 'index', 'Featured');
      //  $featureResults = dbQuery('SELECT * FROM listing WHERE feature = 1');
      // getListings($featureResults, 'grid', 'index', 'Featured');
      // getListings($featureResults, 10, $page);
	  
	  ?>

    <!-- Page Features -->
    </div>
    <!-- /.row -->
  </div>
  <!-- /.container -->

<? include('footer.inc.php'); ?>
       