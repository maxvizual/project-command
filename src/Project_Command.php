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
        
        $sitename = \cli\prompt( 'Project/Site Name', '' );
        if(!$sitename) {
            WP_CLI::error( 'Project/Site Name is empty' );
        }

        $slug = str_replace( '-', ' ', strtolower($sitename) );
        
        $dbname = \cli\prompt( 'Database Name', $slug );
        $dbuser = \cli\prompt( 'Database User', 'root' );
        $dbpass = \cli\prompt( 'Database Password', '' );

        if ( ! @mkdir( $slug, 0644, true /*recursive*/ ) ) {
            $error = error_get_last();
            WP_CLI::error( sprintf( "Failed to create directory '%s': %s.", $slug, $error['message'] ) );
        }
        file_put_contents("$slug/wp-cli.yml", "path: wp\napache_modules: mod_rewrite");
        file_put_contents("$slug/index.php", "<?php\ndefine('WP_USE_THEMES', true); \nrequire(__DIR__ . '/wp/wp-blog-header.php');");
        
        @chdir($slug);
                
        //create database, download and install WordPress
        WP_CLI::runcommand( "core download" );        
        WP_CLI::runcommand( "core config --dbname=$dbname --dbuser=$dbuser --dbpass=$dbpass" );
        WP_CLI::runcommand( "db create" );  
        
         //move wp-config to root directory
         @rename('wp/wp-config.php', 'wp-config.php');         
         
         $wpurl = \cli\prompt( 'WP Url', "http://localhost/$sitename" );
         $wpuser = \cli\prompt( 'WP User', 'admin' );
         $wpuserpass = \cli\prompt( 'WP User Password', 'admin' );
         $wpuseremail = \cli\prompt( 'WP User Email', 'admin@localhost.pl' );

        //set wp-config constants
         WP_CLI::runcommand( "config set WP_DEBUG true --raw --type=constant" );       
         WP_CLI::runcommand( "config set WP_HOME  '{$wpurl}/' --raw --type=constant" );       
         WP_CLI::runcommand( "config set WP_SITEURL '{$wpurl}/wp/' --raw --type=constant" );
        
        WP_CLI::runcommand( "core install --url=\"$wpurl\" --title=\"$sitename\" --admin_user=\"$wpuser\" --admin_password=\"$wpuser\" --admin_email=\"$wpuseremail\"" );  

        //set pretty urls
        WP_CLI::runcommand( 'rewrite structure "/%postname%/" --hard' );    
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
       
        
    }
    
}