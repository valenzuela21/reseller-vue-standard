<?php
namespace App;

class Frontendtendero{

        public function __construct()
        {
            add_shortcode( 'shortcode-tendero-shop', [ $this, 'frontend_tendero_shop' ] );
            add_action('wp_enqueue_scripts', [$this,'style_general']);
        }


        public function style_general(){
            wp_enqueue_style('admin_css_vuetify_icon',  plugins_url('../assets/css/materialdesignicons.css', __FILE__));
            wp_enqueue_style('admin_css_vuetify', plugins_url('../assets/css/vuetify.css', __FILE__) );
        }

         /**
             * Render frontend app
             *
             * @param  array $atts
             * @param  string $content
             *
             * @return string
          */

        public function frontend_tendero_shop($atts, $content = ''){
            wp_enqueue_style( 'baseplugin-frontend-tendero' );
            wp_enqueue_script( 'baseplugin-frontend-tendero' );

            $content .= '<div id="vue-frontend-tendero"></div>';
            return $content;
        }


}