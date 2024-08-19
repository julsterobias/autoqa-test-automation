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
use cauto\admin\includes\cauto_admin_ui;
use cauto\includes\cauto_test_automation;
use cauto\includes\cauto_steps;
use cauto\includes\cauto_test_runners;

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
        add_action('admin_enqueue_scripts', [$this, 'load_assets']);

        //admin UIs
        $this->admin_ui = new cauto_admin_ui();
        //save new flow
        add_action('wp_ajax_cauto_new_flow', [$this, 'new_flow']);
        add_action('cauto_flow_builder', [$this, 'load_builder'], 10);
        add_action('cauto_render_flows', [$this, 'load_flows']);
        //save flow
        add_action('wp_ajax_cauto_do_save_flow', [$this, 'save_steps']);
        //load flow steps in the builder
        add_action('cauto_load_saved_steps', [$this, 'load_saved_steps']);
        //setup and run the flow
        add_action('wp_ajax_cauto_setup_run_flow', [$this, 'setup_run_flow']);

        add_action('admin_head', function(){
            if (isset($_GET['reset'])) {
                delete_post_meta($_GET['flow'], '_cauto_test_automation_steps');
            }

            if (isset($_GET['debug'])) {
                print_r(get_post_meta($_GET['post'], '_flow_steps', true));
                die();
            }
        });
        
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
            
            do_action('cauto_before_update_flow', ['flow_name' => $flowname, 'stop_on_error' => $stop_on_error]);

            $cflows = new cauto_test_automation();
            $cflows->set_name($flowname);
            $cflows->set_stop_on_error($stop_on_error);
            $post_id = $cflows->save_flow();

            do_action('cauto_after_update_flow', $post_id, ['flow_name' => $flowname, 'stop_on_error' => $stop_on_error]);

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

    /**
     * 
     * 
     * save_steps
     * @since 1.0.0
     * 
     * 
     */
    public function save_steps()
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


        $step_data  = (isset($_POST['step']))? $_POST['step'] : null;
        $flow_id    = (is_numeric($_POST['flow_id']))? sanitize_text_field($_POST['flow_id']) : null;

        if ($step_data) {

            $step_data = json_decode(stripslashes($step_data));

            if (empty($step_data)) {
                delete_post_meta($flow_id, $this->flow_steps_key);
                echo json_encode(
                    [
                        'status'    => 'success'
                    ]
                );
                exit();
            }

            //map array to sanitize
            //this will choke the server? we find it soon
            foreach ($step_data as $index => $step) {
                $step = (array) $step;
                foreach ($step as $i => $data) {
                    if ($i === 'record' && !empty($data)) {
                        $data = json_decode(stripslashes($data));
                        $data = (array) $data;
                        foreach ($data as $x => $indata) {
                            $indata = (array) $indata;
                            $indata['value'] = sanitize_text_field($indata['value']);
                            $data[$x] = $indata;
                        }
                    }
                    $step[$i] = $data;
                }
                $step_data[$index] = $step;
            }
        } 
        
        if ($step_data && !empty($step_data) && $flow_id) {

            do_action('cauto_before_save_steps',$flow_id, $step_data);
            update_post_meta($flow_id, $this->flow_steps_key, $step_data);
            do_action('cauto_after_save_steps',$flow_id, $step_data);

            echo json_encode(
                [
                    'status'    => 'success'
                ]
            );
        }

        exit();

    }

    public function load_saved_steps()
    {
        if (!isset($_GET['flow'])) return;

        $flow_id = sanitize_text_field($_GET['flow']);
        $get_steps = get_post_meta($flow_id, $this->flow_steps_key, true);

        if (!$get_steps) return;

        $data = cauto_steps::steps();
        $flow_steps = [];

        foreach ($get_steps as $steps) {
        
            //$step_type          = ($steps['step'] === 'start')? 'default' : $steps['step'];
            $step_group         = (isset($data[$steps['step']]['group']))? $data[$steps['step']]['group'] : []; 
            $step_indicator     = (isset($data[$steps['step']]['step_indicator']))? $data[$steps['step']]['step_indicator'] : [];
            $step_selectors     = (isset($step_indicator['selector']))? $step_indicator['selector'] : [];
            $icon               = (isset($data[$steps['step']]['icon']))? $data[$steps['step']]['icon'] : [];
            $step_label         = (isset($data[$steps['step']]['label']))? $data[$steps['step']]['label'] : [];

    
            $describe_text      = (!empty($step_indicator['describe_text']))? $step_indicator['describe_text'] : null;
            $describe_text_set  = [];

            if (is_array($step_selectors)) { 

                $clean_selector = array_map(function($selector){
                    return substr($selector, 1);
                },$step_selectors);

                if (!empty($steps['record'])) {
                    foreach ($steps['record'] as $record) {
                        if (in_array($record['id'], $clean_selector)) {
                            $describe_text_set['#'.$record['id']] = $record['value'];
                        }
                    }
                }
                

            } else {
                $clean_selector = substr($step_selectors, 1);

                if (!empty($steps['record'])) {
                    foreach ($steps['record'] as $record) {
                        if ($record['id'] === $clean_selector) {
                            $describe_text_set['#'.$record['id']] = $record['value'];
                        }
                    }
                }
            }
       
            foreach ($describe_text_set as $index => $describe_set) {
                $describe_text = str_replace("{".$index."}", $describe_set, $describe_text);
            }

            $flow_steps[] = [
                'step_group'        => $step_group,
                'step'              => $steps['step'],
                'icon_label'        => $icon.$step_label,
                'record'            => $steps['record'],
                'describe_label'    => $describe_text,
                'icon'              => $icon,
                'step_label'        => $step_label
            ]; 

        }

        $this->get_view('flow/saved-steps', ['path' => 'admin', 'flow_steps' => $flow_steps]);

    }

    /**
     * 
     * 
     * setup_run_flow
     * @since 1.0.0
     * 
     * 
     */
    public function setup_run_flow()
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

        $flow_id = ($_POST['flow_id'])? sanitize_text_field($_POST['flow_id']) : null;
        $flow_id = (int) $flow_id;

        if (!$flow_id) {
            echo json_encode(
                [
                    'status'    => 'failed',
                    'message'   => __('No flow is found', 'codecorun-test-automation')
                ]
            );
            exit();
        }

        $steps      = get_post_meta($flow_id, $this->flow_steps_key, true);
        $target_url = '';

        //get the first step and validate
        if (isset($steps[0]['step'])) {
            if ($steps[0]['step'] !== 'start') {
                echo json_encode(
                    [
                        'status'    => 'failed',
                        'message'   => __('No valid step to start', 'codecorun-test-automation')
                    ]
                );
                exit();
            }

            $target_url = (isset($steps[0]['record'][0]['value']))? $steps[0]['record'][0]['value'] : null;

            if (!$target_url) {
                echo json_encode(
                    [
                        'status'    => 'failed',
                        'message'   => __('No valid URL to start', 'codecorun-test-automation')
                    ]
                );
                exit();
            }
        }


        $runner     = new cauto_test_runners();
        $runner->set_name(uniqid('runner-'));
        $runner->set_flow_id($flow_id);
        $runner->set_steps($steps);
        $runner_id  = $runner->save();


        if ($runner_id > 0) {
            echo json_encode(
                [
                    'status'    => 'success',
                    'message'   => '',
                    'flow_id'   => $flow_id,
                    'runner_id' => $runner_id,
                    'url'       => $target_url
                ]
            );
        } else {
            echo json_encode(
                [
                    'status'    => 'failed',
                    'message'   => __('Failed to save runner, contact developer', 'codecorun-test-automation')
                ]
            );
        }

        exit();

    }

    
}