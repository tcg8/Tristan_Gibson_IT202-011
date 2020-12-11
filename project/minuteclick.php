<?php require_once(__DIR__ . "/partials/nav.php"); ?>

<?php
//This is the game I made for Milestone 2, you click a button as much as possible in one minute for a score. The clock hasn't been added yet but the score still works
//which is enough for this milestone

//This game is now completely done, Finished for milestone 4

if (!is_logged_in()) {
    //this will redirect to login and kill the rest of this script (prevent it from executing)
    flash("You are not logged in, your score won't be saved!");
    //die(header("Location: login.php"));
}

?>





<html>
<head>
<script>
count.value=0;
var constTime=20;   //this is so I can change the time length of the game easier, maily for testing
var constTime2=5;   //this is for cooldown timer

//variables for game and game timer
var gameOff=true;
var clickcount=0;
var time=constTime;

//variables for cooldown
var cooldownTime=constTime2; //this is a cooldown timer that starts after the game timer ends to prevent you from accidentailly starting a new game right away or clearing your score before you submit it.
var onCooldown=false;

//display info
document.getElementById("timeLeft").innerHTML = "You have " + time + " seconds to click the button. Timer starts when you click.";
document.getElementById("result").innerHTML = "Your current score is " + clickcount;


function clickCounter() {
	//console.log("gameOff is " + gameOff);
    if(!onCooldown){
	clickcount++;
    }
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
  if(gameOff && !onCooldown){
    gameOff=false;
    startTimer();
  }

}
	
	
	
//This sets the value of the hidden field which then submits your score.
//It also resets the score for the next game.
//function submitScore() {
//count.value=clickcount;
// clickcount = 0;
//}
	

	
//This is the Timer function for how long you have to click the button
function startTimer(){
	
	clickcount=1;//these 2 lines are so the score resets when you restart a new game
	document.getElementById("result").innerHTML = "Your current score is " + clickcount;
	
    time = constTime;
	document.getElementById("timeLeft").innerHTML = "You have " + time + " seconds left to click the button!";
    timer = setInterval(function(){
       time--;
	    if(time==1) { //this just here so it says "1 second" instead of "1 seconds"
		document.getElementById("timeLeft").innerHTML = "You have " + time + " second left to click the button!"; 
	    }else{
	document.getElementById("timeLeft").innerHTML = "You have " + time + " seconds left to click the button!"; 
	    }
       if(time<=0){
           //console.log("stop me");
           gameOff=true;
           clearInterval(timer);
	       //Start cooldown now that game is over
	       onCooldown=true;
	       count.value=clickcount;
	       addScore(clickcount);
	       startCooldown();
	       
       }//---if(time<=0)
    }, 1000);//---setInterval
}//---startTimer function

	
	
//Here is the Timer function to make the button clicks not work for a short time (might not do)
function startCooldown(){
	cooldownTime=constTime2; 
	onCooldown=true;
	
	document.getElementById("timeLeft").innerHTML = "Game Over, wait " + cooldownTime + " seconds to start again.";
    timer = setInterval(function(){
       cooldownTime--;
       //console.log(time);
	    if(cooldownTime==1) { //this just here so it says "1 second" instead of "1 seconds"
		document.getElementById("timeLeft").innerHTML = "Game Over, wait " + cooldownTime + " second to start again."; 
	    }else{
	document.getElementById("timeLeft").innerHTML = "Game Over, wait " + cooldownTime + " seconds to start again."; 
	    }
       if(cooldownTime<=0){
           gameOff=true;
           clearInterval(timer);
	   onCooldown=false;
	    time=constTime;
	   document.getElementById("timeLeft").innerHTML = "You have " + time + " seconds to click the button. Timer starts when you click.";
	       
       }//---if(time<=0)
    }, 1000);//---setInterval
}//---startTimer function

	
	
	
		function addScore(clickcount) {
            //https://www.w3schools.com/xml/ajax_xmlhttprequest_send.asp
            let xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function () {
                if (this.readyState == 4 && this.status == 200) {
                    let json = JSON.parse(this.responseText);
                    if (json) {
                        if (json.status == 200) {
                            //alert("Congrats you scored " + clickcount + " points!");
                            //location.reload();
                        } else {
                            //alert(json.error);
                        }
                    }
                }
            };
            
            xhttp.open("POST", "<?php echo getURL("api/sendGameScore.php");?>", true);
	    //this is required for post ajax calls to submit it as a form
            xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            //map any key/value data similar to query params
            xhttp.send(clickcount);

        }
	
	
	
	
	
</script>
</head>
	
<body>
	<form method="POST">
	<h3>Game starts when you click the button, You have 20 seconds to get a high score!</h3>
	<div id="timeLeft"></div>
	<button onclick="clickCounter()" id="clicker" type="button"  name="clicker" style="width: 100%; height: 200px;" >Click Me!</button>
	<div id="result"></div>
		<input type="hidden" id="count" name="count" value=0 />
	<!--<input class="btn btn-primary" onclick="submitScore()" type="submit" name="sendscore" value="Submit Score" />-->
	<!----></form>
</body>
	

</html>

<?php require(__DIR__ . "/partials/flash.php");
	
