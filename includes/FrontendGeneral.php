<?php
class FrontendGeneral{

    function __construct()
    {
        add_action('wp_enqueue_scripts', [$this,'quizbook_frontend_styles']);
        add_action('admin_enqueue_scripts', [$this,'quizbook_backend_styles']);
    }

    public function quizbook_frontend_styles(){
        wp_enqueue_script('script_general_params', plugins_url('../assets/js/script_params.js', __FILE__), array('jquery'), 1.0, true );
        wp_enqueue_script('script_add_cart_custom', plugins_url('../assets/js/ajax-add-to-cart.js', __FILE__), array('jquery'), 1.0, true );
        wp_enqueue_script('script_add_cart_custom_variable', plugins_url('../assets/js/ajax-add-to-cart-variable.js', __FILE__), array('jquery'), 1.0, true );

        //Reseller ID;
        $reseller_id = get_post_meta(get_the_ID(), 'meta_reseller_shopping', true);

        // Localize the script with new data
        $translation_array = array(
            'id_shopping' => get_the_ID(),
            'taxonomy' => get_query_var( 'taxonomy' ),
            'term' => get_query_var( 'term' ),
            'id_reseller' => $reseller_id[0],
        );
        wp_localize_script( 'script_general_params', 'object_param', $translation_array );

        // Enqueued script with localized data.
        wp_enqueue_script( 'script_general_params' );
    }
    public function quizbook_backend_styles()
    {
        wp_enqueue_script('script_general_params', plugins_url('../assets/js/script_params.js', __FILE__), array('jquery'), 1.0, true );
        // Localize the script with new data
        $translation_array = array(
            'id_shopping' => get_the_ID(),
        );
        wp_localize_script( 'script_general_params', 'object_param', $translation_array );

        // Enqueued script with localized data.
        wp_enqueue_script( 'script_general_params' );
    }

}
new FrontendGeneral();
