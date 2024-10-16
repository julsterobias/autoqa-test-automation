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

    private $flow_id         = 0;
    
    private $runner_id       = 0;
    
    private $flow_steps      = null;

    public function __construct()
    {

        add_action('admin_enqueue_scripts', [$this, 'load_global_assets']);
        add_action('wp_enqueue_scripts', [$this, 'load_global_assets']);

        add_action('wp', [$this, 'run_flow']);
        add_action('admin_init', [$this, 'run_flow']);

        add_action('wp_ajax_cauto_prepare_runner', [$this, 'prepare_runner']);
        add_action('wp_ajax_cauto_execute_pre_run', [$this, 'pre_run']);

        add_action('wp_ajax_cauto_generate_image_step', [$this, 'do_generate_image']); 
        add_action('wp_ajax_cauto_generate_pdf_step', [$this, 'do_generate_pdf']);
        
        add_action('init', function(){
            if (isset($_GET['pdf'])) {
                header('Content-Type: application/pdf');
                header('Content-Disposition: inline; filename="simple.pdf"');

                // Define the basic PDF structure
                $pdf = "%PDF-1.4\n";
                $pdf .= "1 0 obj << /Type /Catalog /Pages 2 0 R >> endobj\n";
                $pdf .= "2 0 obj << /Type /Pages /Kids [3 0 R] /Count 1 >> endobj\n";
                $pdf .= "3 0 obj << /Type /Page /Parent 2 0 R /MediaBox [0 0 612 792] /Contents 4 0 R /Resources << >> >> endobj\n";
                $pdf .= "4 0 obj << /Length 44 >> stream\n";
                $pdf .= "BT\n/F1 12 Tf\n50 730 Td\n(Hello, Julius!) Tj\nET\n";
                $pdf .= "endstream endobj\n";
                $pdf .= "xref\n";
                $pdf .= "0 5\n";
                $pdf .= "0000000000 65535 f \n";
                $pdf .= "0000000010 00000 n \n";
                $pdf .= "0000000079 00000 n \n";
                $pdf .= "0000000178 00000 n \n";
                $pdf .= "0000000329 00000 n \n";
                $pdf .= "trailer << /Size 5 /Root 1 0 R >>\n";
                $pdf .= "startxref\n";
                $pdf .= "394\n";
                $pdf .= "%%EOF";

                // Output the PDF content to the browser
                echo $pdf;
                die();
            }
        });

    }

    public function load_global_assets()
    {
        $logged_user    = get_current_user_id();   
        if ( $logged_user && current_user_can('administrator') ) {
            wp_register_script('autoqa-runner-global-js', CAUTO_PLUGIN_URL.'assets/onloadrunner.js', ['jquery'], null );
            wp_enqueue_script('autoqa-runner-global-js');
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

        global $post;

        wp_register_script('cauto-runner-js', CAUTO_PLUGIN_URL.'assets/runners/runner.js', ['jquery'], null );
        wp_enqueue_script('cauto-runner-js');
        
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

        $app_settings = get_option($this->settings_key);
        wp_enqueue_style('cauto-runner-css', CAUTO_PLUGIN_URL.'assets/runner.css' , [], null);
        wp_localize_script('cauto-runner-js', 'cauto_runner', 
            [
                'ajaxurl'           => admin_url( 'admin-ajax.php' ), 
                'nonce'             => wp_create_nonce( $this->nonce ),
                'step_duration'     => (isset($app_settings['step-duration']))? $app_settings['step-duration'] : 3000,
                'post_id'           => (isset($post->ID))? $post->ID : 0
            ]
        );        

        //translatable will go here
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

            $runner = new cauto_test_runners($runner_id);
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


            //add dollar
            $available_variables = $this->runner_available_variables($flow_id);
            if (!empty($available_variables)) {
                foreach ($available_variables as $i => $variable) {
                    $available_variables[$i] = '$'.$variable;
                }
            }
            echo json_encode(
                [
                    'status'        => 'success',
                    'runner_steps'  => $available_runners,
                    'bars'          => $bars,
                    'variables'     => $available_variables
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
            $running_flows  = $cauto_test->get_running_flow();

            if ( $logged_user && current_user_can('administrator') && !empty($running_flows)) {

                //let's load all available steps. In the future find a solution on how to load the steps of the running flow.
                $running_flows_ids = [];
                foreach ($running_flows as $flow_id => $flows) {
                    $running_flows_ids[] = $flow_id;
                }
                $flows = $cauto_test->get_flows( ['post__in' => $running_flows_ids] );
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

                $this->return_last_step(
                    [
                        'payload'   => $runner->get_runner_flow_step(),
                        'flow_id'   => $flow_id,
                        'runner_id' => $runner_id
                    ]
                );
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
                        $this->return_last_step(
                            [
                                'payload'   => $runner->get_runner_flow_step(),
                                'flow_id'   => $flow_id,
                                'runner_id' => $runner_id
                            ]
                        );
                        exit();
                    }
                }

                if ( $started_steps >= count($runner_steps) ) {
                    $this->return_last_step(
                        [
                            'payload'   => $runner->get_runner_flow_step(),
                            'flow_id'   => $flow_id,
                            'runner_id' => $runner_id
                        ]
                    ); //uncomment this after the plotter is implemented
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
                        $this->return_last_step(
                            [
                                'payload'   => $runner->get_runner_flow_step(),
                                'flow_id'   => $flow_id,
                                'runner_id' => $runner_id
                            ]
                        ); //uncomment this after the plotter is implemented
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
                    $this->return_last_step(
                        [
                            'payload'   => $runner_steps,
                            'flow_id'   => $flow_id,
                            'runner_id' => $runner_id
                        ]
                    );
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

    public function return_last_step($data = [])
    {
        //stop the running flow
        $flow_class = new cauto_test_automation($data['flow_id']);
        $flow_class->stop($data['runner_id']);

        echo json_encode([
            'status'       => 'completed',
            'payload'      => $data['payload'],
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

    public function do_generate_image() 
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

        $type       = (isset($_POST['type']))? sanitize_text_field($_POST['type']) : null;
        $width      = (isset($_POST['width']))? sanitize_text_field($_POST['width']) : null;
        $height     = (isset($_POST['height']))? sanitize_text_field($_POST['height']) : null;
        $filename   = (isset($_POST['file_alias']))? sanitize_text_field($_POST['file_alias']) : uniqid();
        $filename   = strtolower(str_replace(' ','-', $filename)).'.'.$type;
        
        ob_start();
        $this->generate_image(
            [
                'type'      => $type,
                'width'     => $width,
                'height'    => $height
            ]
        );
        $data = ob_get_clean();
        $base64 = base64_encode($data);

        wp_send_json(
            [
                'status'    => 'success',
                'image'     => "data:image/$type;base64," . $base64, 
                'filename'  => $filename
            ]
        );
        exit();

    }

    public function do_generate_pdf()
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

        $content    = (isset($_POST['content']))? sanitize_text_field($_POST['content']) : null;
        $filename   = (isset($_POST['file_alias']))? sanitize_text_field($_POST['file_alias']) : uniqid();
        $filename   = strtolower(str_replace(' ','-', $filename)).'.pdf';
        
        ob_start();
        $this->generate_pdf(
            [
                'content'      => $content,
                'filename'     => $filename,
            ]
        );
        $data = ob_get_clean();
        $pdf = base64_encode($data);

        wp_send_json(
            [
                'status'    => 'success',
                'pdf'       => $pdf, 
                'filename'  => $filename
            ]
        );
        exit();
    }
    
}

new cauto_runner();