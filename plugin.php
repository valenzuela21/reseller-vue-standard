<?php
/*
Plugin Name: Master Tendero Woocommerce v2
Plugin URI: https://creatives.com.co/
Description: Component woocommerce tendero master product reseller V2
Version: 0.1
Author: David Fernando Valenzuela Pardo
Author URI: https://github.com/valenzuela21/
License: GPL2
License URI: https://creatives.com.co/
Text Domain: baseplugin
Domain Path: master-tienda
*/

// don't call the file directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Base_Plugin class
 *
 * @class Base_Plugin The class that holds the entire Base_Plugin plugin
 */
final class Base_Plugin {

    /**
     * Plugin version
     *
     * @var string
     */
    public $version = '0.1.0';

    /**
     * Holds various class instances
     *
     * @var array
     */
    private $container = array();

    /**
     * Constructor for the Base_Plugin class
     *
     * Sets up all the appropriate hooks and actions
     * within our plugin.
     */
    public function __construct() {

        $this->define_constants();

        register_activation_hook( __FILE__, array( $this, 'activate' ) );
        register_deactivation_hook( __FILE__, array( $this, 'deactivate' ) );

        add_action( 'plugins_loaded', array( $this, 'init_plugin' ) );
    }

    /**
     * Initializes the Base_Plugin() class
     *
     * Checks for an existing Base_Plugin() instance
     * and if it doesn't find one, creates it.
     */
    public static function init() {
        static $instance = false;

        if ( ! $instance ) {
            $instance = new Base_Plugin();
        }

        return $instance;
    }

    /**
     * Magic getter to bypass referencing plugin.
     *
     * @param $prop
     *
     * @return mixed
     */
    public function __get( $prop ) {
        if ( array_key_exists( $prop, $this->container ) ) {
            return $this->container[ $prop ];
        }

        return $this->{$prop};
    }

    /**
     * Magic isset to bypass referencing plugin.
     *
     * @param $prop
     *
     * @return mixed
     */
    public function __isset( $prop ) {
        return isset( $this->{$prop} ) || isset( $this->container[ $prop ] );
    }

    /**
     * Define the constants
     *
     * @return void
     */
    public function define_constants() {
        define( 'BASEPLUGIN_VERSION', $this->version );
        define( 'BASEPLUGIN_FILE', __FILE__ );
        define( 'BASEPLUGIN_PATH', dirname( BASEPLUGIN_FILE ) );
        define( 'BASEPLUGIN_INCLUDES', BASEPLUGIN_PATH . '/includes' );
        define( 'BASEPLUGIN_URL', plugins_url( '', BASEPLUGIN_FILE ) );
        define( 'BASEPLUGIN_ASSETS', BASEPLUGIN_URL . '/assets' );
    }

    /**
     * Load the plugin after all plugis are loaded
     *
     * @return void
     */
    public function init_plugin() {
        $this->includes();
        $this->init_hooks();
    }

    /**
     * Placeholder for activation function
     *
     * Nothing being called here yet.
     */
    public function activate() {

        $installed = get_option( 'baseplugin_installed' );

        if ( ! $installed ) {
            update_option( 'baseplugin_installed', time() );
        }

        update_option( 'baseplugin_version', BASEPLUGIN_VERSION );
    }

    /**
     * Placeholder for deactivation function
     *
     * Nothing being called here yet.
     */
    public function deactivate() {

    }

    /**
     * Include the required files
     *
     * @return void
     */
    public function includes() {

        require_once BASEPLUGIN_INCLUDES . '/Assets.php';
        require_once BASEPLUGIN_INCLUDES . '/functions/options_aditional.php';
        require_once BASEPLUGIN_INCLUDES . '/admin/tendero-master-proptype.php';
        require_once BASEPLUGIN_INCLUDES . '/admin/tendero-master-taxonomy.php';
        require_once BASEPLUGIN_INCLUDES . '/FrontendGeneral.php';
        require_once BASEPLUGIN_INCLUDES . '/functions/options_hooks_view.php';
        require_once BASEPLUGIN_INCLUDES . '/functions/email/email_template.php';

        //Include Plugin CMB2
        if ( $this->is_request( 'admin' ) ) {
            if (file_exists(BASEPLUGIN_INCLUDES . '/plugins/CMB2/init.php')) {
                require_once BASEPLUGIN_INCLUDES . '/plugins/CMB2/init.php';
            }

            require_once BASEPLUGIN_INCLUDES . '/admin/tendero-master-metabox.php';
        }

        if ( $this->is_request( 'frontend' ) ) {
            require_once BASEPLUGIN_INCLUDES . '/Frontend.php';
        }

        if ( $this->is_request( 'frontend' ) ) {
            require_once  BASEPLUGIN_INCLUDES . '/shortcodeSearch.php';
        }

        if( $this->is_request( 'frontend' ) ){
            require_once BASEPLUGIN_INCLUDES . '/Frontendtendero.php';
        }

        if ( $this->is_request( 'admin' ) ) {
            require_once BASEPLUGIN_INCLUDES . '/AdminMetabox.php';
        }

        if ( $this->is_request( 'ajax' ) ) {
            // require_once BASEPLUGIN_INCLUDES . '/class-ajax.php';
        }

    }

    /**
     * Initialize the hooks
     *
     * @return void
     */
    public function init_hooks() {

        add_action( 'init', array( $this, 'init_classes' ) );

        // Localize our plugin
        add_action( 'init', array( $this, 'localization_setup' ) );
    }

    /**
     * Instantiate the required classes
     *
     * @return void
     */
    public function init_classes() {


        if ( $this->is_request( 'admin' ) ) {
            $this->container['admin'] = new App\AdminMetabox();
        }


        if ( $this->is_request( 'frontend' ) ) {
            $this->container['frontend'] = new App\Frontend();
        }

        if ($this->is_request('frontend' )){
            $this->container['frontend'] = new App\Frontendtendero();
        }

        if ( $this->is_request( 'ajax' ) ) {
            // $this->container['ajax'] =  new App\Ajax();
        }

        $this->container['assets'] = new App\Assets();
        $proptype = new App\ProptypeMaster();
        $proptype->_post_type_tendero();
        $taxonomy = new App\TaxonomyTenderos();
        $taxonomy->type_tendero();
    }

    /**
     * Initialize plugin for localization
     *
     * @uses load_plugin_textdomain()
     */
    public function localization_setup() {
        load_plugin_textdomain( 'baseplugin', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
    }

    /**
     * What type of request is this?
     *
     * @param  string $type admin, ajax, cron or frontend.
     *
     * @return bool
     */
    private function is_request( $type ) {
        switch ( $type ) {
            case 'admin' :
                return is_admin();

            case 'ajax' :
                return defined( 'DOING_AJAX' );

            case 'rest' :
                return defined( 'REST_REQUEST' );

            case 'cron' :
                return defined( 'DOING_CRON' );

            case 'frontend' :
                return ( ! is_admin() || defined( 'DOING_AJAX' ) ) && ! defined( 'DOING_CRON' );
        }
    }

} // Base_Plugin

$baseplugin = Base_Plugin::init();
