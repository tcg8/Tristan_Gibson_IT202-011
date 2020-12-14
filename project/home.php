<?php require_once(__DIR__ . "/partials/nav.php"); ?>
<?php
//we use this to safely get the email to display
$email = "";
if (isset($_SESSION["user"]) && isset($_SESSION["user"]["email"])) {
    $email = $_SESSION["user"]["email"];
}
flash("Welcome, $email");
//flash("testing, <a href='profile.php'>$email</a>");
?>

<script>
    var weekArray= <?php get10week();?> ;
    for (var i=0;i< <?php get10week();?>.length;i++){
        console.log(weekArray[i]);
    }
</script>

<?php
get10week(); 
get10month(); 
get10lifetime();

?>

    
<?php require(__DIR__ . "/partials/flash2.php");?>
<?php require(__DIR__ . "/partials/flash.php");
