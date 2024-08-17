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
use cauto\includes\cauto_steps;

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

        $this->steps = cauto_steps::steps();

        //prepare UI
        add_action('cauto_top_control', [$this, 'load_top_controls']);
        //load new flow
        add_action('cauto_load_new_flow', [$this, 'load_new_flow']);
        //load ui
        add_action('cauto_load_ui', [$this, 'render_ui'], 10, 3);
        //load builder buttons
        add_action('cauto_load_builder_control', [$this, 'builder_buttons'], 10, 1);
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

    public function render_ui($data = [], $type = '', $value = [])
    {
        if (empty($data) || !$type) return;

        //add more fields in the future
        switch($type) {
            case 'fields':
                $this->get_view('ui/fields', ['path' => 'admin', 'data' => $data, 'value' => $value]);
                break;
            case 'buttons':
                $this->get_view('ui/buttons', ['path' => 'admin', 'data' => $data, 'value' => $value]);
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

    public function builder_buttons($data)
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
                    "id"        => "cauto-delete-flow",
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

        $type           = (isset($_POST['type']))? sanitize_text_field($_POST['type']) : null;
        $saved_steps    = (isset($_POST['saved_data']))? json_decode(stripslashes($_POST['saved_data'])) : null;

        if (isset($this->steps[$type])) {

            $setting_ui = $this->prepare_attr($this->steps[$type]['settings']);

            $field_ids = [];
            foreach ($this->steps[$type]['settings'] as $step) {
                if (isset($step['attr']['id']) || isset($step['attr']['class'])) {
                    $field_ids[] = [
                        'field'     => $step['field'],
                        'type'      => (isset($step['attr']['type']))? $step['attr']['type'] : null,
                        'class'     => (isset($step['attr']['class']))? $step['attr']['class'] : null,
                        'id'        => $step['attr']['id']
                    ];
                }
            }

            $describe_text = (isset($this->steps[$type]['step_indicator']))? $this->steps[$type]['step_indicator'] : null;

            ob_start();
            $this->get_view('steps/'.$type, ['path' => 'admin', 'config' => $setting_ui, 'field_ids' => $field_ids, 'step_indicator' => $describe_text, 'saved_steps' => $saved_steps]);
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