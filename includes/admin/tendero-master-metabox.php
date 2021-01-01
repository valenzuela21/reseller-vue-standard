<?php
if (!defined('ABSPATH')) exit;
class tendero_master_metabox{

    function __construct()
    {
        add_action('cmb2_admin_init', array($this, 'product_add_metaboxes'));
        add_action('cmb2_admin_init', array($this, 'product_add_metaboxes_product'));
    }

    public function product_add_metaboxes_product()
    {
        $prefix = "input_options_";

        $metabox_events = new_cmb2_box(array(
            "id" => $prefix . "metabox_tendero",
            "title" => __("Opciones Envio", "cmb2"),
            "object_types" => array("product")
        ));

        $metabox_events->add_field( array(
            'name'    => 'Calificación del producto',
            'desc'    =>  __( 'Ingresa la calificación del producto, Máximo 5.', 'cmb2' ),
            'default' =>  __(4, "cmb2"),
            'id'      =>  $prefix . 'rating_product',
            'type'    => 'text_small',
        ) );

    }

    public function product_add_metaboxes(){
        $prefix = "input_options_product_";

        $metabox_events = new_cmb2_box(array(
            "id" => $prefix . "metabox_tendero",
            "title" => __("Opciones Envio", "cmb2"),
            "object_types" => array("tienda")
        ));


        $metabox_events->add_field( array(
            'name' => __("Envio Gratis", "cmb2"),
            'desc' => __("Seleccione si el envio es gratis.", "cmb2"),
            'id'   => $prefix . "free",
            'type' => 'checkbox',
        ) );


        $metabox_events->add_field( array(
            'name'    => 'Valor Minimo',
            'desc'    =>  __( 'Ingresa el precio minimo de envio.', 'cmb2' ),
            'default' =>  '',
            'id'      =>  $prefix . 'pressing_fixed_tendero',
            'type'    => 'text_small',
        ) );


        $metabox_events->add_field( array(
            'name'    => __("Valor Envio Por Km", "cmb2"),
            'desc'    => __("Ingrese el valor del envio por 1Km. Ejemplo: 300", "cmb2"),
            'default' => __("", "cmb2"),
            "id" => $prefix . "presing_send",
            'type'    => 'text_small'
        ) );



        $metabox_events->add_field( array(
            'name'    => 'Correo Electrónico',
            'desc'    =>  __( 'Ingresa el precio minimo de envio.', 'cmb2' ),
            'default' =>  '',
            'id'      =>  $prefix . 'email_tendero',
            'type' => 'text_email',
        ) );


        $metabox_events->add_field( array(
            'name'    => __("Latitud", "cmb2"),
            'desc'    => __("Ingresa la latitud Ejemplo: 4.6482837", "cmb2"),
            'default' => __("", "cmb2"),
            "id" => $prefix . "latitud_reseller",
            'type'    => 'text_medium'
        ) );

        
        $metabox_events->add_field( array(
            'name'    => __("Longitud", "cmb2"),
            'desc'    => __("Ingresa la longitud Ejemplo: -74.2478943", "cmb2"),
            'default' => __("", "cmb2"),
            "id" => $prefix . "longitud_reseller",
            'type'    => 'text_medium'
        ) );

    }


}
new tendero_master_metabox();
