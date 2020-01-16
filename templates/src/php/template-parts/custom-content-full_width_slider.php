<?php

$slides = $item['slides'];

?>

<div class="row">
    <div class="col-12">
        <div class="swiper-container">
            <div class="swiper-wrapper">
                <?php 

                foreach($slides as $key=>$image):

                    $image_desktop = get_image_src($image['image_desktop']['id']);
                    $image_mobile = get_image_src($image['image_mobile']['id']);
                    $caption = get_image_src($image['caption']);
                    $link = $image['link'];
                    $link_url = $link['url'];

                ?>
                <div class="swiper-slide">
                    <?php
                    // if there's a link
                    if($link_url) : ?>
                    <a href="<?php echo $link_url; ?>">
                    <?php else : ?>
                    <div>
                    <?php endif; ?>
                        <?php 
                        // if there's mobile image
                        if($image_mobile) : 
                        ?>
                        <img class="d-none d-lg-block img-fluid mx-auto" src="<?php echo $image_desktop; ?>" alt="">     
                        <img class="d-block d-lg-none img-fluid mx-auto" src="<?php echo $image_mobile; ?>" alt="">
                        <?php 
                        // if there's only desktop image
                        else : 
                        ?>
                        <img class="img-fluid mx-auto" src="<?php echo $image_desktop; ?>" alt="">
                        <?php endif; ?>

                        <?php 
                        // if there's a caption
                        if($caption): ?>
                        <div class="swiper-slide-image-caption-wrapper">
                            <div class="container-fluid">
                                <div class="row justify-content-center">
                                    <div class="col-10">
                                        <div class="swiper-slide-image-caption">
                                            <?php echo $caption; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endif;
                    // if there's a link
                    if($link_url) : ?>
                    </a>
                    <?php else : ?>
                    </div>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            </div>
            <div class="swiper-pagination"></div>
        </div>
    </div>
</div>
