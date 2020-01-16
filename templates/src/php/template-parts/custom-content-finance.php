<?php 

$contacts = $item['contacts'];

?>

<div class="row">
    <?php foreach($contacts as $key=>$value): ?>
    <div class="col-12">
        <h2><?php echo $value['company']; ?></h2>
        <div class="row">
            <div class="col-12">
                <?php echo $value['description']; ?>
            </div>
        </div>   
    </div>
    <?php endforeach; ?>
</div>
