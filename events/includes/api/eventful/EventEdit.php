<?php
/*
	Helios Calendar
	Copyright (C) 2004-2010 Refresh Web Development, LLC. [www.RefreshMy.com]
	
	This file is part of Helios Calendar, it's usage is governed by
	the Helios Calendar SLA found at www.HeliosCalendar.com/license.html
*/
	$errorMsg = '';
	$result = doQuery("SELECT * FROM " . HC_TblPrefix . "settings WHERE PkID IN(36,37,38,39);");
	if(!hasRows($result)){
		$apiFail = true;
		$errorMsg = 'Settings Table Corrupted.';
	} else {
		if(mysql_result($result,0,1) == '' || mysql_result($result,1,1) == '' || mysql_result($result,2,1) == ''){
			$apiFail = true;
			$errorMsg = 'Eventful API Settings Missing.';
		} else {
			$efKey = mysql_result($result,0,1);
			$efUser = mysql_result($result,1,1);
			$efPass = mysql_result($result,2,1);
			$efSig = mysql_result($result,3,1);
			$efID = (!isset($efID)) ? 0 : $efID;
			$efSend = ($efID == '') ? "/rest/events/new" : "/rest/events/modify";

			$ip = gethostbyname("api.evdb.com");
			if(!($fp = fsockopen($ip, 80, $errno, $errstr, 1)) ){
				$apiFail = true;
				$errorMsg = 'Connection to Eventful Service Failed.';
			} else {
				$efSend .= "?app_key=" . $efKey;
				$efSend .= "&user=" . $efUser;
				if($efID != ''){
					$efSend .= "&id=" . $efID;
				}//end if
				$efSend .= "&password=" . urlencode($efPass);
				$efSend .= "&title=" . urlencode($eventTitle);

				if($tbd == 0){
					$efSend .= "&start_time=" . $eventDate . "T" . str_replace("'", "", $startTime);
					if(!isset($_POST['ignoreendtime'])){
						$endDate = $eventDate;
						if($startTime > $endTime){
							$dateParts = explode("-", $eventDate);
							$endDate = date("Y-m-d", mktime(0,0,0,$dateParts[1],($dateParts[2]+1),$dateParts[0]));
						}//end if
						$efSend .= "&stop_time=" . $endDate . "T" . str_replace("'", "", $endTime);
					}//end if
					$efSend .= "&all_day=";
				} else {
					$efSend .= "&start_time=" . $eventDate . "T00:00:00";
					$efSend .= "&all_day=1";
				}//end if
				$efSend .= "&price=" . urlencode(htmlentities($cost));

				$tags = '';
				$catIDs = "'" . implode("','",$catID) . "'";
				$resultC = doQuery("SELECT CategoryName FROM " . HC_TblPrefix . "categories WHERE PkID IN (" . $catIDs . ")");
				if(hasRows($resultC)){
					while($row = mysql_fetch_row($resultC)){
						$tags .= str_replace(" ", "_", $row[0]) . " ";
					}//end while
				}//end if
				$efSend .= "&tags=" . urlencode($tags) . "Helios_Calendar";

				if(strlen($eventDesc) > 400){
					$eventD = substr($eventDesc,0,400) . '...<br /><br /><a href="' . CalRoot . '/index.php?com=detail&eID=' . $eID . '">View Full Event Details</a>';
				} else {
					$eventD = $eventDesc;
				}//end if
				
				$resultLoc = doQuery("SELECT NetworkID FROM " . HC_TblPrefix . "locationnetwork WHERE NetworkType = 1 AND LocationID = '" . $locID . "'");
				if(hasRows($resultLoc)){
					$efSend .= "&venue_id=" . mysql_result($resultLoc,0,0);
				} else {
					if($locID > 0){
						$resultLoc = doQuery("SELECT * From " . HC_TblPrefix . "locations WHERE PkID = '" . cIn($locID) . "'");
						if(hasRows($resultLoc)){
							$locName = mysql_result($resultLoc,0,1);
							$locAddress = mysql_result($resultLoc,0,2);
							$locAddress2 = mysql_result($resultLoc,0,3);
							$locCity = mysql_result($resultLoc,0,4);
							$locState = mysql_result($resultLoc,0,5);
							$locCountry = mysql_result($resultLoc,0,6);
							$locZip = mysql_result($resultLoc,0,7);
						}//end if
					}//end if
					$eventD .= "<p><b>Venue</b><br>";
					$eventD .= ($locName != '') ? $locName . '<br>' : '';
					$eventD .= ($locAddress != '') ? $locAddress . '<br>' : '';
					$eventD .= ($locAddress2 != '') ? $locAddress2 . '<br>' : '';
					$eventD .= ($locCity != '') ? $locCity . '<br>' : '';
					$eventD .= ($locState != '') ? $locState . '<br>' : '';
					$eventD .= ($locCountry != '') ? $locCountry . '<br>' : '';
					$eventD .= ($locZip != '') ? $locZip . '<br>' : '';
					$eventD .= "</p>";
					$efSend .= "&venue_id=";
				}//end if
				$eventD .= "<p>" . $efSig . "<br>";
				$eventD .= "<b><a href=\"" . CalRoot . "\">" . CalRoot . "/</a></b></p>";
				
				$efSend .= "&description=" . urlencode(nl2br($eventD));
				$efSend .= "&privacy=1";	//1 = public, 2 = private

				$request = "GET " . $efSend . " HTTP/1.0\r\nHost: api.evdb.com\r\nConnection: Close\r\n\r\n";

				fwrite($fp, $request);
				$data = '';
				while(!feof($fp)) {
					$data .= fread($fp,1024);
				}//end while
				fclose($fp);
				
				$fetched = xml2array($data);
				if($fetched[0]['name'] == 'error'){
					$apiFail = true;
					$errorMsg = 'Error Msg From Eventful - <i>' . $fetched[0]['elements'][0]['value'] . '</i>';
				} else {
					$stopEF = count($fetched[0]['elements']);
					for($x=0;$x<$stopEF;$x++){
						if($fetched[0]['elements'][$x]['name'] == 'id'){
							$efID = $fetched[0]['elements'][$x]['value'];
							break;
						}//end if
					}//end for
				}//end if
			}//end if
			echo ($errorMsg != '') ? $errorMsg : '';
		}//end if
	}//end if?>