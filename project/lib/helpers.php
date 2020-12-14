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

function get_status() {
    if (is_logged_in() && isset($_SESSION["user"]["status"])) {
        return $_SESSION["user"]["status"];
    }
    return "";
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


//other flash for styling for milestone 2
function flash2($msg) {
    if (isset($_SESSION['flash2'])) {
        array_push($_SESSION['flash2'], $msg);
    }
    else {
        $_SESSION['flash2'] = array();
        array_push($_SESSION['flash2'], $msg);
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
//Made this second messages to help styling for milestone 2
function getMessages2() {
    if (isset($_SESSION['flash2'])) {
        $flashes2 = $_SESSION['flash2'];
        $_SESSION['flash2'] = array();
        return $flashes2;
    }
    return array();
}


//One of the functions for milestone 2
function get10week(){
$db = getDB();
$stmt = $db->prepare("SELECT score from Scores where created >= :timeCon order by score desc limit 10");

$timeType="Week";
$testtime=strtotime("-1 " . $timeType); // THIS IS WHERE TO CHANGE BY WEEK/MONTH/YEAR
$params = array(":timeCon" => date("Y-m-d h:i:s", $testtime));
$results = $stmt->execute($params);
$results = $stmt->fetchAll();
    
    //$theScore = $results["score"];
        //flash2(" here is " . strlen(implode($theScore[$a-1])));
    //flash2("the score is " . strlen(implode($theScore)));
    
$stmt2 = $db->prepare("SELECT user_id from Scores where created >= :timeCon order by score desc limit 10");
$params2 = array(":timeCon" => date("Y-m-d h:i:s", $testtime));
$results2 = $stmt2->execute($params2);
$results2 = $stmt2->fetchAll();
    
    
    
    
    
    
    

$hasScores=true;
if (count($results)==0) {
    $hasScores=false;
    flash2("There have been no scores set in the past " . $timeType);
}
if($hasScores) {
        flash2("The Top " . count($results) . " scores of the last " . $timeType);
    $i=10-count($results);
    $a=1;
    do {
        flash2(" hope this appears " . strlen(implode($results2[$a-1])));
        //Check profile.php code comments to see why this code is here. Basically its because the scores were being printed twice so this fixes that.
        $numlength = strlen(implode($results[$a-1]))/2; //this gets the number of digits that is supposed to be printed
        $modifier = 10**$numlength;//this is the number that $results will be modified by, it just gets 10^power of $numlength
        $finalNum = implode($results[$a-1]) % $modifier;
        flash2("The #" . $a . " top score is " . $finalNum);
      $a++;
      $i++;
    }
    while($i<10);
}
flash2("</br>");
        foreach($results as $r):
        endforeach;
}


//one of the scoreboard functions for milestone 2
function get10month(){
$db = getDB();
$stmt = $db->prepare("SELECT score from Scores where created >= :timeCon order by score desc limit 10");

$timeType="Month";
$testtime=strtotime("-1 " . $timeType); // THIS IS WHERE TO CHANGE BY WEEK/MONTH/YEAR
$params = array(":timeCon" => date("Y-m-d h:i:s", $testtime));
$results = $stmt->execute($params);
$results = $stmt->fetchAll();

$hasScores=true;
if (count($results)==0) {
    $hasScores=false;
    flash2("There have been no scores set in the past " . $timeType);
}
if($hasScores) {
        flash2("The Top " . count($results) . " scores of the last " . $timeType);
    $i=10-count($results);
    $a=1;
    do {
        //Check profile.php code comments to see why this code is here. Basically its because the scores were being printed twice so this fixes that.
        $numlength = strlen(implode($results[$a-1]))/2; //this gets the number of digits that is supposed to be printed
        $modifier = 10**$numlength;//this is the number that $results will be modified by, it just gets 10^power of $numlength
        $finalNum = implode($results[$a-1]) % $modifier;
        flash2("The #" . $a . " top score is " . $finalNum);
      $a++;
      $i++;
    }
    while($i<10);
}
flash2("</br>");
foreach($results as $r):
endforeach;
}




//the lifetime scoreboard funtion for milestone 2
function get10lifetime(){
$db = getDB();
$stmt = $db->prepare("SELECT score from Scores order by score desc limit 10");
//THIS SHOULD BE LIFETIME NOT YEAR
$timeType="Lifetime";
$testtime=strtotime("-1 Year"); //SINCE GOT RID OF "WHERE" PART IN $STMT IT DOESN'T MATTER WHAT GOES HERE
$params = array(":timeCon" => date("Y-m-d h:i:s", $testtime));
$results = $stmt->execute($params);
$results = $stmt->fetchAll();

$hasScores=true;
if (count($results)==0) {
    $hasScores=false;
    flash2("There have been no scores set in the past " . $timeType);
}
if($hasScores) {
        flash2("The Top " . count($results) . " scores of the games whole " . $timeType);
    $i=10-count($results);
    $a=1;
    do {
        //Check profile.php code comments to see why this code is here. Basically its because the scores were being printed twice so this fixes that.
        $numlength = strlen(implode($results[$a-1]))/2; //this gets the number of digits that is supposed to be printed
        $modifier = 10**$numlength;//this is the number that $results will be modified by, it just gets 10^power of $numlength
        $finalNum = implode($results[$a-1]) % $modifier;
        flash2("The #" . $a . " top score is " . $finalNum);
      $a++;
      $i++;
    }
    while($i<10);
}
flash2("</br>");
foreach($results as $r):
endforeach;
}




//end flash
?>
