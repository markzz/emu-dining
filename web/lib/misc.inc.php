<?php

set_include_path(get_include_path() . PATH_SEPARATOR . "../lib");

include_once "DB.class.php";
include_once "routes.inc.php";

function get_header($title="") {
	include "header.php";
}

function menu_exists($name) {
	$dbh = DB::connect();

	$q = "SELECT id ";
	$q.= "FROM locations ";
	$q.= "WHERE short_name = " . $dbh->quote($name);

	$result = $dbh->query($q);

	if (!$result) {
		return false;
	}

	return true;
}