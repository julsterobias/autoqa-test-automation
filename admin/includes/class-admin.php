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


        //prepare UI
        add_action('cauto_top_control', [$this, 'load_top_controls']);
        //load new flow
        add_action('cauto-load-new-flow', [$this, 'load_new_flow']);
    }

    public function load_top_controls()
    {
        $controls = [
            [
                'attr'  => [
                    "class"     => "cauto-top-class cauto-button primary caut-ripple",
                    "id"        => "cauto-new-case"
                ],
                'label' => __('New Test Flow', 'codecorun-test-automation'),
                'icon'  => '<span class="dashicons dashicons-insert"></span>'
            ],
            [
                'attr'  => [
                    "class" => "cauto-top-class cauto-button caut-ripple",
                    "id"    => "cauto-support"
                ],
                'label' =>  __('Support', 'codecorun-test-automation'),
                'icon'  => '<span class="dashicons dashicons-sos"></span>'
            ],
           
        ];
        $controls = apply_filters('cauto_top_controls', $controls);
        $controls = $this->prepare_attr($controls);
        $this->get_view('part-controls', ['path' => 'admin', 'controls' => $controls]);
    }

    public function load_new_flow()
    {
        $fiels = [
            [
                'type'  => 'text',
                'attr'  => [
                    'id'    => 'cauto-new-flow-name',
                    'class' => 'cauto-field'
                ],
                'label'     => __('Flow Name', 'codecorun-test-automation'),
                'icon'      => null
            ],
            [
                'type'  => 'toggle',
                'attr'  => [
                    'id'    => 'cauto-flow-stop-on-error',
                    'class' => 'cauto-field-checkbox'
                ],
                'label'     => __('Stop on error', 'codecorun-test-automation'),
                'icon'      => null
            ]
        ];

        $this->get_view('popups/new-flow', ['path' => 'admin', 'fields' => $fiels]);
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