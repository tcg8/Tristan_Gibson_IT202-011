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

//$stmt = $db->prepare("SELECT password from Users WHERE id = :id LIMIT 1");
$stmt = $db->prepare("SELECT * from Scores where user_id = :id order by created desc limit 10");

//flash("itsa me " . implode("", $results));
//echo $results;
/*foreach($results as $index){
    echo "$index <br>\n";
}*/
$results = $stmt->fetchAll();
?>

<?php foreach($results as $r):?>

<?php endforeach;?>
<?php

flash("itsa me " . count($results));
?>







/*
SELECT * from Scores where user_id = :id order by created desc limit 10;
<?php
$results = $stmt->fetchAll();
?>
<? foreach($results as $r):?>
<?php endforeach;?>
*/


/*$arr = ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday"];
foreach($arr as $index=>$value){
    echo "$index => $value<br>\n";
}

$arr2 = "SELECT score from Scores";
foreach($arr2 as $index){
    echo "$index <br>\n";
}
*/
//$stmt = $db->prepare("
//SELECT Scores.score FROM Scores JOIN Users on Scores.user_id = Users.id where Users.id = :user_id");// and Roles.is_active = 1 and UserRoles.is_active = 1");
//$stmt = $db->prepare("
//SELECT Roles.name FROM Roles JOIN UserRoles on Roles.id = UserRoles.role_id where UserRoles.user_id = :user_id and Roles.is_active = 1 and UserRoles.is_active = 1");


//$arr = ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday"];
/*$testingThing=$_SESSION["user"]["id"];
flash("testing thisThing " . $testingThing);// . " ==== " . get_id());

$arr = ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday"];
//note we take the array first, then we get the value "as" the next variable we declare
foreach($arr as $day){
 echo "$day <br>\n";
}
//we can also return the key/value separately for associative arrays
foreach($arr as $index=>$value){
 echo "$index => $value<br>\n";
}
flash("in a loop bb");
*/
//$safetyUser=get_user_id();
//$stmt = $db->prepare("SELECT score from Scores WHERE id = $safetyUser");
//flash("hi " . $stmt);


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
                <td>  <label for="lastscores">Last 10 scores</label>  </td>
                <td>  <label>insert scores here </label> </td>
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

