<?php
namespace App;

/**
 * Frontend Pages Handler
 */
class Frontend {

    public function __construct() {
        add_shortcode( 'shortcode-tendero-view', [ $this, 'render_frontend' ] );
        add_action('wp_enqueue_scripts', [$this,'style_general']);
    }

    public function style_general(){
        wp_enqueue_style('style_frond_vuetify_icon',  plugins_url('../assets/css/materialdesignicons.css', __FILE__));
        wp_enqueue_style('style_frond_vuetify', plugins_url('../assets/css/vuetify.css', __FILE__) );
        wp_enqueue_style('style_frond_general',  plugins_url('../assets/css/style_general.css', __FILE__));
    }

    /**
     * Render frontend app
     *
     * @param  array $atts
     * @param  string $content
     *
     * @return string
     */
    public function render_frontend( $atts, $content = '' ) {
        wp_enqueue_style( 'baseplugin-frontend' );
        wp_enqueue_script( 'baseplugin-frontend' );

        $content .= '<div id="vue-frontend-app"></div>';

        return $content;
    }


}
