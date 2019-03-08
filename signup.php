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

<title><?php echo SITE_TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="styles.css">
<link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
<link href="css/heroic-features.css" rel="stylesheet">

<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.8/css/all.css">
<link href="css/all.css" rel="stylesheet">

<script language="javascript" src="javascript.js" type="text/javascript"></script>

<style type="text/css">

.divider-text {
    position: relative;
    text-align: center;
    margin-top: 15px;
    margin-bottom: 15px;
}
.divider-text span {
    padding: 7px;
    font-size: 12px;
    position: relative;   
    z-index: 2;
}
.divider-text:after {
    content: "";
    position: absolute;
    width: 100%;
    border-bottom: 1px solid #ddd;
    top: 55%;
    left: 0;
    z-index: 1;
}

.btn-facebook {
    background-color: #405D9D;
    color: #fff;
}
.btn-twitter {
    background-color: #42AEEC;
    color: #fff;
}
</style>

<body>
<? include('header.inc.php'); ?>

 <div class="container" style="margin-top:100px; margin-bottom:100px;">
    <div class="row" >
        <div class="col-lg-4 offset-lg-4">

                              
              <div class="card bg-light">
              
              <article class="card-body mx-auto" style="max-width: 600px;">
                <h4 class="card-title mt-3 text-center">Create Account</h4>
                <p class="text-center">Get started with your free account</p>
                  <p>
                    <a href="" class="btn btn-block btn-facebook"> <i class="fab fa-facebook-f"></i>   Login via facebook</a>
                  </p>
                <p class="divider-text">
                    <span class="bg-light">OR</span>
                </p>

                <form>
                  
                  <div class="form-group input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"> <i class="fa fa-user"></i> </span>
                     </div>

                        <input name="" class="form-control" placeholder="Name" type="text">
                    </div> <!-- form-group// -->

                    <div class="form-group input-group">
                      <div class="input-group-prepend">
                        <span class="input-group-text"> <i class="fa fa-envelope"></i> </span>
                     </div>

                        <input name="" class="form-control" placeholder="Email address" type="email">
                    </div> <!-- form-group// -->

                    <div class="form-group input-group">
                      <div class="input-group-prepend">
                        <span class="input-group-text"> <i class="fa fa-lock"></i> </span>
                    </div>

                        <input class="form-control" placeholder="Create password" type="password">
                    </div> <!-- form-group// -->

                    <div class="form-group input-group">
                      <div class="input-group-prepend">
                        <span class="input-group-text"> <i class="fa fa-lock"></i> </span>
                    </div>

                        <input class="form-control" placeholder="Repeat password" type="password">
                    </div> <!-- form-group// -->

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary btn-block"> Create Account  </button>
                    </div> <!-- form-group// -->  

                    <p class="text-center">Have an account? <a href="login.php">Log In</a> </p>     


              </form>
            </article>
          </div> <!-- card.// -->

        </div>
      </div>
  </div>

<? include('footer.inc.php'); ?>