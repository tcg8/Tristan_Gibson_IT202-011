<?php require_once(__DIR__ . "/partials/nav.php"); ?>
<?php
//we use this to safely get the email to display
$email = "";
if (isset($_SESSION["user"]) && isset($_SESSION["user"]["email"])) {
    $email = $_SESSION["user"]["email"];
}
flash("Welcome, $email");
?>

<?php

//$userbro="<a href='profile.php'>$userbro</a>"
//echo '<a href="mycgi?foo=', urlencode($userbro), '">';
flash("testing, <a href='profile.php'>$email</a>");

?>

<?php
get10week(); 
get10month(); 
get10lifetime();

?>

    
<?php require(__DIR__ . "/partials/flash2.php");?>
<?php require(__DIR__ . "/partials/flash.php");
