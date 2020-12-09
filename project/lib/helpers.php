<?php
session_start();//we can start our session here so we don't need to worry about it on other pages
require_once(__DIR__ . "/db.php");
//this file will contain any helpful functions we create
//I have provided two for you
function is_logged_in() {
    return isset($_SESSION["user"]);
}

function has_role($role) {
    if (is_logged_in() && isset($_SESSION["user"]["roles"])) {
        foreach ($_SESSION["user"]["roles"] as $r) {
            if ($r["name"] == $role) {
                return true;
            }
        }
    }
    return false;
}

function get_username() {
    if (is_logged_in() && isset($_SESSION["user"]["username"])) {
        return $_SESSION["user"]["username"];
    }
    return "";
}

function get_email() {
    if (is_logged_in() && isset($_SESSION["user"]["email"])) {
        return $_SESSION["user"]["email"];
    }
    return "";
}

function get_user_id() {
    if (is_logged_in() && isset($_SESSION["user"]["id"])) {
        return $_SESSION["user"]["id"];
    }
    return -1;
}

function safer_echo($var) {
    if (!isset($var)) {
        echo "";
        return;
    }
    echo htmlspecialchars($var, ENT_QUOTES, "UTF-8");
}

//for flash feature
function flash($msg) {
    if (isset($_SESSION['flash'])) {
        array_push($_SESSION['flash'], $msg);
    }
    else {
        $_SESSION['flash'] = array();
        array_push($_SESSION['flash'], $msg);
    }

}

function getMessages() {
    if (isset($_SESSION['flash'])) {
        $flashes = $_SESSION['flash'];
        $_SESSION['flash'] = array();
        return $flashes;
    }
    return array();
}

function get10week(){
$db = getDB();
//$stmt = $db->prepare("SELECT score,created from Scores where created >= :timeCon order by score desc limit 10");
$stmt = $db->prepare("SELECT score from Scores where created >= :timeCon order by score desc limit 10");

$timeType="Week";
$testtime=strtotime("-1 " . $timeType); // THIS IS WHERE TO CHANGE BY WEEK/MONTH/YEAR
$params = array(":timeCon" => date("Y-m-d h:i:s", $testtime));
$results = $stmt->execute($params);
$results = $stmt->fetchAll();
/*
$currtime=time();//$currtime=mktime(11, 14, 54, 8, 12, 2014);
$testtime=strtotime("-1 Month");//strtotime("-1 Weeks");//strtotime("-1 Years");
flash( "Current date is " . date("Y-m-d h:i:s", $currtime));
flash( "Testing date is " . date("Y-m-d h:i:s", $testtime));
*/
$hasScores=true;
if (count($results)==0) {
    $hasScores=false;
    flash("There have been no scores set in the past " . $timeType);
}
if($hasScores) {
        flash("The Top " . count($results) . " scores of the last " . $timeType);
    $i=10-count($results);
    $a=1;
    do {
        //Check profile.php code comments to see why this code is here. Basically its because the scores were being printed twice so this fixes that.
        $numlength = strlen(implode($results[$a-1]))/2; //this gets the number of digits that is supposed to be printed
        $modifier = 10**$numlength;//this is the number that $results will be modified by, it just gets 10^power of $numlength
        $finalNum = implode($results[$a-1]) % $modifier;
        flash("The #" . $a . " top score is " . $finalNum);
        //flash("#" . $a . " most recent score is " . implode($results[$a-1]) . " finalNum " . $finalNum);//%$check);//for some reason the score displayed is being doubled
      $a++;
      $i++;
    }
    while($i<10);
}
flash("</br>");
        foreach($results as $r):
        endforeach;
}

//end flash
?>
