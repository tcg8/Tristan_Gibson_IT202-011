<?php require_once(__DIR__ . "/partials/nav.php"); ?>

<form method="POST">
<table style="width:100%">    
    <tr>
        <td><label for="email">Email or Username:</label></td>
        <td><input class="form-control" id="email" name="email" required/></td>
    </tr><tr>
        <td><label for="p1">Password:</label></td>
        <td><input class="form-control" type="password" id="p1" name="password" required/>
    </td></tr>
    </table>
        <input class="btn btn-primary" type="submit" name="login" value="Login"/>
    </form>
    

<?php
if (isset($_POST["login"])) {
    $email = null;
    $password = null;
    if (isset($_POST["email"])) {
        $email = $_POST["email"];
    }
    if (isset($_POST["password"])) {
        $password = $_POST["password"];
    }
    $isValid = true;
    if (!isset($email)) {
        $isValid = false;
        flash("Email is missing");
    }
    if (!isset($password)) {
        $isValid = false;
        flash("Password is missing");
    }
    $usingemail=true;
    if (!strpos($email, "@")) {
        $usingemail=false;
        //$isValid = false;
        //echo "<br>Invalid email<br>";
        //flash("Invalid email");
    }
    if ($isValid) {
        $db = getDB();
        if (isset($db)) {
            if($usingemail){
                $stmt = $db->prepare("SELECT id, email, username, password, points from Users WHERE email = :email LIMIT 1");
                $params = array(":email" => $email);
            }else{
                $stmt = $db->prepare("SELECT id, email, username, password, points from Users WHERE username = :email LIMIT 1");
                $params = array(":email" => $email);
            }
            $r = $stmt->execute($params);
            //echo "db returned: " . var_export($r, true);
            $e = $stmt->errorInfo();
            if ($e[0] != "00000") {
                //echo "uh oh something went wrong: " . var_export($e, true);
                flash("Something went wrong, please try again");
            }
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($result && isset($result["password"])) {
                $password_hash_from_db = $result["password"];
                if (password_verify($password, $password_hash_from_db)) {
                    $stmt = $db->prepare("
SELECT Roles.name FROM Roles JOIN UserRoles on Roles.id = UserRoles.role_id where UserRoles.user_id = :user_id and Roles.is_active = 1 and UserRoles.is_active = 1");
                    $stmt->execute([":user_id" => $result["id"]]);
                    $roles = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    unset($result["password"]);//remove password so we don't leak it beyond this page
                    //let's create a session for our user based on the other data we pulled from the table
                    $_SESSION["user"] = $result;//we can save the entire result array since we removed password
                    if ($roles) {
                        $_SESSION["user"]["roles"] = $roles;
                    }
                    else {
                        $_SESSION["user"]["roles"] = [];
                    }
                    //on successful login let's serve-side redirect the user to the home page.
                    flash("Log in successful");
                    die(header("Location: profile.php"));
                }
                else {
                    flash("Invalid password");
                }
            }
            else {
                flash("Invalid user");
            }
        }
    }
    else {
        flash("There was a validation issue");
    }
}
?>
<?php require(__DIR__ . "/partials/flash.php");
