<?php

$content = get_field('content', $page);
foreach ($content as $key => $item) :

    $width = $item['width'];
    $extra_classes = $item['extra_classes'];
    $disabled = $item['disable'];
    $layout = $item['acf_fc_layout'];

    if (!$disabled) :
        ?>

<div class="custom-content custom-content-<?php echo $layout; ?> <?php echo $extra_classes; ?>">
    <div class="container<?php ($width == 'full') ? '-fluid' : ''; ?>">
        <?php
        set_query_var('item', $item);
        get_template_part('template-parts/custom-content', $layout);
        ?>
    </div>
</div>

<?php 
endif;
endforeach;
