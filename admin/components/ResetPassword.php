<?php
/*
	Helios Calendar
	Copyright (C) 2004-2010 Refresh Web Development, LLC. [www.RefreshMy.com]

	This file is part of Helios Calendar, it's usage is governed by
	the Helios Calendar SLA found at www.HeliosCalendar.com/license.html
*/
	$isAction = 1;
	include('../includes/include.php');
	
	$aID = (isset($_POST['aID'])) ? $_POST['aID'] : 0;
	$pass1 = (isset($_POST['pass1'])) ? $_POST['pass1'] : '';
	$pass2 = (isset($_POST['pass2'])) ? $_POST['pass2'] : '';
	
	$result = doQuery("SELECT * FROM " . HC_TblPrefix . "admin WHERE PkID = " . cIn($aID));
	if(hasRows($result)){
		if($pass1 == $pass2){
			doQuery("UPDATE " . HC_TblPrefix . "admin SET Passwrd = '" . cIn(md5(md5($pass1) . mysql_result($result,0,3))) . "', PCKey = NULL WHERE PkID = " . cIn($aID));
			header('Location: ' . CalAdminRoot . '/?msg=4');
		} else {
			header('Location: ' . CalAdminRoot . '/?msg=5');
		}//end if
	} else {
		header('Location: ' . CalAdminRoot . '/');
	}//end if?>