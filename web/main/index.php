<?php

set_include_path(get_include_path() . PATH_SEPARATOR . "../lib" . PATH_SEPARATOR . "../templates");

include_once "misc.inc.php";

$path = isset($_GET["p"]) ? $_GET["p"] : "";
$tokens = preg_split('/\//', $path);

if ($path == "") {
	include "pages/home.php";
} else if ('/' . $tokens[0] == LOGIN_PATH) {
	include "pages/login.php";
} else if ('/' . $tokens[0] == MENU_PATH) {
	if (!empty($tokens[1])) {
		if (!menu_exists($tokens[1])) {
			header("HTTP/1.0 404 Not Found");
			include "./404.php";
			return;
		}
		include "pages/menu.php";
		return;
	}
	header("Location: /");
} else if('/' . $tokens[0] == RATINGS_PATH) {
	if (!empty($tokens[1])) {
		$item_id = $tokens[1];
		include "modals/ratings.php";
		return;
	}
	header("Location: /");
}else if('/' . $tokens[0] == CREATE_RATING_PATH) {
	if (!empty($tokens[1])) {
		$item_id = $tokens[1];
		include "modals/ratings.php";
		return;
	}
	header("Location: /");
}
else {
	switch ($path) {
		default:
			header("HTTP/1.0 404 Not Found");
			include "./404.php";
			break;
	}
}