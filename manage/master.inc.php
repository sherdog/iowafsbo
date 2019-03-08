<?php

	define('COMPANY', 'IOWA FSBO Listings.com');
	define('SITE_TITLE', 'IOWA FSBO Listings.com');
	
	define('SITE_URL',	'http://www.iowafsbolistings.com/');
	define('SITE_URL_HTTPS','http://www.iowafsbolistings.com/');
	define('SITE_PATH',	'/home/iowafsbo/public_html/');
	define('MANAGE_PATH',SITE_PATH . 'manage/');
	
	define('UPLOAD_DIR','uploads/');
	define('UPLOAD_DIR_ZIP', 'zips/');
	
	define('deliverMail', true);	// If site is live, this should be true, or else no mail will be sent

	define('DB_SERVER', 'localhost');
	define('DB_USERNAME', 'iowafsbo_admin');
	define('DB_PASSWORD', 'UE$_H~-{nA?J');
	define('DB_DATABASE', 'iowafsbo_realty');
	
	define('USE_PCONNECT', 'false');
	define('this_php', basename($_SERVER["PHP_SELF"]));
	
	define('LOGIN_SUCCESS', 'listings.php');
	
	$selmenu = basename(this_php);
	$autologoff_seconds = 900; // How many seconds of inactivity to stay logged in (900 = 15 minutes)
	
	// External web services login/pass info
	//define('paypal_username', "mikesh_1185076365_biz_api1.msn.com");
	//define('paypal_password', "1185076372");
	//define('paypal_signature', "Acy-EqxEW7vvOrVBAzMumYoMmUbUAnC3L-gxMljoUtELUcn.i5a3o0pr");

	require_once MANAGE_PATH."database.inc.php";
	require_once MANAGE_PATH."functions.inc.php";
	require_once MANAGE_PATH."siteFunctions.inc.php";
	require_once MANAGE_PATH."siteFunctions-realty.inc.php";


	session_start();
	
	//initial settings
	$settingResults = dbQuery('SELECT * FROM settings ORDER BY settings_title ASC');
	while( $setting = dbFetchArray($settingResults) ) {
		$settings[$setting['settings_title']] = $setting['settings_value'];
	}

/*
Table of parameters for this site
bid = banner_id
cid = content_id
oid = company_id
pid = page_id
cpid = content_picture_id
cvid = content_video_id
vid = video_id

Table of uploaded files
cphoto_nn.jpg	= Content Photo with thumbnails
clogo_nn.jpg	= Company logo
sphoto_nn.jpg	= Staff Photo with sphoto_onn.jpg=original
*/ 
?>