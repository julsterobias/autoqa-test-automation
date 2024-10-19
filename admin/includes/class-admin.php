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
use cauto\includes\cauto_ui_translatables;

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

    private int $see_more_max       = 10; 

    private $admin_ui               = null;

    public function __construct()
    {
        add_action('admin_enqueue_scripts', [$this, 'load_assets']);

        //admin UIs
        $this->admin_ui = new cauto_admin_ui();
        //save new flow
        add_action('wp_ajax_cauto_new_flow', [$this, 'update_flow']);
        add_action('cauto_flow_builder', [$this, 'load_builder'], 10);
        add_action('cauto_render_flows', [$this, 'load_flows']);
        //save flow
        add_action('wp_ajax_cauto_do_save_flow', [$this, 'save_steps']);
        //load flow steps in the builder
        add_action('cauto_load_saved_steps', [$this, 'load_saved_steps']);
        //setup and run the flow
        add_action('wp_ajax_cauto_setup_run_flow', [$this, 'setup_run_flow']);
        
        //load UI from ajax
        //add_action('wp_ajax_cauto_steps_ui', [$this, 'load_step_ui']);
        //get flow details to edit
        add_action('wp_ajax_cauto_get_flow_details_to_edit', [$this, 'flow_details']);
        //get runner results
        add_action('wp_ajax_cauto_load_more_runner_results', [$this, 'load_more_runner']);
        //get runner steps results
        add_action('wp_ajax_cauto_load_runner_results', [$this, 'load_runner_steps']);
        //load saved runners
        add_action('cauto_load_runners', [$this, 'load_runners'], 10, 3);
        //load the default variables
        add_action('wp_ajax_cauto_load_runner_variables', [$this, 'load_variables']);
        //delete flow
        add_action('wp_ajax_cauto_delete_flow', [$this, 'delete_flow']);
        //save settings
        add_action('wp_ajax_cauto_save_settings', [$this, 'cauto_save_settings']);
        //load data into select2 via source(ajax)
        add_action('wp_ajax_cauto_get_select2_data', [$this, 'load_select2_data']);
        
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

        if (!isset($_GET['page'])) return; //do not load assets to non autoqa pages
        if ($_GET['page'] !== $this->settings_page) return; //do not load assets to non autoqa pages

        wp_register_script('cauto-admin-js', CAUTO_PLUGIN_URL.'admin/assets/admin.js', ['jquery'], null );
        wp_enqueue_script('cauto-admin-js');
        wp_enqueue_style('cauto-admin-css', CAUTO_PLUGIN_URL.'admin/assets/admin.css' , [], null);
        wp_enqueue_style('cauto-admin-icons', CAUTO_PLUGIN_URL.'assets/icons/icons.css' , [], null);
        wp_enqueue_style('cauto-admin-grid-css', CAUTO_PLUGIN_URL.'admin/assets/admin-grid.css' , [], null);

        //load select2 library
        wp_register_script('cauto-select2-js', CAUTO_PLUGIN_URL.'libs/select2/js/select2.min.js', ['jquery'], null );
        wp_enqueue_script('cauto-select2-js');
        wp_enqueue_style('cauto-select2-css', CAUTO_PLUGIN_URL.'libs/select2/css/select2.min.css' , [], null);

        $cauto_variables = [
            'ajaxurl'   => admin_url( 'admin-ajax.php' ), 
            'nonce'     => wp_create_nonce( $this->nonce )
        ];

        //pass the variable to inline js variable for editing
        if (!empty($_GET['flow'])) {
            $cauto_variables['flow_id'] = $_GET['flow'];
        }

        wp_localize_script('cauto-admin-js', 'cauto_ajax', $cauto_variables);
        wp_localize_script('cauto-admin-js', 'cauto_translable_labels', cauto_ui_translatables::ui_text()['admin']);

        //jquery libraries
        wp_enqueue_script('jquery-ui-core');
        wp_enqueue_script('jquery-ui-sortable');
        wp_enqueue_script('jquery-ui-draggable');
        wp_enqueue_script('jquery-ui-autocomplete');
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
        $cflows     = new cauto_test_automation();
        $flows      = $cflows->get_flows();

        $data_flows = [];
        if (!empty($flows)) {
            foreach ($flows as $flow) {

                $runner_class = new cauto_test_runners();
                $runner_class->set_flow_id($flow->ID);
                $runners = $runner_class->get_runners(
                    [
                        'posts_per_page' => 1 // we only need one data and should be the latest
                    ]
                );

                $temp_flow = [
                    'ID'            => $flow->ID,
                    'name'          => $flow->post_title,
                    'status'        => 'passed',
                    'last_run'      => (isset($runners[0]['date']))? $runners[0]['date'] : '--'
                ];

                $is_failed      = false;
                $number_steps   = 0;
                if (isset($runners[0]['steps'])) {
                    foreach ($runners[0]['steps']  as $step) {
                        if (isset($step['result'])) {
                            if ($step['result'][0]->status === 'failed') {
                                $is_failed = true;
                                break;
                            }
                        }
                    }
                    $number_steps = count($runners[0]['steps']);
                }

                if ($is_failed) {
                    $temp_flow['status'] = 'failed';
                }

                if ($number_steps === 0) {
                    $temp_flow['status'] = 'no-run';
                }

                $temp_flow['steps_number'] = $number_steps;
                $data_flows[] = $temp_flow;

            }
        }

        $this->get_view('part-flows', ['path' => 'admin', 'flows' => $data_flows, 'ui' => $this->admin_ui]);
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

        $get_runners = [];
        $is_result  = (isset($_GET['result']))? sanitize_text_field($_GET['result']) : null;
        $flow_id    = (isset($_GET['flow']))? sanitize_text_field($_GET['flow']) : null;
        if ($flow_id) {
            $runners = new cauto_test_runners();
            $runners->set_flow_id($flow_id);
            $get_runners = $runners->get_runners(
                [
                    'posts_per_page'    => $this->see_more_max,
                    'offset'            => 0 
                ]
            );

            $get_runners_all = $runners->get_runners(
                [
                    'posts_per_page'    => -1
                ]
            );
            $runner_total_count = count($get_runners_all);

        }

        if (isset($get_runners[0]['date'])) {
            $last_run = __($get_runners[0]['date'], 'autoqa-test-automation');
        } else {
            $last_run = __('No history', 'autoqa-test-automation');
        }
        
        $this->get_view('builder/part-builder-tools', [
            'path'          => 'admin' , 
            'details'       => $data, 
            'results'       => $get_runners, 
            'flow_id'       => $flow_id, 
            'last_run'      => $last_run,
            'is_results'    => (!empty($get_runners))? $is_result : null,
            'total'         => $runner_total_count
        ]);

    }


    /**
     * 
     * 
     * new_flow
     * @since 1.0.0
     * 
     * 
     */
    public function update_flow()
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


        $flowname       = (isset($_POST['name']))? sanitize_text_field($_POST['name']) : null;
        $stop_on_error  = ($_POST['stop_on_error'] === "true")? true : false;
        $is_edit        = (isset($_POST['is_edit']))? sanitize_text_field($_POST['is_edit']) : null;
        $redirect_to    = (isset($_POST['redirect_to']))? sanitize_text_field($_POST['redirect_to']) : null;

        $flow_id        = 0;
        if ($is_edit) {
            $flow_id = (int) $is_edit;
        }


        if ($flowname) {
            
            do_action('cauto_before_update_flow', ['flow_name' => $flowname, 'stop_on_error' => $stop_on_error]);
            
            $cflows = new cauto_test_automation();
            $cflows->set_name($flowname);
            $cflows->set_stop_on_error($stop_on_error);
            $post_id = $cflows->save_flow($flow_id);

            do_action('cauto_after_update_flow', $post_id, ['flow_name' => $flowname, 'stop_on_error' => $stop_on_error]);

            if ($post_id) {

                $redirect_url = admin_url('tools.php?page='.$this->settings_page.'&flow='.$post_id);
                if (strlen($redirect_to) > 0) {
                    $redirect_url = admin_url('tools.php?page=cauto-test-tools');
                }

                echo json_encode(
                    [
                        'status'        => 'success',
                        'message'       => __('Flow is added', 'autoqa-test-automation'),
                        'flow_id'       => $post_id,
                        'redirect_to'   => $redirect_url
                    ]
                );
            }
        } else {
            echo json_encode(
                [
                    'status'    => 'failed',
                    'message'   => __('Flow name is required', 'autoqa-test-automation')
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
                    'message'   => __('Invalid nonce please contact developer or clear your cache', 'autoqa-test-automation')
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
                        $data = json_decode($data);
                        $data = (array) $data;
                        foreach ($data as $x => $indata) {
                            $indata = (array) $indata;
                            if (is_array($indata['value'])) {
                                $indata['value'] = array_map('sanitize_text_field', $indata['value']);
                            } else {
                                $indata['value'] = sanitize_text_field($indata['value']);
                            }
                          
                            $data[$x] = $indata;
                        }
                    }
                    $step[$i] = $data;
                }
                $step_data[$index] = $step;
            }
        } 

       

        $step_data = apply_filters('autoqa_steps_before_save_filter', $step_data, $flow_id);
        
        if ($step_data && !empty($step_data) && $flow_id) {

            do_action('autoqa_before_save_steps_action', $step_data, $flow_id);
            //move to class
            update_post_meta($flow_id, $this->flow_steps_key, $step_data);

            do_action('autoqa_after_save_steps_action', $step_data, $flow_id);

            echo json_encode([
                'status'    => 'success',
                'message'   => ''
            ]);
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
        
            $step_group         = (isset($data[$steps['step']]['group']))? $data[$steps['step']]['group'] : null; 
            $step_indicator     = (isset($data[$steps['step']]['step_indicator']))? $data[$steps['step']]['step_indicator'] : null;
            $step_selectors     = (isset($step_indicator['selector']))? $step_indicator['selector'] : null;
            $icon               = (isset($data[$steps['step']]['icon']))? $data[$steps['step']]['icon'] : null;
            $step_label         = (isset($data[$steps['step']]['label']))? $data[$steps['step']]['label'] : null;

    
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

            if (!empty($describe_text_set)) {
                foreach ($describe_text_set as $index => $describe_set) {
                    $describe_text = str_replace("{".$index."}", $describe_set, $describe_text);
                }
            } else {
                $describe_text = null;
            }
            

            $flow_steps[] = [
                'step_group'        => $step_group,
                'step'              => $steps['step'],
                'icon_label'        => $icon.$step_label,
                'record'            => $steps['record'],
                'describe_label'    => $describe_text,
                'icon'              => $icon,
                'step_label'        => $step_label,
                'no_settings'       => (isset($data[$steps['step']]['no_settings']))? 1 : null
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
                    'message'   => __('Invalid nonce please contact developer or clear your cache', 'autoqa-test-automation')
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
                    'message'   => __('No flow is found', 'autoqa-test-automation')
                ]
            );
            exit();
        }

        $steps      = get_post_meta($flow_id, $this->flow_steps_key, true);
        $target_url = '';

        if (empty($steps)) {
            echo json_encode(
                [
                    'status'    => 'failed',
                    'message'   => __('No available steps to run', 'autoqa-test-automation')
                ]
            );
            exit();
        }

        //get the first step and validate
        if (!empty($steps)) {
            if ($steps[0]['step'] !== 'start') {
                echo json_encode(
                    [
                        'status'    => 'failed',
                        'message'   => __('No valid step to start', 'autoqa-test-automation')
                    ]
                );
                exit();
            }

            $target_url = (isset($steps[0]['record'][0]['value']))? $steps[0]['record'][0]['value'] : null;

            if (!$target_url) {
                echo json_encode(
                    [
                        'status'    => 'failed',
                        'message'   => __('No valid URL to start', 'autoqa-test-automation')
                    ]
                );
                exit();
            }
        }

        $runner = new cauto_test_runners();
        $runner->set_name($this->generate_runner_name());
        $runner->set_flow_id($flow_id);
        $runner->set_steps($steps);
        $runner_id = $runner->save();

        $params = [
            'flow_id'   => $flow_id,
            'runner_id' => $runner_id
        ];
        
        $target_url = get_site_url().'?'.http_build_query($params);

        if ($runner_id > 0) {
            echo json_encode(
                [
                    'status'    => 'success',
                    'message'   => '',
                    'url'       => $target_url
                ]
            );
        } else {
            echo json_encode(
                [
                    'status'    => 'failed',
                    'message'   => __('Failed to save runner, contact developer', 'autoqa-test-automation')
                ]
            );
        }

        exit();

    }

    /**
     * 
     * 
     * flow_details
     * 
     */
    public function flow_details()
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

        $flow_id = (isset($_POST['flow_id']))? sanitize_text_field($_POST['flow_id']) : null;

        if (!$flow_id) {
            echo json_encode(
                [
                    'status'        => 'failed',
                    'message'       => __('No flow is found, please contact developer', 'autoqa-test-automation') 
                ]
            );
            exit();
        };

        $flow       = new cauto_test_automation($flow_id);
        $details    = $flow->get_flow(); 
        
        if (!empty($details)) {
            echo json_encode(
                [
                    'status'        => 'success',
                    'data'          => [
                        'title'            => $details['flow_data']->post_title,
                        'stop_on_error'    => ($details['stop_on_error'])? true : false
                    ]
                ]
            );
        } else {
            echo json_encode(
                [
                    'status'        => 'failed',
                    'message'       => __('No flow is found, please contact developer', 'autoqa-test-automation') 
                ]
            );
        }

        exit();
    }

    /**
     * 
     * runner_results
     * load runner results
     * 
     */
    public function load_more_runner()
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
        $offset     = (isset($_POST['offset']))?  sanitize_text_field($_POST['offset']) : 0;

        if ($flow_id) {
            $runner_class   = new cauto_test_runners();
            $runner_class->set_flow_id($flow_id);
            $runners        = $runner_class->get_runners(
                [
                    'posts_per_page'    => $this->see_more_max,
                    'offset'            => $offset
                ]
            );

            $to_display_runners = [];
            foreach ($runners as $result) {
                
                $is_failed = 0;
                if (!empty($result['steps'])) {
                    foreach ($result['steps'] as $y => $step) {
                        if ($step['result'][0]->status === 'failed') {
                            $is_failed++;
                        }
                    }
                }

                $temp_result = [
                    'ID'        => $result['ID'],
                    'name'      => $result['name'],
                    'date'      => $result['date'],
                ];
                if ($is_failed > 0) {
                    $temp_result['flow_status'] = 'failed';
                }
                $to_display_runners[] = $temp_result;
            }

            if (!empty($to_display_runners)) {

                ob_start();
                $this->get_view('flow/part-results', ['path' => 'admin','runners' => $to_display_runners]);
                $reponse = ob_get_clean();

                echo json_encode(
                    [
                        'status'    => 'success',
                        'content'   => $reponse
                    ]
                );
            }

        }
        exit();

    }


    public function load_runner_steps()
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

        $runner_id  = (isset($_POST['runner_id']))? sanitize_text_field($_POST['runner_id']) : null;
        $flow_id    = (isset($_POST['flow_id']))? sanitize_text_field($_POST['flow_id']) : null;

        if ($runner_id && $flow_id) {
            $runner_class = new cauto_test_runners($runner_id);
            $runner_class->set_flow_id($flow_id);
            $runner_steps = $runner_class->get_runner_flow_step();
            if (!empty($runner_steps)) {
                ob_start();
                $this->get_view('flow/part-steps-results', ['path' => 'admin', 'steps' => $runner_steps]);
                $reponse = ob_get_clean();
                echo json_encode(
                    [
                        'status'    => 'success',
                        'content'   => $reponse
                    ]
                );
            }
        } else {
            echo json_encode(
                [
                    'status'    => 'failed',
                    'message'   => __('AutoQA Error: No runner ID or Flow ID found, please contact the developer.', 'autoqa-test-automation')
                ]
            );
        }

        exit();

    }

    public function load_runners($results = [], $flow_id = 0, $total = 0)
    {
        if (empty($results) || $flow_id === 0) return;

        foreach ($results as $i => $result) {
            $is_failed = 0;
            if (!empty($result['steps'])) {
                foreach ($result['steps'] as $step) {
                    if (!empty($step['result'])) {
                        if (isset($step['result'][0]->status)) {
                            if ($step['result'][0]->status === 'failed') {
                                $is_failed++;
                            }
                        }
                    }
                }
            }
            if ($is_failed > 0) {
                $results[$i]['flow_status'] = 'failed';
            }
        }

        $results = apply_filters('autoqa-results-list', $results);
        $this->get_view('flow/results', ['path' => 'admin', 'results' => $results, 'runner_count' => $total, 'flow_id' => $flow_id]);
    }

    public function load_variables()
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
        $default_runner_variables = $this->runner_available_variables($flow_id);

        echo json_encode(
            [
                'status'    => 'success',
                'variables' => $default_runner_variables
            ]
        );
        exit();

    }

    public function delete_flow()
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

        $flow_id = (isset($_POST['flow_id']))? sanitize_text_field($_POST['flow_id']) : null;

        if ($flow_id) {

            $runners = new cauto_test_runners();
            $runners->set_flow_id($flow_id);
            $get_runners = $runners->get_runners();

            if (!empty($get_runners)) {
                foreach ($get_runners as $index => $runner) {
                    wp_delete_post($runner['ID']);
                }
            }

            $deleted = wp_delete_post($flow_id);

            if ($deleted) {

                $url = admin_url('tools.php?page=cauto-test-tools');
                echo json_encode([
                    'status'    => 'success',
                    'redirect'  => $url
                ]);
            }
        }

        exit();
        
    }

    public function cauto_save_settings()
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

        $duration = (isset($_POST['duration']))? $_POST['duration'] : null;
        $options = get_option($this->settings_key);
        if ($duration) {
            if (!$options) {
                update_option( $this->settings_key, ['step-duration' => $duration ] );
            } else {
                $options['step-duration'] = $duration;
                update_option( $this->settings_key, $options );
            }
        } else {
            if ($options) {
                if (isset($options['step-duration'])) {
                    unset($options['step-duration']);
                    update_option( $this->settings_key, $options );
                }
            }
        }

        echo json_encode([
            'status'    => 'success'
        ]);

        exit();
    }

    public function load_select2_data()
    {
        if ( !wp_verify_nonce( $_GET['nonce'], $this->nonce ) ) {
            echo json_encode(
                [
                    'status'    => 'failed',
                    'message'   => __('Invalid nonce please contact developer or clear your cache', 'autoqa-test-automation')
                ]
            );
            exit();
        }

        $search  = (isset($_GET['search']))? sanitize_text_field($_GET['search']) : null;
        $source  = (isset($_GET['source']))? $_GET['source'] : null;
       
        if ($search && $source) {

            $source = json_decode(stripslashes($source));
            $source = array_map('sanitize_text_field', $source);
            
            $args = [
                'posts_per_page'    => -1,
                'post_type'         => $source,
                'orderby'           => 'post_title',
                'order'             => 'ASC',
                's'                 => $search
            ];
            $posts = get_posts($args);
            $result = [];

            if ($posts) {
                foreach ($posts as $post) {
                    $result[] = ['id' => $post->ID.' - '.$post->post_title, 'text' => $post->ID.' - '.$post->post_title];
                }
            }

            echo json_encode($result);

        } 

        exit();
    }

    
}