<?php
/**
 * 
 * 
 * class_admin_ui
 * Class to render admin UIs
 * @since 1.0.0
 * 
 * 
 */
namespace cauto\admin\includes;
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

class cauto_admin_ui extends cauto_utils
{

    private array $steps = [];

    public function __construct()
    {

        $this->steps = $this->init_steps();

        //prepare UI
        add_action('cauto_top_control', [$this, 'load_top_controls']);
        //load new flow
        add_action('cauto_load_new_flow', [$this, 'load_new_flow']);
        //load ui
        add_action('cauto_load_ui', [$this, 'render_ui'], 10, 3);
        //load builder buttons
        add_action('cauto_load_builder_control', [$this, 'builder_buttons']);
        //load steps in builder
        add_action('cauto_load_builder_steps', [$this,'load_steps']);
        //add run button to saved flows
        add_action('cauto_flow_run_button', [$this, 'load_run_button']);
        //add flow meta
        add_action('cauto_flow_meta_details', [$this, 'load_flow_meta']);

        
        //load UI from ajax
        add_action('wp_ajax_cauto_steps_ui', [$this, 'load_step_ui']);
        //load start popup
        add_action('cauto_load_step_config', [$this, 'load_step_popup']);
    }

    public function init_steps()
    {
        $steps = [
            'start' => [
                'label'     => __('Start', 'codecorun-test-automation'),
                'settings'      => [
                    [
                        'field' => 'input',
                        'attr'  => [
                            'type'  => 'text',
                            'id'    => 'cauto_start_name',
                            'class' => 'cauto-step-nodes cauto-start-step cauto-field wide'
                        ],
                        'label'         => __('Page URL', 'codecorun-test-automation')
                    ]
                ],
                'icon'      => '<span class="dashicons dashicons-laptop"></span>',
                'group'     => 'default'
            ],
            'events_divider' => [
                'divider'    => true,
                'label'     => __('Events', 'codecorun-test-automation') 
            ],
            'click' => [
                'label'     => __('Click', 'codecorun-test-automation'),
                'settings'      => [
                    [
                        'field' => 'input',
                        'attr'  => [
                            'type'  => 'text',
                            'id'    => uniqid(),
                            'class' => 'cauto-step-nodes cauto-click-step'
                        ],
                        'label' => __('Identifier', 'codecorun-test-automation')
                    ]
                ],
                'icon'      => '<span class="cauto-icon cauto-icon-point-up"></span>',
                'group'     => 'events'
            ],
            'check_divider' => [
                'divider'    => true,
                'label'     => __('Check', 'codecorun-test-automation') 
            ],
            'check-title' => [
                'label'     => __('Page Title', 'codecorun-test-automation'),
                'settings'      => [
                    [
                        'field' => 'input',
                        'attr'  => [
                            'type'  => 'text',
                            'id'    => uniqid(),
                            'class' => 'cauto-step-nodes cauto-check-text-step cauto-field wide'
                        ],
                        'label' => __('Title', 'codecorun-test-automation')
                    ]
                ],
                'icon'      => '<span class="dashicons dashicons-admin-site"></span>',
                'group'     => 'check'
            ],
        ];

        return apply_filters('cauto-ui-steps', $steps);
    }

    public function render_ui($data = [], $type = '', $value = [])
    {
        if (empty($data) || !$type) return;

        switch($type) {
            case 'fields':
                $this->get_view('ui/fields', ['path' => 'admin', 'data' => $data]);
                break;
            case 'buttons':
                $this->get_view('ui/buttons', ['path' => 'admin', 'data' => $data]);
                break;
            case 'steps':
                $this->get_view('builder/part-builder-steps', ['path' => 'admin', 'data' => $data]);
                break;
        }
    }

    public function load_top_controls()
    {
        $controls = [
            [
                'field'  => 'button',
                'attr'  => [
                    "class"     => "cauto-top-class cauto-button primary caut-ripple",
                    "id"        => "cauto-new-case"
                ],
                'label' => __('New Test Flow', 'codecorun-test-automation'),
                'icon'  => '<span class="dashicons dashicons-insert"></span>'
            ],
            [
                'field'  => 'button',
                'attr'  => [
                    "class" => "cauto-top-class cauto-button caut-ripple",
                    "id"    => "cauto-support"
                ],
                'label' =>  __('Help', 'codecorun-test-automation'),
                'icon'  => '<span class="dashicons dashicons-sos"></span>'
            ],
           
        ];
        $controls = apply_filters('cauto_top_controls', $controls);
        $controls = $this->prepare_attr($controls);
        $this->render_ui(['buttons' => $controls], 'buttons', []);
    }

