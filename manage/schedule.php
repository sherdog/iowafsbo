<?php
include('manage/master.inc.php');
include('manage/master.inc.php');
$banners = pickBanners($_REQUEST["cat"]);


//get content based on category =)
$page = $_GET['page'];
if($page == ''){
	$page = 'national';//default page if not defined =)
}



switch($page) { 
case 'national':
	$color = 'orange';
break;
case 'north':
	$color = 'blue';
break;
case 'south':
	$color = 'purple';
break;
case 'west':
	$color = 'green';
break;
case 'midwest':
	$color = 'bluegreen';
break;
case 'crate':
	$color = 'black';
break;
case 'weekly':
	$color = 'grey';
break;
case 'video':
	$color = 'red';
break;
default:
	$color = 'black';
}

$pageTitle = ucfirst($page);

//ok ok now we just need to umm grab the content and we should be golden beoottchh 
//get category id

$getCatID = dbQuery('SELECT content_category_id FROM content_category WHERE content_category_title LIKE "%'.$page.'%"');
$catstuff = dbFetchArray($getCatID);
$category_id = $catstuff['content_category_id']; // now we should have the id if it =)



?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Dirt on Dirt :: All Late Models. All the Time.</title>
<link rel="stylesheet" href="global.css" />
<link rel="stylesheet" href="styles-index.css" />
<script src="Scripts/AC_RunActiveContent.js" type="text/javascript"></script>
<script src="rollovers.js" type="text/javascript" ></script>
<script src="javascript.js" type="text/javascript"></script>
</head>
<body onload="MM_preloadImages('images/b_home-over.jpg','images/b_subscribe-over.jpg','images/b_advertise-over.jpg','images/b_contactus-over.jpg')">
<div id="mainContainer">
  <!-- START HEADER AREA -->
  <div id="topadvertisement">
  <div id="bannertop"><a href="redirect.php?id=<?=$banners[0]["banner_id"]?>" target="_blank"><img src="<?=$banners[0]["banner_src"]?>" width="728" height="90" border="0"/></a></div>
    <div id="accommodations"><img src="images/accommodations.jpg" /></div>
    
  </div>
 <!-- START HEADER AREA -->
