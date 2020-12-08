<?php require_once(__DIR__ . "/partials/nav.php"); ?>


<?php


if (!is_logged_in()) {
    //this will redirect to login and kill the rest of this script (prevent it from executing)
    flash("You are not logged in, your score won't be saved");
    //die(header("Location: login.php"));
}
else{
	
flash("You are logged in, your score will be saved");
//$score=localStorage.clickcount;
	//$testvar=3;
	//flash("testing display " . $testvar);
	//$score=4;//xhttp.send("score=" + localStorage.clickcount);//$_POST["sendscore"];//localStorage.clickcount;
	//flash("the score is " . $score);
	
	$db = getDB();
	
	
	/*
	if (isset($_POST["clicker"])) {
		flash("33This should appear when button is clicked");
	}
	*/
	/*
if (isset($_POST["sendscore"])) {
	flash("2This should appear when submit score is clicked");
	$isValid = true;
}
	
	*/
	/*
	if ($isValid) {
		$stmt = $db->prepare("UPDATE Scores set email = :email, username= :username where id = :id");
		$r = $stmt->execute([":email" => $newEmail, ":username" => $newUsername, ":id" => get_user_id()]);
		if ($r) {
		    flash("Updated profile");
		}
		else {
		    flash("Error updating profile");
		}
	}
	*/
	if (isset($_POST["sendscore"])) {
		//$id = get_id();
		$user_id = get_id();
		$score = 7;
		$created = null;
		if (isset($db)) {
			flash("1This should appear when submit score is clicked");
			///*
		    //here we'll use placeholders to let PDO map and sanitize our data
		    $stmt = $db->prepare("INSERT INTO Scores( user_id, score, created) VALUES(:user_id,:score,:created)");
		    //here's the data map for the parameter to data
		    $params = array( ":user_id" => $user_id, ":score" => $score, ":created" => $created);

		    $r = $stmt->execute($params);

		    $e = $stmt->errorInfo();
		    if ($e[0] == "00000") {
			flash("Successfully recorded score");
		    }
		    else {
			flash("something went wrong");
		    }//*/
		}
	}
}

//$c = (int)$result["eggs"];


?>


<!--<form>
	<h3>Game starts when you click the button, You have a minute to get a high score!</h3>
	<button  id="clicker" name="clicker" type="button" style="width: 100%; height: 200px;" value="Click me">Click Me!</button>
	<label for="score">The score is </label> 
	<label value="hi" />
	<!--<div id="result"></div>-->
	<!--<input class="btn btn-primary" type="submit" name="sendscore" value="Submit Score"/>
</form>
-->

<html>
<head>
<script>
function clickCounter() {
  if (typeof(Storage) !== "undefined") {
    if (localStorage.clickcount) {
      localStorage.clickcount = Number(localStorage.clickcount)+1;
    } else {
      localStorage.clickcount = 1;
    }
    document.getElementById("result").innerHTML = "Your current score is " + localStorage.clickcount;
  } else {
    document.getElementById("result").innerHTML = "Sorry, your browser does not support web storage...";
  }
}
	
function submitScore() {
 //xhttp.send("score=" + localStorage.clickcount);
 localStorage.clickcount = 0;
	//value=localStorage.clickcount;
 document.getElementById("result").innerHTML = "Your current score is " + localStorage.clickcount;
}
</script>
</head>
	
<body>
	<form>
	<h3>Game starts when you click the button, You have a minute to get a high score!</h3>
	<button onclick="clickCounter()" id="clicker" type="button"  name="clicker" style="width: 100%; height: 200px;" >Click Me!</button>
	<div id="result"></div>
	<!--<button onclick="submitScore()" id="sendscore" type="button">Submit Score</button>-->
<!----><input class="btn btn-primary" onclick="submitScore()" type="submit" name="sendscore" value="Submit Score" />
	<!--<button class="btn btn-primary" id="reset" type="button" style>Restart</button>
	<h3 id="countdown">Time Left</h3>-->
	</form>
</body>
	

</html><!---->

<?php require(__DIR__ . "/partials/flash.php");
	
