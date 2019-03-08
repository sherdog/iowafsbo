<?php
  include('manage/master.inc.php');
  $contentResults = dbQuery('SELECT * FROM page WHERE page_name = "sell.php"');

  if(dbNumRows($contentResults) > 0)
  { 
  	$page = dbFetchArray($contentResults);
  } 
  else 
  {
  	exit('There was an error in processing your request contact the webmaster');
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
<style type="text/css">
.fa-facebook-f:before{content:"\f39e"}
</style>
<title><?php echo SITE_TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="styles.css">
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.8/css/all.css">

es
<link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
<link href="css/heroic-features.css" rel="stylesheet">

<script language="javascript" src="javascript.js" type="text/javascript"></script>

<body>
<? include('header.inc.php'); ?>

 <div class="container" style="margin-top:100px; margin-bottom:100px;">
    <div class="row" >
        <div class="col-lg-4 offset-lg-4">

                <div class="card">
                  <article class="card-body">
                    <a href="signup.php" class="float-right btn btn-outline-primary">Sign up</a>
                    <h4 class="card-title mb-4 mt-1">Sign in</h4>
                    <p>
                      <a href="" class="btn btn-block btn-outline-info"> <i class="fab fa-twitter"></i>   Login via Twitter</a>
                      <a href="" class="btn btn-block btn-outline-primary"> <i class="fab fa-facebook-f"></i>   Login via facebook</a>
                    </p>
                    <hr>
                    <form>
                      <div class="form-group">
                          <input name="" class="form-control" placeholder="Email or login" type="email">
                      </div> <!-- form-group// -->
                      <div class="form-group">
                          <input class="form-control" placeholder="******" type="password">
                      </div> <!-- form-group// -->                                      
                      <div class="row">
                          <div class="col-md-6">
                              <div class="form-group">
                                  <button type="submit" class="btn btn-primary btn-block"> Login  </button>
                              </div> <!-- form-group// -->
                          </div>
                          <div class="col-md-6 text-right">
                              <a class="small" href="#">Forgot password?</a>
                          </div>                                            
                      </div> <!-- .row// -->                                                                  
                  </form>
                  </article>
                </div> <!-- card.// -->
        </div>
      </div>
  </div>

<? include('footer.inc.php'); ?>