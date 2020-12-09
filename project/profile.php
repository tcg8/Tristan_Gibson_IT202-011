<?php require_once(__DIR__ . "/partials/nav.php"); ?>
<?php
//Note: we have this up here, so our update happens before our get/fetch
//that way we'll fetch the updated data and have it correctly reflect on the form below
//As an exercise swap these two and see how things change
if (!is_logged_in()) {
    //this will redirect to login and kill the rest of this script (prevent it from executing)
    flash("You must be logged in to access this page");
    die(header("Location: login.php"));
}





$db = getDB();
//save data if we submitted the form
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
            flash("Email already in use");
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
}



?>







<?php


$stmt = $db->prepare("SELECT score from Scores where user_id = :id order by id desc limit 10");
$params = array(":id" => get_user_id());
$results = $stmt->execute($params);
$results = $stmt->fetchAll();

//flash("line 145 " . count($results));
//flash("line 150 " . count($results));

//ADD A FOR LOOP HERE TO CREATE THE TOP 10 CHART    USE ECHO OR FLASH   TO CREATE THE CHART
$hasScores=true;
if (count($results)==0) {
    $hasScores=false;
    flash("You do not have any scores recorded, try playing the game!");
}
if($hasScores) {
    flash("Your last 10 scores (still need message for when user has less than 10 scores)");
    $i=10-count($results);
    
    if(count($results)==10) {
        $i=count($results)-10;
    }
    $a=1;
    do {
        //So $results was printing double, like 27 came out at 2727 and 0 as 00 so I am modding by 10^(number of digits / 2)
        //so when $results is 2727 we do $results%$modifier where $modifier will be 100 since 27 is a 2 digit number and 10^2=100
        //doing modifier like this will get rid of the extra digits being produced
        //$numlength = strlen((string)$num);
        $numlength = strlen(implode($results[$a-1]))/2; //this gets the number of digits that is supposed to be printed
        $modifier = 10**$numlength;//this is the number that $results will be modified by, it just gets 10^power of $numlength
        $finalNum = implode($results[$a-1]) % $modifier;
        //flash("digits " . length(implode($results[$i])));
        //if(implode($results[$i])>10){$check=100;}
        flash("#" . $a . " most recent score is " . $finalNum);
        //flash("#" . $a . " most recent score is " . implode($results[$i]) . " finalNum " . $finalNum);//%$check);//for some reason the score displayed is being doubled
      $a++;
      $i++;
    }
    while($i<10);

}
?>

<?php foreach($results as $r):?>

<?php endforeach;?>






    <form method="POST">
        <table style="width:100%">
            
            <tr>
        <td>  <label for="email">Email</label>  </td>
        <td>  <input class="form-control" type="email" name="email" value="<?php safer_echo(get_email()); ?>"/>  </td>
            </tr><tr>
        <td>  <label for="username">Username</label>  </td>
        <td>  <input class="form-control" type="text" maxlength="60" name="username" value="<?php safer_echo(get_username()); ?>"/>  </td>
            </tr><tr>

        <!-- DO NOT PRELOAD PASSWORD-->

        <td>  <label for="pwc">Current Password</label>  </td>
        <td>  <input id="pwc" class="form-control" type="password" required minlength="4" required maxlength="60" name="current_password"/>  </td>
            </tr><tr>
        <td>  <label for="pw">New Password</label>  </td>
        <td>  <input id="pw" class="form-control" type="password" required minlength="4" required maxlength="60" name="password"/>  </td>
            </tr><tr>
        <td>  <label for="cpw">Confirm Password</label>  </td>
        <td>  <input type="password" required minlength="4" required maxlength="60" name="confirm"/>  </td>
            </tr>
       
        </table>
        <input class="btn btn-primary" type="submit" name="saved" value="Save Profile"/>
        
    
    </form>


    <form method="POST">
        <table style="width:50%">
            
            
     <tr>
        <td>  <label for="topscores">Top 10 scores from the past: </label>  </td>
        <td>  <select name="topscores" id="topscores">
    <option value="week">Week</option>
    <option value="month">Month</option>
    <option value="alltime">All Time</option>
  </select>  </td>
    </tr> 
            <tr>
                <td><label> #1 </label></td>
                <td><label>insert score here </label></td>
            </tr>
            <tr>
                <td><label> #2 </label></td>
                <td><label>insert score here </label></td>
            </tr>
            <tr>
                <td><label> #3 </label></td>
                <td><label>insert score here </label></td>
            </tr>
            <tr>
                <td><label> #4 </label></td>
                <td><label>insert score here </label></td>
            </tr>
            <tr>
                <td><label> #5 </label></td>
                <td><label>insert score here </label></td>
            </tr>
            <tr>
                <td><label> #6 </label></td>
                <td><label>insert score here </label></td>
            </tr>
            <tr>
                <td><label> #7 </label></td>
                <td><label>insert score here </label></td>
            </tr>
            <tr>
                <td><label> #8 </label></td>
                <td><label>insert score here </label></td>
            </tr>
            <tr>
                <td><label> #9 </label></td>
                <td><label>insert score here </label></td>
            </tr>
            <tr>
                <td><label> #10 </label></td>
                <td><label>insert score here </label></td>
            </tr>
            
        </table>
    </form> 



<?php require(__DIR__ . "/partials/flash.php");

