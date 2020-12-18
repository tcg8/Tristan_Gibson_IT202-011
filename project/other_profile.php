<?php require_once(__DIR__ . "/partials/nav.php"); ?>
<?php
//Note: we have this up here, so our update happens before our get/fetch
//that way we'll fetch the updated data and have it correctly reflect on the form below
//As an exercise swap these two and see how things change



if(isset($_GET["id"])){
$id = $_GET["id"];
//flash("yyyyyyyyyooooooooooooooo $id");
}
else{
$id= get_user_id();
//flash("grouuuuuuuuuuup");
}
/*if(isset($_GET["username"])){
	$username=$_GET["username"];
	flash(" ITSSSSSSSSSSSSSSSSSSSS A $username");
}*/
//




$db = getDB();

$stmt = $db->prepare("SELECT * from Users WHERE id = :id LIMIT 1");
    $params = array(":id" => $id);
    $r = $stmt->execute($params);
    if($r){
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        flash("This account is $result["status"]");
//flash("This account has " . $profilePoints . " points.");
    }


//get users points and show on profile page
    $stmt = $db->prepare("SELECT points from Users WHERE id = :id LIMIT 1");
    $params = array(":id" => $id);
    $r = $stmt->execute($params);
    if($r){
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $profilePoints = $result["points"];
flash("This account has " . $profilePoints . " points.");
    }

//update status to public
/*if (isset($_POST["makePub"])) {
    $stmt = $db->prepare("UPDATE Users set status = :status where id = :id");
        $r = $stmt->execute([":status" => "public", ":id" => get_user_id()]);
        //flash("line 73 " . count($r));
        if ($r) {
            flash("Your profile is public");
        }
        else {
            flash("Error updating profile");
        }
}
//update status to private
if (isset($_POST["makePriv"])) {
    $stmt = $db->prepare("UPDATE Users set status = :status where id = :id");
        $r = $stmt->execute([":status" => "private", ":id" => get_user_id()]);
        //flash("line 73 " . count($r));
        if ($r) {
            flash("Your profile is private");
        }
        else {
            flash("Error updating profile");
        }
}*/



//save data if we submitted the form
/*
if (isset($_POST["saved"])) {
    $isValid = true;
    //check if our email changed
    $newEmail = get_email();
    if (get_email() != $_POST["email"]) {
        //TODO we'll need to check if the email is available
        $email = $_POST["email"];
        $stmt = $db->prepare("SELECT COUNT(1) as InUse from Users where email = :email");
        $stmt->execute([":email" => $email]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $inUse = 1;//default it to a failure scenario
        if ($result && isset($result["InUse"])) {
            try {
                $inUse = intval($result["InUse"]);
            }
            catch (Exception $e) {

            }
        }
        if ($inUse > 0) {
            flash("Email already in use!");
            //for now we can just stop the rest of the update
            $isValid = false;
        }
        else {
            $newEmail = $email;
        }
    }
    $newUsername = get_username();
    if (get_username() != $_POST["username"]) {
        $username = $_POST["username"];
        $stmt = $db->prepare("SELECT COUNT(1) as InUse from Users where username = :username");
        $stmt->execute([":username" => $username]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $inUse = 1;//default it to a failure scenario
        if ($result && isset($result["InUse"])) {
            try {
                $inUse = intval($result["InUse"]);
            }
            catch (Exception $e) {

            }
        }
        if ($inUse > 0) {
            flash("Username already in use");
            //for now we can just stop the rest of the update
            $isValid = false;
        }
        else {
            $newUsername = $username;
        }
    }
    if ($isValid) {
        $stmt = $db->prepare("UPDATE Users set email = :email, username= :username where id = :id");
        $r = $stmt->execute([":email" => $newEmail, ":username" => $newUsername, ":id" => get_user_id()]);
        //flash("line 73 " . count($r));
        if ($r) {
            flash("Updated profile");
        }
        else {
            flash("Error updating profile");
        }
        //password is optional, so check if it's even set
        //if so, then check if it's a valid reset request
        if (!empty($_POST["password"]) && !empty($_POST["confirm"]) && !empty($_POST["current_password"])) {
            $current = $_POST["current_password"];
            $stmt = $db->prepare("SELECT password from Users WHERE id = :id LIMIT 1");

            $params = array(":id" => get_user_id());
            $r = $stmt->execute($params);
            //flash("line 88 " . count($r));
            if($r){
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                $_current = $result["password"];
                if(password_verify($current, $_current)){
                    if (($_POST["password"] == $_POST["confirm"]) ){//&& ($_POST["confirm"] == ____)) { //flash($_SESSION["user"]["password"])

                        $password = $_POST["password"];
                        $hash = password_hash($password, PASSWORD_BCRYPT);

                        $stmt = $db->prepare("UPDATE Users set password = :password where id = :id");
                        $r = $stmt->execute([":id" => get_user_id(), ":password" => $hash]);

                        if ($r) {
                            flash("Reset Password");
                        }
                        else {
                            flash("Error resetting password");
                        }
                    }
                }
                else{
                    flash("That is not your current password, please try again", "danger");
                }
            }
        }
//fetch/select fresh data in case anything changed
        $stmt = $db->prepare("SELECT email, username from Users WHERE id = :id LIMIT 1");
        $stmt->execute([":id" => get_user_id()]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($result) {
            $email = $result["email"];
            $username = $result["username"];
            //let's update our session too
            $_SESSION["user"]["email"] = $email;
            $_SESSION["user"]["username"] = $username;
        }
    }
    else {
        //else for $isValid, though don't need to put anything here since the specific failure will output the message
    }
}//*/

