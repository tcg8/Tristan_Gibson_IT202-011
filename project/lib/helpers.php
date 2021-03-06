<?php
session_start();//we can start our session here so we don't need to worry about it on other pages
require_once(__DIR__ . "/db.php");
//this file will contain any helpful functions we create
//I have provided two for you
function is_logged_in() {
    return isset($_SESSION["user"]);
}

function getBalance() {
    if (is_logged_in() && isset($_SESSION["user"]["points"])) {
        return $_SESSION["user"]["points"];
    }
    return 0;
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
$arr = [];
$db = getDB();
$stmt = $db->prepare("SELECT score from Scores where created >= :timeCon order by score desc limit 10");

$timeType="Week";
$testtime=strtotime("-1 " . $timeType); // THIS IS WHERE TO CHANGE BY WEEK/MONTH/YEAR
$params = array(":timeCon" => date("Y-m-d h:i:s", $testtime));
$results = $stmt->execute($params);
$results = $stmt->fetchAll();
    
$stmt2 = $db->prepare("SELECT Users.username FROM Users JOIN Scores on Users.id = Scores.user_id where Scores.created >= :timeCon order by Scores.score desc limit 10");   
$params2 = array(":timeCon" => date("Y-m-d h:i:s", $testtime));
$results2 = $stmt2->execute($params2);
$results2 = $stmt2->fetchAll();
        //flash2(" hope this appears2 " . implode($results2[$a-1]));//THIS IS THE WINNER
$stmt3 = $db->prepare("SELECT Users.points FROM Users JOIN Scores on Users.id = Scores.user_id where Scores.created >= :timeCon order by Scores.score desc limit 10");   
$params3 = array(":timeCon" => date("Y-m-d h:i:s", $testtime));
$results3 = $stmt3->execute($params2);
$results3 = $stmt3->fetchAll();

$stmt4 = $db->prepare("SELECT Users.id FROM Users JOIN Scores on Users.id = Scores.user_id where Scores.created >= :timeCon order by Scores.score desc limit 10");   
$params4 = array(":timeCon" => date("Y-m-d h:i:s", $testtime));
$results4 = $stmt4->execute($params4);
$results4 = $stmt4->fetchAll();
    

$hasScores=true;
if (count($results)==0) {
    $hasScores=false;
    echo "There have been no scores set in the past " . $timeType . "</br>";
}
if($hasScores) {
        echo "The Top " . count($results) . " scores of the last " . $timeType . "</br>";
    $i=10-count($results);
    $a=1;
    $w=0;
    do {
        //flash2(" hope this appears2 " . implode($results3[$a-1]));//THIS IS THE WINNER
        //Check profile.php code comments to see why this code is here. Basically its because the scores were being printed twice so this fixes that.
        $numlength = strlen(implode($results[$a-1]))/2; //this gets the number of digits that is supposed to be printed
        $modifier = 10**$numlength;//this is the number that $results will be modified by, it just gets 10^power of $numlength
        $finalNum = implode($results[$a-1]) % $modifier;
        
        $numlength = strlen(implode($results2[$a-1]))/2; //this gets the number of digits that is supposed to be printed
        $modifier = 10**$numlength;//this is the number that $results will be modified by, it just gets 10^power of $numlength
        $userbro = substr(implode($results2[$a-1]),0,$numlength);// % $modifier;
//$userbro="<a href='profile.php'>$userbro</a>"
//echo '<a href="mycgi?foo=', urlencode($userbro), '">';
        $numlength = strlen(implode($results3[$a-1]))/2; //this gets the number of digits that is supposed to be printed
        $modifier = 10**$numlength;//this is the number that $results will be modified by, it just gets 10^power of $numlength
        $pointsbro = implode($results3[$a-1]) % $modifier;
        
        $numlength = strlen(implode($results4[$a-1]))/2; //this gets the number of digits that is supposed to be printed
        $modifier = 10**$numlength;//this is the number that $results will be modified by, it just gets 10^power of $numlength
        $idbro = implode($results4[$a-1]) % $modifier;
        $arr[$w]=$a;
        $w++;
        $arr[$w]=$finalNum;
        $w++;
        $arr[$w]=$userbro;
        $w++;
        $arr[$w]=$pointsbro;
        $w++;
        
        //flash2("he $idbro " . get_username() . " ye ");
        if(get_username() == $userbro){
           
            echo "The #" . $a . " top score is " . $finalNum . " scored by user <a href='profile.php?id=$idbro'>$userbro</a> who has " . $pointsbro . " profile points" . "</br>";
        }else{
            $id=  get_user_id();
            //flash2("the id should be " . implode($results4[$a-1]));
            //if(isset($_GET[$idbro])){
            //$id = $_GET[$idbro];
            //    echo "the id is $id" . "</br>";
            //}
            
            echo "The #" . $a . " top score is " . $finalNum . " scored by user <a href='other_profile.php?id=$idbro'>$userbro</a> who has " . $pointsbro . " profile points" . "</br>";
        }
      $a++;//flash("testing, <a href='profile.php'>$email</a>");
      $i++;
    }
    while($i<10);
}
echo "</br>";
echo "</br>";
echo "</br>";
        foreach($results as $r):
        endforeach;
    return $arr;
}








