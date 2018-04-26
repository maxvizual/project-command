<?php
use \Composer\Semver\Comparator;
use \WP_CLI\Extractor;
use \WP_CLI\Utils;


/**
 * Project_Command class
 */
class Project_Command extends WP_CLI_Command {

    /**
     * init function
     *
     * @param [type] $args
     * @param [type] $assoc_args
     * @return void
     * 
     * @when before_wp_load
     */
    public function new( $args, $assoc_args ) {
        WP_CLI::line( "=======================" );
        WP_CLI::line( " WordPress Installer!! " );
        WP_CLI::line( "=======================" );

        echo WP_CLI::get_config('path');
        return false;
        
        
        $sitename = \cli\prompt( 'Project/Site Name', '' );
        if(!$sitename) {
            WP_CLI::error( 'Project/Site Name is empty' );
        }
        $dbname = \cli\prompt( 'Database Name', $sitename );
        $dbuser = \cli\prompt( 'Database User', 'root' );
        $dbpass = \cli\prompt( 'Database Password', '' );

        // if ( ! @mkdir( $sitename, 0644, true /*recursive*/ ) ) {
        //     $error = error_get_last();
        //     WP_CLI::error( sprintf( "Failed to create directory '%s': %s.", $sitename, $error['message'] ) );
        // }

        // @chdir($sitename);
        // $path = @getcwd();

                
        //create database, download and install WordPress
        WP_CLI::runcommand( "core download --path=$sitename" );        
        WP_CLI::runcommand( "core config --dbname=$dbname --dbuser=$dbuser --dbpass=$dbpass" );
        WP_CLI::runcommand( "db create" );    
        
        $wpurl = \cli\prompt( 'WP Url', "http://localhost/$sitename" );
        $wpuser = \cli\prompt( 'WP User', 'admin' );
        $wpuserpass = \cli\prompt( 'WP User Password', 'admin' );
        $wpuseremail = \cli\prompt( 'WP User Email', 'admin@localhost' );


        
        WP_CLI::runcommand( "core install --url=\"$wpurl\" --title=\"$sitename\" --admin_user=\"$wpuser\" --admin_password=\"$wpuser\" --admin_email=\"$wpuseremail\" --path=\"$sitename\"" );       
        
        //set pretty urls
        WP_CLI::runcommand( "rewrite structure '/%postname%/' --hard" );    
        WP_CLI::runcommand( "rewrite flush --hard" );    
        
        //delete akismet and hello dolly
        WP_CLI::runcommand( "plugin delete akismet" ); 
        WP_CLI::runcommand( "plugin delete hello" ); 
        WP_CLI::runcommand( "theme delete twentyfifteen" ); 
        WP_CLI::runcommand( "theme delete twentysixteen" ); 
        
        //create menu
        WP_CLI::runcommand( "menu create \"Main Navigation\"" ); 
        
        WP_CLI::success( "Installation is complete. Your username/password is listed below.");
        WP_CLI::line( "=======================" );
        WP_CLI::line( " Username: $wpuser" );
        WP_CLI::line( " Password: $wpuserpass" );
        WP_CLI::line( " Site URL: $wpurl" );
        WP_CLI::line( "=======================" );
        
        
        // WP_CLI::confirm( "co ?", $assoc_args = array() );
        // $input = \cli\input();
        // $input = \cli\prompt('db name', 'root');
        // $input = \cli\choose('choose', 'yna', 'n');
        // $menu = array(
        //     'output' => 'Output Examples',
        //     'notify' => 'cli\Notify Examples',
        //     'progress' => 'cli\Progress Examples',
        //     'table' => 'cli\Table Example',
        //     'colors' => 'cli\Colors example',
        //     'quit' => 'Quit',
        // );
        
        // $choice = \cli\menu($menu, null, 'Choose an example');
        // \cli\line();
        // \cli\line("========\nDots\n");
        // $this->test_notify(new \cli\progress\Bar('  \cli\progress\Bar displays a progress bar', 1000000));
    }
    public function test_notify(cli\Notify $notify, $cycle = 1000000, $sleep = null) {
        for ($i = 0; $i < $cycle; $i++) {
            $notify->tick();
            if ($sleep) usleep($sleep);
        }
        $notify->finish();
    }
}