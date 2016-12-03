<?php

set_include_path(get_include_path() . PATH_SEPARATOR . "../lib" . PATH_SEPARATOR . "../templates");

include_once "misc.inc.php";

$path = isset($_GET["p"]) ? $_GET["p"] : "";
$tokens = preg_split('/\//', $path);

if ($path == "") {
	include('home.php');
} else if ('/' . $tokens[0] == get_login_path()) {
	/* TODO: Implement login */
} else if ('/' . $tokens[0] == get_menu_path()) {
	if (!empty($tokens[1])) {
		if (!menu_exists($tokens[1])) {
			header("HTTP/1.0 404 Not Found");
			include "./404.php";
			return;
		}

		return;
	}
	header("Location: /");
} else {
	switch ($path) {
		default:
			header("HTTP/1.0 404 Not Found");
			include "./404.php";
			break;
	}
}