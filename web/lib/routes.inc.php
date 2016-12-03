<?php

$ROUTES = array(
	"" => "home.php",
	"index.php" => "home.php",
	"/menu" => "menu.php",
	"/login" => "login.php",
);

$MENU_PATH = "/menu";
$LOGIN_PATH = "/login";

function get_route() {
	global $ROUTES;

	$path = rtrim('/');

	return isset($ROUTES[$path]) ? $ROUTES[$path] : null;
}

function get_menu_path() {
	global $MENU_PATH;
	return $MENU_PATH;
}

function get_login_path() {
	global $LOGIN_PATH;
	return $LOGIN_PATH;
}