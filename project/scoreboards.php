<?php require_once(__DIR__ . "/partials/nav.php"); ?>


<?php
//Note: we have this up here, so our update happens before our get/fetch
//that way we'll fetch the updated data and have it correctly reflect on the form below
//As an exercise swap these two and see how things change
if (!is_logged_in()) {
    //this will redirect to login and kill the rest of this script (prevent it from executing)
    flash("You must be logged in to access this page");
    die(header("Location: login.php"));
}
?>

<?php
$db = getDB();
//$stmt = $db->prepare("SELECT score from Scores where user_id = :id order by id desc limit 10");
$stmt = $db->prepare("SELECT score from Scores where user_id = :id order by score desc limit 10");
//WILL NEED A WHERE STATEMENT TO GET TIME FRAME
$params = array(":id" => get_user_id());
$results = $stmt->execute($params);
$results = $stmt->fetchAll();
//flash("array length check " . count($results));

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
        //So $results was printing double, like 27 came out at 2727 and 0 as 00 so I am modding by 10^(number of digits / 2)
        //so when $results is 2727 we do ( $results % $modifier ) where $modifier will be 100 since 27 is a 2 digit number and 10^2=100
        //doing modifier like this will get rid of the extra digits being produced
        //$numlength = strlen((string)$num);
        $numlength = strlen(implode($results[$a-1]))/2; //this gets the number of digits that is supposed to be printed
        $modifier = 10**$numlength;//this is the number that $results will be modified by, it just gets 10^power of $numlength
        $finalNum = implode($results[$a-1]) % $modifier;
        flash("#" . $a . " most recent score is " . $finalNum);
        //flash("#" . $a . " most recent score is " . implode($results[$i]) . " finalNum " . $finalNum);//%$check);//for some reason the score displayed is being doubled
      $a++;
      $i++;
    }
    while($i<10);
}
?>

<?php foreach($results as $r):?>

<?php endforeach;?>


<?php require(__DIR__ . "/partials/flash.php");
