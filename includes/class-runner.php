<?php 
/**
 * 
 * 
 * cuato_utils
 * @since 1.0.0
 * 
 * 
 */

namespace cauto\includes;
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

class cauto_runner extends cauto_utils
{
    public function __construct()
    {
        add_action('wp', [$this, 'run_flow']);
    }

    public function load_assets()
    {
        //wp_register_script('cauto-admin-js', CAUTO_PLUGIN_URL.'admin/assets/admin.js', ['jquery'], null );
        //wp_enqueue_script('cauto-admin-js');
        wp_enqueue_style('cauto-public-css', CAUTO_PLUGIN_URL.'assets/public.css' , [], null);
    }

    public function run_flow()
    {
        $flow       = (isset($_GET['flow']))? sanitize_text_field($_GET['flow']) : null;
        $do_run     = (isset($_GET['run']))? sanitize_text_field($_GET['run']) : null;
        if ($flow > 0 && $do_run > 0) {

            $flow = get_post_meta($flow, $this->flow_steps_key, true);

            if (is_admin()) {
                add_action('admin_enqueue_scripts', [$this, 'load_assets']);
            } else {
                add_action('wp_enqueue_scripts', [$this, 'load_assets']);
            }
            $this->get_view('runner-bar',['flows' => $flow]);
            
        }
    }

    
}

new cauto_runner();