<?php
include('manage/master.inc.php');

if(isset($_GET['page'])){
	$pagefile = 'page.php?page='.$_GET['page'];
} else { 
	$pagefile = 'contact.php';
}

$contentResults = dbQuery('SELECT * FROM page WHERE page_name = "'.$pagefile.'"');

if(dbNumRows($contentResults) > 0){ 
	$page = dbFetchArray($contentResults);
} else {
	exit('There was an error in processing your request contact the webmaster');
}


?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo SITE_TITLE; ?></title>
<link rel="stylesheet" href="styles.css" />
<script language="javascript" src="javascript.js"></script>
<style>
<!--
.chatbottom-online { background-image:url(images/chat-online-bottom.jpg); background-repeat:no-repeat; background-position:top right; }
-->
</style>
</head>

<body>
<table width="768" border="0" cellspacing="0" cellpadding="0" align="center">
  <tr>
    <td class="style2"><?=$settings['Contact Email']?>&nbsp;&nbsp;&nbsp;&nbsp;<?=$settings['Contact Phone']?></td>
    <td class="style2"><div align="right"></div></td>
  </tr>
</table>
<table width="768" border="0" cellspacing="0" cellpadding="0" id="mainTable" >
  <tr>
    <td><? include('header.inc.php'); ?></td>
  </tr>
  <tr>
    <td>
    <div id="contentContainer">
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="640" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td valign="top">
             
              <div id="title">
                <h2><?=stripslashes($page['page_htmltitle']);?></h2>
              </div>
              <div id="listings" class="tan12">
              <?
			  echo html_entity_decode(stripslashes($page['page_contents']));
			  ?>
              </div>              </td>
          </table>
          <? 
		  if($_GET['page'] == 'calculators') {
		  ?>
          <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td valign="top">
              <div id="listings" class="tan12">
              <table width="100%" cellpadding="5" cellspacing="0" class="calculator_area">
              
			  <?php
			  
			  $getCalcs = dbQuery('SELECT * FROM calcs ORDER BY calcs_title ASC');
			  while($cal = dbFetchArray($getCalcs)) {
			  	echo "<tr>\n";
              	echo "<td valign=\"top\" width=\"3\"> <img src=\"images/bullet-light-on-dark.jpg\" /></td>\n";
				echo "<td><a href=\"calculators.php?show=".$cal['calcs_id']."\" class=\"listing\">".stripslashes($cal['calcs_title'])."</a><br></td>\n";
			  	echo "</tr>\n";
			  }
			  
			  ?>
              </table>
              </div>
              </td>
          </table>
          <?
		  }
		  ?>
          </td>
          <td valign="top">
          
          <div id="rightcolumn">
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td valign="top"><h2 class="gold">Search My Listings</h2></td>
              </tr>
              <tr>
                <td><? include("search.inc.php"); ?></td>
              </tr>
            </table>
          </div>
          
          <div id="rightcolumn" style="margin-top:5px;">
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td valign="top"></td>
              </tr>
              <tr>
                <td><table width="100%" border="0" cellspacing="0" cellpadding="3">
                  <tr>
                    <td><h2 class="gold">Resources</h2></td>
                  </tr>
                  <tr>
                    <td>
                      <? include('resources.inc.php'); ?>
                      </td>
                  </tr>
                </table></td>
              </tr>
            </table>
          </div>
          <div id="props">
            <div align="center"><img src="images/props.jpg" width="228"  /></div>
          </div>
          </td>
        </tr>
      </table>
      
    </div>
    </td>
  </tr>
  <tr>
    <td><? include('footer.inc.php'); ?></td>
  </tr>
</table>
<!-- OUTPUT GOOGLE ANALYTICS CODE -->
<?php
if($settings['Google Analytics Code'] != ''){
	echo html_entity_decode($settings['Google Analytics Code']);
}
?>
</body>
</html>
