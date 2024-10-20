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
        $this->register_post_test_automation( $this->slug );
        $this->register_post_runner($this->runner_slug);
       
    }

    function register_post_test_automation($slug) {

        $labels = array(
            'name'                  => _x( 'AutoQA Test Automation', 'Post type general name', 'autoqa-test-automation' ),
            'singular_name'         => _x( 'AutoQA Test Automation', 'Post type singular name', 'autoqa-test-automation' ),
            'menu_name'             => _x( 'AutoQA Test Automation', 'Admin Menu text', 'autoqa-test-automation' ),
            'name_admin_bar'        => _x( 'AutoQA Test Automation', 'Add New on Toolbar', 'autoqa-test-automation' ),
            'add_new'               => __( 'Add New', 'autoqa-test-automation' ),
            'add_new_item'          => __( 'Add New AutoQA Test Automation', 'autoqa-test-automation' ),
            'new_item'              => __( 'New AutoQA Test Automation', 'autoqa-test-automation' ),
            'edit_item'             => __( 'Edit AutoQA Test Automation', 'autoqa-test-automation' ),
            'view_item'             => __( 'View AutoQA Test Automation', 'autoqa-test-automation' ),
            'all_items'             => __( 'All AutoQA Test Automation', 'autoqa-test-automation' ),
            'search_items'          => __( 'Search AutoQA Test Automation', 'autoqa-test-automation' ),
            'parent_item_colon'     => __( 'Parent AutoQA Test Automation:', 'autoqa-test-automation' ),
            'not_found'             => __( 'No AutoQA Test Automation found.', 'autoqa-test-automation' ),
            'not_found_in_trash'    => __( 'No AutoQA Test Automation found in Trash.', 'autoqa-test-automation' ),
            'featured_image'        => _x( 'AutoQA Test Automation Cover Image', 'Overrides the “Featured Image” phrase for this post type. Added in 4.3', 'autoqa-test-automation' ),
            'set_featured_image'    => _x( 'Set cover image', 'Overrides the “Set featured image” phrase for this post type. Added in 4.3', 'autoqa-test-automation' ),
            'remove_featured_image' => _x( 'Remove cover image', 'Overrides the “Remove featured image” phrase for this post type. Added in 4.3', 'autoqa-test-automation' ),
            'use_featured_image'    => _x( 'Use as cover image', 'Overrides the “Use as featured image” phrase for this post type. Added in 4.3', 'autoqa-test-automation' ),
            'archives'              => _x( 'AutoQA Test Automation archives', 'The post type archive label used in nav menus. Default “Post Archives”. Added in 4.4', 'autoqa-test-automation' ),
            'insert_into_item'      => _x( 'Insert into AutoQA Test Automation', 'Overrides the “Insert into post”/”Insert into page” phrase (used when inserting media into a post). Added in 4.4', 'autoqa-test-automation' ),
            'uploaded_to_this_item' => _x( 'Uploaded to this AutoQA Test Automation', 'Overrides the “Uploaded to this post”/”Uploaded to this page” phrase (used when viewing media attached to a post). Added in 4.4', 'autoqa-test-automation' ),
            'filter_items_list'     => _x( 'Filter AutoQA Test Automation list', 'Screen reader text for the filter links heading on the post type listing screen. Default “Filter posts list”/”Filter pages list”. Added in 4.4', 'autoqa-test-automation' ),
            'items_list_navigation' => _x( 'AutoQA Test Automation list navigation', 'Screen reader text for the pagination heading on the post type listing screen. Default “Posts list navigation”/”Pages list navigation”. Added in 4.4', 'autoqa-test-automation' ),
            'items_list'            => _x( 'AutoQA Test Automation list', 'Screen reader text for the items list heading on the post type listing screen. Default “Posts list”/”Pages list”. Added in 4.4', 'autoqa-test-automation' ),
        );
    
        $args = array(
            'labels'             => $labels,
            'public'             => false,
            'publicly_queryable' => false,
            'show_ui'            => false,
            'show_in_menu'       => false,
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

    function register_post_runner($slug) {

        $labels = array(
            'name'                  => _x( 'AutoQA Runners', 'Post type general name', 'autoqa-test-automation' ),
            'singular_name'         => _x( 'AutoQA Runners', 'Post type singular name', 'autoqa-test-automation' ),
            'menu_name'             => _x( 'AutoQA Runners', 'Admin Menu text', 'autoqa-test-automation' ),
            'name_admin_bar'        => _x( 'AutoQA Runners', 'Add New on Toolbar', 'autoqa-test-automation' ),
            'add_new'               => __( 'Add New', 'autoqa-test-automation' ),
            'add_new_item'          => __( 'Add New AutoQA Runners', 'autoqa-test-automation' ),
            'new_item'              => __( 'New AutoQA Runners', 'autoqa-test-automation' ),
            'edit_item'             => __( 'Edit AutoQA Runners', 'autoqa-test-automation' ),
            'view_item'             => __( 'View AutoQA Runners', 'autoqa-test-automation' ),
            'all_items'             => __( 'All AutoQA Runners', 'autoqa-test-automation' ),
            'search_items'          => __( 'Search AutoQA Runners', 'autoqa-test-automation' ),
            'parent_item_colon'     => __( 'Parent AutoQA Runners:', 'autoqa-test-automation' ),
            'not_found'             => __( 'No AutoQA Runners found.', 'autoqa-test-automation' ),
            'not_found_in_trash'    => __( 'No AutoQA Runners found in Trash.', 'autoqa-test-automation' ),
            'featured_image'        => _x( 'AutoQA Runners Cover Image', 'Overrides the “Featured Image” phrase for this post type. Added in 4.3', 'autoqa-test-automation' ),
            'set_featured_image'    => _x( 'Set cover image', 'Overrides the “Set featured image” phrase for this post type. Added in 4.3', 'autoqa-test-automation' ),
            'remove_featured_image' => _x( 'Remove cover image', 'Overrides the “Remove featured image” phrase for this post type. Added in 4.3', 'autoqa-test-automation' ),
            'use_featured_image'    => _x( 'Use as cover image', 'Overrides the “Use as featured image” phrase for this post type. Added in 4.3', 'autoqa-test-automation' ),
            'archives'              => _x( 'AutoQA Runners archives', 'The post type archive label used in nav menus. Default “Post Archives”. Added in 4.4', 'autoqa-test-automation' ),
            'insert_into_item'      => _x( 'Insert into AutoQA Runners', 'Overrides the “Insert into post”/”Insert into page” phrase (used when inserting media into a post). Added in 4.4', 'autoqa-test-automation' ),
            'uploaded_to_this_item' => _x( 'Uploaded to this AutoQA Runners', 'Overrides the “Uploaded to this post”/”Uploaded to this page” phrase (used when viewing media attached to a post). Added in 4.4', 'autoqa-test-automation' ),
            'filter_items_list'     => _x( 'Filter AutoQA Runners list', 'Screen reader text for the filter links heading on the post type listing screen. Default “Filter posts list”/”Filter pages list”. Added in 4.4', 'autoqa-test-automation' ),
            'items_list_navigation' => _x( 'AutoQA Runners list navigation', 'Screen reader text for the pagination heading on the post type listing screen. Default “Posts list navigation”/”Pages list navigation”. Added in 4.4', 'autoqa-test-automation' ),
            'items_list'            => _x( 'AutoQA Runners list', 'Screen reader text for the items list heading on the post type listing screen. Default “Posts list”/”Pages list”. Added in 4.4', 'autoqa-test-automation' ),
        );
    
        $args = array(
            'labels'             => $labels,
            'public'             => false,
            'publicly_queryable' => false,
            'show_ui'            => false,
            'show_in_menu'       => false,
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
            __('AutoQA', 'autoqa-test-automation'),        
            __('AutoQA', 'autoqa-test-automation'),        
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
        $flow_id = (isset($_GET['flow']))? sanitize_text_field( wp_unslash($_GET['flow']) ) : null; 
        $flow_details = null;
        if ($flow_id) {
            $flow_details = get_post($flow_id);
        }
        $this->get_view('part-tools', ['path' => 'admin', 'details' => $flow_details]);
    }

}

//register custom post type
new cauto_admin_init();