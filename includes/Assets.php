<?php
namespace App;

/**
 * Scripts and Styles Class
 */
class Assets {

    function __construct() {
        add_action ('wp_head',[$this,'modal_alert_success_danger']);
        if ( is_admin() ) {
            add_action( 'admin_enqueue_scripts', [ $this, 'register' ], 5 );
        } else {
            add_action( 'wp_enqueue_scripts', [ $this, 'register' ], 5 );
        }
    }

    /**
     * Register our app scripts and styles
     *
     * @return void
     */
    public function register() {
        $this->register_scripts( $this->get_scripts() );
        $this->register_styles( $this->get_styles() );
    }

    /**
     * Register scripts
     *
     * @param  array $scripts
     *
     * @return void
     */
    private function register_scripts( $scripts ) {
        foreach ( $scripts as $handle => $script ) {
            $deps      = isset( $script['deps'] ) ? $script['deps'] : false;
            $in_footer = isset( $script['in_footer'] ) ? $script['in_footer'] : false;
            $version   = isset( $script['version'] ) ? $script['version'] : BASEPLUGIN_VERSION;

            wp_register_script( $handle, $script['src'], $deps, $version, $in_footer );
        }
    }

    /**
     * Register styles
     *
     * @param  array $styles
     *
     * @return void
     */
    public function register_styles( $styles ) {
        foreach ( $styles as $handle => $style ) {
            $deps = isset( $style['deps'] ) ? $style['deps'] : false;

            wp_register_style( $handle, $style['src'], $deps, BASEPLUGIN_VERSION );
        }
    }

    /**
     * Get all registered scripts
     *
     * @return array
     */
    public function get_scripts() {
        $prefix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '.min' : '';

        $scripts = [
            'baseplugin-runtime' => [
                'src'       => BASEPLUGIN_ASSETS . '/js/runtime.js',
                'version'   => filemtime( BASEPLUGIN_PATH . '/assets/js/runtime.js' ),
                'in_footer' => true
            ],
            'baseplugin-vendor' => [
                'src'       => BASEPLUGIN_ASSETS . '/js/vendors.js',
                'version'   => filemtime( BASEPLUGIN_PATH . '/assets/js/vendors.js' ),
                'in_footer' => true
            ],
            'baseplugin-frontend' => [
                'src'       => BASEPLUGIN_ASSETS . '/js/frontend.js',
                'deps'      => [ 'jquery', 'baseplugin-vendor', 'baseplugin-runtime' ],
                'version'   => filemtime( BASEPLUGIN_PATH . '/assets/js/frontend.js' ),
                'in_footer' => true
            ],
            'baseplugin-frontend-tendero' => [
                'src'       => BASEPLUGIN_ASSETS . '/js/frontendtendero.js',
                'deps'      => [ 'jquery', 'baseplugin-vendor', 'baseplugin-runtime' ],
                'version'   => filemtime( BASEPLUGIN_PATH . '/assets/js/frontendtendero.js' ),
                'in_footer' => true
            ],
            'baseplugin-frontend-products' => [
                'src'       => BASEPLUGIN_ASSETS . '/js/frontendproducts.js',
                'deps'      => [ 'jquery', 'baseplugin-vendor', 'baseplugin-runtime' ],
                'version'   => filemtime( BASEPLUGIN_PATH . '/assets/js/frontendproducts.js' ),
                'in_footer' => true
            ],

            'baseplugin-adminmetabox' => [
                'src'       => BASEPLUGIN_ASSETS . '/js/adminmetabox.js',
                'deps'      => [ 'jquery', 'baseplugin-vendor', 'baseplugin-runtime' ],
                'version'   => filemtime( BASEPLUGIN_PATH . '/assets/js/adminmetabox.js' ),
                'in_footer' => true
            ],
            'baseplugin-frontend-category-tendero'=>[
                'src'       => BASEPLUGIN_ASSETS . '/js/frontendcategory.js',
                'deps'      => [ 'jquery', 'baseplugin-vendor', 'baseplugin-runtime' ],
                'version'   => filemtime( BASEPLUGIN_PATH . '/assets/js/frontendcategory.js' ),
                'in_footer' => true
            ],
            'baseplugin-frontend-general-tendero'=>[
                'src'       => BASEPLUGIN_ASSETS . '/js/frontendgeneral.js',
                'deps'      => [ 'jquery', 'baseplugin-vendor', 'baseplugin-runtime' ],
                'version'   => filemtime( BASEPLUGIN_PATH . '/assets/js/frontendgeneral.js' ),
                'in_footer' => true
            ],
            'baseplugin-frontend-category-product'=>[
                'src'       => BASEPLUGIN_ASSETS . '/js/frontendcategoryproduct.js',
                'deps'      => [ 'jquery', 'baseplugin-vendor', 'baseplugin-runtime' ],
                'version'   => filemtime( BASEPLUGIN_PATH . '/assets/js/frontendcategoryproduct.js' ),
                'in_footer' => true
            ],
            'baseplugin-frontend-buttom-payment'=>[
                'src'       => BASEPLUGIN_ASSETS . '/js/frontendbuttonpayment.js',
                'deps'      => [ 'jquery', 'baseplugin-vendor', 'baseplugin-runtime' ],
                'version'   => filemtime( BASEPLUGIN_PATH . '/assets/js/frontendbuttonpayment.js' ),
                'in_footer' => true
            ],
            'baseplugin-admin-metabox-products'=>[
                'src'       => BASEPLUGIN_ASSETS . '/js/adminmetaboxproduct.js',
                'deps'      => [ 'jquery', 'baseplugin-vendor', 'baseplugin-runtime' ],
                'version'   => filemtime( BASEPLUGIN_PATH . '/assets/js/adminmetaboxproduct.js' ),
                'in_footer' => true
            ],
            'baseplugin-frontend-search'=>[
                'src'       => BASEPLUGIN_ASSETS . '/js/frontendsearch.js',
                'deps'      => [ 'jquery', 'baseplugin-vendor', 'baseplugin-runtime' ],
                'version'   => filemtime( BASEPLUGIN_PATH . '/assets/js/frontendsearch.js' ),
                'in_footer' => true
            ],
            'baseplugin-frontend-general-relations'=>[
                'src'       => BASEPLUGIN_ASSETS . '/js/frontendproductrelation.js',
                'deps'      => [ 'jquery', 'baseplugin-vendor', 'baseplugin-runtime' ],
                'version'   => filemtime( BASEPLUGIN_PATH . '/assets/js/frontendproductrelation.js' ),
                'in_footer' => true
            ],
            'baseplugin-frontend-rating-product'=>[
                'src'       => BASEPLUGIN_ASSETS . '/js/frontratingproduct.js',
                'deps'      => [ 'jquery', 'baseplugin-vendor', 'baseplugin-runtime' ],
                'version'   => filemtime( BASEPLUGIN_PATH . '/assets/js/frontratingproduct.js' ),
                'in_footer' => true
            ]
        ];

        return $scripts;
    }

