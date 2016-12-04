<?php

function config_load() {
	global $MENU_CONFIG;
	if (!isset($MENU_CONFIG)) {
		$MENU_CONFIG = parse_ini_file("../../conf/config", true, INI_SCANNER_RAW);
	}
}
function config_get($section, $key) {
	global $MENU_CONFIG;
	config_load();
	return $MENU_CONFIG[$section][$key];
}
function config_get_int($section, $key) {
	return intval(config_get($section, $key));
}
function config_get_bool($section, $key) {
	$val = strtolower(config_get($section, $key));
	return ($val == 'yes' || $val == 'true' || $val == '1');
}
function config_items($section) {
	global $MENU_CONFIG;
	config_load();
	return $MENU_CONFIG[$section];
}
function config_section_exists($key) {
	global $MENU_CONFIG;
	config_load();
	return array_key_exists($key, $MENU_CONFIG);
}