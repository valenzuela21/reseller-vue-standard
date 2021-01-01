<?php

namespace App;

class ProptypeMaster
{

    public function _post_type_tendero()
    {
        $labels = array(
            'name' => _x('Tienda', 'Post type general name', 'master-tienda'),
            'singular_name' => _x('Tienda', 'Post type singular name', 'master-tienda'),
            'menu_name' => _x('Tiendas', 'Admin Menu text', 'master-tienda'),
            'name_admin_bar' => _x('Tienda', 'Add New on Toolbar', 'master-tienda'),
            'add_new' => __('Nueva Tienda', 'master-tienda'),
            'add_new_item' => __('Adjuntar Nueva Tienda', 'master-tienda'),
            'new_item' => __('Nueva Tienda', 'master-tienda'),
            'edit_item' => __('Editar Tienda', 'master-tienda'),
            'view_item' => __('Ver Tienda', 'master-tienda'),
            'all_items' => __('Todos Tiendas', 'master-tienda'),
            'search_items' => __('Buscar Tiendas', 'master-tienda'),
            'parent_item_colon' => __('Padre Tiendas:', 'master-tienda'),
            'not_found' => __('No encontrados.', 'master-tienda'),
            'not_found_in_trash' => __('No encontrados.', 'master-tienda'),
            'featured_image' => _x('Imagen Destacada', '', 'master-tienda'),
            'set_featured_image' => _x('Añadir imagen destacada', '', 'master-tienda'),
            'remove_featured_image' => _x('Borrar imagen', '', 'master-tienda'),
            'use_featured_image' => _x('Usar como imagen', '', 'master-tienda'),
            'archives' => _x('Tiendas Archivo', '', 'master-tienda'),
            'insert_into_item' => _x('Insertar en Tienda', '', 'master-tienda'),
            'uploaded_to_this_item' => _x('Cargado en esta Tienda', '', 'master-tienda'),
            'filter_items_list' => _x('Filtrar Tiendas por lista', '”. Added in 4.4', 'master-tienda'),
            'items_list_navigation' => _x('Navegación de Tiendas', '', 'master-tienda'),
            'items_list' => _x('Lista de Tiendas', '', 'master-tienda'),
        );

        $args = array(
            'labels'=>$labels,
            'public' => true,
            'supports' => array( 'editor','title','thumbnail'),
            'has_archive' => true,
            'exclude_from_search' => true,
            'publicly_queryable' => true,
            'menu_position' => 6, // places menu item directly below Posts
            'taxonomies' => array( 'type-tendero' )

        );

        register_post_type('tienda', $args);

    }

}



