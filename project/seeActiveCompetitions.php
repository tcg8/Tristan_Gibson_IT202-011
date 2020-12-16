<?php require_once(__DIR__ . "/partials/nav.php"); ?>
<?php

$db = getDB();
if (isset($_POST["join"])) {
    $balance = getBalance();
    //prevent user from joining expired or paid out comps
    //$stmt = $db->prepare("select fee from Competitions where id = :id && expires > current_timestamp && paid_out = 0");
    $stmt = $db->prepare("select fee from Competitions where expires > current_timestamp && paid_out = 0");
    $r = $stmt->execute();//[":id" => $_POST["cid"]]
    if ($r) {
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($result) {
            $fee = (int)$result["fee"];
            if ($balance >= $fee) {
                $stmt = $db->prepare("INSERT INTO UserCompetitions (competition_id, user_id) VALUES(:cid, :uid)");
                $r = $stmt->execute([":cid" => $_POST["cid"], ":uid" => get_user_id()]);
                if ($r) {
                    flash("Successfully join competition", "success");
                    die(header("Location: #"));
                }
                else {
                    flash("There was a problem joining the competition: " . var_export($stmt->errorInfo(), true), "danger");
                }
            }
            else {
                flash("You can't afford to join this competition, try again later", "warning");
            }
        }
        else {
            flash("Competition is unavailable", "warning");
        }
    }
    else {
        flash("Competition is unavailable", "warning");
    }
}
$stmt = $db->prepare("SELECT c.*, UC.user_id as reg FROM Competitions c LEFT JOIN (SELECT * FROM UserCompetitions where user_id = :id) as UC on c.id = UC.competition_id WHERE c.expires > current_timestamp AND paid_out = 0 AND (UC.user_id = :id OR c.user_id = :id) ORDER BY expires ASC");
$r = $stmt->execute([":id" => get_user_id(),]);
if ($r) {
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
else {
    flash("There was a problem looking up competitions: " . var_export($stmt->errorInfo(), true), "danger");
}

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

<div class="container-fluid">
        <h3>My Competitions (Created and Registered)</h3>
        <div class="list-group">
            <?php if (isset($results) && count($results)): ?>
                <div class="list-group-item font-weight-bold">
                    <div class="row">
                        <div class="col">
                            Name
                        </div>
                        <div class="col">
                            Participants
                        </div>
                        <div class="col">
                            Required Score
                        </div>
                        <div class="col">
                            Reward
                        </div>
                        <div class="col">
                            Expires
                        </div>
                        <div class="col">
                            Actions
                        </div>
                    </div>
                </div>
                <?php foreach ($results as $r): ?>
                    <div class="list-group-item">
                        <div class="row">
                            <div class="col">
                                <?php safer_echo($r["name"]); ?>
                                <?php if ($r["user_id"] == get_user_id()): ?>
                                    (Created)
                                <?php endif; ?>
                            </div>
                            <div class="col">
                                <?php safer_echo($r["participants"]); ?>
                            </div>
                            <div class="col">
                                <?php safer_echo($r["min_score"]); ?>
                            </div>
                            <div class="col">
                                <?php safer_echo($r["reward"]); ?>
                                <!--TODO show payout-->
                            </div>
                            <div class="col">
                                <?php safer_echo($r["expires"]); ?>
                            </div>
                            <div class="col">
                                <?php if ($r["reg"] != get_user_id()): ?>
                                    <form method="POST">
                                        <input type="hidden" name="cid" value="<?php safer_echo($r["id"]); ?>"/>
                                        <input type="submit" name="join" class="btn btn-primary"
                                               value="Join (Cost: <?php safer_echo($r["fee"]); ?>)"/>
                                    </form>
                                <?php else: ?>
                                    Already Registered
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="list-group-item">
                    No competitions available right now
                </div>
            <?php endif; ?>
        </div>
    </div>


<?php require(__DIR__ . "/partials/flash.php");
