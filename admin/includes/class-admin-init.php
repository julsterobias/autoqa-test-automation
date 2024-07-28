<?php
/**
 * 
 * 
 * class_admin_init
 * where you register all you need in the admin such as post type, user type and others.
 * @since 1.0.0
 * 
 * 
 */
namespace cauto\admin\includes;
use cauto\includes\class_utils;

if ( !function_exists( 'add_action' ) ){
    header( 'Status: 403 Forbidden' );
    header( 'HTTP/1.1 403 Forbidden' );
    exit();
}

if ( !function_exists( 'add_filter' ) ){
    header( 'Status: 403 Forbidden' );
    header( 'HTTP/1.1 403 Forbidden' );
    exit();
}

class class_admin_init extends class_utils
{
    public function __construct()
    {
        add_action('init', [$this, 'register_post_type']);
    }

    function register_post_type() {
        $labels = array(
            'name'                  => _x( 'Codecorun Test Automation', 'Post type general name', 'codecorun-test-automation' ),
            'singular_name'         => _x( 'Codecorun Test Automation', 'Post type singular name', 'codecorun-test-automation' ),
            'menu_name'             => _x( 'Codecorun Test Automations', 'Admin Menu text', 'codecorun-test-automation' ),
            'name_admin_bar'        => _x( 'Codecorun Test Automation', 'Add New on Toolbar', 'codecorun-test-automation' ),
            'add_new'               => __( 'Add New', 'codecorun-test-automation' ),
            'add_new_item'          => __( 'Add New Codecorun Test Automation', 'codecorun-test-automation' ),
            'new_item'              => __( 'New Codecorun Test Automation', 'codecorun-test-automation' ),
            'edit_item'             => __( 'Edit Codecorun Test Automation', 'codecorun-test-automation' ),
            'view_item'             => __( 'View Codecorun Test Automation', 'codecorun-test-automation' ),
            'all_items'             => __( 'All Codecorun Test Automation', 'codecorun-test-automation' ),
            'search_items'          => __( 'Search Codecorun Test Automation', 'codecorun-test-automation' ),
            'parent_item_colon'     => __( 'Parent Codecorun Test Automation:', 'codecorun-test-automation' ),
            'not_found'             => __( 'No Codecorun Test Automation found.', 'codecorun-test-automation' ),
            'not_found_in_trash'    => __( 'No Codecorun Test Automation found in Trash.', 'codecorun-test-automation' ),
            'featured_image'        => _x( 'Codecorun Test Automation Cover Image', 'Overrides the “Featured Image” phrase for this post type. Added in 4.3', 'codecorun-test-automation' ),
            'set_featured_image'    => _x( 'Set cover image', 'Overrides the “Set featured image” phrase for this post type. Added in 4.3', 'codecorun-test-automation' ),
            'remove_featured_image' => _x( 'Remove cover image', 'Overrides the “Remove featured image” phrase for this post type. Added in 4.3', 'codecorun-test-automation' ),
            'use_featured_image'    => _x( 'Use as cover image', 'Overrides the “Use as featured image” phrase for this post type. Added in 4.3', 'codecorun-test-automation' ),
            'archives'              => _x( 'Codecorun Test Automation archives', 'The post type archive label used in nav menus. Default “Post Archives”. Added in 4.4', 'codecorun-test-automation' ),
            'insert_into_item'      => _x( 'Insert into Codecorun Test Automation', 'Overrides the “Insert into post”/”Insert into page” phrase (used when inserting media into a post). Added in 4.4', 'codecorun-test-automation' ),
            'uploaded_to_this_item' => _x( 'Uploaded to this Codecorun Test Automation', 'Overrides the “Uploaded to this post”/”Uploaded to this page” phrase (used when viewing media attached to a post). Added in 4.4', 'codecorun-test-automation' ),
            'filter_items_list'     => _x( 'Filter Codecorun Test Automations list', 'Screen reader text for the filter links heading on the post type listing screen. Default “Filter posts list”/”Filter pages list”. Added in 4.4', 'codecorun-test-automation' ),
            'items_list_navigation' => _x( 'Codecorun Test Automations list navigation', 'Screen reader text for the pagination heading on the post type listing screen. Default “Posts list navigation”/”Pages list navigation”. Added in 4.4', 'codecorun-test-automation' ),
            'items_list'            => _x( 'Codecorun Test Automations list', 'Screen reader text for the items list heading on the post type listing screen. Default “Posts list”/”Pages list”. Added in 4.4', 'codecorun-test-automation' ),
        );
    
        $args = array(
            'labels'             => $labels,
            'public'             => false,
            'publicly_queryable' => false,
            'show_ui'            => false,
            'show_in_menu'       => false,
            'query_var'          => false,
            'rewrite'            => array( 'slug' => $this->slug ),
            'capability_type'    => 'post',
            'has_archive'        => false,
            'hierarchical'       => false,
            'menu_position'      => null,
            'supports'           => array( 'title' ),
        );
    
        register_post_type( $this->slug, $args );
    }

}