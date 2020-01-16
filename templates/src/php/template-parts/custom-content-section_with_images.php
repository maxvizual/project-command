<?php 
$invert_order = $item['image_position'] == 'left';
$title = $item['title'];
$text = $item['text'];
$images = $item['images'];
?>

<div class="row <?php echo ($invert_order) ? 'justify-content-start' : 'justify-content-end justify-content-xl-start inverted'; ?> justify-content-xxl-center">
    <div class="col-12">
        <div class="swiper <?php echo ($invert_order) ? '' : 'inverted'; ?>">
            <div class="swiper-container">
                <div class="swiper-wrapper">
                    <?php 
                        foreach($images as $key=>$image): 
                        $src = get_image_src($image['id']);                  
                    ?>
                    <div class="swiper-slide">
                        <img src="<?php echo $src; ?>" class="img-fluid swiper-slide-image">
                        <?php if (is_page( 'deweloper' ) ):?>
                        <div class="caption-small <?php echo ($invert_order) ? '' : 'inverted-caption'; ?>">
                            <p><?php echo $image[title]; ?></p>
                        </div>
                        <?php  endif;
                        ?>
                    </div>
                    <?php endforeach; ?>
                </div>
                <div class="swiper-pagination"></div>
            </div>
        </div>
    </div>

    <div class="swiper-title col-11 mx-auto col-md-4 my-md-auto d-xl-none <?php echo (!$invert_order) ? 'inverted' : ''; ?>">
        <h1><?php echo $title; ?></h1>
        <div class="swirl"></div>
    </div>
    
    <div class="swiper-text col-11 mx-auto col-md-10 d-xl-none <?php echo (!$invert_order) ? 'inverted' : ''; ?>">
        <?php echo $text; ?>
    </div>
    
    <div class="swiper-title-text d-none my-auto d-xl-block col-xl-4 mx-xl-auto mx-xxl-0 <?php echo (!$invert_order) ? 'inverted mr-xxl-5' : 'ml-xxl-5'; ?>">
        <h1><?php echo $title; ?></h1>
        <div class="swirl"></div>
        <?php echo $text; ?>
    </div>
</div>
