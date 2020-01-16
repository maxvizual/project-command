<?php 
$title = $item['title'];
$text1 = $item['column_1_text'];
$text2 = $item['column_2_text'];
$note = $item['note'];
$warning = $item['warning'];
$has_button = $item['map_button'] == 'yes';
$button_target = $item['button'];
?>

<div class="row">
    <div class="col-12">
        <h3 class="title"><strong><?php echo $title; ?></strong></h3>
    </div>
    <div class="col-12">
        <div class="row">
            <div class="col-6">
                <?php echo $text1; ?>
            </div>
            <div class="col-6">
                <?php echo $text2; ?>
            </div>
            <?php 
            // przycisk "pokaz na mapie"
            if ($has_button) :
                ?>
            <div class="col-12">
                <a href="#" class="icon-map-marker" data-map-target="<?php echo $button_target; ?>"><?php _e('Pokaż na mapie', 'echo'); ?></a>
            </div>
            <?php endif; ?>

            <?php 
            // wyswietlanie komunikatu od ECHO
            if ($warning) :
                ?>
            <div class="col-12">
                <div class="office-warning">
                    <?php echo $warning; ?>
                </div>
            </div>
            <?php endif; ?>

            <?php 
            // wyswietlanie notatki od ECHO
            if ($note) :
                ?>
            <div class="col-12">
                <div class="office-note">
                    <?php echo $note; ?>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>
