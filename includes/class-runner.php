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

    private $flow_id                    = 0;
    
    private $runner_id                  = 0;
    
    private $flow_steps                 = null;

    private int $data_transient_lifespan    = 3600; //one hour

    public function __construct()
    {

        add_action('admin_enqueue_scripts', [$this, 'load_global_assets']);
        add_action('wp_enqueue_scripts', [$this, 'load_global_assets']);

        add_action('wp', [$this, 'run_flow']);
        add_action('admin_init', [$this, 'run_flow']);

        add_action('wp_ajax_cauto_prepare_runner', [$this, 'prepare_runner']);
        add_action('wp_ajax_cauto_execute_pre_run', [$this, 'pre_run']);
        add_action('wp_ajax_cauto_do_stop_runner', [$this, 'stop_runner']);

        //store data
        add_action('wp_ajax_cauto_save_element_step_data_to_transient', [$this, 'store_element_data']);
    }

    public function load_global_assets()
    {
        $logged_user    = get_current_user_id();   
        if ( $logged_user && current_user_can('administrator') ) {
            wp_register_script('cauto-runner-global-js', CAUTO_PLUGIN_URL.'assets/onloadrunner.js', ['jquery'], null );
            wp_enqueue_script('cauto-runner-global-js');
        }
    }


    /**
     * 
     * 
     * remember to unload these once no flow is running
     * 
     * 
     */
    public function load_assets()
    {
        wp_register_script('cauto-runner-js', CAUTO_PLUGIN_URL.'assets/runners/runner.js', ['jquery'], null );
        wp_enqueue_script('cauto-runner-js');
        
        //we load the assets regardless to the current status of runner
        //find a way to clear the flow after completing the run time. Maybe during the run is completed.
        if (isset($this->flow_steps)) {
            foreach ($this->flow_steps as $steps) {
                if (!empty($steps)) {
                    foreach ($steps as $step) {
                        if (isset($step['step'])) {
                            wp_register_script('cauto-runner-'.$step['step'], CAUTO_PLUGIN_URL.'assets/runners/'.$step['step'].'.js', ['jquery'], null );
                            wp_enqueue_script('cauto-runner-'.$step['step']);
                        }
                    }
                }
            }
        }

        wp_enqueue_style('cauto-runner-css', CAUTO_PLUGIN_URL.'assets/runner.css' , [], null);
        wp_localize_script('cauto-runner-js', 'cauto_runner', 
            [
                'ajaxurl'           => admin_url( 'admin-ajax.php' ), 
                'nonce'             => wp_create_nonce( $this->nonce )
            ]
        );

        $cauto_steps_text = [
            'element_not_found' => __('Matched 0: The element cannot be found.', 'autoqa-test-automation'),
            'multiple_element'  => __('Matched > 1: Multiple elements were found, but the specific event cannot be dispatched.', 'autoqa-test-automation'),
            'element_not_found_dispatch'    => __('Matched 0: The element cannot be found after dispatch.', 'autoqa-test-automation'),
            'event_validated'   => __('Matched 1: Event is validated.', 'autoqa-test-automation'),
            'unconfigured_msg'  => __('The step is not configured','autoqa-test-automation')
        ];
        wp_localize_script('cauto-runner-js', 'cauto_step_text', $cauto_steps_text);

        $footer_position = (is_admin())? 'admin_footer' : 'wp_footer';
        add_action($footer_position, function(){
            $this->get_view('runner-bar', []);
        });
    }

    public function prepare_runner()
    {
        if ( !wp_verify_nonce( $_POST['nonce'], $this->nonce ) ) {
            echo json_encode(
                [
                    'status'    => 'failed',
                    'message'   => __('Invalid nonce please contact developer or clear your cache', 'autoqa-test-automation')
                ]
            );
            exit();
        }

        $flow_id    = (isset($_POST['flow_id']))? sanitize_text_field($_POST['flow_id']) : null;
        $runner_id  = (isset($_POST['runner_id']))? sanitize_text_field($_POST['runner_id']) : null;

        if ($flow_id && $runner_id) {
            $runner = new cauto_test_runners($this->runner_id);
            $runner->set_flow_id($flow_id);
            $runner_steps = $runner->get_runner_flow_step();

            if (empty($runner_steps)) {
                echo json_encode(
                    [
                        'status'     => 'failed',
                        'message'    => __('No available steps to run', 'autoqa-test-automation') 
                    ]
                );
                exit();
            }

            $available_runners = [$runner_id => $runner_steps];

            //let's load the bar indicators
            $flow_steps = get_post_meta($flow_id, $this->flow_steps_key, true);

            ob_start();
            $this->get_view('part-runner-bar', ['steps' => $flow_steps]);
            $bars = ob_get_clean();

            echo json_encode(
                [
                    'status'        => 'success',
                    'runner_steps'  => $available_runners,
                    'bars'          => $bars
                ]
            );

        }
        exit();
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

            $title = get_the_title($this->flow_id);
            $runner_class = new cauto_test_automation($this->flow_id);
            $runner_class->start($this->runner_id);
            
            $this->get_view('runner', 
                [
                    'flows'         => $this->flow_steps,
                    'flow_id'       => $this->flow_id, 
                    'runner_id'     => $this->runner_id, 
                    'url'           => $target_url, 
                    'title'         => $title
                ]
            );
            exit();
            
        } else {

            $logged_user    = get_current_user_id();
            $cauto_test     = new cauto_test_automation();
            $running_flows  = $cauto_test->get_running_flow(); //clean this up. I don't think we still need this?????

            if ( $logged_user && current_user_can('administrator') && !empty($running_flows)) {

                $this->flow_id      = $running_flows['flow_id'];
                $this->runner_id    = $running_flows['runner_id'];

                //let's load all available steps. In the future find a solution on how to load the steps of the running flow.
                $flows = $cauto_test->get_flows();
                if (!empty($flows)) {
                    foreach ($flows as $flow) {
                        $this->flow_steps[] = get_post_meta($flow->ID, $this->flow_steps_key, true);
                    }
                }
                
                if (is_admin()) {
                    add_action('admin_enqueue_scripts', [$this, 'load_assets']);
                } else {
                    add_action('wp_enqueue_scripts', [$this, 'load_assets']);
                }
            }

        }   
       
    }


    public function pre_run()
    {
        if ( !wp_verify_nonce( $_POST['nonce'], $this->nonce ) ) {
            echo json_encode(
                [
                    'status'    => 'failed',
                    'message'   => __('Invalid nonce please contact developer or clear your cache', 'autoqa-test-automation')
                ]
            );
            exit();
        }

        $flow_id    = (isset($_POST['flow_id']))? (int) sanitize_text_field($_POST['flow_id']) : null;
        $runner_id  = (isset($_POST['runner_id']))? (int) sanitize_text_field($_POST['runner_id']) : null;
        $response   = (!empty($_POST['response']))? json_decode(stripslashes($_POST['response'])) : null;
        $index      = (isset($_POST['index']))? (int)sanitize_text_field($_POST['index']) : null;

        $has_failed = false;

        if ($flow_id && $runner_id) {
            
            $steps_obj          = cauto_steps::steps();
            $step_index         = $index;
            $runner             = new cauto_test_runners($runner_id);
            $runner->set_flow_id($flow_id);

            $runner_steps   = $runner->get_runner_flow_step();

            if (empty($runner_steps)) {
                echo json_encode([
                    'status'       => 'failed',
                    'payload'      => [],
                    'message'      => __('Cannot run flow no steps found', 'autoqa-test-automation') 
                ]);
                exit();
            }

            //for completion
            if ( $step_index >= count($runner_steps) ) {

                $temp_index = $step_index;
                $temp_index--;
                $runner->update_runner_steps($temp_index, $response);
                $this->return_last_step($runner->get_runner_flow_step());
                exit();
            }

            if (isset($runner_steps[$step_index]['result']) && empty($response) && $step_index === 0) {

                //let's check for continue
                $started_steps = 0;
                foreach ($runner_steps as $run_step) {
                    if (isset($run_step['result'])) {
                        $started_steps++; //uncomment this after the plotter is implemented
                        if ($run_step['result'][0]->status === 'failed') {
                            $has_failed = true;
                        }
                    }
                }

                if ($has_failed) {
                    $flow_class = new cauto_test_automation($flow_id);
                    $on_error   = $flow_class->get_stop_on_error();
                    if ($on_error) {
                        $this->return_last_step($runner_steps);
                        exit();
                    }
                }

                if ( $started_steps >= count($runner_steps) ) {
                    $this->return_last_step($runner->get_runner_flow_step()); //uncomment this after the plotter is implemented
                    exit();
                }

                if ($started_steps > 0) {

                    //update the postback step
                    if (!isset($runner_steps[$started_steps]['result'])) {
                        $postback_result = (object) ['status' => 'passed', 'message' => __('step validated and continued', 'cauto-test-automation')];
                        $runner->update_runner_steps($started_steps, [$postback_result]);
                    }
                    $started_steps++;

                    if ($started_steps >= count($runner_steps)) {
                        $this->return_last_step($runner->get_runner_flow_step()); //uncomment this after the plotter is implemented
                        exit();
                    }

                    //this is continue
                    $payload = [
                        'callback'      => $steps_obj[$runner_steps[$started_steps]['step']]['callback'],
                        'index'         => $started_steps,
                        'params'        => $runner_steps[$started_steps]['record']
                    ];

                    echo json_encode([
                        'status'       => 'continue',
                        'payload'      => $payload,
                        'message'      => null 
                    ]);

                    exit();
                }

            }
            


            if (!isset($runner_steps[$step_index]['result']) && empty($response) && $step_index === 0) {

                //prepare the first step
                $payload = [
                    'callback'      => $steps_obj[$runner_steps[$step_index]['step']]['callback'],
                    'index'         => 0,
                    'params'        => $runner_steps[$step_index]['record']
                ];

                echo json_encode([
                    'status'       => 'success',
                    'payload'      => $payload,
                    'message'      => null 
                ]);

                exit();
            }

            $temp_index = $step_index;
            $temp_index--;
            
            $runner->update_runner_steps($temp_index, $response);
            //get the update steps to include the changes above
            $runner_steps   = $runner->get_runner_flow_step();


            $stop_error     = get_post_meta($flow_id, $this->flow_stop_on_error, true);
            if ($stop_error && !empty($runner_steps)) {
                $abort = false;
                foreach ($runner_steps as $step) {
                    if (isset($step['result'])) {
                        if ( $step['result'][0]->status === 'failed' ) {
                            $abort = true;
                            break;
                        }
                    } 
                }
                if ($abort) {
                    $this->return_last_step($runner_steps);
                    exit();
                }
            }



            $runner_steps_ = $this->extract_results($runner_steps);
            $payload = [
                'callback'      => $steps_obj[$runner_steps[$step_index]['step']]['callback'], //watch out here
                'index'         => $step_index,
                'params'        => $runner_steps[$step_index]['record'],
                'results'       => $runner_steps_
            ];
            
            echo json_encode([
                'status'       => 'success',
                'payload'      => $payload,
                'message'      => null 
            ]);
            exit();


        } else {
            echo json_encode([
                'status'       => 'failed',
                'payload'      => [],
                'message'      => __('No flow and runner were found', 'autoqa-test-automation') 
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
                    'message'   => __('Invalid nonce please contact developer or clear your cache', 'autoqa-test-automation')
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

    public function return_last_step($payload = [])
    {
        echo json_encode([
            'status'       => 'completed',
            'payload'      => $payload,
            'message'      => null
        ]);
    }

    public function extract_results($steps = []) 
    {
        if (empty($steps)) return;

        $results = [];
        foreach ($steps as $step) {
            if (isset($step['result'])) {
                $results[] = $step['result'];
            }    
        }

        return $results;

    }

    public function store_element_data()
    {
        if ( !wp_verify_nonce( $_POST['nonce'], $this->nonce ) ) {
            echo json_encode(
                [
                    'status'    => 'failed',
                    'message'   => __('Invalid nonce please contact developer or clear your cache', 'autoqa-test-automation')
                ]
            );
            exit();
        }

        $data_name      = (isset($_POST['data_name']))? sanitize_text_field($_POST['data_name']) : null;
        $data_to_store   = (isset($_POST['data_to_store']))? sanitize_text_field($_POST['data_to_store']) : null;
        
        if (!$data_name || !$data_to_store) {
            echo json_encode(
                [
                    'status'    => 'failed',
                    'message'   => __('Required data is missing when storing to transient, contact the developer', 'autoqa-test-automation')
                ]
            );
            exit();
        }

        //save data to stransient and will expire in one hour
        set_transient( $data_name, $data_to_store, $this->data_transient_lifespan );

        echo json_encode(
            [
                'status'    => 'success',
                'message'   => ''
            ]
        );
        exit();
    }

    
}

new cauto_runner();