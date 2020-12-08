<?php require_once(__DIR__ . "/partials/nav.php"); ?>


<?php
/*
 $db = getDB();
	if (isset($db)) {
            //here we'll use placeholders to let PDO map and sanitize our data
            $stmt = $db->prepare("INSERT INTO Scores(id, user_id, score, created) VALUES(:id,:user_id,:score,:created)");
            //here's the data map for the parameter to data
            /$params = array(":id" => id, ":user_id" => $user_id, ":score" => $score, ":created" => $created);
            $r = $stmt->execute($params);
            $e = $stmt->errorInfo();
            if ($e[0] == "00000") {
                flash("Successfully registered! Please login.");
            }
            else {
                if ($e[0] == "23000") {//code for duplicate entry
                    flash("Username or email already exists.");
                }
                else {
                    flash("An error occurred, please try again");
                }
            }
        }*/





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
 localStorage.clickcount = 0;
 document.getElementById("result").innerHTML = "Your current score is " + 0;
}
</script>
</head>
	
<body>
	<h3>Game starts when you click the button, You have a minute to get a high score!</h3>
	<button onclick="clickCounter()" id="clicker" type="button" style="width: 100%; height: 200px;">Click Me!</button>
	<div id="result"></div>
	<button onclick="submitScore()" id="sendscore" type="button">Submit Score</button>
	<!--<button class="btn btn-primary" id="reset" type="button" style>Restart</button>
	<h3 id="countdown">Time Left</h3>-->
</body>
	

</html>
	?>
