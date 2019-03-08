<?php
$navItems = array("Home"=>"index.php", "Search"=>"listings.php", "Sell Your Property"=>"sell.php", "Login" => "login.php", "Sign up"=>"signup.php");
?>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
  <div class="container">
    <a class="navbar-brand" href="/index.php"> <img src="images/logo.png" alt="Iowa For Sale by Owner Listings" border="0" style="padding:10px;" /></a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarResponsive">
      <ul class="navbar-nav ml-auto">
        <?php
          foreach($navItems as $key => $val) 
          {
          ?>
            <li class="nav-item <? if($_SERVER['PHP_SELF']  == '/'.$val) echo ' active'; ?>">
                <a class="nav-link" href="<?php echo $val; ?>"><?php echo $key; ?>
                  <?php
                    if($_SERVER['PHP_SELF'] == '/' . $val)
                    {
                      ?>
                      <span class="sr-only">(current)</span>
                      <?php
                    }
                  ?>
                </a>
              </li>
          <?php  
          }
        ?>
      </ul>
    </div>
  </div>
</nav>
<?php
/*s
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="324" rowspan="2"><a href="index.php" title="Back Home">
      <img src="images/logo.png" alt="Iowa For Sale by Owner Listings" border="0" style="padding:10px;" /></a></td>
    <td><div style="text-align:right; position:relative; top:-4px;">
   
	?></div></td>
  </tr>
  <tr>
    <td valign="bottom"><div id="navcontainer">
      <ul>
        <li <? if($_SERVER['PHP_SELF']  == '/index.php') echo 'class="active"'; ?>><a href="index.php" title="Home">Home</a></li>
        <li <? if($_SERVER['PHP_SELF']  == '/listings.php' || $_SERVER['PHP_SELF'] == '/listing-detail.php') echo 'class="active"'; ?> ><a href="listings.php" title="Listings">Listings</a></li>
        <li <? if($_SERVER['PHP_SELF']  == '/sell.php') echo 'class="active"'; ?>><a href="sell.php"  title="Sell Your Home">Sell Your House</a></li>
        <li <? if($_SERVER['PHP_SELF']  == '/contact.php') echo 'class="active"'; ?>><a href="contact.php"  title="Contact Brian Goldsberry">Contact</a></li>
      </ul>
    </div></td>
  </tr>
  <tr>
    <td colspan="2" bgcolor="#c0b9a6"><img src="images/spacer.gif" width="1" height="5" alt="Waterloo/Cedar Falls" /></td>
  </tr>
</table> */