    /**
     * Get registered styles
     *
     * @return array
     */
    public function get_styles() {

        $styles = [
            'baseplugin-style' => [
                'src' =>  BASEPLUGIN_ASSETS . '/css/style.css'
            ],
            'baseplugin-frontend' => [
                'src' =>  BASEPLUGIN_ASSETS . '/css/frontend.css'
            ],
            'baseplugin-frontend-tendero' => [
                'src' =>  BASEPLUGIN_ASSETS . '/css/frontendtendero.css'
            ],
            'baseplugin-frontend-products' => [
                'src' =>  BASEPLUGIN_ASSETS . '/css/frontendproducts.css'
            ],

            'baseplugin-frontend-category-tendero'=>[
                'src' =>  BASEPLUGIN_ASSETS . '/css/frontendcategory.css'
            ],
            'baseplugin-frontend-general-tendero'=>[
                'src' =>  BASEPLUGIN_ASSETS . '/css/frontendgeneral.css'
            ],
            'baseplugin-frontend-category-product'=>[
                'src' =>  BASEPLUGIN_ASSETS . '/css/frontendcategoryproduct.css'
            ],
            'baseplugin-frontend-buttom-payment' =>[
                'src' =>  BASEPLUGIN_ASSETS . '/css/frontendbuttonpayment.css'
            ],
            'baseplugin-admin-metabox-products'=>[
                'src' =>  BASEPLUGIN_ASSETS . '/css/adminmetaboxproduct.css'
            ],
             'baseplugin-frontend-search'=>[
                'src' =>  BASEPLUGIN_ASSETS . '/css/frontendsearch.css'
             ],
            'baseplugin-frontend-general-relations'=>[
                'src' =>  BASEPLUGIN_ASSETS . '/css/frontendproductrelation.css'
            ],
            'baseplugin-frontend-rating-product'=>[
                'src' =>  BASEPLUGIN_ASSETS . '/css/frontratingproduct.css'
            ]
        ];

        return $styles;
    }

    public function modal_alert_success_danger(){
        echo "<div id='danger-alert-cart'></div>";
    }

}