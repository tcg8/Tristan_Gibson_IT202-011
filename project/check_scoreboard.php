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
//Competitions C,  created, expires, id
//Scores S,    user_id, score, created
//Users U,     username???? or get_username();
//UserCompetitions UC,	competition_id, user_id
//$stmt = $db->prepare("SELECT u.*,c.name FROM UserCompetitions u LEFT JOIN Competitions c ON c.id=u.competition_id WHERE u.user_id = :id ORDER BY u.created DESC LIMIT :offset,:count");
/*
	Combos needed:
S.created > UC.created ---score happened after user joined the competition
S.created < C.expires ---score happened before competition ended

S.user_id=UC.user_id ---make sure everything is for the SAME USER
S.user_id=U.id ---------make sure everything is for the SAME USER
C.id=UC.user_id ---make sure everything is for the SAME COMPETITION 

-------
*/
///*
$stmt = $db->prepare("SELECT U.username,S.score,C.id FROM Users U, Scores S, Competitions C WHERE C.id=$id AND (C.id=UC.user_id AND S.user_id=U.id AND S.user_id=UC.user_id AND S.created < C.expires AND S.created > UC.created) LIMIT 10");
$stmt->bindValue(":offset", $offset, PDO::PARAM_INT);
$stmt->bindValue(":count", $per_page, PDO::PARAM_INT);
$stmt->bindValue(":id", get_user_id(), PDO::PARAM_INT);
$stmt->execute();
//$stmt->execute([":id"=>get_user_id()]);
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
//*/
?>



<div class="container-fluid">
        <h3>The Top 10 Scores of this Competition</h3>
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
