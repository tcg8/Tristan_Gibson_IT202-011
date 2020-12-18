<?php require_once(__DIR__ . "/partials/nav.php"); ?>


<?php

if(isset($_GET["id"])){
$id = $_GET["id"];
}else{
	flash("You can't access this page this way. BEGONE!");
}
flash("comp id is " . $id);



/*$stmt = $db->prepare("SELECT u.*,c.name FROM UserCompetitions u LEFT JOIN Competitions c ON c.id=u.competition_id WHERE u.user_id = :id ORDER BY u.created DESC LIMIT :offset,:count");
$stmt->bindValue(":offset", $offset, PDO::PARAM_INT);
$stmt->bindValue(":count", $per_page, PDO::PARAM_INT);
$stmt->bindValue(":id", get_user_id(), PDO::PARAM_INT);
$stmt->execute();
//$stmt->execute([":id"=>get_user_id()]);
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);*/


//need 
//UserCompetitions UC,  created, competition_id
//Competitions C,  created, expires, 
//Scores S,    user_id, score, created
//Users U,     username???? or get_username();
//$stmt = $db->prepare("SELECT u.*,c.name FROM UserCompetitions u LEFT JOIN Competitions c ON c.id=u.competition_id WHERE u.user_id = :id ORDER BY u.created DESC LIMIT :offset,:count");
$stmt = $db->prepare("SELECT U.username,  LIMIT 10");
$stmt->bindValue(":offset", $offset, PDO::PARAM_INT);
$stmt->bindValue(":count", $per_page, PDO::PARAM_INT);
$stmt->bindValue(":id", get_user_id(), PDO::PARAM_INT);
$stmt->execute();
//$stmt->execute([":id"=>get_user_id()]);
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>



<div class="container-fluid">
        <h3>The Top 10 Scores</h3>
        <div class="list-group">
            <?php if (isset($results) && count($results)): ?>
                <?php foreach ($results as $r): ?>
                    <div class="list-group-item" style="background-color: #25E418">
                        <div class="row">
				
                            <div class="col">
                                User: 
                                <?php safer_echo($r["username"]); ?>
                            </div>
                            <div class="col">
                                Scored: 
                                <?php safer_echo($r["score"]); ?>
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
                    No scores to show, sorry.
                </div>
            <?php endif; ?>
        </div>
    </div>

<?php require(__DIR__ . "/partials/flash.php");
