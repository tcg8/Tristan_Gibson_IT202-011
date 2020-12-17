<?php require_once(__DIR__ . "/partials/nav.php"); ?>

<?php
if (!is_logged_in()) {
    //this will redirect to login and kill the rest of this script (prevent it from executing)
    flash("You don't have permission to access this page");
    die(header("Location: login.php"));
}
?>


<?php

$db = getDB();

$per_page = 10;
$theID = get_user_id();
//$query = "SELECT count(*) as total FROM UserCompetitions WHERE user_id = $theID ORDER BY created DESC";
$query = "SELECT u.count(*) as total FROM UserCompetitions u LEFT JOIN Competitions c ON c.id=u.competition_id WHERE u.user_id = $theID ORDER BY created DESC";
paginate($query, [], $per_page);


//$query = "SELECT count(*) as total FROM UserCompetitions WHERE user_id = $theID ORDER BY created DESC";
$stmt = $db->prepare("SELECT u.count(*) as total FROM UserCompetitions u LEFT JOIN Competitions c ON c.id=u.competition_id WHERE u.user_id = $theID ORDER BY created DESC LIMIT :offset,:count");
$stmt->bindValue(":offset", $offset, PDO::PARAM_INT);
$stmt->bindValue(":count", $per_page, PDO::PARAM_INT);
$stmt->bindValue(":id", get_user_id(), PDO::PARAM_INT);
$stmt->execute();
//$stmt->execute([":id"=>get_user_id()]);
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);


//UserCompetitions, competition_id, user_id
//Competitions, id, name

?>

<div class="container-fluid">
        <h3>Your Competition History</h3>
        <div class="list-group">
            <?php if (isset($results) && count($results)): ?>
                <?php foreach ($results as $r): ?>
                    <div class="list-group-item" style="background-color: #25E418">
                        <div class="row">
				
                            <div class="col">
                                You joined: 
                                <?php safer_echo($r["u.id"]); ?>
                            </div><div class="col">
                                Competition ID: 
                                <?php safer_echo($r["u.competition_id"]); ?>
                            </div>
                            <div class="col">
                                You joined this competition on: 
                                <?php safer_echo($r["u.created"]); ?>
                            </div>
			    <div class="col">
                                <form method="POST">
				</form>
                            </div>
                             
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="list-group-item">
                    No competitions to show, sorry.
                </div>
            <?php endif; ?>
        </div>
    </div>

<?php include(__DIR__ . "/partials/pagination.php");?>

<?php require(__DIR__ . "/partials/flash.php");
