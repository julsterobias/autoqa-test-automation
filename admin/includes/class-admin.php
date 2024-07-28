<?php
/**
 * 
 * 
 * class_admin
 * @since 1.0.0
 * 
 * 
 */
namespace cauto\admin\includes;
use cauto\includes\class_utils;
use cauto\admin\includes\class_admin_init;
use cauto\admin\includes\class_admin_ui;

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

class class_admin extends class_utils
{

    public function __construct()
    {
        //register custom post type
        new class_admin_init();

        add_action('admin_enqueue_scripts', [$this, 'load_assets']);
        add_action('admin_menu', [$this, 'load_tool']);
        
        //admin UIs
        new class_admin_ui;
        
    }

    /**
     * 
     * 
     * load_assets
     * @since 1.0.0
     * 
     * 
     */
    public function load_assets()
    {
        wp_register_script('cauto-admin-js', CAUTO_PLUGIN_URL.'admin/assets/admin.js', ['jquery'], null );
        wp_enqueue_script('cauto-admin-js');
        wp_enqueue_style('cauto-admin-css', CAUTO_PLUGIN_URL.'admin/assets/admin.css' , [], null);
    }

     /**
     * 
     * 
     * load_assets
     * @since 1.0.0
     * 
     * 
     */
    public function load_tool()
    {
        add_management_page(
            __('Test Automation', 'codecorun_automation'),        
            __('Test Automation', 'codecorun_automation'),        
            'manage_options',  
            'test-tools',   
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
        $this->get_view('part-tools', ['path' => 'admin']);
    }
}