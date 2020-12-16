<?php require_once(__DIR__ . "/partials/nav.php"); ?>
<?php
/*
$db = getDB();
$stmt = $db->prepare("SELECT score from Scores where created >= :timeCon order by score desc limit 10");

$timeType="Week";
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
*/
?>
<?php require(__DIR__ . "/partials/flash.php");
