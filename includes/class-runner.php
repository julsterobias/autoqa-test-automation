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
use cauto\includes\cauto_test_automation;
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

class cauto_runner extends cauto_utils
{

    private $flow_id                = 0;
    
    private $runner_id              = 0;
    
    private $flow_steps             = null;

    public function __construct()
    {

        add_action('admin_enqueue_scripts', [$this, 'load_global_assets']);
        add_action('wp_enqueue_scripts', [$this, 'load_global_assets']);

        add_action('wp', [$this, 'run_flow']);
        add_action('admin_init', [$this, 'run_flow']);

        add_action('wp_ajax_cauto_prepare_exe_runner', [$this, 'prepare_runner']);
        add_action('wp_ajax_nopriv_cauto_prepare_exe_runner', [$this, 'prepare_runner']);

        add_action('wp_ajax_cauto_execute_runner', [$this, 'execute_runner']);
        add_action('wp_ajax_nopriv_cauto_execute_runner', [$this, 'execute_runner']);

        add_action('wp_ajax_cauto_do_stop_runner', [$this, 'stop_runner']);
        add_action('wp_ajax_nopriv_cauto_do_stop_runner', [$this, 'stop_runner']);
        
    }

    public function load_global_assets()
    {
        $logged_user    = get_current_user_id();   
        if ( $logged_user && current_user_can('administrator') ) {
            wp_register_script('cauto-runner-global-js', CAUTO_PLUGIN_URL.'assets/onloadrunner.js', ['jquery'], null );
            wp_enqueue_script('cauto-runner-global-js');
        }
    }

    public function load_assets()
    {
        wp_register_script('cauto-runner-js', CAUTO_PLUGIN_URL.'assets/runners/runner.js', ['jquery'], null );
        wp_enqueue_script('cauto-runner-js');
       
        if (isset($this->flow_steps)) {
            foreach ($this->flow_steps as $step) {
                if (isset($step['step'])) {
                    wp_register_script('cauto-runner-'.$step['step'].'-js', CAUTO_PLUGIN_URL.'assets/runners/'.$step['step'].'.js', ['jquery'], null );
                    wp_enqueue_script('cauto-runner-'.$step['step'].'-js');
                }
            }
        }
        wp_enqueue_style('cauto-runner-css', CAUTO_PLUGIN_URL.'assets/runner.css' , [], null);
        wp_localize_script('cauto-runner-js', 'cauto_ajax', 
                    [
                        'ajaxurl'           => admin_url( 'admin-ajax.php' ), 
                        'nonce'             => wp_create_nonce( $this->nonce ),
                        'cauto_flow_id'     => $this->flow_id,
                        'cauto_runner_id'   => $this->runner_id
                    ]
        );

        $footer_position = (is_admin())? 'admin_footer' : 'wp_footer';
        add_action($footer_position, function(){
            $this->get_view('runner-bar', ['steps' => $this->flow_steps]);
        });
    }

    public function run_flow()
    {

        $this->flow_id      = (isset($_GET['flow_id']))? $_GET['flow_id'] : null;
        $this->runner_id    = (isset($_GET['runner_id']))? $_GET['runner_id'] : null;

        if ($this->flow_id > 0 && $this->runner_id > 0) {
            
            $this->flow_steps   = get_post_meta($this->flow_id, $this->flow_steps_key, true);
            $target_url = null;
            if (isset($this->flow_steps[0])) {
                $first_step = $this->flow_steps[0]; 
                if ($first_step['step'] === 'start' && isset($first_step['record'][0]['value'])) {
                    $target_url = $first_step['record'][0]['value'];
                }
            }
            
            $this->get_view('runner', ['flows' => $this->flow_steps, 'flow_id' => $this->flow_id, 'runner_id' => $this->runner_id, 'url' => $target_url]);
            exit();
            
        } else {

            $logged_user    = get_current_user_id();
            $cauto_test     = new cauto_test_automation();
            $running_flows  = $cauto_test->get_running_flow();
            if ( $logged_user && current_user_can('administrator') && !empty($running_flows) ) {
                //load runner assets
                $this->flow_id      = $running_flows['flow_id'];
                $this->runner_id    = $running_flows['runner_id'];
                $this->flow_steps   = get_post_meta($this->flow_id, $this->flow_steps_key, true);
                if (is_admin()) {
                    add_action('admin_enqueue_scripts', [$this, 'load_assets']);
                } else {
                    add_action('wp_enqueue_scripts', [$this, 'load_assets']);
                }
            }

        }   
       
    }

