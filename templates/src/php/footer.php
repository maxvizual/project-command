<?php if ( !is_page('kontakt') ||  !is_page('404')) { ?>

<?php
    $args = array('post_type' => 'footer', 'posts_per_page' => '1');
    $theQuery = new WP_Query($args);
?>

<footer class="custom-content bg-grey">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-11 col-md-10 col-xxl-8">
                <div class="row">
                    <div class="col-12 col-md-6 col-lg-5">
                        <img src="<?php echo get_template_directory_uri(); ?>/images/logo-echo.png">
                        <div class="swirl"></div>
                        <div class="copyrights">
                            <a href="https://www.echo.com.pl/" target="_blank">© 2018 ECHO investment S.A.</a>
                            <a href="http://republikakreatywna.pl/" target="_blank">Design by RK</a>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-7 d-lg-flex">
                        <div class="sales-offices">

                        <?php if ( $theQuery->have_posts() ) : ?>
			                <?php while ($theQuery->have_posts()) : $theQuery->the_post() ?>

                            <p><strong><?php echo get_the_title(); ?></strong></p>
                            <div class="sales-offices-address">
                                <p><?php echo get_field('address'); ?></p>
                            </div>
                            <div class="sales-offices-workhours">
                                <p><?php echo get_field('hours'); ?></p>
                            </div>
                        </div>
                        <div class="footer-contact">
                            <a href="tel:<?php echo str_replace(' ','',get_field('contact')['phone']); ?>" class="footer-contact-phone">
                                <?php echo get_field('contact')['phone']; ?>
                            </a>
                            <a href="<?php echo get_field('contact')['link']['url']; ?>" target="<?php echo get_field('contact')['link']['target']; ?>" class="footer-contact-email">
                                <?php echo get_field('contact')['link']['title']; ?>
                            </a>
                        </div>

                            <?php endwhile;  wp_reset_postdata(); ?>
                        <?php endif; ?>
                    </div>
                    <div class="col-12">
                        <hr>
                    </div>
                    <div class="col-12 col-xl-8 mx-auto">
                        <div class="footer-notice disclaimer text-center">
                            <?php _e('Prezentowane wizualizacje i rzuty służą wyłącznie do celów prezentacyjnych i nie stanowią oferty w rozumieniu kodeksu cywilnego. Podczas realizacji niektóre szczegóły mogą zostać zweryfikowane','echo'); ?>.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>

<?php } else { ?>

<footer class="custom-content bg-grey">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-11 col-md-10 col-xxl-8">
                <div class="row">
                    <div class="col-12">
                        <div class="d-flex align-items-center justify-content-between flex-column flex-md-row">
                            <div>
                                <img src="<?php echo get_template_directory_uri(); ?>/images/logo-echo.png">
                            </div>
                            <div class="d-flex align-items-md-center flex-column flex-md-row">
                                <div class="swirl m-md-0 mr-md-5"></div>
                                <div class="copyrights m-md-0">
                                    <a href="https://www.echo.com.pl/" target="_blank">© 2018 ECHO investment S.A.</a>
                                    <a href="http://republikakreatywna.pl/" target="_blank">Design by RK</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <hr>
                    </div>
                    <div class="col-12 col-xl-8 mx-auto">
                        <div class="footer-notice disclaimer text-center">
                            <?php _e('Prezentowane wizualizacje i rzuty służą wyłącznie do celów prezentacyjnych i nie stanowią oferty w rozumieniu kodeksu cywilnego. Podczas realizacji niektóre szczegóły mogą zostać zweryfikowane','echo'); ?>.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>

<?php } ?>

<div id="cookies" class="container-fluid disclaimer cookies slide-up" ng-if="showCookies" ng-class="{enabled: showCookies}">
    <div class="row justify-content-center">
        <div class="col-11 col-md-10 col-xxl-8">
            <div class="row align-items-center">
                <div class="col-12 col-xl-8">
                    <?php _e('W ramach naszej witryny stosujemy pliki cookies w celu świadczenia Państwu usług na najwyższym poziomie, w tym w sposób dostosowany do indywidualnych potrzeb. Korzystanie z witryny bez zmiany ustawień dotyczących cookies w przeglądarce oznacza, że będą one zamieszczane w Państwa urządzeniu końcowym. Możecie Państwo dokonać w każdym czasie zmiany ustawień dotyczących cookies.', 'echo'); ?>
                </div>
                <div class="col-12 col-xl-auto">
                    <div class="buttons">
                        <a href="<?php echo get_permalink(get_page_by_path('polityka-cookies')); ?>" class="btn"><?php _e('Szczegóły', 'echo'); ?></a>
                        <a href="" ng-click="hideCookies()" class="btn"><?php _e('Akceptuję', 'echo'); ?></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php wp_footer(); ?>

</body>
</html>