    public function load_new_flow()
    {
        $fields = [
            [
                'field'  => 'input',
                'attr'  => [
                    'id'    => 'cauto-new-flow-name',
                    'class' => 'cauto-field wide',
                    'type'  => 'text'
                ],
                'label'     => __('Flow Name', 'codecorun-test-automation'),
                'icon'      => null
            ],
            [
                'field'  => 'toggle',
                'attr'  => [
                    'id'    => 'cauto-flow-stop-on-error',
                    'class' => 'cauto-toggle',
                    'type'  => 'checkbox'
                ],
                'label'     => __('Stop on error', 'codecorun-test-automation'),
                'icon'      => null
            ]
        ];

        $fields = apply_filters('cauto_new_flow_fields', $fields);
        $fields = $this->prepare_attr($fields);

        $buttons = [
            [
                'field'  => 'button',
                'attr'  => [
                    'id'    => 'cauto-save-new-flow',
                    'class' => 'cauto-top-class cauto-button primary caut-ripple'
                ],
                'label'     => __('Save Flow', 'codecorun-test-automation'),
                'icon'      => '<span class="dashicons dashicons-saved"></span>'
            ],
            [
                'field' => 'button',
                'attr'  => [
                    "class" => "cauto-top-class cauto-button caut-ripple cauto-cancel"
                ],
                'label' =>  __('Cancel', 'codecorun-test-automation'),
                'icon'  => '<span class="dashicons dashicons-redo"></span>'
            ]
        ];

        $buttons = apply_filters('cauto_new_flow_buttons', $buttons);
        $buttons = $this->prepare_attr($buttons);
        $this->get_view('popups/new-flow', ['path' => 'admin', 'fields' => $fields, 'buttons' => $buttons]);
    }

    public function builder_buttons()
    {
        $controls = [
            [
                'field'     => 'button',
                'attr'      => [
                    "class"     => "cauto-button-icon primary",
                    "id"        => "cauto-run-flow",
                    "title"     => __('Run Flow', 'condecorun-test-automation')
                ],
                'label'     => null,
                'icon'      => '<span class="dashicons dashicons-controls-play"></span>'
            ],
            [
                'field'     => 'button',
                'attr'      => [
                    "class"     => "cauto-button-icon",
                    "id"        => "cauto-save-flow",
                    "title"     => __('Save Changes', 'condecorun-test-automation')
                ],
                'label'     =>  null,
                'icon'      => '<span class="dashicons dashicons-saved"></span>'
            ],
            [
                'field'     => 'button',
                'attr'      => [
                    "class"     => "cauto-button-icon",
                    "id"        => "cauto-save-flow",
                    "title"     => __('Delete Flow', 'condecorun-test-automation')
                ],
                'label'     =>  null,
                'icon'      => '<span class="dashicons dashicons-trash"></span>'
            ]
           
        ];
        $controls = apply_filters('cauto_builder_buttons', $controls);
        $controls = $this->prepare_attr($controls);
        $this->render_ui(['buttons' => $controls], 'buttons', []);
    }


    public function load_steps()
    {
        $this->render_ui($this->steps, 'steps', []);
    }


    public function load_run_button($flow = null)
    {
        if (!$flow) return;

        $run_button = [
            [
                'field'     => 'button',
                'attr'      => [
                    "class"     => "cauto-button-icon primary cauto-run-saved-flow",
                    "id"        => uniqid(),
                    "title"     => __('Run Flow', 'condecorun-test-automation'),
                    "data-flow" => $flow->ID
                ],
                'label'     => null,
                'icon'      => '<span class="dashicons dashicons-controls-play"></span>'
            ],
        ];
        $run_button = $this->prepare_attr($run_button);
        $this->render_ui(['buttons' => $run_button], 'buttons', []);

    }

    
    public function load_flow_meta($flow)
    {
        $flow = apply_filters('cauto_before_load_meta', $flow);
        //get flow meta

        $last_run   = get_post_meta($flow->ID, '_cauto_last_run', true);
        $steps      = get_post_meta($flow->ID, '_cauto_flow_steps', true);
        $status     = get_post_meta($flow->ID, '_cauto_flow_status', true);

        $metas = [
            'last_run'  => ($last_run)? $last_run : '--',
            'steps'     => ($steps)? $steps : 0,
            'status'    => ($status)? $status : '--'
        ];

        $metas = apply_filters('cauto_flow_metas', $metas);
        $this->get_view('flow/meta', ['path' => 'admin', 'flow' => $flow, 'metas' => $metas]);
    }


    public function load_step_ui()
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

        $type = (isset($_POST['type']))? $_POST['type'] : null;

        if (isset($this->steps[$type])) {

            $start_ui = $this->prepare_attr($this->steps[$type]['settings']);


            ob_start();
            $this->get_view('steps/'.$type, ['path' => 'admin', 'config' => $start_ui]);
            $reponse = ob_get_clean();

            echo json_encode(
                [
                    'status'    => 'success',
                    'html'   => $reponse
                ]
            );

        } else {
            echo json_encode(
                [
                    'status'    => 'failed',
                    'message'   => __('Step type is not found', 'codecorun-test-automation')
                ]
            );
        }
        exit();

        
    }

    public function load_step_popup()
    {
        $this->get_view('popups/step-config', ['path' => 'admin']);
    }


}