<? include('header.php'); ?>
  <!-- END HEADER AREA -->
  <? include("mainnavigation.php"); ?>
  <!-- START CONTENT AREA -->
  <div id="contentContainer" >
  <div id="pageHeading-red">
  <h1 class="white">Schedules</h1>
  </div>
  
  <table width="100%" border="0" cellspacing="5" cellpadding="0">
    <tr>
     
      <td valign="top" width="100%" ><table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="60%"><h1><strong>Series Schedule</strong></h1></td>
          <td width="40%"><h1><strong>Monthly Schedules</strong></h1></td>
        </tr>
        <tr>
          <td valign="top"><table width="100%" border="0" cellspacing="5" cellpadding="0">
            <tr>
              <td colspan="2" class="style5">National</td>
            </tr>
            <?php
			$nationalResults = dbQuery('SELECT * FROM schedule_division WHERE schedule_division_region = 2');
	 		while($nat = dbFetchArray($nationalResults)){
			?>
            <tr>
              <td width="5" valign="top"><img src="images/bullet-main.jpg" width="5" height="12" /></td>
              <td width="462"><a href="schedule.php?division=<?=$nat['schedule_division_id']?>" class="normal">
                <?=$nat['schedule_division_desc']?>
              </a></td>
            </tr>
            <?
			}
			?>
            <tr>
              <td colspan="2" valign="top" class="style5">Midwest</td>
            </tr>
            <?php
			$midwestResults = dbQuery('SELECT * FROM schedule_division WHERE schedule_division_region = 5');
	 		while($mw = dbFetchArray($midwestResults)){
			?>
            <tr>
              <td width="5" valign="top"><img src="images/bullet-main.jpg" width="5" height="12" /></td>
              <td><a href="schedule.php?division=<?=$mw['schedule_division_id']?>" class="normal">
                <?=$mw['schedule_division_desc']?>
              </a></td>
            </tr>
            <?php
			}
			?>
            <tr>
              <td colspan="2" valign="top" class="style5">North</td>
            </tr>
            <?php
			$nResults = dbQuery('SELECT * FROM schedule_division WHERE schedule_division_region = 3');
			while($north = dbFetchArray($nResults)){
			?>
            <tr>
              <td width="5" valign="top"><img src="images/bullet-main.jpg" width="5" height="12" /></td>
              <td><a href="schedule.php?division=<?=$north['schedule_division_id']?>" class="normal">
                <?=$north['schedule_division_desc']?>
              </a></td>
            </tr>
            <?php
			}
			?>
            <tr>
              <td colspan="2" valign="top" class="style5">South</td>
            </tr>
            <?php
			$sResults = dbQuery('SELECT * FROM schedule_division WHERE schedule_division_region = 4');
			while($south = dbFetchArray($sResults)){
			?>
            <tr>
              <td width="5" valign="top"><img src="images/bullet-main.jpg" width="5" height="12" /></td>
              <td><a href="schedule.php?division=<?=$south['schedule_division_id']?>" class="normal">
                <?=$south['schedule_division_desc']?>
              </a></td>
            </tr>
            <?php
			}
			?>
            <tr>
              <td colspan="2" valign="top" class="style5">West</td>
            </tr>
            <?php
			 $wResults = dbQuery('SELECT * FROM schedule_division WHERE schedule_division_region = 6');
			 while($west = dbFetchArray($wResults)){
			 ?>
            <tr>
              <td width="5" valign="top"><img src="images/bullet-main.jpg" width="5" height="12" /></td>
              <td><a href="schedule.php?division=<?=$west['schedule_division_id']?>" class="normal">
                <?=$west['schedule_division_desc']?>
              </a></td>
            </tr>
            <?php
			}
			?>
            <tr>
              <td colspan="2" valign="top" class="style5">Crate</td>
            </tr>
            <?php
			$cResults = dbQuery('SELECT * FROM schedule_division WHERE schedule_division_region = 7');
			while($crate = dbFetchArray($cResults)){
			?>
            <tr>
              <td width="5" valign="top"><img src="images/bullet-main.jpg" width="5" height="12" /></td>
              <td><a href="schedule.php?division=<?=$crate['schedule_division_id']?>" class="normal">
                <?=$crate['schedule_division_desc']?>
              </a></td>
            </tr>
            <?php
			}
			?>
          </table></td>
          <td valign="top"><table width="100%" border="0" cellspacing="5" cellpadding="0">
			<?php
			//Display months to filter
			$monthArray = array('January', 'Feburary', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');
			
			for($i=0; $i<=count($monthArray)-1; $i++){
			?>
            
            <tr>
              <td width="5"><img src="images/bullet-main.jpg" width="5" height="12" /></td>
              <td><a href="schedules.php?month=<?=$monthArray[$i]?>" class="normal"><?=$monthArray[$i]?></a></td>
            </tr>
            <?php
			}
			?>
            
          </table></td>
        </tr>
      </table></td>
      <td valign="top" width="231"><table width="230" border="0" cellspacing="0" cellpadding="0">
        
        <tr>
          <td><img src="images/spacer.gif" width="1" height="10" /></td>
        </tr>
        <tr>
          <td><table width="100%" border="0" cellpadding="5" cellspacing="0" bgcolor="#202020">
              <tr>
                <td align="center"><img src="images/spacer.gif" width="1" height="10" /></td>
              </tr>
              <tr>
                <td align="center"><a href="redirect.php?id=<?=$banners[1]["banner_id"]?>" target="_blank"><img src="<?=$banners[1]["banner_src"]?>" width="210" height="275" border="0" /></a></td>
              </tr>
              <tr>
                <td align="center"><img src="images/spacer.gif" width="1" height="10" /></td>
              </tr>
          </table></td>
        </tr>
        <tr>
          <td><img src="images/spacer.gif" width="1" height="10" /></td>
        </tr>

      </table></td>
    </tr>
  </table>
 
  <div id="seperator">&nbsp;</div>
    <div id="bottom">
  	<div  style="width:250px; float:left; text-align:center; padding:0px 0px 0px 0px; "><a href="redirect.php?id=<?=$banners['2']['banner_id']?>" target="_blank"><img src="<?=$banners[2]["banner_src"]?>" width="250" height="250" border="0" /></a></div>
  	<div style="width:730px;  float:left; margin-left:5px;">
        <div id="digitalDarkroom" style="height:180px; ">
        <div id="pics" style="padding-top:30px;  padding-left:70px; height:150px;">
          <div style="float:left; width:23%; text-align:center;"><img src="images/pic1.jpg" width="150" height="101" class="imgBorderBlack" /><br />
       	       <a href="#" class="slideshow">Fans of the week</a>
            </div>
      		<div style="float:left; width:23%; text-align:center;"><img src="images/pic2.jpg" width="151" height="101" class="imgBorderBlack" /><br />
       	      <a href="#" class="slideshow">Best in (slide) show</a>
            </div>
       	    <div style="float:left; width:23%; text-align:center;"><img src="images/pic3.jpg" width="150" height="99" class="imgBorderBlack" /><br />
       	      <a href="#" class="slideshow">WoO's Ohio swing</a>
            </div>
       	    <div style="float:left; width:23%; text-align:center;"><img src="images/pic4.jpg" width="151" height="101" class="imgBorderBlack" /><br />
       	      <a href="#" class="slideshow">Tapless and loving it</a>
            </div>
        </div>
        </div>
        <div id="footer" style="clear:both; height:47px; width:710px;">
          <? include('footer.inc.php'); ?>
        </div>
  	</div>
    </div>
   
   
 
  </div>
   <br style="clear:both;" />
  <!-- END CONTENT AREA -->
</div>

</body>
</html>
