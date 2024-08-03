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
use cauto\includes\cauto_utils;
use cauto\admin\includes\cauto_admin_init;
use cauto\admin\includes\cauto_admin_ui;
use cauto\includes\cauto_test_automation;

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

class cauto_admin extends cauto_utils
{

    private $admin_ui = null;

    public function __construct()
    {
        //register custom post type
        new cauto_admin_init();

        add_action('admin_enqueue_scripts', [$this, 'load_assets']);

        //admin UIs
        $this->admin_ui = new cauto_admin_ui();
        //save new flow
        add_action('wp_ajax_cauto_new_flow', [$this, 'new_flow']);
        add_action('cauto_flow_builder', [$this, 'load_builder'], 10);
        add_action('cauto_render_flows', [$this, 'load_flows']);
        
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
        wp_enqueue_style('cauto-admin-icons', CAUTO_PLUGIN_URL.'admin/assets/icons/style.css' , [], null);
        wp_enqueue_style('cauto-admin-grid-css', CAUTO_PLUGIN_URL.'admin/assets/admin-grid.css' , [], null);
        wp_localize_script('cauto-admin-js', 'cauto_ajax', ['ajaxurl' => admin_url( 'admin-ajax.php' ), 'nonce' => wp_create_nonce( $this->nonce )]);

        //jquery libraries
        wp_enqueue_script('jquery-ui-core');
        wp_enqueue_script('jquery-ui-sortable');
        wp_enqueue_script('jquery-ui-draggable');

        // Optionally enqueue the jQuery UI CSS for styling (If needed)
        //wp_enqueue_style('jquery-ui-css', 'https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css');
    }


    /**
     * 
     * 
     * load_flows
     * @since 1.0.0
     * 
     * 
     */
    public function load_flows()
    {
        $cflows = new cauto_test_automation();
        $flows  = $cflows->get_flows();
        $this->get_view('part-flows', ['path' => 'admin', 'flows' => $flows, 'ui' => $this->admin_ui]);
    }

    /**
     * 
     * 
     * load_builder
     * @since 1.0.0
     * 
     * 
     */
    public function load_builder($data)
    {
        if (empty($data)) {
            return;
        }
        $this->get_view('builder/part-builder-tools', ['path' => 'admin' , 'details' => $data]);
    }


    /**
     * 
     * 
     * new_flow
     * @since 1.0.0
     * 
     * 
     */
    public function new_flow()
    {
        if ( !wp_verify_nonce( $_POST['nonce'], $this->nonce ) ) {
            echo json_encode(
                [
                    'status'    => 'failed',
                    'message'   => __('Invalid nonce please contact developer or clear your cache', 'codecorun-test-automation')
                ]
            );
            exit();
        }

        $flowname       = (isset($_POST['name']))? sanitize_text_field($_POST['name']) : null;
        $stop_on_error  = (isset($_POST['stop_on_error']))? true : false;

        if ($flowname) {
            
            $cflows = new cauto_test_automation();
            $cflows->set_name($flowname);
            $cflows->set_stop_on_error($stop_on_error);
            $post_id = $cflows->save_flow();

            do_action('cauto_update_flow', $post_id);

            if ($post_id) {
                echo json_encode(
                    [
                        'status'        => 'success',
                        'message'       => __('Flow is added', 'codecorun-test-automation'),
                        'flow_id'       => $post_id,
                        'redirect_to'   => admin_url().'tools.php?page=test-tools&flow='.$post_id
                    ]
                );
            }
        } else {
            echo json_encode(
                [
                    'status'    => 'failed',
                    'message'   => __('Flow name is required', 'codecorun-test-automation')
                ]
            );
        }

        exit();

    }
}