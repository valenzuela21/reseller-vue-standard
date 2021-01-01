<?php
namespace App;

class TaxonomyTenderos
{

    function type_tendero()
    {
        $labels = array(
            'name' => _x('Tipo de Tienda', 'taxonomy general name'),
            'singular_name' => _x('tipo de Tienda', 'taxonomy singular name'),
            'search_items' => __('Buscar Tienda'),
            'all_items' => __('Todos las Tindas'),
            'parent_item' => __('Tipo Tienda'),
            'parent_item_colon' => __('Tipo de Tienda:'),
            'edit_item' => __('Editar Tienda'),
            'update_item' => __('Actualizar Tienda'),
            'add_new_item' => __('Agregar Nuevo Tienda'),
            'new_item_name' => __('Nuevo Tienda'),
            'menu_name' => __('Categoria Tienda'),
        );

        $args = array(
            'hierarchical' => true,
            'labels' => $labels,
            'show_ui' => true,
            'show_admin_column' => true,
            'query_var' => true,
            'rewrite' => array('slug' => 'type-tendero'),
        );

        register_taxonomy('type-tendero', array('tienda'), $args);


    }
}

