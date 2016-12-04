<?php

set_include_path(get_include_path() . PATH_SEPARATOR . "../lib");

include_once "DB.class.php";
include_once "routes.inc.php";
include_once "menufuncs.inc.php";
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

//die(get_location_id("commons"));