<?php

get_header(); the_post();

set_query_var('page', get_the_ID());
get_template_part('template-parts/custom-content');

get_footer();