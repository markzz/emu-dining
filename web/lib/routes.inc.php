<?php

$ROUTES = array(
	"" => "home.php",
	"index.php" => "home.php",
	"/menu" => "menu.php",
	"/login" => "login.php",
);

const MENU_PATH = "/menu";
const LOGIN_PATH = "/login";
const LOGOUT_PATH = "/logout";
const AUTH_PATH = "/auth";
const RATINGS_PATH = "/ratings";
const CREATE_RATING_PATH = "/create_rating";
const DELETE_RATING_PATH = "/delete_rating";

function get_route() {
	global $ROUTES;

	$path = rtrim('/');

	return isset($ROUTES[$path]) ? $ROUTES[$path] : null;
}
