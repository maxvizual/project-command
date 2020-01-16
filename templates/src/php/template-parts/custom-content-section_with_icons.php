<?php
$icon_with_desc = $item['icon_with_description']; 
?>

<div class="row">
<?php foreach($icon_with_desc as $icon): ?>
    <div class="col-4 icon">
        <div class="icon-image" style="background-color: <?php echo $icon['color']; ?>"><img src="<?php echo $icon['picture']; ?>"></div>
        <div class="icon-text"><?php echo $icon['text']; ?></div>
    </div>
<?php endforeach; ?>
</div>