//one of the scoreboard functions for milestone 2
function get10month(){
$arr = [];
$db = getDB();
$stmt = $db->prepare("SELECT score from Scores where created >= :timeCon order by score desc limit 10");

$timeType="Month";
$testtime=strtotime("-1 " . $timeType); // THIS IS WHERE TO CHANGE BY WEEK/MONTH/YEAR
$params = array(":timeCon" => date("Y-m-d h:i:s", $testtime));
$results = $stmt->execute($params);
$results = $stmt->fetchAll();
    
$stmt2 = $db->prepare("SELECT Users.username FROM Users JOIN Scores on Users.id = Scores.user_id where Scores.created >= :timeCon order by Scores.score desc limit 10");   
$params2 = array(":timeCon" => date("Y-m-d h:i:s", $testtime));
$results2 = $stmt2->execute($params2);
$results2 = $stmt2->fetchAll();
        //flash2(" hope this appears2 " . implode($results2[$a-1]));//THIS IS THE WINNER
$stmt3 = $db->prepare("SELECT Users.points FROM Users JOIN Scores on Users.id = Scores.user_id where Scores.created >= :timeCon order by Scores.score desc limit 10");   
$params3 = array(":timeCon" => date("Y-m-d h:i:s", $testtime));
$results3 = $stmt3->execute($params2);
$results3 = $stmt3->fetchAll();
      
$stmt4 = $db->prepare("SELECT Users.id FROM Users JOIN Scores on Users.id = Scores.user_id where Scores.created >= :timeCon order by Scores.score desc limit 10");   
$params4 = array(":timeCon" => date("Y-m-d h:i:s", $testtime));
$results4 = $stmt4->execute($params4);
$results4 = $stmt4->fetchAll();
    
    
$hasScores=true;
if (count($results)==0) {
    $hasScores=false;
    echo "There have been no scores set in the past " . $timeType . "</br>";
}
if($hasScores) {
        echo "The Top " . count($results) . " scores of the last " . $timeType . "</br>";
    $i=10-count($results);
    $a=1;
    $w=0;
    do {
        //flash2(" hope this appears2 " . implode($results3[$a-1]));//THIS IS THE WINNER
        //Check profile.php code comments to see why this code is here. Basically its because the scores were being printed twice so this fixes that.
        $numlength = strlen(implode($results[$a-1]))/2; //this gets the number of digits that is supposed to be printed
        $modifier = 10**$numlength;//this is the number that $results will be modified by, it just gets 10^power of $numlength
        $finalNum = implode($results[$a-1]) % $modifier;
        
        $numlength = strlen(implode($results2[$a-1]))/2; //this gets the number of digits that is supposed to be printed
        $modifier = 10**$numlength;//this is the number that $results will be modified by, it just gets 10^power of $numlength
        $userbro = substr(implode($results2[$a-1]),0,$numlength);// % $modifier;
        
        $numlength = strlen(implode($results3[$a-1]))/2; //this gets the number of digits that is supposed to be printed
        $modifier = 10**$numlength;//this is the number that $results will be modified by, it just gets 10^power of $numlength
        $pointsbro = implode($results3[$a-1]) % $modifier;
        
        $numlength = strlen(implode($results4[$a-1]))/2; //this gets the number of digits that is supposed to be printed
        $modifier = 10**$numlength;//this is the number that $results will be modified by, it just gets 10^power of $numlength
        $idbro = implode($results4[$a-1]) % $modifier;
        $arr[$w]=$a;
        $w++;
        $arr[$w]=$finalNum;
        $w++;
        $arr[$w]=$userbro;
        $w++;
        $arr[$w]=$pointsbro;
        $w++;
        
        if(get_username() == $userbro){
           
            echo "The #" . $a . " top score is " . $finalNum . " scored by user <a href='profile.php?id=$idbro'>$userbro</a> who has " . $pointsbro . " profile points" . "</br>";
        }else{
            $id=  get_user_id();
            //flash2("the id should be " . implode($results4[$a-1]));
            //if(isset($_GET[$idbro])){
            //$id = $_GET[$idbro];
            //    echo "the id is $id" . "</br>";
            //}
            
            echo "The #" . $a . " top score is " . $finalNum . " scored by user <a href='other_profile.php?id=$idbro'>$userbro</a> who has " . $pointsbro . " profile points" . "</br>";
        }
      $a++;
      $i++;
    }
    while($i<10);
}
echo "</br>";
    echo "</br>";
    echo "</br>";
        foreach($results as $r):
        endforeach;
}








