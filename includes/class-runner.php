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

    private $flow_id        = 0;
    private $runner_id      = 0;
    private $flow_steps     = null;

    public function __construct()
    {

        add_action('wp', [$this, 'run_flow']);
        add_action('admin_init', [$this, 'run_flow']);

        add_action('wp_ajax_cauto_execute_runner', [$this, 'execute_runner']);
        add_action('wp_ajax_nopriv_cauto_execute_runner', [$this, 'execute_runner']);
    }

    public function load_assets()
    {
        wp_register_script('cauto-runner-js', CAUTO_PLUGIN_URL.'assets/runners/runner.js', ['jquery'], null );
        wp_enqueue_script('cauto-runner-js');
        foreach ($this->flow_steps as $step) {
            if (isset($step['step'])) {
                wp_register_script('cauto-runner-'.$step['step'].'-js', CAUTO_PLUGIN_URL.'assets/runners/'.$step['step'].'.js', ['jquery'], null );
                wp_enqueue_script('cauto-runner-'.$step['step'].'-js');
            }
        }
        wp_enqueue_style('cauto-runner-css', CAUTO_PLUGIN_URL.'assets/public.css' , [], null);
        wp_localize_script('cauto-runner-js', 'cauto_ajax', 
                    [
                        'ajaxurl'           => admin_url( 'admin-ajax.php' ), 
                        'nonce'             => wp_create_nonce( $this->nonce ),
                        'cauto_flow_id'     => $this->flow_id,
                        'cauto_runner_id'   => $this->runner_id
                    ]
        );
    }

    public function run_flow()
    {
        $this->flow_id       = (isset($_GET['flow']))? sanitize_text_field($_GET['flow']) : null;
        $this->runner_id              = (isset($_GET['runner']))? sanitize_text_field($_GET['runner']) : null;
        $this->flow_steps     = get_post_meta($this->flow_id, $this->flow_steps_key, true);

        if ($this->flow_id > 0 && $this->runner_id > 0) {

            if (is_admin()) {
                add_action('admin_enqueue_scripts', [$this, 'load_assets']);
            } else {
                add_action('wp_enqueue_scripts', [$this, 'load_assets']);
            }
            $this->get_view('runner-bar',['flows' => $this->flow_steps]);
            
        }
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

                /*if (isset($runner_response[0]->abort)) {
                    echo json_encode([
                        'status'    => 'success',
                        'message'    => 'reload'
                    ]);
                    exit();
                }*/
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
                'message'    => 'Runner: required data for step is not found, please contact developer.'
            ]);
            
        }

        exit();
    }

    
}

new cauto_runner();