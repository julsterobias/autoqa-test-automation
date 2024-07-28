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
use cauto\includes\class_utils;

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

class class_admin_ui extends class_utils
{
    public function __construct()
    {
        //prepare UI
        add_action('cauto_top_control', [$this, 'load_top_controls']);
        //load new flow
        add_action('cauto_load_new_flow', [$this, 'load_new_flow']);

        add_action('cauto_load_ui', [$this, 'render_ui'], 10, 3);
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
                    'class' => 'cauto-field',
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


}