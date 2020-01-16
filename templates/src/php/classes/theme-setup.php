<?php 

class ThemeSetup
{

    public $options = null;
    public static $instance;


    public function __construct()
    {


        $this->options = new ThemeOptions();
        $this->setup();

        add_action('after_setup_theme', array($this, 'after_setup_theme'));
        add_action('wp_head', array($this, 'append_head'));
        add_action('wp_body', array($this, 'append_body'));
        add_action('admin_menu', array($this, 'custom_menu_page_removing'));
        add_action('wp_enqueue_scripts', array($this, 'theme_scripts_styles'));
    }

    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    function setup()
    {

        
        if (function_exists('acf_add_options_page')) {
            acf_add_options_page(array(
                'page_title'    => 'Ustawienia Motywu',
                'menu_title'    => 'Ustawienia Motywu',
                'menu_slug'     => 'theme-general-settings',
                'capability'    => 'edit_posts',
                'redirect'      => false
            ));
        }
        show_admin_bar(false);
    }
    
    function after_setup_theme()
    {
        load_theme_textdomain('echo', get_template_directory() . '/languages');
        add_theme_support('title-tag');
        add_theme_support('post-thumbnails');
        register_nav_menus(array(
            'top'    => __('Top Menu')
        ));
    }


    function append_head()
    {

        $google_api = $this->options->googleApi;
        if ($this->options->isProduction) {
            echo $google_api['gtm_head'];
        }
    }

    function append_body()
    {

        $google_api = $this->options->googleApi;
        if ($this->options->isProduction) {
            echo $google_api['gtm_body'];
        }
    }


    function custom_menu_page_removing()
    {

        remove_menu_page('edit-comments.php');
        remove_menu_page('edit.php');
    }

    function theme_scripts_styles()
    {

        $js = get_theme_file_uri('/script.min.js');
        $js_lib = get_theme_file_uri('/script_lib.min.js');

        $vars = array(
            'home_url' => trailingslashit(home_url()),
            'ajax_url' => admin_url('admin-ajax.php'),
            'l10n'     => array()
        );

        wp_enqueue_style('style', get_theme_file_uri('/style.css'), array(), $this->get_file_version('/style.css'));

        wp_register_script('script-lib', $js_lib, array('jquery'), $this->get_file_version('/script-lib.min.js'), true);
        wp_enqueue_script('script', $js, array('script-lib'), $this->get_file_version('/script.min.js'), true);
        wp_localize_script('script', '__jsVars', apply_filters('theme/localize/script', $vars));
    }

    function get_file_version($filename)
    {
        if (!$this->options->isProduction) {
            return 'dev';
        }
        return filemtime(get_theme_file_path($filename));
    }
}
