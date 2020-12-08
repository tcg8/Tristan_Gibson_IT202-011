SELECT * from Scores where user_id = :id order by created desc limit 10; 

<?php
$results = $stmt->fetchAll();


?>

<?php foreach($results as $r):?>

<?php endforeach;?>
