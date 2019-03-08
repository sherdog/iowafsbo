<?php
include "master.inc.php";

function importSchedule($date, $year, $track="", $division="", $series="", $city="", $state="", $purse="", $winner=""){
	echo "<br>";
	$states = getStates();
	$states = end(preg_grep("/$state/", $states));
	$states = explode("|", $states);
	$state = $states[0]; 
	$date = str_replace("  ", "", $date);
	$date = str_replace(".", "", $date);
	$ff = explode(" ", $date);
	$dd = explode("-", $ff[1]);
	if(!isset($dd[1]))
		$dd[1] = $dd[0];
	
	$date1 = strtotime("$ff[0] $dd[0] $year");
	$date2 = strtotime("$ff[0] $dd[1] $year");
	$result = dbQuery("SELECT track_id FROM track WHERE track_title=\"$track\" AND track_state='$state'");
	if(dbNumRows($result) == 0){	// Add Track
		dbQuery("INSERT INTO track SET track_title=\"$track\", track_city=\"$city\", track_state=\"$state\"");
		$track_id = dbLastInsertId("track");
		
	}
	else{
		$rec = dbFetchArray($result);
		$track_id = $rec["track_id"];
	}
	echo "INSERT INTO schedule SET schedule_track=$track_id, schedule_division=\"$division\", schedule_series=\"$series\", schedule_purse=\"$purse\", schedule_winner=\"$winner\", schedule_start_date=$date1, schedule_end_date=$date2";
	echo "<br>\n";
}



importSchedule("July 20",2007,"Simpson County Motorsports Park","Crate","Independent","D'Lo","Mississippi",2000,"rained out; reset for July 27");
importSchedule("Jan. 26-28",2007,"East Alabama Motor Speedway","Crate","Independent","Phenix City","Alabama",3000,"Ty Lowe");
importSchedule("Feb. 24-25",2007,"Green Valley Speedway","Crate","Independent","Glencoe","Alabama",3000,"Brian Smith");
importSchedule("March 2-4",2007,"East Alabama Motor Speedway","Crate","Independent","Phenix City","Alabama",10000,"Frankie Beard");
importSchedule("March 16",2007,"Southern Speedway","Crate","Independent","Hattiesburg","Mississippi",2000,"Mike Boland");
importSchedule("March 17",2007,"Whynot Motorsports Park","Crate","Independent","Meridian","Mississippi",2000,"Mike Boland");
importSchedule("June 8",2007,"Tomahawk Speedway","Crate","Independent","Wegner","South Dakota",2000);
importSchedule("July 3",2007,"Green Valley Speedway","Crate","Independent","Glencoe","Alabama",1500,"Dale McDowell");
importSchedule("July 21",2007,"Pike County Speedway","Crate","Independent","Magnolia","Mississippi",2000,"Mike Boland");
importSchedule("July 27",2007,"Simpson County Motorsports Park","Crate","Independent","D'Lo","Mississippi",2000,"Mike Boland");
importSchedule("July 28",2007,"Southern Raceway","Crate","Independent","Milton","Florida",4400,"Bo Slay");
importSchedule("Aug. 18",2007,"Pittsburgh's Pennsylvania Motor Speedway","Crate","Independent","Imperial","Pennsylvania","TBA");
importSchedule("Sept. 8",2007,"Central Mississippi Speedway","Crate","Independent","Winona","Mississippi","TBA");
importSchedule("Sept. 21",2007,"Jackson Motor Speedway","Crate","Independent","Byram","Mississippi","TBA");
importSchedule("Sept. 29",2007,"Soggy Bottom Speedway","Crate","Independent","Morgantown","Kentucky",3000);



?>
