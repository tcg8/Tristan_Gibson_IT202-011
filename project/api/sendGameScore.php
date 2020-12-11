<?php
//This is the game I made for Milestone 2, you click a button as much as possible in one minute for a score. The clock hasn't been added yet but the score still works
//which is enough for this milestone

//This game is now completely done, Finished for milestone 4

if (!is_logged_in()) {
    //this will redirect to login and kill the rest of this script (prevent it from executing)
    //flash("You are not logged in, your score won't be saved!");
    //die(header("Location: login.php"));
}else{
	//if (isset($_POST["sendscore"])) {
	//if (isset($_POST["count"])){
		$db = getDB();
        	if (isset($db)) {
			$user_id = get_user_id();//$_SESSION["user"]["id"];
			$score = $_POST["count"];//7;
			//flash("1This should appear when submit score is clicked");
			///*
			//here we'll use placeholders to let PDO map and sanitize our data
			$stmt = $db->prepare("INSERT INTO Scores( user_id, score) VALUES(:user_id,:score)");
			//here's the data map for the parameter to data
			$params = array( ":user_id" => $user_id, ":score" => $score);
			$r = $stmt->execute($params);
			
			if ($r) {
			    $response = ["status" => 200, "message" => "Added score to database"];
			    echo json_encode($response);
			    die();
			}
			/*$r = $stmt -> execute([":user_id" => $user_id, ":score" => $score]);
			if($r){
				flash("Created successfully with id: " . $db->lastInsertId());
			}
			else{
				$e = $stmt->errorInfo();
				flash("Error creating :" . var_export($e, true));
			}
			*/$e = $stmt->errorInfo();
			if ($e[0] == "00000") {
				//flash("Successfully recorded score");
			}
			else {
				//flash("You are not logged in so the score was not saved");
			}//*/
		}
	//}
}//if(is_logged_in()) end
?>
