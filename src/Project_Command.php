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

        $slug = str_replace( ' ', '-', strtolower($sitename) );
        
        $dbname = \cli\prompt( 'Database Name', $slug );
        $dbuser = \cli\prompt( 'Database User', 'root' );
        $dbpass = \cli\prompt( 'Database Password', '' );

        if ( ! @mkdir( $slug, 0644, true /*recursive*/ ) ) {
            $error = error_get_last();
            WP_CLI::error( sprintf( "Failed to create directory '%s': %s.", $slug, $error['message'] ) );
        }
        if ( ! @mkdir( "$slug/app/mu-plugins", 0644, true /*recursive*/ ) ) {
            $error = error_get_last();
            WP_CLI::error( sprintf( "Failed to create directory '%s': %s.", $slug, $error['message'] ) );
        }
        file_put_contents("$slug/wp-cli.yml", "path: wp\napache_modules: mod_rewrite");
        file_put_contents("$slug/index.php", "<?php\ndefine('WP_USE_THEMES', true); \nrequire(__DIR__ . '/wp/wp-blog-header.php');");
        file_put_contents("$slug/README.md", self::mustache_render( 'README.md.mustache', array() ));
        file_put_contents("$slug/app/mu-plugins/mu-autoloader.php", self::mustache_render( 'mu-autoloader.php.mustache', array() ));
        
        @chdir($slug);
                     
        //create database, download and install WordPress
        WP_CLI::runcommand( "core download" );        
        WP_CLI::runcommand( "core config --dbname=$dbname --dbuser=$dbuser --dbpass=$dbpass" );
        WP_CLI::runcommand( "db create" );  
        
         //move wp-config to root directory
         @rename('wp/wp-config.php', 'wp-config.php');         
         
         $wpurl = \cli\prompt( 'WP Url', "http://localhost/$slug" );
         $wpuser = \cli\prompt( 'WP User', 'admin' );
         $wpuserpass = \cli\prompt( 'WP User Password', 'admin' );
         $wpuseremail = \cli\prompt( 'WP User Email', 'admin@localhost.pl' );

        //set wp-config constants
        WP_CLI::runcommand( "config set WP_DEBUG true --raw --type=constant" );       
        WP_CLI::runcommand( "config set WP_HOME  '{$wpurl}' --raw --type=constant" );       
        WP_CLI::runcommand( "config set WP_SITEURL '{$wpurl}/wp/' --raw --type=constant" );
        WP_CLI::runcommand( "config set WP_CONTENT_DIR \"dirname(__FILE__) . '/app'\" --raw --type=constant");
        WP_CLI::runcommand( "config set WP_CONTENT_URL \"WP_HOME . '/app'\" --raw --type=constant");
        
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
        WP_CLI::runcommand( "menu create \"Top Menu\"" ); 
        
        WP_CLI::success( "Installation is complete. Your username/password is listed below.");
        WP_CLI::line( "=======================" );
        WP_CLI::line( " Username: $wpuser" );
        WP_CLI::line( " Password: $wpuserpass" );
        WP_CLI::line( " Site URL: $wpurl" );
        WP_CLI::line( "=======================" );    
        
        $ask_theme = \cli\prompt( 'Create Theme [Y/n]?', "Y" );

        if(strtolower($ask_theme) == 'y') {
            $theme_name = \cli\prompt( 'Theme name', $slug );
            WP_CLI::runcommand( "project add theme $theme_name" ); 
        }
        
    }
   
    /**
     * install function
     *
     * @param [type] $args
     * @param [type] $assoc_args
     * @return void
     * 
     * @when before_wp_load
     */
    public function install( $args, $assoc_args ) {

        $this->get_config();
        
        WP_CLI::line( "=======================" );
        WP_CLI::line( " WordPress Installer!! " );
        WP_CLI::line( "=======================" );  

        $slug = basename ( getcwd() );

        $sitename = \cli\prompt( 'Project/Site Name', $slug );        
        $dbname = \cli\prompt( 'Database Name', $slug );
        $dbuser = \cli\prompt( 'Database User', 'root' );
        $dbpass = \cli\prompt( 'Database Password', '' );
       
        if(!file_exists("wp-cli.yml")){
            file_put_contents("wp-cli.yml", "path: wp\napache_modules: mod_rewrite");
        }
        if(!file_exists("index.php")){
            file_put_contents("index.php", "<?php\ndefine('WP_USE_THEMES', true); \nrequire(__DIR__ . '/wp/wp-blog-header.php');");
        }
        
                        
        // //create database, download and install WordPress
        WP_CLI::runcommand( "core download" );        
        WP_CLI::runcommand( "core config --dbname=$dbname --dbuser=$dbuser --dbpass=$dbpass" );
        WP_CLI::runcommand( "db create" );  
        
         //move wp-config to root directory
         @rename('wp/wp-config.php', 'wp-config.php');         
         
        $wpurl = \cli\prompt( 'WP Url', "http://localhost/$slug" );
        $wpuser = \cli\prompt( 'WP User', 'admin' );
        $wpuserpass = \cli\prompt( 'WP User Password', 'admin' );
        $wpuseremail = \cli\prompt( 'WP User Email', 'admin@localhost.pl' );

        //set wp-config constants
        WP_CLI::runcommand( "config set WP_DEBUG true --raw --type=constant" );       
        WP_CLI::runcommand( "config set WP_HOME  '{$wpurl}/' --raw --type=constant" );       
        WP_CLI::runcommand( "config set WP_SITEURL '{$wpurl}/wp/' --raw --type=constant" );

        WP_CLI::runcommand( "config set WP_CONTENT_DIR \"dirname(__FILE__) . '/app'\" --raw --type=constant --add");
        WP_CLI::runcommand( "config set WP_CONTENT_URL \"WP_HOME . '/app'\" --raw --type=constant --add");

                
        WP_CLI::runcommand( "core install --url=\"$wpurl\" --title=\"$sitename\" --admin_user=\"$wpuser\" --admin_password=\"$wpuser\" --admin_email=\"$wpuseremail\" --skip-plugins" );  

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
     * wpsdb function create profiles
     *
     * @param [type] $args
     * @param [type] $assoc_args
     * @return void
     */
    public function wpsdb( $args, $assoc_args ) {
        $profiles = $this->wpsdb_create_profiles();
        $this->wpsdb_set_settings($profiles);
    }
    /**
     * wpsdb function create profiles
     *
     * @param [type] $args
     * @param [type] $assoc_args
     * @return void
     */
    public function migrate( $args, $assoc_args ) {
        
        $wpsdb_settings = get_option( 'wpsdb_settings');
        
        if(!$wpsdb_settings){
            $this->wpsdb(null, null);
            $wpsdb_settings = get_option( 'wpsdb_settings');
        }

        WP_CLI::line( "=======================" );
        if($wpsdb_settings['profiles']) {
            foreach($wpsdb_settings['profiles'] as $key=>$profile){
                WP_CLI::line( $profile['name'] . ' ID:' . ($key+1) );
            }
        }
        WP_CLI::line( "=======================" );
        
        $profile_id = \cli\prompt( 'Select Profile ID', '' );

        if(!isset($wpsdb_settings['profiles'][$profile_id-1])){
            WP_CLI::error( "Brak profilu.");
        }

        $profile_name = $wpsdb_settings['profiles'][$profile_id-1]['name'];
       
        WP_CLI::line( "Migracja profilu: {$profile_name}" );
        WP_CLI::runcommand( "wpsdb migrate {$profile_id}" );

    }
    /**
     * add function
     *
     * @param [type] $args
     * @param [type] $assoc_args
     * @return void
     */
    public function add( $args, $assoc_args ) {

        WP_CLI::line( "=========== add {$args[0]} ============" );

        if ( ! $args ) {
			WP_CLI::error( "Invalid args." );
        }
        
        $cwd =  getcwd(); 
        $base = $cwd; 
        $base_src = "$cwd/src"; 

        // nie wymaga slug w parametrze 
        $skip_slug = array('src' , 'log');

        if(!in_array($args[0], $skip_slug )){
            if (!isset( $args[1]) || ! preg_match( '/^[a-z][a-z0-9\-_]*$/', $args[1] ) ) {
                WP_CLI::error( "Invalid slug specified. Template slugs can contain only lowercase alphanumeric characters or dashes, and start with a letter." );
            }
        }

        $slug = isset( $args[1]) ? $args[1] : '';

        $data = array(
            'slug' => $slug,
            'title_ucfirst' => ucfirst( $slug )
        );

        $force = isset($assoc_args['force']);

        switch($args[0]) {
            case 'theme': 

                $theme_root = get_theme_root();
                $theme_path = "$theme_root/$slug";

                if(!is_dir($theme_path)){
                    @mkdir($theme_path, 0777, true);
                }

                $files_written = $this->create_files( array(
                    "$theme_path/header.php" => self::mustache_render( 'src/php/header.php.mustache', $data ),
                    "$theme_path/footer.php" => self::mustache_render( 'src/php/footer.php.mustache', $data ),
                    "$theme_path/index.php" => self::mustache_render( 'src/php/index.php.mustache', $data ),
                    "$theme_path/functions.php" => self::mustache_render( 'src/php/functions.php.mustache', $data ),
                    "$theme_path/inc/class-wp-bootstrap-navwalker.php" => self::read_file( 'src/php/inc/class-wp-bootstrap-navwalker.php.mustache', $data ),
                    "$theme_path/inc/enqueue-scripts-styles.php" => self::read_file( 'src/php/inc/enqueue-scripts-styles.php.mustache', $data ),
                    "$theme_path/inc/custom-post-types.php" => self::read_file( 'src/php/inc/custom-post-types.php.mustache', $data ),
                    "$theme_path/inc/helpers-functions.php" => self::read_file( 'src/php/inc/helpers-functions.php.mustache', $data ),
                    "$theme_path/inc/setup-theme.php" => self::read_file( 'src/php/inc/setup-theme.php.mustache', $data ),
                    "$theme_path/inc/shortcodes.php" => self::read_file( 'src/php/inc/shortcodes.php.mustache', $data ),
                    "$theme_path/style.css" => self::mustache_render( 'src/css/style.css.mustache', $data ),
                    "$theme_path/script.js" => self::mustache_render( 'src/js/script.js.mustache', $data ),
                ), $force );

                WP_CLI::runcommand( "theme activate $slug" ); 
                WP_CLI::runcommand( "project add src" ); 
                WP_CLI::runcommand( "project add log" ); 
              

            break;
            case 'log':
                $files_written = $this->create_files( array(
                    "log.php" => self::mustache_render( 'log.php.mustache', array() ),   
                ), $force );
            break;
            case 'src':  
               
                $dir = dirname( dirname( __FILE__ ) ) . '/templates/';
                $files = self::find_all_files($dir . 'src');
                $dirs = array_diff(scandir($dir . 'src'), array('.','..'));
                
                if(!is_dir($base_src)){
                    @mkdir($base_src, 0777, true);
                    foreach($dirs as $d) {
                        @mkdir("$base_src/$d/", 0777, true);
                    }
                }else{
                    // WP_CLI::error( "Directory src exists." );
                }

                $slug = $this->get_theme_name(true);

                $data = array(
                    'slug' => $slug,
                    'title_ucfirst' => ucfirst( $slug ),
                    'theme_name' => $slug,
                    'home_url' => htmlentities( home_url() ),
                );

                $files_array = array(
                    "package.json" => self::mustache_render( 'package.json.mustache', $data ),
                    "gulpfile.js" => self::mustache_render( 'gulpfile.js.mustache', $data ),
                    "config.json" => self::mustache_render( 'config.json.mustache', $data ),
                    ".git-ftp-ignore" => self::mustache_render( '.git-ftp-ignore.mustache', $data ),
                    ".git-ftp-include" => self::mustache_render( '.git-ftp-include.mustache', $data ),
                    ".gitignore" => self::mustache_render( '.gitignore.mustache', $data ),
                    "README.md" => self::mustache_render( 'README.md.mustache',  $data  ),
                );
                foreach($files as $file){
                    $file_template = str_replace( $dir, '', $file);
                    $file_target = str_replace( '.mustache', '', $file_template);
                    $files_array["$base/$file_target"] = self::mustache_render($file_template, $data );
                }
                
                $files_written = $this->create_files( $files_array, $force );

            break;
            case 'typology':
                $typology_content = dirname( dirname( __FILE__ ) ) . '/templates/typology.txt';
                WP_CLI::runcommand( "post create $typology_content --post_type=page --post_title=Typology --post_status=publish" );                 
            break;
            case 'page-template':
            case 'pt': 

                if(!is_dir("$base_src/php/page-templates")){
                    @mkdir("$base_src/php/page-templates", 0777, true);
                }
                if(!is_dir("$base_src/scss/page-templates")){
                    @mkdir("$base_src/scss/page-templates", 0777, true);
                }

                $files_written = $this->create_files( array(
                    "$base_src/php/page-templates/$slug-page.php" => self::mustache_render( 'page-template.php.mustache', $data ),
                    "$base_src/scss/page-templates/_$slug.scss" => self::mustache_render( 'page-template.scss.mustache', $data ),
                ), false );

                $code = "@import 'page-templates/$slug';";
                $file = "$base_src/scss/style.scss";                
                $this->append_to_file($file, $code, 'pt');

            break;
            case 'template-part':
            case 'tp':

                if(!is_dir("$base_src/php/template-parts")){
                    @mkdir("$base_src/php/template-parts", 0777, true);
                }
                if(!is_dir("$base_src/scss/template-parts")){
                    @mkdir("$base_src/scss/template-parts", 0777, true);
                }

                $files_written = $this->create_files( array(
                    "$base_src/php/template-parts/$slug.php" => self::mustache_render( 'template-part.php.mustache', $data ),
                    "$base_src/scss/template-parts/_$slug.scss" => self::mustache_render( 'template-part.scss.mustache', $data ),
                ), false );

                $code = "@import 'template-parts/$slug';";
                $file = "$base_src/scss/style.scss";                
                $this->append_to_file($file, $code, 'tp');

            break;
            case 'part':
                if(!is_dir("$base_src/scss/partials")){
                    @mkdir("$base_src/scss/partials", 0777, true);
                }
                $files_written = $this->create_files( array(                    
                    "$base_src/scss/partials/_$slug.scss" => self::mustache_render( 'template-part.scss.mustache', $data ),
                ), false );
                $code = "@import 'partials/$slug';";
                $file = "$base_src/scss/style.scss";                
                $this->append_to_file($file, $code, 'part');
            break;
            case 'cpt':
                $code = self::mustache_render( 'template-custom_post_type.mustache', $data );
                $file = "$base_src/php/inc/custom-post-types.php";                
                $this->append_to_file($file, $code, 'cpt');
            break;
            case 'tax':
                if ( !isset( $args[2]) || ! preg_match( '/^[a-z][a-z0-9\-]*$/', $args[2] ) ) {
                    WP_CLI::error( "Invalid post_type specified. Template post_type can contain only lowercase alphanumeric characters or dashes, and start with a letter." );
                }

                $data['post_type'] = $args[2];

                $code = self::mustache_render( 'template-taxonomy.mustache', $data );
                $file = "$base_src/php/inc/custom-post-types.php";
                $this->append_to_file($file, $code, 'tax');    
            break;
            case 'sc':
                $code = self::mustache_render( 'template-shortcode.mustache', $data );
                $file = "$base_src/php/inc/shortcodes.php";                
                $this->append_to_file($file, $code, 'sc');
            break;
        }
        
    }

    protected function append_to_file($file, $code, $type) {
        $file_content = file_get_contents($file);
        if(false !== ($content = $this->append_content($file_content, $code,  $type)) ){
            file_put_contents($file, $content);
        }
    }

    protected function append_content($subject, $code, $type) {
        $inject = "/* [inject $type] */";
        $split = preg_split("/(\/.+inject\s$type.+\/)/", $subject);
        if(count($split) == 2) {
            return $split[0] . $code . "\n$inject\n" . $split[1];
        }else {
            WP_CLI::error( "$inject can not be found" );
        }
        return false;
    }
    protected function wpsdb_create_profiles() {
        
        
        $profiles = array();
        $this->get_config();
        
        if(isset($this->config->wpsdb->profiles) && is_array($this->config->wpsdb->profiles)){            

            $home_url = preg_replace( '#^https?:#', '', htmlentities( home_url() ) ); 
            $absolute_path = rtrim( ABSPATH, '\\/' );

            foreach($this->config->wpsdb->profiles as $profile){
                $profiles[] = array (
                    'save_computer' => '1',
                    'gzip_file' => '1',
                    'replace_guids' => '1',
                    'exclude_spam' => '0',
                    'keep_active_plugins' => '0',
                    'create_backup' => '0',
                    'exclude_post_types' => '0',
                    'action' => 'pull',
                    'connection_info' => $profile->connection_info,
                    'replace_old' => $profile->replace_old,
                    'replace_new' => array (
                        1 => $home_url,
                        2 => $absolute_path,
                    ),
                    'table_migrate_option' => 'migrate_only_with_prefix',
                    'exclude_transients' => '1',
                    'backup_option' => 'backup_only_with_prefix',
                    'save_migration_profile' => '1',
                    'save_migration_profile_option' => 'new',
                    'create_new_profile' => $profile->name,
                    'name' => $profile->name,
                );
            }
        }


        return $profiles;
    }
    //WP DB SYNC set settings
    protected function wpsdb_set_settings($profiles=array()) {
        $default_settings = array(
            'max_request' => 1048576,
			'key'  => $this->generate_key(),
			'allow_pull' => false,
			'allow_push' => false,
			'profiles'  => $profiles,
			'verify_ssl'	=> false,
			'blacklist_plugins' => array(),
        );
        
        update_option( 'wpsdb_settings', $default_settings );
    }
    // Generates our secret key
	protected function generate_key() {
		$keyset = 'abcdefghijklmnopqrstuvqxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789+/';
		$key = '';
		for ( $i = 0; $i < 32; $i++ ) {
			$key .= substr( $keyset, rand( 0, strlen( $keyset ) -1 ), 1 );
		}
		return $key;
	}

    protected function get_config() {
        $this->config = json_decode(file_get_contents('config.json'));

        return $this->config;
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

    private static function read_file( $template ) {
		return file_get_contents( dirname( dirname( __FILE__ ) ) . '/templates/' . $template );
    }

    private static function find_all_files($dir) { 
        $root = scandir($dir); 
        $result = [];
        foreach($root as $value) { 
            if($value === '.' || $value === '..') {continue;} 
            if(is_file("$dir/$value")) {$result[]="$dir/$value";continue;} 
            foreach(self::find_all_files("$dir/$value") as $value) { 
                $result[]=$value; 
            } 
        } 
        return $result; 
    } 
    
}
