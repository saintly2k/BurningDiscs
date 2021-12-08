<?php

if(!empty($_SESSION["username"])) {

$total_pages = $conn->query("SELECT COUNT(*) FROM `favourites` WHERE `user`='$uID'")->fetch_row()[0];

$page = isset($_GET['pagination']) && is_numeric($_GET['pagination']) ? $_GET['pagination'] : 1;

$num_results_on_page = "50";

if ($stmt = $conn->prepare("SELECT * FROM `favourites` WHERE `user`='$uID' ORDER BY `when` DESC LIMIT ?,?")) {
	$calc_page = ($page - 1) * $num_results_on_page;
	$stmt->bind_param('ii', $calc_page, $num_results_on_page);
	$stmt->execute(); 
	$result = $stmt->get_result();
	$stmt->close();
}

?>
<div class="panel panel-success">
    <div class="panel-heading">
        <h3 class="panel-title">My Favourites</h3>
    </div>


    <?php
    if ($result->num_rows > 0) {
        while($disc = $result->fetch_assoc()) { ?>
    <div class="panel-body">
        <?php 
        
        $reDisc = $disc["disc"];
        $reDisc = $conn->query("SELECT * FROM `discs` WHERE `id`='$reDisc' LIMIT 1");
        $reDisc = mysqli_fetch_assoc($reDisc);   
        ?>

        <a href="<?= $url ?>disc/<?= $disc["disc"] ?>"><b><?= $reDisc["name"] ?></b></a> at <?= $disc["when"] ?>
    </div>
    <?php } } ?>

    <div class="panel-body text-center">

        <?php if (ceil($total_pages / $num_results_on_page) > 0): ?>
        <ul class="pagination">
            <?php if ($page > 1): ?>
            <li class="prev"><a href="<?php echo $url; echo "user/favourites/"; echo $page-1 ?>">Prev</a></li>
            <?php endif; ?>

            <?php if ($page > 3): ?>
            <li class="start"><a href="<?php echo $url; echo "user/favourites/"; ?>1">1</a></li>
            <li class="dots"></li>
            <?php endif; ?>

            <?php if ($page-2 > 0): ?><li class="page"><a href="<?php echo $url; echo "user/favourites/"; echo $page-2 ?>"><?php echo $page-2 ?></a></li><?php endif; ?>
            <?php if ($page-1 > 0): ?><li class="page"><a href="<?php echo $url; echo "user/favourites/"; echo $page-1 ?>"><?php echo $page-1 ?></a></li><?php endif; ?>

            <li class="currentpage"><a href="<?php echo $url; echo "user/favourites/"; echo $page; ?>"><?php echo $page ?></a></li>

            <?php if ($page+1 < ceil($total_pages / $num_results_on_page)+1): ?><li class="page"><a href="<?php echo $url; echo "user/favourites/"; echo $page+1 ?>"><?php echo $page+1 ?></a></li><?php endif; ?>
            <?php if ($page+2 < ceil($total_pages / $num_results_on_page)+1): ?><li class="page"><a href="<?php echo $url; echo "user/favourites/"; echo $page+2 ?>"><?php echo $page+2 ?></a></li><?php endif; ?>

            <?php if ($page < ceil($total_pages / $num_results_on_page)-2): ?>
            <li class="dots"></li>
            <li class="end"><a href="<?php echo $url; echo "user/favourites/"; echo ceil($total_pages / $num_results_on_page) ?>"><?php echo ceil($total_pages / $num_results_on_page) ?></a></li>
            <?php endif; ?>

            <?php if ($page < ceil($total_pages / $num_results_on_page)): ?>
            <li class="next"><a href="<?php echo $url; echo "user/favourites/"; echo $page+1 ?>">Next</a></li>
            <?php endif; ?>
        </ul>
        <?php endif; ?>
    </div>
</div>
<?php } else { ?>
<div class="panel panel-success">
    <div class="panel-heading">
        <h3 class="panel-title">Error!</h3>
    </div>
    <div class="panel-body">
        This page is only for Members...
    </div>
</div>
<?php } ?>