//the lifetime scoreboard funtion for milestone 2
function get10lifetime(){
$arr = [];
$db = getDB();
$stmt = $db->prepare("SELECT score from Scores order by score desc limit 10");
//THIS SHOULD BE LIFETIME NOT YEAR
$timeType="Lifetime";
$testtime=strtotime("-1 Year"); //SINCE GOT RID OF "WHERE" PART IN $STMT IT DOESN'T MATTER WHAT GOES HERE
$params = array(":timeCon" => date("Y-m-d h:i:s", $testtime));
$results = $stmt->execute($params);
$results = $stmt->fetchAll();
    
$stmt2 = $db->prepare("SELECT Users.username FROM Users JOIN Scores on Users.id = Scores.user_id order by Scores.score desc limit 10");   
$params2 = array(":timeCon" => date("Y-m-d h:i:s", $testtime));
$results2 = $stmt2->execute($params2);
$results2 = $stmt2->fetchAll();
        //flash2(" hope this appears2 " . implode($results2[$a-1]));//THIS IS THE WINNER
$stmt3 = $db->prepare("SELECT Users.points FROM Users JOIN Scores on Users.id = Scores.user_id order by Scores.score desc limit 10");   
$params3 = array(":timeCon" => date("Y-m-d h:i:s", $testtime));
$results3 = $stmt3->execute($params2);
$results3 = $stmt3->fetchAll();
    
$stmt4 = $db->prepare("SELECT Users.id FROM Users JOIN Scores on Users.id = Scores.user_id where Scores.created >= :timeCon order by Scores.score desc limit 10");   
$params4 = array(":timeCon" => date("Y-m-d h:i:s", $testtime));
$results4 = $stmt4->execute($params4);
$results4 = $stmt4->fetchAll();
    
    
$hasScores=true;
if (count($results)==0) {
    $hasScores=false;
    echo "There have been no scores set in the past " . $timeType . "</br>";
}
if($hasScores) {
        echo "The Top " . count($results) . " scores of the games whole " . $timeType . "</br>";
    $i=10-count($results);
    $a=1;
    $w=0;
    do {
        //Check profile.php code comments to see why this code is here. Basically its because the scores were being printed twice so this fixes that.
        $numlength = strlen(implode($results[$a-1]))/2; //this gets the number of digits that is supposed to be printed
        $modifier = 10**$numlength;//this is the number that $results will be modified by, it just gets 10^power of $numlength
        $finalNum = implode($results[$a-1]) % $modifier;
        
        $numlength = strlen(implode($results2[$a-1]))/2; //this gets the number of digits that is supposed to be printed
        $modifier = 10**$numlength;//this is the number that $results will be modified by, it just gets 10^power of $numlength
        $userbro = substr(implode($results2[$a-1]),0,$numlength);// % $modifier;
        
        $numlength = strlen(implode($results3[$a-1]))/2; //this gets the number of digits that is supposed to be printed
        $modifier = 10**$numlength;//this is the number that $results will be modified by, it just gets 10^power of $numlength
        $pointsbro = implode($results3[$a-1]) % $modifier;
        
        $numlength = strlen(implode($results4[$a-1]))/2; //this gets the number of digits that is supposed to be printed
        $modifier = 10**$numlength;//this is the number that $results will be modified by, it just gets 10^power of $numlength
        $idbro = implode($results4[$a-1]) % $modifier;
        $arr[$w]=$a;
        $w++;
        $arr[$w]=$finalNum;
        $w++;
        $arr[$w]=$userbro;
        $w++;
        $arr[$w]=$pointsbro;
        $w++;
        
        if(get_username() == $userbro){
           
            echo "The #" . $a . " top score is " . $finalNum . " scored by user <a href='profile.php?id=$idbro'>$userbro</a> who has " . $pointsbro . " profile points" . "</br>";
        }else{
            $id=  get_user_id();
            //flash2("the id should be " . implode($results4[$a-1]));
            //if(isset($_GET[$idbro])){
            //$id = $_GET[$idbro];
            //    echo "the id is $id" . "</br>";
            //}
            
            echo "The #" . $a . " top score is " . $finalNum . " scored by user <a href='other_profile.php?id=$idbro'>$userbro</a> who has " . $pointsbro . " profile points" . "</br>";
        }
      $a++;
      $i++;
    }
    while($i<10);
}
echo "</br>";
echo "</br>";
echo "</br>";
foreach($results as $r):
endforeach;
}







function paginate($query, $params = [], $per_page = 10) {
    global $page;
    if (isset($_GET["page"])) {
        try {
            $page = (int)$_GET["page"];
        }
        catch (Exception $e) {
            $page = 1;
        }
    }
    else {
        $page = 1;
    }
    $db = getDB();
    $stmt = $db->prepare($query);
    $stmt->execute($params);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $total = 0;
    if ($result) {
        $total = (int)$result["total"];
        //flash("total is $total");
    }
    global $total_pages;
    $total_pages = ceil($total / $per_page);
    global $offset;
    $offset = ($page - 1) * $per_page;
}



//end flash
?>
