<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?=SITE_TITLE?></title>
<link rel="stylesheet" href="styles.css" />
<link rel="stylesheet" href="tabs.css" />
<?
if($javascript != "")
	echo "<script>$javascript</script>";
if(isset($jscalendar))
	$jscalendar->load_files();
	
?>
<script type="text/javascript" src="jscripts/SWFUpload/SWFUpload.js"></script>
<script type="text/javascript" src="jscripts/example_callbacks.js"></script>
<?
if($page == 'article'){
	printUploadJS();
}
?>

<style type="text/css">
<!--
.style1 {color: #FFFFFF}
-->
</style>
</head>

<body>
<div id="wrapper">
<div id="masshead">
  <div id="logo">
   
&nbsp;&nbsp;<img src="images/cplogo.jpg" width="244" height="57" /> </div>
  <div style='position: absolute;top:2px;right: 40px'></div>
</div>
<div id="mainNav">
<ul id="primary">
<?

// Setup the tabs
$tabs["user.php"] = "Accounts"; 

//$tabs["banner.php"] = "Banners";
//$tabs["video.php"] = "Media";
//$tabs["subscribers.php"] = "Subscriptions";
$tabs['listings.php'] = "Listings";
$tabs["editPage.php"] = "Content";
$tabs["content.php"] = "News/Articles";
$tabs["schedules.php"] = "Events";
$tabs["companies.php"] = "Links";
$tabs["logout.php"] = "Logout"; 
$tabs["settings.php"] = "Site Settings";
//$tabs["trackAccommodations.php"] = "Accommodations";
//$tabs["history.php"] = "History";
//$tabs["track.php"] = "Tracks";
// print the tabs
if(isset($_SESSION["cp_active_tab"]))
	$activeTab = $_SESSION["cp_active_tab"];
else
	$activeTab = "banner.php";
foreach($tabs as $tab=>$name){
	$class = "";
	if($tab == $activeTab)
		$class=" class=\"active\"";
	echo "<li$class><a href=\"$tab\"$class>$name</a></li>\n";
}
?>
</ul>
</div>
<div id="subNav">
	<ul >
<?
if($activeTab == "user.php"){
	$subtabs["user.php"] = "Users";
	$subtabs["user.php?action=edit"] = "Your Profile";
	$subtabs["user.php?action=add"] = "Create New User";
	$subtabs["logout.php"] = "Log out";
}
if($activeTab == 'listings.php'){
	$subtabs["listings.php?action=manage"] = "Manage Listing";
	$subtabs["listings.php?action=add"] = "Add Listing";
	$subtabs["listings.php?action=import"] = "Import Listings";
	$subtabs["listings.php?action=upload"] = "Image Upload";
	$subtabs["listings.php?action=bulkZipUpload"] = "Import Images";
	
}
/*elseif($activeTab == "banner.php"){
	$subtabs["banner.php"] = "Overview";
	$subtabs["banner.php?action=add"] = "Add Banner";
	$subtabs["bannerplacement.php"] = "Placement";
	$subtabs["advertisers.php"] = "Advertisers";
}
elseif($activeTab == "video.php"){
	$subtabs["video.php"] = "Videos";
	$subtabs["video.php?action=upload"] = "Upload Videos";
	$subtabs["videoCategories.php"] = "Video Categories";
	$subtabs["slideshow.php"] = "Slideshow";
	$subtabs["slideshow.php?action=upload"] = "Upload Slides";
//	$subtabs["videofeatured.php"] = "Set Featured";
}
elseif($activeTab == "subscribers.php"){
	$subtabs["subscribers.php"] = "Subscribers";
	$subtabs["subscribers.php?action=add"] = "Add Subscribers";
}*/
elseif($activeTab == "editPage.php"){
//	$subtabs["staff.php"] = "Staff";
//	$subtabs["34TheRoad.php"] = "3 for the road";
	$subtabs["editPage.php"] = "All Editable Pages";
//	$subtabs["regionLanding.php"] = "Region Landing Pages";
}
elseif($activeTab == "content.php"){
	$subtabs["content.php"] = "News / Articles";
	$subtabs["content.php?action=add"] = "Write an article";
//	$subtabs["contentcategory.php"] = "Setup Regions";
}
/*
elseif($activeTab == "schedules.php"){
	$subtabs["schedules.php"] = "Schedules";
	$subtabs["results.php"] = "Results";
	$subtabs["importSchedule.php"] = "Import Schedules";
	$subtabs["importResults.php"] = "Import Results";
	$subtabs["weeklyDirtModelResults.php"] = "Weekly Late Model Results";
	$subtabs["importWeekly1.php"] = "Import Weekly Late Model Results";
	$subtabs["importWeekly2.php"] = "Import Weekly Points";
}*/
elseif($activeTab == "companies.php"){
	$subtabs["companyCategories.php"] = "Link Categories";
	$subtabs["companies.php"] = "Links";
}/*
elseif($activeTab == "trackAccommodations.php"){
	$subtabs["trackAccommodations.php"] = "Listings";
	$subtabs["track.php"] = "Tracks";
}
elseif($activeTab == "history.php"){
	$subtabs["historyActiveSeries.php"] = "Active Series";
	$subtabs["historyDefunctSeries.php"] = "Defunct Series";
	$subtabs["historyYearByYear.php"] = "Year by Year";
	$subtabs["historyMajorEvents.php"] = "Major Events";
}
*/
if(count($subtabs) > 0){
	foreach($subtabs as $tab=>$name){
		$class = "";
		if($tab == $selmenu)
			$class=" class=\"subactive\"";
		echo "<li$class><a href=\"$tab\"$class>$name</a></li>\n";
	}
}
?>	
	</ul>

</div><div id="content">
<?
printMessage();
printErrors();
?>