?>



<?php

//THIS PHP SECTION WAS CREAED FOR MILESTONE 2, THIS IS WHAT SENDS THE SCORE TO THE DATABASE WHEN THE USER IS LOGGED IN.
///*
$stmt = $db->prepare("SELECT * from Scores where user_id = :id order by id desc limit 10");
$params = array(":id" => $id);
$results = $stmt->execute($params);
$results = $stmt->fetchAll();
//flash("array length check " . count($results));
/*
//ADD A FOR LOOP HERE TO CREATE THE TOP 10 CHART    USE ECHO OR FLASH   TO CREATE THE CHART
$hasScores=true;
if (count($results)==0) {
    $hasScores=false;
    flash2("You do not have any scores recorded, try playing the game!");
}
if($hasScores) {
        flash2("Your last " . count($results) . " scores");
    $i=10-count($results);
    $a=1;
    do {
        //So $results was printing double, like 27 came out at 2727 and 0 as 00 so I am modding by 10^(number of digits / 2)
        //so when $results is 2727 we do ( $results % $modifier ) where $modifier will be 100 since 27 is a 2 digit number and 10^2=100
        //doing modifier like this will get rid of the extra digits being produced
        //$numlength = strlen((string)$num);
        $numlength = strlen(implode($results[$a-1]))/2; //this gets the number of digits that is supposed to be printed
        $modifier = 10**$numlength;//this is the number that $results will be modified by, it just gets 10^power of $numlength
        $finalNum = strlen(implode($results[$a-1])) % $modifier;
        flash2("#" . $a . " most recent score is " . $finalNum);
        //flash("#" . $a . " most recent score is " . implode($results[$i]) . " finalNum " . $finalNum);//%$check);//for some reason the score displayed is being doubled
      $a++;
      $i++;
    }
    while($i<10);
}
//*/


/*$stmt = $db->prepare("SELECT * FROM Scores WHERE user_id = :id ORDER BY created DESC LIMIT :offset,:count");
$stmt->bindValue(":offset", $offset, PDO::PARAM_INT);
$stmt->bindValue(":count", $per_page, PDO::PARAM_INT);
$stmt->bindValue(":id", get_user_id(), PDO::PARAM_INT);
$stmt->execute();
//$stmt->execute([":id"=>get_user_id()]);
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);*/


?>


<html>
    
    <script>
        //var statusState = "Public";
        //document.getElementById("currStatus").innerHTML = "Your profile is currently set to "+statusState; 
    </script>
    
    
    

</html>

<div class="container-fluid">
        <h3>The Last 10 Scores of this account</h3>
        <div class="list-group">
            <?php if (isset($results) && count($results)): ?>
                <?php foreach ($results as $r): ?>
                    <div class="list-group-item" style="background-color: #25E418">
                        <div class="row">
				
                            <div class="col">
                                They scored: 
                                <?php safer_echo($r["score"]); ?>
                            </div>
                            <div class="col">
                                Scored on: 
                                <?php safer_echo($r["created"]); ?>
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

<?php require(__DIR__ . "/partials/flash2.php");?>
<?php require(__DIR__ . "/partials/flash.php");?>
