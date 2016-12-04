<?php

$ROUTES = array(
	"" => "home.php",
	"index.php" => "home.php",
	"/menu" => "menu.php",
	"/login" => "login.php",
);

const MENU_PATH = "/menu";
const LOGIN_PATH = "/login";
const AUTH_PATH = "/auth";
const RATINGS_PATH = "/ratings";
const CREATE_RATING_PATH = "/create_rating";

function get_route() {
	global $ROUTES;

	$path = rtrim('/');

	return isset($ROUTES[$path]) ? $ROUTES[$path] : null;
}
