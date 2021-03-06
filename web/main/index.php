<?php
set_include_path(get_include_path() . PATH_SEPARATOR . "../lib" . PATH_SEPARATOR . "../templates");
session_start();
include_once "misc.inc.php";

$path = isset($_GET["p"]) ? $_GET["p"] : "";
$tokens = preg_split('/\//', $path);

if ($path == "") {
	header("Location: /menu/eagle-cafes");
} else if ('/' . $tokens[0] == LOGIN_PATH) {
	login();
	include "pages/login.php";
}else if ('/' . $tokens[0] == LOGOUT_PATH) {
	logout();
	header('Location: /');
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
} else if('/' . $tokens[0] == CREATE_RATING_PATH) {
	create_rating();
} else if('/' . $tokens[0] == DELETE_RATING_PATH) {
	delete_rating();
} else if('/' . $tokens[0] == AUTH_PATH) {
	auth();
} else if ('/' . $tokens[0] == LOGOUT_PATH) {
	log_out();
	header("Location: /");
} else {
	switch ($path) {
		default:
			header("HTTP/1.0 404 Not Found");
			include "./404.php";
			break;
	}
}
