<?php
use \Composer\Semver\Comparator;
use \WP_CLI\Extractor;
use \WP_CLI\Utils;


/**
 * Project_Command class
 */
class Project_Command extends WP_CLI_Command {

    /**
     * new function
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
        WP_CLI::runcommand( "core download --locale=pl_PL" );        
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
    /**
     * add function
     *
     * @param [type] $args
     * @param [type] $assoc_args
     * @return void
     */
    public function add( $args, $assoc_args ) {

        if ( ! $args ) {
			WP_CLI::error( "Invalid args." );
		}

        switch($args[0]) {
            case 'typology':
                $typology_content = dirname( dirname( __FILE__ ) ) . '/templates/typology.txt';
                WP_CLI::runcommand( "post create $typology_content --post_type=page --post_title=Typology --post_status=publish" );                 
            break;
            case 'page-template':
            case 'pt':
                $theme = $this->get_theme_name(true);                
                $template_dir = get_theme_root( $theme ) . '/' . $theme;

               
                if ( !isset( $args[1]) || ! preg_match( '/^[a-z][a-z0-9\-]*$/', $args[1] ) ) {
                    WP_CLI::error( "Invalid template slug specified. Template slugs can contain only lowercase alphanumeric characters or dashes, and start with a letter." );
                }

                $slug = $args[1];

                $data = array(
                    'slug' => $slug,
                    'title_ucfirst' => ucfirst( $slug )
                );

                if(!is_dir("$template_dir/page-templates")){
                    @mkdir("$template_dir/page-templates", 0777, true);
                }
                if(!is_dir("$template_dir/assets/src/scss/page-templates")){
                    @mkdir("$template_dir/assets/src/scss/page-templates", 0777, true);
                }

                $files_written = $this->create_files( array(
                    "$template_dir/page-templates/$slug-page.php" => self::mustache_render( 'page-template-php.mustache', $data ),
                    "$template_dir/assets/src/scss/page-templates/_$slug.scss" => self::mustache_render( 'page-template-scss.mustache', $data ),
                ), false );

            break;
            case 'template-part':
            case 'tp':
                $theme = $this->get_theme_name(true);                
                $template_dir = get_theme_root( $theme ) . '/' . $theme;

            
                if ( !isset( $args[1]) || ! preg_match( '/^[a-z][a-z0-9\-]*$/', $args[1] ) ) {
                    WP_CLI::error( "Invalid template slug specified. Template slugs can contain only lowercase alphanumeric characters or dashes, and start with a letter." );
                }

                $slug = $args[1];

                $data = array(
                    'slug' => $slug,
                    'title_ucfirst' => ucfirst( $slug )
                );

                if(!is_dir("$template_dir/template-parts")){
                    @mkdir("$template_dir/template-parts", 0777, true);
                }
                if(!is_dir("$template_dir/assets/src/scss/template-parts")){
                    @mkdir("$template_dir/assets/src/scss/template-parts", 0777, true);
                }

                $files_written = $this->create_files( array(
                    "$template_dir/template-parts/$slug-page.php" => self::mustache_render( 'template-part-php.mustache', $data ),
                    "$template_dir/assets/src/scss/template-parts/_$slug.scss" => self::mustache_render( 'template-part-scss.mustache', $data ),
                ), false );
            break;
        }
        
    }

    protected function create_dir($dir) {
		$wp_filesystem = $this->init_wp_filesystem();
		$wp_filesystem->mkdir( dirname( $dir ) );
	}


    protected function create_files( $files_and_contents, $force ) {
		$wp_filesystem = $this->init_wp_filesystem();
		$wrote_files = array();

		foreach ( $files_and_contents as $filename => $contents ) {
			$should_write_file = $this->prompt_if_files_will_be_overwritten( $filename, $force );
			if ( ! $should_write_file ) {
				continue;
			}

			$wp_filesystem->mkdir( dirname( $filename ) );

			if ( ! $wp_filesystem->put_contents( $filename, $contents ) ) {
				WP_CLI::error( "Error creating file: $filename" );
			} elseif ( $should_write_file ) {
				$wrote_files[] = $filename;
			}
		}
		return $wrote_files;
    }
    
    protected function init_wp_filesystem() {
		global $wp_filesystem;
		WP_Filesystem();

		return $wp_filesystem;
	}

    private function get_output_path( $assoc_args, $subdir ) {
		if ( $assoc_args['theme'] ) {
			$theme = $assoc_args['theme'];
			if ( is_string( $theme ) ) {
				$path = get_theme_root( $theme ) . '/' . $theme;
			} else {
				$path = get_stylesheet_directory();
			}
			if ( ! is_dir( $path ) ) {
				WP_CLI::error( "Can't find '$theme' theme." );
			}
		} elseif ( $assoc_args['plugin'] ) {
			$plugin = $assoc_args['plugin'];
			$path   = WP_PLUGIN_DIR . '/' . $plugin;
			if ( ! is_dir( $path ) ) {
				WP_CLI::error( "Can't find '$plugin' plugin." );
			}
		} else {
			return false;
		}

		$path .= $subdir;

		return $path;
    }
    
    protected function prompt_if_files_will_be_overwritten( $filename, $force ) {
		$should_write_file = true;
		if ( ! file_exists( $filename ) ) {
			return true;
		}

		WP_CLI::warning( 'File already exists.' );
		WP_CLI::log( $filename );
		if ( ! $force ) {
			do {
				$answer = cli\prompt(
					'Skip this file, or replace it with scaffolding?',
					$default = false,
					$marker = '[s/r]: '
				);
			} while ( ! in_array( $answer, array( 's', 'r' ) ) );
			$should_write_file = 'r' === $answer;
		}

		$outcome = $should_write_file ? 'Replacing' : 'Skipping';
		WP_CLI::log( $outcome . PHP_EOL );

		return $should_write_file;
	}

    private function generate_machine_name( $slug ) {
		return str_replace( '-', '_', $slug );
	}

    private function get_theme_name( $theme ) {
		if ( true === $theme ) {
			$theme = wp_get_theme()->template;
		}
		return strtolower( $theme );
    }
    
    
    private static function mustache_render( $template, $data = array() ) {
		return Utils\mustache_render( dirname( dirname( __FILE__ ) ) . '/templates/' . $template, $data );
    }
    
}