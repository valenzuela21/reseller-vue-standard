<?php
namespace App;

class AdminMetabox{

    public function __construct()
    {
        add_action( 'add_meta_boxes', [$this,'add_metabox_products'] );
        add_action( 'add_meta_boxes', [$this,'add_metabox_products_reseller'] );
        add_action( 'admin_enqueue_scripts', [$this, 'script_style_admin'], 10, 1);
    }

    public function script_style_admin(){
        wp_enqueue_style('admin_css_vuetify_icon',  plugins_url('../assets/css/materialdesignicons.css', __FILE__));
        wp_enqueue_style('admin_css_vuetify', plugins_url('../assets/css/vuetify.css', __FILE__) );
        wp_enqueue_style('admin_css_general', plugins_url('../assets/css/admin.css', __FILE__) );
    }

    public function add_metabox_products(){
        /* Postype type product, post, page or personalized*/
        /* Position metabox of Type String: normal, side or avanced */

        add_meta_box( 'tendero_meta_box', 'Tenderos', [$this, 'content_metabox_admin'], array('product'), 'normal', 'high', null );
    }

    public function add_metabox_products_reseller(){
        /* Postype type product, post, page or personalized*/
        /* Position metabox of Type String: normal, side or avanced */

        add_meta_box( 'tendero_products_meta_box', 'Productos', [$this, 'content_metabox_admin_products'], array('tienda'), 'normal', 'high', null );
    }

    public function content_metabox_admin(){
        wp_enqueue_script( 'baseplugin-adminmetabox' );
		
		if(isset($_GET['post'])){
			echo "<div id='vue-metaboxes-app'></div>";
		}else{
			echo '
			<h1 style="text-align:center">'.__('Alerta','master-tienda').'</h1>
			<p style="text-align:center; margin:auto">'.__('Para poder publicar el téndero por favor, Primeramente publicar el producto.','master-tienda').'</p>';
		}
        

    }

    public function content_metabox_admin_products(){
        wp_enqueue_script( 'baseplugin-admin-metabox-products' );
        wp_enqueue_style( 'baseplugin-admin-metabox-products' );

        echo "<div id='vue-metaboxes-products'></div>";
    }


}


