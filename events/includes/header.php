<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright  2007 Refresh Web Development [http://www.refreshwebdev.com]
	
	Developed By: Chris Carlevato <chris@refreshwebdev.com>
	
	For the most recent version, visit the Helios Calendar website:
	[http://www.helioscalendar.com]
	
	License Information is found in docs/license.html
*/
	include('includes/include.php');
	
	$result = doQuery("SELECT SettingValue FROM " . HC_TblPrefix . "settings WHERE PkID IN (1,5,6,7,10,11,12,13,14,15,21,22,23,24,26,27,29,30,31,32,33,34,35) ORDER BY PkID");
	
	if(hasRows($result)){
		$hc_pubSubmit = cOut(mysql_result($result,0,0));
		$hc_keywords = cOut(mysql_result($result,1,0));
		$hc_description = cOut(mysql_result($result,2,0));
		$hc_allowindex = mysql_result($result,3,0);
		$hc_maxPopular = mysql_result($result,4,0);
		$hc_browsePast = mysql_result($result,5,0);
		$hc_maxShow = mysql_result($result,6,0);
		$hc_fillMax = mysql_result($result,7,0);
		$hc_dateFormat = mysql_result($result,8,0);
		$hc_showTime = mysql_result($result,9,0);
		$hc_defaultState = mysql_result($result,10,0);
		$hc_calStartDay = cOut(mysql_result($result,11,0));
		$hc_timeFormat = cOut(mysql_result($result,12,0));
		$hc_popDateFormat = cOut(mysql_result($result,13,0));
		$hc_googleKey = cOut(mysql_result($result,14,0));
		$hc_mapZoom = cOut(mysql_result($result,15,0));
		$hc_pubCat = cOut(mysql_result($result,16,0));
		$hc_WYSIWYG = cOut(mysql_result($result,17,0));
		$hc_captchas = explode(",", cOut(mysql_result($result,19,0)));
		$hc_series = cOut(mysql_result($result,20,0));
		$hc_browseType = cOut(mysql_result($result,21,0));
		$hc_timezoneOffset = cOut(mysql_result($result,22,0));
		
		$hc_timeInput = 23;
		if(cOut(mysql_result($result,18,0)) == 12){
			$hc_timeInput = 12;
		}//end if
		
	} else {
		exit(handleError(0, "Helios Settings Data Missing. You will need to run Helios Setup again."));
	}//end if
	
	define("HC_Menu", "components/Menu.php");
	define("HC_Core", "components/Core.php");
	define("HC_Controls", "components/ControlPanel.php");
	define("HC_Links", "components/Links.php");
	define("HC_Billboard", "components/Billboard.php");
	define("HC_Popular", "components/Popular.php");
	define("HC_GMap", "components/GMap.php");
	switch($hc_popDateFormat){
		case 'm/d/Y':
			$hc_popDateValid = "MM/dd/yyyy";
			break;
			
		case 'd/m/Y':
			$hc_popDateValid = "dd/MM/yyyy";
			break;
			
		case 'Y/m/d':
			$hc_popDateValid = "yyyy/MM/dd";
			break;
	}//end switch	?>
<!DOCTYPE html
PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<?php
	if($hc_allowindex == 1){?>
	<meta name="robots" content="all, index, follow" />
	<meta name="GOOGLEBOT" content="index, follow" />
<?php 
	} else {	?>
	<meta name="robots" content="noindex, nofollow" />
	<meta name="GOOGLEBOT" content="noindex, nofollow" />
<?php
	}//end if	?>
	<meta http-equiv="author" content="Refresh Web Development LLC" />
	<meta http-equiv="email" content="<?php echo CalAdminEmail;?>" />
	<meta http-equiv="copyright" content="2004-<?php echo date("Y");?> All Rights Reserved" />
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta http-equiv="expires" content="604800" />
	<meta http-equiv="MSSmartTagsPreventParsing" content="yes" />
	
	<meta name="description" content="<?php echo $hc_description;?>" />
	<meta name="keywords" content="<?php echo $hc_keywords;?>" />
	
	<link rel="bookmark" title="<?php echo CalName;?>" href="<?php echo CalRoot;?>" />
	<link rel="stylesheet" type="text/css" href="<?php echo CalRoot;?>/css/helios.css" />
	<!--[if IE]><link rel="stylesheet" type="text/css" href="<?php echo CalRoot;?>/css/heliosIE.css" /><![endif]-->
	<link rel="icon" href="<?php echo CalRoot;?>/images/favicon.png" type="image/png" />
	<link rel="alternate" type="application/rss+xml" title="<?php echo CalName;?> All Events" href="<?php echo CalRoot;?>/rss.php" />
	<link rel="alternate" type="application/rss+xml" title="<?php echo CalName;?> Newest Events" href="<?php echo CalRoot;?>/rss.php?s=1" />
	<link rel="alternate" type="application/rss+xml" title="<?php echo CalName;?> Billboard Events" href="<?php echo CalRoot;?>/rss.php?s=3" />
	<link rel="alternate" type="application/rss+xml" title="<?php echo CalName;?> Most Popular Events" href="<?php echo CalRoot;?>/rss.php?s=2" />
	
	<link rel="search" type="application/opensearchdescription+xml" href="<?php echo CalRoot;?>/addSearch.php" title="<?php echo CalName;?> Event Search" />
	
	<meta http-equiv="generator" content="Helios Calendar <?php echo HC_Version;?>" /> <!-- leave this for stats -->

<?php
	if(isset($hc_googleKey) && isset($_GET['com']) && ($_GET['com'] == 'detail' || $_GET['com'] == 'location')){	?>
	<script language="JavaScript" type="text/JavaScript" src="http://maps.google.com/maps?file=api&amp;v=2&amp;key=<?php echo $hc_googleKey;?>"></script>

<?php
	}//end if	?>