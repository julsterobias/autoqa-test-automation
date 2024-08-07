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
        //load step controls
        add_action('cauto_step_controls', [$this, 'load_step_controls'], 10, 3);

        //load flow hidden field
        add_action('cauto_load_flow_id', [$this, 'load_flow_hidden_field']);
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
                'icon'              => '<span class="dashicons dashicons-laptop"></span>',
                'group'             => 'default',
                'step_indicator'    => [
                    'selector'      => '#cauto_start_name',
                    'describe_text' => __('to open', 'codecorun-test-automation')
                ] 
            ],
            'events_divider' => [
                'divider'    => true,
                'label'     => __('Events', 'codecorun-test-automation') 
            ],
            'click' => [
                'label'     => __('Click', 'codecorun-test-automation'),
                'settings'      => [
                    [
                        'field' => 'select',
                        'attr'  => [
                            'id'    => 'cauto_step_selector_type',
                            'class' => 'cauto-step-nodes cauto-check-text-step cauto-field'
                        ],
                        'label'     => __('Attribute', 'codecorun-test-automation'),
                        'options'   => [
                            'class' => __('Class', 'codecorun-test-automation'),
                            'id'    => __('ID', 'codecorun-test-automation'),
                            'xpath' => __('Xpath', 'codecorun-test-automation')
                        ]
                    ],
                    [
                        'field' => 'input',
                        'attr'  => [
                            'type'  => 'text',
                            'id'    => 'cauto_step_selector',
                            'class' => 'cauto-step-nodes cauto-check-text-step cauto-field wide'
                        ],
                        'label' => __('Selector', 'codecorun-test-automation')
                    ],
                    [
                        'field' => 'input',
                        'attr'  => [
                            'type'  => 'text',
                            'id'    => 'cauto_step_click_alias',
                            'class' => 'cauto-step-nodes cauto-check-text-step cauto-field wide',
                            'placeholder'   => __('Element temporary name', 'codecorun-test-automation')
                        ],
                        'label'         => __('Alias', 'codecorun-test-automation')
                    ]
                ],
                'icon'      => '<span class="cauto-icon cauto-icon-point-up"></span>',
                'group'     => 'events',
                'step_indicator'    => [
                    'selector'      => '#cauto_step_click_alias',
                    'describe_text' => __('to', 'codecorun-test-automation')
                ] 
            ],
            'check_divider' => [
                'divider'    => true,
                'label'     => __('Check', 'codecorun-test-automation') 
            ],
            'check-title' => [
                'label'     => __('Page Title', 'codecorun-test-automation'),
                'settings'      => [
                    [
                        'field' => 'select',
                        'attr'  => [
                            'id'    => 'cauto_field_check_title_condition',
                            'class' => 'cauto-step-nodes cauto-check-text-step cauto-field wide block'
                        ],
                        'label'     => __('Condition', 'codecorun-test-automation'),
                        'options'   => [
                            'equals to'     => __('Equals to', 'codecorun-test-automation'),
                            'not equals to' => __('Not equals to', 'codecorun-test-automation'),
                            'contains with'      => __('Contains with', 'codecorun-test-automation'),
                            'start with'    => __('Start with', 'codecorun-test-automation'),
                            'end with'      => __('End with', 'codecorun-test-automation')
                        ]
                    ],
                    [
                        'field' => 'input',
                        'attr'  => [
                            'type'  => 'text',
                            'id'    => 'cauto_field_check_title',
                            'class' => 'cauto-step-nodes cauto-check-text-step cauto-field wide'
                        ],
                        'label' => __('Title', 'codecorun-test-automation')
                    ]
                ],
                'icon'      => '<span class="dashicons dashicons-admin-site"></span>',
                'group'     => 'check',
                'step_indicator'    => [
                    'selector'      => ['#cauto_field_check_title_condition', '#cauto_field_check_title'],
                    'describe_text' => null
                ] 
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
                'icon'  => '<span class="dashicons dashicons-no"></span>'
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

            $field_ids = [];
            foreach ($this->steps[$type]['settings'] as $step) {
                if (isset($step['attr']['id'])) {
                    $field_ids[] = [
                        'field'     => $step['field'],
                        'type'      => (isset($step['attr']['type']))? $step['attr']['type'] : null,
                        'class'     => (isset($step['attr']['class']))? $step['attr']['class'] : null,
                        'id'        => $step['attr']['id']
                    ];
                }
            }

            $describe_text = $this->steps[$type]['step_indicator'];

            ob_start();
            $this->get_view('steps/'.$type, ['path' => 'admin', 'config' => $start_ui, 'field_ids' => $field_ids, 'step_indicator' => $describe_text]);
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

    public function load_step_controls($type = null, $field_ids = [], $step_indicator = [])
    {

        $right_buttons = [
            [
                'field'     => 'button',
                'attr'      => [
                    "class"     => "cauto-top-class cauto-button primary caut-ripple",
                    "id"        => "cauto-save-step",
                    "title"     => __('Save Changes', 'condecorun-test-automation')
                ],
                'label'     => __('Save', 'condecorun-test-automation'),
                'icon'      => '<span class="dashicons dashicons-saved"></span>'
            ],
            [
                'field'     => 'button',
                'attr'      => [
                    "class"     => "cauto-top-class cauto-button caut-ripple cauto-cancel",
                    "id"        => "",
                    "title"     => __('Abort Changes', 'condecorun-test-automation')
                ],
                'label'     =>  __('Cancel', 'condecorun-test-automation'),
                'icon'      => '<span class="dashicons dashicons-no"></span>'
            ],
        ];
        $right_buttons = apply_filters('cauto_step_config_buttons', $right_buttons);
        $right_buttons = $this->prepare_attr($right_buttons);


        $left_control = [
            [
                'field'     => 'button',
                'attr'      => [
                    "class"     => "cauto-button-icon",
                    "id"        => "cauto-delete-step",
                    "title"     => __('Delete Step', 'condecorun-test-automation')
                ],
                'label'     => null,
                'icon'      => '<span class="dashicons dashicons-trash"></span>'
            ],
            [
                'field'     => 'button',
                'attr'      => [
                    "class"     => "cauto-top-class cauto-button caut-ripple",
                    "id"        => "cauto-delete-step-confirm",
                    "title"     => __('Detele', 'condecorun-test-automation')
                ],
                'label'     => __("Yes", 'condecorun-test-automation'),
                'text'      => __("You sure?", 'condecorun-test-automation'),
                'icon'      => null,
                'hidden'    => true
            ]
        ];
        $left_control = apply_filters('cauto_step_config_delete_buttons', $left_control);
        $left_control = $this->prepare_attr($left_control);

        $this->get_view('steps/part-controls', ['path' => 'admin', 'field_ids' => $field_ids, 'right_controls' => $right_buttons, 'left_controls' => $left_control, 'step_indicator' => $step_indicator]);

    }

    public function load_flow_hidden_field($flow_id)
    {
        $fields = [
            [
                'field'     => 'input',
                'attr'      => [
                    "type"      => "hidden",
                    "class"     => "",
                    "id"        => "cauto-flow-id",
                    "title"     => "",
                    "value"     => esc_attr($flow_id)
                ],
                'label'     => '',
                'icon'      => ''
            ]
        ];
        $fields = $this->prepare_attr($fields);
        $this->render_ui(['fields' => $fields], 'fields', []);
    }


}