<?php require_once(__DIR__ . "/partials/nav.php"); ?>

<form method="POST">
        <table style="width:50%">
     <tr>
        <td>  <label for="topscores">Top 10 scores from the past: </label>  </td>
        <td>  <select name="topscores" id="topscores">
    <option value="week">Week</option>
    <option value="month">Month</option>
    <option value="alltime">All Time</option>
  </select>  </td>
    </tr> 
    </table>
</form>

<?php

<label for="topscores">Top 10 scores from the past: </label>

//can copy and paste this whole php statement 3 times for week month and year 
//other option is to try and find a different way
$db = getDB();
//$stmt = $db->prepare("SELECT score,created from Scores where created >= :timeCon order by score desc limit 10");
$stmt = $db->prepare("SELECT score from Scores where created >= :timeCon order by score desc limit 10");
//WILL NEED A WHERE STATEMENT TO GET TIME FRAME
$testtime=strtotime("-1 Month"); // THIS IS WHERE TO CHANGE BY WEEK/MONTH/YEAR
$params = array(":timeCon" => date("Y-m-d h:i:s", $testtime));
$results = $stmt->execute($params);
$results = $stmt->fetchAll();
/*
$currtime=time();//$currtime=mktime(11, 14, 54, 8, 12, 2014);
$testtime=strtotime("-1 Month");//strtotime("-1 Weeks");//strtotime("-1 Years");
flash( "Current date is " . date("Y-m-d h:i:s", $currtime));
flash( "Testing date is " . date("Y-m-d h:i:s", $testtime));
*/


//ADD A FOR LOOP HERE TO CREATE THE TOP 10 CHART    USE ECHO OR FLASH   TO CREATE THE CHART
$hasScores=true;
if (count($results)==0) {
    $hasScores=false;
    flash("You do not have any scores recorded, try playing the game!");
}
if($hasScores) {
        flash("Your last " . count($results) . " scores");
    $i=10-count($results);
    $a=1;
    do {
        //Check profile.php code comments to see why this code is here. Basically its because the scores were being printed twice so this fixes that.
        $numlength = strlen(implode($results[$a-1]))/2; //this gets the number of digits that is supposed to be printed
        $modifier = 10**$numlength;//this is the number that $results will be modified by, it just gets 10^power of $numlength
        $finalNum = implode($results[$a-1]) % $modifier;
        flash("#" . $a . " most recent score is " . $finalNum);
        //flash("#" . $a . " most recent score is " . implode($results[$a-1]) . " finalNum " . $finalNum);//%$check);//for some reason the score displayed is being doubled
      $a++;
      $i++;
    }
    while($i<10);
}
?>

<?php foreach($results as $r):?>

<?php endforeach;?>


<?php require(__DIR__ . "/partials/flash.php");
