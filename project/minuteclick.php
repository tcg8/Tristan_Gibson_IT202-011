<?php require_once(__DIR__ . "/partials/nav.php"); ?>

		    
<?php
/*
<script>
var time=10;
timer = setInterval(function(){
	
	time--;
	console.log(time);
	if(time<=0){
		console.log("stop me");
		clearInterval(timer);
	}
}, 1000);
</script>
*/

//This is the game I made for Milestone 2, you click a button as much as possible in one minute for a score. The clock hasn't been added yet but the score still works
//which is enough for this milestone

if (!is_logged_in()) {
    //this will redirect to login and kill the rest of this script (prevent it from executing)
    flash("You are not logged in, your score won't be saved!");
    //die(header("Location: login.php"));
}
	if (isset($_POST["sendscore"])) {
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
				flash("Successfully recorded score");
			}
			else {
				flash("You are not logged in so the score was not saved");
			}//*/
		}
	}
?>





<html>
<head>
<script>

var gameOff=true;
var clickcount=0;

function clickCounter() {
	console.log("gameOff is " + gameOff);
	clickcount++;
	document.getElementById("result").innerHTML = "Your current score is " + clickcount;
/*
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
*/
  if(gameOff){
    gameOff=false;
    startTimer();
  }

}
	
	
	
	
function submitScore() {
count.value=clickcount;
 clickcount = 0;
 //xhttp.send("score=" + localStorage.clickcount);
//	count.value=localStorage.clickcount;
 //localStorage.clickcount = 0;
	//value=localStorage.clickcount;
 //document.getElementById("result").innerHTML = "Your current score is " + localStorage.clickcount;
}

	
	
	
//This is the Timer function for how long you have to click the button
function startTimer(){
    var time=10;
    //localStorage.clickcount=1;//startTimer only runs when you click to run a new game so the score will start at 1
	document.getElementById("timeLeft").innerHTML = "You have " + time + " seconds left to click the button!";
    timer = setInterval(function(){
       time--;
       //console.log(time);
	    if(time==1) { //this just here so it says "1 second" instead of "1 seconds"
		document.getElementById("timeLeft").innerHTML = "You have " + time + " second left to click the button!"; 
	    }else{
	document.getElementById("timeLeft").innerHTML = "You have " + time + " seconds left to click the button!"; 
	    }
       if(time<=0){
           console.log("stop me");
           gameOff=true;
           clearInterval(timer);
       }//---if(time<=0)
    }, 1000);//---setInterval
  //}//---if(gameOff)
}//---startTimer function

//Here is the Timer function to make the button clicks not work for a short time (might not do)


</script>
</head>
	
<body>
	<form method="POST">
	<h3>Game starts when you click the button, You have a minute to get a high score!</h3>
	<div id="timeLeft"></div>
	<button onclick="clickCounter()" id="clicker" type="button"  name="clicker" style="width: 100%; height: 200px;" >Click Me!</button>
	<div id="result"></div>
		<input type="hidden" id="count" name="count" value=0 />
	<!--<button onclick="submitScore()" id="sendscore" type="button">Submit Score</button>-->
<!----><input class="btn btn-primary" onclick="submitScore()" type="submit" name="sendscore" value="Submit Score" />
	<!--<button class="btn btn-primary" id="reset" type="button" style>Restart</button>
	<h3 id="countdown">Time Left</h3>-->
	<!----></form>
</body>
	

</html>

<?php require(__DIR__ . "/partials/flash.php");
	
