<?php

/*
Menu functions
*/

/**
 * Gets data for a menu
 *
 * @param date          The date for the menu
 * @param location_id   The id of the location
*/
function get_menu($date, $location_id) {

    $file_name = "../../data/" . $location_id . $date . ".json";

    if(file_exists($file_name)) {
        $json = file_get_contents($file_name);
    }
    else {

        $url = 'https://new.dineoncampus.com/v1/location/menu.json';
        $url.= '?date=' . $date;
        $url.= '&location_id=' . $location_id;
        $url.= '&platform=0&site_id=5759a998e551b879726805ba';

        $curl = curl_init($url); 
        curl_setopt($curl, CURLOPT_FAILONERROR, true); 
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true); 
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true); 
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false); 
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);   
        $json = curl_exec($curl);

        file_put_contents($file_name, $json);
    }

    $json_arr = json_decode($json);
    return $json_arr;
}

//var_dump(get_menu(date('Y-m-j', time()), "57d6ed562cc8da6ce636371a"));
