<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright � 2007 Refresh Web Development [http://www.refreshwebdev.com]
	
	Developed By: Chris Carlevato <chris@refreshwebdev.com>
	
	For the most recent version, visit the Helios Calendar website:
	[http://www.helioscalendar.com]
	
	License Information is found in docs/license.html
*/
	
	$isAction = 1;
	include('../includes/include.php');
	checkIt(1);
	
	$eventStatus = $_POST['eventStatus'];
	$eventBillboard = $_POST['eventBillboard'];
	$eventTitle = $_POST['eventTitle'];
	$eventDesc = $_POST['eventDescription'];
	$eventDate = dateToMySQL($_POST['eventDate'], "/", $_POST['dateFormat']);
	$contactName = $_POST['contactName'];
	$contactEmail = $_POST['contactEmail'];
	$contactPhone = $_POST['contactPhone'];
	$contactURL = $_POST['contactURL'];
	$allowRegistration = $_POST['eventRegistration'];
	$locID = $_POST['locPreset'];
	$startTime = "NULL";
	$endTime = "NULL";
	$maxRegistration = 0;
	$publishDate = "NULL";
	$cost = $_POST['cost'];
	$msgID = 2;
	
	if($locID > 0){
		$locName = "";
		$locAddress = "";
		$locAddress2 = "";
		$locCity = "";
		$locState = "";
		$locZip = "";
		$locCountry = "";
	} else {
		$locName = $_POST['locName'];
		$locAddress = $_POST['locAddress'];
		$locAddress2 = $_POST['locAddress2'];
		$locCity = $_POST['locCity'];
		$locState = $_POST['locState'];
		$locZip = $_POST['locZip'];
		$locCountry = $_POST['locCountry'];
	}//end if
	
	if($eventStatus == 1){
		$publishDate = date("Y-m-d H:i:s");
	}//end if
	
	if($allowRegistration == 1){
		$maxRegistration = $_POST['eventRegAvailable'];
	}//end if
	
	if($contactURL == "http://"){
		$contactURL = NULL;
	}//end if
	
	if(!isset($_POST['overridetime'])){
		$startTimeHour = $_POST['startTimeHour'];
		$startTimeMins = $_POST['startTimeMins'];
		
		if($_POST['timeFormat'] == 12){
			if($_POST['startTimeAMPM'] == 'PM' AND $startTimeHour != 12){
				$startTimeHour = $startTimeHour + 12;
			}//end if
			
			if($_POST['startTimeAMPM'] == 'AM' AND $startTimeHour == 12){
				$startTimeHour = $startTimeHour + 12;
			}//end if
		}//end if
		
		$endTime = "NULL";
		if(!isset($_POST['ignoreendtime'])){
			$endTimeHour = $_POST['endTimeHour'];
			$endTimeMins = $_POST['endTimeMins'];
			
			if($_POST['timeFormat'] == 12){
				if($_POST['endTimeAMPM'] == 'PM' AND $endTimeHour != 12){
					$endTimeHour = $endTimeHour + 12;
				}//end if
				
				if($_POST['endTimeAMPM'] == 'AM' AND $endTimeHour == 12){
					$endTimeHour = $endTimeHour + 12;
				}//end if
			}//end if
			
			$endTime = "'" . cIn($endTimeHour) . ":" . cIn($endTimeMins) . ":00'";
		}//end if
		
		$tbd = 0;
		$startTime = "'" . cIn($startTimeHour) . ":" . cIn($startTimeMins) . ":00'";
		
	} else {
		$startTime = "NULL";
		$endTime = "NULL";
		$tbd = 2;
		if($_POST['specialtime'] == "allday"){
			$tbd = 1;
		}//end if
	}//end if
	
	if(isset($_POST['recurCheck'])){
		$dates = array();
		
		switch($_POST['recurType']){
			case 'daily':
				$days = $_POST['dailyDays'];
				$curDate = $eventDate;
				$stopDate = dateToMySQL($_POST['recurEndDate'], "/", $_POST['dateFormat']);
				
				if($_POST['dailyOptions'] == 'EveryXDays'){
					while(strtotime($curDate) <= strtotime($stopDate)){
						$dates[] = $curDate;
						
						$dateParts = explode("-", $curDate);
						$curDate = date("Y-m-d", mktime(0, 0, 0, $dateParts[1], $dateParts[2] + $days, $dateParts[0]));
					}//end while
					
				} else {
					while(strtotime($curDate) <= strtotime($stopDate)){
						$dateParts = explode("-", $curDate);
						$curDayOfWeek = date("w", mktime(0, 0, 0, $dateParts[1], $dateParts[2], $dateParts[0]));
						
						if((($curDayOfWeek != 0) AND ($curDayOfWeek != 6)) OR $eventDate == $curDate){
							$dates[] = $curDate;
						}//end if
						$curDate = date("Y-m-d", mktime(0, 0, 0, $dateParts[1], $dateParts[2] + 1, $dateParts[0]));
					}//end while
					
				}//end if
				break;
				
			case 'weekly':
				$curDate = $eventDate;
				$stopDate = dateToMySQL($_POST['recurEndDate'], "/", $_POST['dateFormat']);
				$weeks = $_POST['recWeekly'];
				$recWeeklyDay = $_POST['recWeeklyDay'];
				
				while(strtotime($curDate) <= strtotime($stopDate)){
					$dateParts = explode("-", $curDate);
					$curDateDayOfWeek = date("w", mktime(0, 0, 0, $dateParts[1], $dateParts[2], $dateParts[0]));
					
					if(in_array($curDateDayOfWeek, $recWeeklyDay) OR $eventDate == $curDate){
						$dates[] = $curDate;
					}//end if
					
					if(($curDateDayOfWeek == 6) AND ($weeks > 1)){
						$curDate = date("Y-m-d", mktime(0, 0, 0, $dateParts[1], $dateParts[2] + ((($weeks - 1) * 7) + 1), $dateParts[0]));
					} else {
						$curDate = date("Y-m-d", mktime(0, 0, 0, $dateParts[1], $dateParts[2] + 1, $dateParts[0]));
					}//end if
				}//end while
				break;
				
			case 'monthly':
				$curDate = $eventDate;
				$stopDate = dateToMySQL($_POST['recurEndDate'], "/", $_POST['dateFormat']);
				
				if($_POST['monthlyOption'] == 'Day'){
					$day = $_POST['monthlyDays'];
					$months = $_POST['monthlyMonths'];
					
					while(strtotime($curDate) <= strtotime($stopDate)){
						$dates[] = $curDate;
						$dateParts = explode("-", $curDate);
						
						if($dateParts[2] < $day){
							$curDate = date("Y-m-d", mktime(0, 0, 0, $dateParts[1], $day, $dateParts[0]));
						} else {
							$curDate = date("Y-m-d", mktime(0, 0, 0, $dateParts[1] + $months, $day, $dateParts[0]));
						}//end if
					}//end while
				} else {
					$whichDay = $_POST['monthlyMonthOrder'];
					$whichDOW = $_POST['monthlyMonthDOW'];
					$whichRepeat = $_POST['monthlyMonthRepeat'];
					
					while(strtotime($curDate) <= strtotime($stopDate)){
						$dates[] = $curDate;
						
						$dateParts = explode("-", $curDate);
						$curMonth = $dateParts[1];
						$curYear = $dateParts[0];
						$cnt = 0;
						
						if($whichDay != 0){
							$x = date("w", mktime(0, 0, 0, $curMonth + $whichRepeat, 1, $curYear));
							while($x % 7 != $whichDOW){
								$x++;
								$cnt++;
							}//end if
							$curDate = date("Y-m-d", mktime(0, 0, 0, $curMonth + $whichRepeat, (1 + $cnt) + ((7 * $whichDay) - 7), $curYear));
						} else {
							$x = date("w", mktime(0, 0, 0, $curMonth + $whichRepeat + 1, 0, $curYear));
							$offset = 0;
							if($x < $whichDOW){$x = $x + 7;}
							while((abs($x) % 7) != $whichDOW){
								$x--;
								$cnt++;
							}//end if
							$curDate = date("Y-m-d", mktime(0, 0, 0, $curMonth + $whichRepeat + 1, 0 - $cnt, $curYear));
						}//end if
					}//end while
				}//end if
				break;
			
		}//end switch
		
		
		//loop array and insert dates
		$seriesID = DecHex(microtime() * 1000000) . DecHex(microtime() * 9999999) . DecHex(microtime() * 8888888);
		foreach ($dates as $val){
			$eventDate = $val;
			$query = "	INSERT INTO " . HC_TblPrefix . "events(Title, LocationName, LocationAddress, LocationAddress2, 
									LocationCity, LocationState, LocationZip, Description,
									StartDate, StartTime, TBD, EndTime, ContactName,
									ContactEmail, ContactPhone, ContactURL, IsActive, IsApproved,
									IsBillboard, SeriesID, AllowRegister, SpacesAvailable, PublishDate, LocID, Cost, LocCountry)
						VALUES('" . cIn($eventTitle) . "', '" . cIn($locName) . "', '" . cIn($locAddress) . "', '" . cIn($locAddress2) . "',
								'" . cIn($locCity) . "', '" . cIn($locState) . "', '" . cIn($locZip) . "', '" . cIn($eventDesc) . "',
								'" . cIn($eventDate) . "', " . $startTime . ", " . cIn($tbd) . ", " . $endTime . ",
								'" . cIn($contactName) . "', '" . cIn($contactEmail) . "', '" . cIn($contactPhone) . "', '" . cIn($contactURL) . "',
								'1', '" . cIn($eventStatus) . "', '" . cIn($eventBillboard) . "', '" . cIn($seriesID) . "',
								'" . cIn($allowRegistration) . "', '" . cIn($maxRegistration) . "', '" . cIn($publishDate) . "',
								'" . cIn($locID) . "', '" . cIn($cost) . "', '" . cIn($locCountry) ."');";
			doQuery($query);
			
			$result = doQuery("SELECT LAST_INSERT_ID() FROM " . HC_TblPrefix . "events");
			$newPkID = mysql_result($result,0,0);
			
			if(isset($_POST['catID'])){
				$catID = $_POST['catID'];
				foreach ($catID as $val){
					doQuery("INSERT INTO " . HC_TblPrefix . "eventcategories(EventID, CategoryID) VALUES('" . cIn($newPkID) . "', '" . cIn($val) . "')");
				}//end foreach
			}//end if
			
			if(isset($_POST['doEventful'])){
				$result = doQuery("SELECT * FROM " . HC_TblPrefix . "settings WHERE PkID IN(36,37,38,39);");
				if(hasRows($result)){
					if(mysql_result($result,0,0) == '36' && mysql_result($result,0,1) == ''){
						$msgID = 7;
					} else {
						$efKey = mysql_result($result,0,1);
						$efUser = "";
						$efPass = "";
						if(mysql_result($result,1,1) == ''){
							if(isset($_POST['efUser'])){
								$efUser = $_POST['efUser'];
							}//end if
						} else {
							$efUser = mysql_result($result,1,1);
						}//end if
						if(mysql_result($result,2,1) == ''){
							if(isset($_POST['efPass'])){
								$efPass = $_POST['efPass'];
							}//end if
						} else {
							$efPass = mysql_result($result,2,1);
						}//end if
						
						$efRest = "/rest/events/new";
						include('EventfulAddEvent.php');
					}//end if
				}//end if
			}//end if
		}//end for
	} else {
		$query = "INSERT INTO " . HC_TblPrefix . "events(Title, LocationName, LocationAddress, LocationAddress2, 
									 LocationCity, LocationState, LocationZip, Description,
									 StartDate, StartTime, TBD, EndTime, ContactName,
									 ContactEmail, ContactPhone, ContactURL, IsActive, IsApproved,
									 IsBillboard, AllowRegister, SpacesAvailable, PublishDate, LocID, Cost, LocCountry)
				VALUES('" . cIn($eventTitle) . "', '" . cIn($locName) . "', '" . cIn($locAddress) . "', '" . cIn($locAddress2) . "',
						'" . cIn($locCity) . "', '" . cIn($locState) . "', '" . cIn($locZip) . "', '" . cIn($eventDesc) . "',
						'" . cIn($eventDate) . "', " . $startTime . ", '" . cIn($tbd) . "', " . $endTime . ",
						'" . cIn($contactName) . "', '" . cIn($contactEmail) . "', '" . cIn($contactPhone) . "', '" . cIn($contactURL) . "',
						'1', '" . cIn($eventStatus) . "', '" . cIn($eventBillboard) . "',
						'" . cIn($allowRegistration) . "', '" . cIn($maxRegistration) . "', '" . cIn($publishDate) . "',
						'" . cIn($locID) . "', '" . cIn($cost) . "', '" . cIn($locCountry) . "');";
		doQuery($query);
		
		$result = doQuery("SELECT LAST_INSERT_ID()");
		$newPkID = mysql_result($result,0,0);
		
		if(isset($_POST['catID'])){
			$catID = $_POST['catID'];
			foreach ($catID as $val){
				doQuery("INSERT INTO " . HC_TblPrefix . "eventcategories(EventID, CategoryID) VALUES('" . cIn($newPkID) . "', '" . cIn($val) . "')");
			}//end foreach
		}//end if
		
		if(isset($_POST['doEventful'])){
			$result = doQuery("SELECT * FROM " . HC_TblPrefix . "settings WHERE PkID IN(36,37,38,39);");
			if(hasRows($result)){
				if(mysql_result($result,0,0) == '36' && mysql_result($result,0,1) == ''){
					$msgID = 7;
				} else {
					$efKey = mysql_result($result,0,1);
					$efUser = "";
					$efPass = "";
					if(mysql_result($result,1,1) == ''){
						if(isset($_POST['efUser'])){
							$efUser = $_POST['efUser'];
						}//end if
					} else {
						$efUser = mysql_result($result,1,1);
					}//end if
					if(mysql_result($result,2,1) == ''){
						if(isset($_POST['efPass'])){
							$efPass = $_POST['efPass'];
						}//end if
					} else {
						$efPass = mysql_result($result,2,1);
					}//end if
					$msgID = 9;
					$efRest = "/rest/events/new";
					include('EventfulAddEvent.php');
				}//end if
			}//end if
		}//end if
	}//end if
	
	header("Location: " . CalAdminRoot . "/index.php?com=eventedit&msg=" . $msgID . "&eID=" . $newPkID);	?>