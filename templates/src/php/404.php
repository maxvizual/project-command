
<?php get_header(); ?>

<div style="margin-top: 75px;"></div>
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-11 col-md-10 col-xxl-8 text-center d-flex flex-column align-items-center bg-grey">
            <h1 style="padding-top: 140px;">404</h1>
            <h2><?php _e('wybrana strona nie istnieje','echo'); ?></h2>
            <div class="swirl m-0 mt-5 mb-5"></div>
            <p style="padding-bottom: 140px;">powrót do <a href="<?php bloginfo('url'); ?>">strony głównej</a></p>
        </div>
    </div>
</div>
<div style="margin-bottom: 75px;"></div>

<?php get_footer();