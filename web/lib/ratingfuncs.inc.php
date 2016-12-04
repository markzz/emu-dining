<?php

function delete_rating() {
    $dbh = DB::connect();

    if(!is_logged_in()) {
        return false;
    }

    $item_id = isset($_POST['id']) ? $_POST['id'] : 0;
    $user_id = get_user_id();

    $q = "DELETE FROM reviews ";
    $q.= "WHERE `user_id` = " . $dbh->quote($user_id) . " ";
    $q.= "AND `item_id` = " . $dbh->quote($item_id);

    $result = $dbh->query($q);
    if(!$result) return false;
    return true;
}

function create_rating() {

    $dbh = DB::connect();

    if(!is_logged_in()) {
        die('Please login!');
    }

    $user_id = get_user_id();
    $user_name = get_user_name();
    $item_id = isset($_POST['item_id']) ? $_POST['item_id'] : 0;
    $rating = isset($_POST['rating']) ? $_POST['rating'] : 0;
    $text = isset($_POST['text']) ? $_POST['text'] : null;

    if($rating <= 0 || $rating > 5) {
        die('Rating is invalid');
    }

    $q = 'INSERT INTO reviews ( user_id, user_name, item_id, rating, `text` ) ';
    $q.= 'VALUES ( ?, ?, ?, ?, ? )';

    $sth = $dbh->prepare($q);
    $result = $sth->execute([$user_id, $user_name, $item_id, $rating, $text]);

    return $result;

}

function has_user_reviewed($item_id) {
    $dbh = DB::connect();

    if(!is_logged_in()) {
        return false;
    }

    $user_id = get_user_id();

    $q = "SELECT id ";
    $q.= "FROM reviews ";
    $q.= "WHERE `user_id` = " . $dbh->quote($user_id) . " ";
    $q.= "AND `item_id` = " . $dbh->quote($item_id);

    $result = $dbh->query($q);
    if ($result->rowCount() == 0) {
        return false;
    }
    return true;
}

function fetch_review_by_user($user_id, $item_id) {
    $dbh = DB::connect();

    if(!is_logged_in()) {
        return false;
    }

    $q = "SELECT * ";
    $q.= "FROM reviews ";
    $q.= "WHERE `user_id` = " . $dbh->quote($user_id) . " ";
    $q.= "AND `item_id` = " . $dbh->quote($item_id);

    $result = $dbh->query($q);
    return $result->fetch();
}

function generate_rating_html($rating) {
    $html = '<span class="glyphicon glyphicon-star '.($rating > 0 ? 'on' : '') . '"></span> ';
    $html.= '<span class="glyphicon glyphicon-star '.($rating >= 2 ? 'on' : '') . '"></span> ';
    $html.= '<span class="glyphicon glyphicon-star '.($rating >= 3 ? 'on' : '') . '"></span> ';
    $html.= '<span class="glyphicon glyphicon-star '.($rating >= 4 ? 'on' : '') . '"></span> ';
    $html.= '<span class="glyphicon glyphicon-star '.($rating >= 5 ? 'on' : '') . '"></span> ';
    return $html;
}