    public function prepare_runner()
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

        //
        exit();

        $flow_id    = (isset($_POST['flow_id']))? (int) sanitize_text_field($_POST['flow_id']) : null;
        $runner_id  = (isset($_POST['runner_id']))? (int) sanitize_text_field($_POST['runner_id']) : null;

        if ($flow_id && $runner_id) {
            
            $runner         = new cauto_test_runners($runner_id);
            $runner->set_flow_id($flow_id);
            $runner_steps   = $runner->get_runner_flow_step();
            
            if (!$runner_steps) {
                echo json_encode([
                    'status'        => 'success',
                    'message'       => '',
                    'action'        => 'new'
                ]);
            } else {

                $index_to_continue = null;
                foreach ($runner_steps as $index => $step) {
                    if (empty($step['result'])) {
                        $index_to_continue = $index;
                        break;
                    } 
                }

                if ($index_to_continue) {
                    $index_to_continue--;
                }

                echo json_encode([
                    'status'    => 'success',
                    'message'   => '',
                    'action'    => 'continue',
                    'index'     => $index_to_continue
                ]);
            }

        } else {
            echo json_encode([
                'status'    => 'failed',
                'message'    => __('Runner: required data for step is not found, please contact developer.', 'codecorun-test-automation')
            ]);
        }

        exit();

    }


    public function execute_runner()
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

        $flow_id    = (isset($_POST['flow_id']))? (int) sanitize_text_field($_POST['flow_id']) : null;
        $runner_id  = (isset($_POST['runner_id']))? (int) sanitize_text_field($_POST['runner_id']) : null;
        
        if ($flow_id && $runner_id) {
               
            $steps_obj              = cauto_steps::steps();
            $steps                  = get_post_meta($flow_id, $this->flow_steps_key, true);
            $stop_error             = get_post_meta($flow_id, '_stop_on_error', true);
            $runner_response        = ($_POST['response'] !== 'null')? $_POST['response'] : null;
            $step_index             = (isset($_POST['index']))? (int) sanitize_text_field($_POST['index']) : null;
           
            if ($runner_response) {
                $runner_response = json_decode(stripslashes($runner_response));

                //update_runner_steps
                //one step back index
                $result_index = $step_index;
                $result_index--;
                $runner = new cauto_test_runners($runner_id);
                $runner->set_flow_id($flow_id);
                $runner->update_runner_steps($result_index, $runner_response);

                if (!$runner_response[0]->status && $stop_error) {
                    echo json_encode([
                        'status'    => 'failed',
                        'message'    => $runner_response[0]->message
                    ]);
                    exit();
                } 
            }

            $payload = [];

            if (!isset($steps[$step_index])) {
                //end of payload
                echo json_encode([
                    'status'    => 'success',
                    'message'      => 'EOP'
                ]);
                exit();
            }

            $payload = [
                'callback'      => $steps_obj[$steps[$step_index]['step']]['callback'],
                'index'         => $step_index,
                'params'        => $steps[$step_index]['record']
            ];

            echo json_encode([
                'status'       => 'success',
                'payload'      => $payload,
                'message'      => null 
            ]);
            
        } else {
            echo json_encode([
                'status'    => 'failed',
                'message'    => __('Runner: required data for step is not found, please contact developer.', 'codecorun-test-automation')
            ]);   
        }

        exit();
    }

    public function stop_runner()
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

        echo json_encode([
            'status'       => 'success',
            'message'      => null 
        ]);

        exit();
    }

    
}

new cauto_runner();