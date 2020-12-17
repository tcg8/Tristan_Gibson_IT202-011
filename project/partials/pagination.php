<?php

if(!isset($page)) {
    flash("Pages variable not set!", "danger");
}
if(!isset($total_pages)) {
    flash("Total Pages variable not set!", "danger");
}
//flash("heyooooooo page is " . $page . " and total_pages is " . $total_pages);
?>
<?php if(isset($page) && isset($total_pages)):?>
<nav aria-label="Page Navigation">
    <ul class="pagination justify-content-center">
        <li class="page-item <?php echo ($page-1) < 1?"disabled":"";?>">
            <a class="page-link" href="?page=<?php echo $page-1;?>" tabindex="-1">Previous</a>
        </li>
        <?php for($i = 0; $i < $total_pages; $i++):?>
            <li class="page-item <?php echo ($page-1) == $i?"active":"";?>"><a class="page-link" href="?page=<?php echo ($i+1);?>"><?php echo ($i+1);?></a></li>
        <?php endfor; ?>
        <li class="page-item <?php echo ($page) >= $total_pages?"disabled":"";?>">
            <a class="page-link" href="?page=<?php echo $page+1;?>">Next</a>
        </li>
    </ul>
</nav>
<?php endif;?>
