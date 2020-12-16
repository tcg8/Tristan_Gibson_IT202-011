<?php require_once(__DIR__ . "/partials/nav.php"); ?>

<?php
if (!is_logged_in()) {
    //this will redirect to login and kill the rest of this script (prevent it from executing)
    flash("You don't have permission to access this page");
    die(header("Location: login.php"));
}
?>

<?php


/*if (!is_logged_in()) {
	if (isset($_POST["join"])) {
		flash("You need to be logged in to join a competition");
	}
}*/
//else{

$db = getDB();
if (isset($_POST["Fake-join"])) {
	flash("Already joined this competition");
}
if (isset($_POST["join"])) {
    $balance = getBalance();
    //flash("ay boss yo balance is $balance");
    //prevent user from joining expired or paid out comps
    //$stmt = $db->prepare("select fee from Competitions where id = :id && expires > current_timestamp && paid_out = 0");
    $stmt = $db->prepare("select fee from Competitions where id = :id && expires > current_timestamp && paid_out = 0 LIMIT 10");
    //$stmt = $db->prepare("select fee from Competitions where expires > current_timestamp && paid_out = 0 LIMIT 10");
	//if (!is_logged_in()) {
    //this will redirect to login and kill the rest of this script (prevent it from executing)
    //flash("You need to be logged in to join a competition");
    //die(header("Location: login.php"));
	//}else{
    $r = $stmt->execute([":id" => $_POST["cid"]]);//[":id" => $_POST["cid"]]
    if ($r) {
	    //flash("HERE I AM BABYYYYY");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($result) {
            $fee = (int)$result["fee"];
            if ($balance >= $fee) {
                
                $stmt = $db->prepare("INSERT INTO UserCompetitions (competition_id, user_id) VALUES(:cid, :uid)");
                //$params = array(  ":competition_id" => $points_change, ":uid" => $user_id);
                $r = $stmt->execute([":cid" => $_POST["cid"], ":uid" => get_user_id()]);
                if ($r) {
                    flash("Successfully join competition", "success");
			
			
			
                //-------------
                ///*
		$user_id=get_user_id();
		$points_change = -($fee);
		$reason = "Joined a new competition";
		$stmt = $db->prepare("INSERT INTO PointsHistory( user_id, points_change, reason) VALUES(:user_id,:points_change,:reason)");
			$params = array( ":user_id" => $user_id, ":points_change" => $points_change, ":reason" => $reason);
			$r = $stmt->execute($params);
            
            $stmt = $db->prepare("UPDATE Users set points = (SELECT IFNULL(SUM(points_change), 0) FROM PointsHistory p where p.user_id = :id) WHERE id = :id");
            $params = array(":id" => get_user_id());
            $r = $stmt->execute($params);
            
                //Update the session variable for points/balance
			    $stmt = $db->prepare("SELECT points from Users WHERE id = :id LIMIT 1");
			    $params = array(":id" => get_user_id());
			    $r = $stmt->execute($params);
			    if($r){
				$result = $stmt->fetch(PDO::FETCH_ASSOC);
				$profilePoints = $result["points"];
				$_SESSION["user"]["points"] = $profilePoints;
			    }
		//--------------------------------------------------------------------------------------
			//flash("GIANNNNNNNNTS " . $_POST["cid"]);
			///*
			//Increments the Competitions.participants value based on the count of participants for this competition in CompetitionParticipants table.
			//get current val  ,   increase by 1   ,  set it
			 //$stmt = $db->prepare("UPDATE Competitions set participants = (SELECT IFNULL(SUM(points_change), 0) FROM PointsHistory p where p.user_id = :id) WHERE id = :id");
            		$stmt = $db->prepare("UPDATE Competitions set participants = participants+1 WHERE id = :id");
            		$params = array(":id" => $_POST["cid"]);
            		$r = $stmt->execute($params);//*/
			//DO WE NEED TO UPDATE THE SESSION VARIABLE FOR THIS TOO?????????
		//--------------------------------------------------------------------------------------
			/*//Update the Competitions.reward based on the # of participants and the appropriate math from the competition requirements above
			 $stmt = $db->prepare("UPDATE Competitions set reward = (SELECT IFNULL(SUM(points_change), 0) FROM PointsHistory p where p.user_id = :id) WHERE id = :id");
            		$params = array(":id" => get_user_id());
            		$r = $stmt->execute($params);*/
			//DO WE NEED TO UPDATE THE SESSION VARIABLE FOR THIS TOO?????????
			
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
//}//end for if logged in
//$stmt = $db->prepare("SELECT c.*, UC.user_id as reg FROM Competitions c LEFT JOIN (SELECT * FROM UserCompetitions ) as UC on c.id = UC.competition_id WHERE c.expires > current_timestamp AND paid_out = 0 AND (UC.user_id = :id OR c.user_id = :id) ORDER BY expires ASC LIMIT 10");
$stmt = $db->prepare("SELECT c.*, UC.user_id as reg FROM Competitions c LEFT JOIN (SELECT * FROM UserCompetitions ) as UC on c.id = UC.competition_id WHERE c.expires > current_timestamp AND paid_out = 0 ORDER BY expires ASC LIMIT 10");//Use this one or you can only see what you created
$r = $stmt->execute([":id" => get_user_id(),]);
if ($r) {
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
else {
    flash("There was a problem looking up competitions: " . var_export($stmt->errorInfo(), true), "danger");
}

?>

<div class="container-fluid">
        <h3>Active Competitions</h3>
        <div class="list-group">
            <?php if (isset($results) && count($results)): ?>
                <?php foreach ($results as $r): ?>
                    <div class="list-group-item" style="background-color: #D7C51B">
                        <div class="row">
                            
                            <div class="col">
                                Name: 
                                <?php safer_echo($r["name"]); ?>
                                <?php if ($r["user_id"] == get_user_id()): ?>
                                    (Created)
                                <?php endif; ?>
                            </div>
                            <div class="col">
                                Participants: 
                                <?php safer_echo($r["participants"]); ?>
                            </div>
                            <div class="col">
                                Required Score: 
                                <?php safer_echo($r["min_score"]); ?>
                            </div>
                            <div class="col">
                                Reward: 
                                <?php safer_echo($r["reward"]); ?>
                                <!--TODO show payout-->
                            </div>
                            <div class="col">
                                Expires: 
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
                                    flash("Already Registered");
				    <form method="POST">
                                        <input type="hidden" name="cid" value="<?php safer_echo($r["id"]); ?>"/>
                                        <input type="submit" name="Fake-join" class="btn btn-primary"
                                               value="Join (Cost: <?php safer_echo($r["fee"]); ?>)"/>
                                    </form>
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
