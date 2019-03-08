<?php
include('manage/master.inc.php');

//get calculator =)
$show = $_REQUEST['show'];
$calcid = dbQuery('SELECT * FROM calcs WHERE calcs_id = ' . $show);
if(dbNumRows($calcid) == 0){
	exit('There was an error processing your request');
}else{
	$cal = dbFetchArray($calcid);
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Brian Goldsberry :: Cedar Falls. Waterloo, Cedar Valley Realty Listings</title>
<link rel="stylesheet" href="styles.css" />
<script language="javascript" src="javascript.js"></script>
<style>
<!--
.chatbottom-online { background-image:url(images/chat-online-bottom.jpg); background-repeat:no-repeat; background-position:top right; }
-->
</style>
<?
if($cal['calcs_head_code'] != "")
	echo html_entity_decode(stripslashes($cal['calcs_head_code']));

if($cal['calcs_style_sheet'] != "")
	//remove style tags
	$ss1 = str_replace("<style>", "", $cal['calcs_style_sheet']);
	$ss2 = str_replace("</style>", "", $ss1);
	echo "<style>\n";
	echo html_entity_decode(stripslashes($cal['calcs_style_sheet']));
	echo "</style>\n";
?>
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
                <h2><?=stripslashes($cal['calcs_title']);?></h2>
              </div>
              <?php
			  
			  if($cal['calcs_desc'] != "") { ?>
              <div id="listings" class="tan12">
              <?
			  echo html_entity_decode(stripslashes($cal['calcs_desc']));
			  ?>
              </div>
              <? } ?></td>
          </table>
        
          <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td valign="top">
              <div id="listings" class="tan12">
              <?php
			  include($cal['calcs_src_code']);
			  ?>
              </div>
              </td>
          </table>
         
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
