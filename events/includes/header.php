<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright � 2006 Refresh Web Development [http://www.refreshwebdev.com]
	
	Developed By: Chris Carlevato <chris@refreshwebdev.com>
	
	For the most recent version, visit the Helios Calendar website:
	[http://www.helioscalendar.com]
	
	License Information is found in docs/license.html
*/
	//ini_set('include_path', '/inc');
	include('includes/include.php');
	
	$result = doQuery("SELECT SettingValue FROM " . HC_TblPrefix . "settings WHERE PkID IN (1,5,6,7,10,11,12,13,14,15,21,22,23,24) ORDER BY PkID");
	
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
		
		$result = doQuery("SELECT SettingValue FROM " . HC_TblPrefix . "settings WHERE PkID = 1");
	$doSubmit = mysql_result($result,0,0);
	
	} else {
		exit(handleError(0, "Helios Settings Data Missing. You will need to run Helios Setup again."));
	}//end if
	
	define("HC_Menu", "components/Menu.php");
	define("HC_Core", "components/Core.php");
	define("HC_Controls", "components/ControlPanel.php");
	define("HC_Billboard", "components/Billboard.php");
	define("HC_Popular", "components/Popular.php");
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
<?} else {?>
	<meta name="robots" content="noindex, nofollow" />
	<meta name="GOOGLEBOT" content="noindex, nofollow" />
<?}//end if?>
	<meta http-equiv="author" content="Refresh Web Development LLC" />
	<meta http-equiv="email" content="<?echo CalAdminEmail;?>" />
	<meta http-equiv="copyright" content="2004-<?echo date("Y");?> All Rights Reserved" />
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta http-equiv="description" content="<?echo $hc_description;?>" />
	<meta http-equiv="keywords" content="<?echo $hc_keywords;?>" />
	<meta http-equiv="expires" content="604800" />
	<meta name="MSSmartTagsPreventParsing" content="yes" />
	
	<link rel="bookmark" title="<?echo CalName;?>" href="<?echo CalRoot;?>" />
	<link rel="stylesheet" type="text/css" href="<?echo CalRoot;?>/css/helios.css" />
	<!--[if IE]><link rel="stylesheet" type="text/css" href="<?echo CalRoot;?>/css/heliosIE.css" /><![endif]-->
	<link rel="icon" href="<?echo CalRoot;?>/images/favicon.png" type="image/png" />
	<link rel="alternate" type="application/rss+xml" title="<?echo CalName;?> All Events" href="<?echo CalRoot;?>/rss.php" />
	<link rel="alternate" type="application/rss+xml" title="<?echo CalName;?> Newest Events" href="<?echo CalRoot;?>/rss.php?s=1" />
	<link rel="alternate" type="application/rss+xml" title="<?echo CalName;?> Billboard Events" href="<?echo CalRoot;?>/rss.php?s=3" />
	<link rel="alternate" type="application/rss+xml" title="<?echo CalName;?> Most Popular Events" href="<?echo CalRoot;?>/rss.php?s=2" />
	
	<meta name="generator" content="Helios Calendar <?echo HC_Version;?>" /> <!-- leave this for stats -->


