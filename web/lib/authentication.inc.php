<?php

set_include_path(get_include_path() . PATH_SEPARATOR . "../../vendor/");

include_once "autoload.php";

$dev_key = config_get('options', 'dev-key');
$redirect_uri = config_get('options', 'redirect-uri');

function login() {
	global $dev_key, $redirect_uri;
	
	$client = new Google_Client();
	$client->setDeveloperKey($dev_key);
	$client->setAuthConfig("../../conf/google_client_id.json");
	$client->setRedirectUri($redirect_uri);
	$client->setScopes("email");
	$auth_url = $client->createAuthUrl();

	header('Location: ' . filter_var($auth_url, FILTER_SANITIZE_URL));
}

function auth() {
	global $dev_key, $redirect_uri;
	$client = new Google_Client();

	$client->setDeveloperKey($dev_key);
	$client->setAuthConfig("../../conf/google_client_id.json");
	$client->setRedirectUri($redirect_uri);
	$client->setScopes("email");

	$client->authenticate($_GET['code']);
	$_SESSION['access_token'] = $client->getAccessToken();

	if(isset($_COOKIE['return_url'])) {
		$return_url = $_COOKIE['return_url'];
		unset($_COOKIE['return_url']);
		header('Location: '.$return_url);
	}
	else {
		header('Location: /');
	}
}

function get_user_name() {

	if(!is_logged_in()) return false;

	global $dev_key, $redirect_uri;
	$client = new Google_Client();

	$client->setDeveloperKey($dev_key);
	$client->setAuthConfig("../../conf/google_client_id.json");
	$client->setRedirectUri($redirect_uri);
	$client->setScopes("email");

	if (isset($_SESSION['access_token'])) {
		$data = $client->verifyIdToken($_SESSION['access_token']['id_token']);
	}

	if(!$data) return false;

	$service = new Google_Service_Plus($client);
	return $service->people->get($data['sub'])->displayName;
}

function get_user_name_from_id($id) {
	if(!is_logged_in()) return false;
	global $dev_key, $redirect_uri;
	$client = new Google_Client();
	$client->setDeveloperKey($dev_key);
	$client->setAuthConfig("../../conf/google_client_id.json");
	$client->setRedirectUri($redirect_uri);
	$client->setScopes("email");

	$service = new Google_Service_Plus($client);
	return $service->people->get(strval($id))->displayName;
}

function get_user_id() {

	if(!isset($_SESSION['access_token'])) return false;

	global $dev_key, $redirect_uri;
	$client = new Google_Client();

	$client->setDeveloperKey($dev_key);
	$client->setAuthConfig("../../conf/google_client_id.json");
	$client->setRedirectUri($redirect_uri);
	$client->setScopes('email');

	if (isset($_SESSION['access_token'])) {
		$data = $client->verifyIdToken($_SESSION['access_token']['id_token']);
		return intval($data['sub']);
	} else {
		return null;
	}
}

function is_logged_in() {
	return get_user_id() ? true : false;
}

function log_out() {
	unset($_SESSION['access_token']);
}