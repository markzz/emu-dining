<?php

set_include_path(get_include_path() . PATH_SEPARATOR . "../lib");

include_once "confparser.inc.php";
include_once "DB.class.php";
include_once "routes.inc.php";
include_once "menufuncs.inc.php";
include_once "ratingfuncs.inc.php";
include_once "authentication.inc.php";

function get_header($title="") {
	include "header.php";
}

function get_footer() {
	include "footer.php";
}

function menu_exists($name) {
	$dbh = DB::connect();

	$q = "SELECT id ";
	$q.= "FROM locations ";
	$q.= "WHERE short_name = " . $dbh->quote($name);

	$result = $dbh->query($q);
	if ($result->rowCount() == 0) {
		return false;
	}

	return true;
}

function get_location_info($name) {
    $dbh = DB::connect();

    $q = "SELECT id, location_id, name, short_name ";
    $q.= "FROM locations ";
    $q.= "WHERE short_name = " . $dbh->quote($name);

    $result = $dbh->query($q);
	if ($result->rowCount() == 0) {
		return false;
	}

	return $result->fetch(PDO::FETCH_ASSOC);
}

function create_date_range($strDateFrom,$strDateTo)
{
    // takes two dates formatted as YYYY-MM-DD and creates an
    // inclusive array of the dates between the from and to dates.

    // could test validity of dates here but I'm already doing
    // that in the main script

    $aryRange=array();

    $iDateFrom=mktime(1,0,0,substr($strDateFrom,5,2),     substr($strDateFrom,8,2),substr($strDateFrom,0,4));
    $iDateTo=mktime(1,0,0,substr($strDateTo,5,2),     substr($strDateTo,8,2),substr($strDateTo,0,4));

    if ($iDateTo>=$iDateFrom)
    {
        array_push($aryRange,date('Y-m-j',$iDateFrom)); // first entry
        while ($iDateFrom<$iDateTo)
        {
            $iDateFrom+=86400; // add 24 hours
            array_push($aryRange,date('Y-m-j',$iDateFrom));
        }
    }
    return $aryRange;
}