<?php
if (!is_logged_in()) {

}else{
	//if (isset($_POST["sendscore"])) {
	if (isset($_POST["count"])){
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

			*/$e = $stmt->errorInfo();
			if ($e[0] == "00000") {
				//flash("Successfully recorded score");
			}
			else {
				//flash("You are not logged in so the score was not saved");
			}//*/
		}
	}
}//if(is_logged_in()) end
?>
