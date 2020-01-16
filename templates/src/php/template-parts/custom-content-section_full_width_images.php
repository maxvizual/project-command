<?php 

$image = $item['image'];

?>
   
<div class="row">
    <div class="col-12">            
        <div class="full-width-image">
            <img src="<?php echo get_image_src($image['id']); ?>" class="img-fluid" alt="">
        </div>
    </div>
</div>  
