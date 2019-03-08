<?php
include('manage/master.inc');
// here what we are doing is when a user clicks on a banner advertisement, we then send this page and id of the banner and then we record that it was clicked

$bannerID = $_GET['id'];
$from = $_GET['from'];

//Get record from id and take current clicks and add 1
$getBannerInfo = dbQuery('SELECT banner_clicks, banner_href FROM banner WHERE banner_id = ' . $bannerID);
$ban = dbFetchArray($getBannerInfo);

//calc clicky clickys
$currentClicks = $ban['banner_clicks'];
$newClicks = $currentClicks++;

//update db
$update = dbQuery('UPDATE banner SET banner_clicks ="'.$newClicks.'" WHERE banner_id = ' . $bannerID);

//now we send the user to the banner href
location($ban['banner_href']);
?>
