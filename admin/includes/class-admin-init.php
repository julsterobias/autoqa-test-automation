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
use cauto\includes\cauto_utils;

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

class cauto_admin_init extends cauto_utils
{
    public function __construct()
    {
        add_action('init', [$this, 'prepare_post_registration']);
        add_action('admin_menu', [$this, 'load_tool']);
    }

    public function prepare_post_registration()
    {   
        $custom_posts = [
            [
                'slug'      => $this->slug,
                'label'     => "Codecorun Test Automation"
            ],
            [
                'slug'      => $this->runner_slug,
                'label'     => "Codecorun Runners"
            ]
        ];
        foreach ($custom_posts as $post) {
            $this->register_post($post['label'], $post['slug']);
        }
       
    }

    function register_post($post_type_label, $slug) {

        $labels = array(
            'name'                  => _x( $post_type_label, 'Post type general name', 'codecorun-test-automation' ),
            'singular_name'         => _x( $post_type_label, 'Post type singular name', 'codecorun-test-automation' ),
            'menu_name'             => _x( $post_type_label, 'Admin Menu text', 'codecorun-test-automation' ),
            'name_admin_bar'        => _x( $post_type_label, 'Add New on Toolbar', 'codecorun-test-automation' ),
            'add_new'               => __( 'Add New', 'codecorun-test-automation' ),
            'add_new_item'          => __( 'Add New '.$post_type_label, 'codecorun-test-automation' ),
            'new_item'              => __( 'New '.$post_type_label, 'codecorun-test-automation' ),
            'edit_item'             => __( 'Edit '.$post_type_label, 'codecorun-test-automation' ),
            'view_item'             => __( 'View '.$post_type_label, 'codecorun-test-automation' ),
            'all_items'             => __( 'All '.$post_type_label, 'codecorun-test-automation' ),
            'search_items'          => __( 'Search '.$post_type_label, 'codecorun-test-automation' ),
            'parent_item_colon'     => __( 'Parent '.$post_type_label.':', 'codecorun-test-automation' ),
            'not_found'             => __( 'No '.$post_type_label.' found.', 'codecorun-test-automation' ),
            'not_found_in_trash'    => __( 'No '.$post_type_label.' found in Trash.', 'codecorun-test-automation' ),
            'featured_image'        => _x( $post_type_label.' Cover Image', 'Overrides the “Featured Image” phrase for this post type. Added in 4.3', 'codecorun-test-automation' ),
            'set_featured_image'    => _x( 'Set cover image', 'Overrides the “Set featured image” phrase for this post type. Added in 4.3', 'codecorun-test-automation' ),
            'remove_featured_image' => _x( 'Remove cover image', 'Overrides the “Remove featured image” phrase for this post type. Added in 4.3', 'codecorun-test-automation' ),
            'use_featured_image'    => _x( 'Use as cover image', 'Overrides the “Use as featured image” phrase for this post type. Added in 4.3', 'codecorun-test-automation' ),
            'archives'              => _x( $post_type_label.' archives', 'The post type archive label used in nav menus. Default “Post Archives”. Added in 4.4', 'codecorun-test-automation' ),
            'insert_into_item'      => _x( 'Insert into '.$post_type_label, 'Overrides the “Insert into post”/”Insert into page” phrase (used when inserting media into a post). Added in 4.4', 'codecorun-test-automation' ),
            'uploaded_to_this_item' => _x( 'Uploaded to this '.$post_type_label, 'Overrides the “Uploaded to this post”/”Uploaded to this page” phrase (used when viewing media attached to a post). Added in 4.4', 'codecorun-test-automation' ),
            'filter_items_list'     => _x( 'Filter '.$post_type_label.' list', 'Screen reader text for the filter links heading on the post type listing screen. Default “Filter posts list”/”Filter pages list”. Added in 4.4', 'codecorun-test-automation' ),
            'items_list_navigation' => _x( $post_type_label.' list navigation', 'Screen reader text for the pagination heading on the post type listing screen. Default “Posts list navigation”/”Pages list navigation”. Added in 4.4', 'codecorun-test-automation' ),
            'items_list'            => _x( $post_type_label.' list', 'Screen reader text for the items list heading on the post type listing screen. Default “Posts list”/”Pages list”. Added in 4.4', 'codecorun-test-automation' ),
        );
    
        $args = array(
            'labels'             => $labels,
            'public'             => false,
            'publicly_queryable' => false,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'query_var'          => false,
            'rewrite'            => array( 'slug' => $slug ),
            'capability_type'    => 'post',
            'has_archive'        => false,
            'hierarchical'       => false,
            'menu_position'      => null,
            'supports'           => array( 'title' ),
        );
    
        register_post_type( $slug, $args );
    }

    /**
     * 
     * 
     * load_tool
     * @since 1.0.0
     * 
     * 
     */
    public function load_tool()
    {
        add_management_page(
            __('autoQA', 'codecorun-test-automation'),        
            __('autoQA', 'codecorun-test-automation'),        
            'manage_options',  
            $this->settings_page,   
            [$this, 'test_tools']      
        );
    }


    /**
     * 
     * 
     * load_assets
     * @since 1.0.0
     * 
     * 
     */
    public function test_tools()
    {
        $flow_id = (isset($_GET['flow']))? $_GET['flow'] : null; 
        $flow_details = null;
        if ($flow_id) {
            $flow_details = get_post($flow_id);
        }
        $this->get_view('part-tools', ['path' => 'admin', 'details' => $flow_details]);
    }

}

//register custom post type
new cauto_admin_init();