<?php 
/**
 * 
 * 
 * test_automation
 * @since 1.0.0
 * 
 * 
 */

namespace cauto\includes;

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

class cauto_steps
{

    public static function steps()
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
                    'describe_text' => __(' to open {#cauto_start_name}', 'codecorun-test-automation')
                ],
                'callback'          => 'cauto_default_do_start' 
            ],
            'events_divider'    => [
                'divider'       => true,
                'label'         => __('Input', 'codecorun-test-automation') 
            ],
            'click' => [
                'label'         => __('Click', 'codecorun-test-automation'),
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
                        'label'         => __('Field Name', 'codecorun-test-automation')
                    ]
                ],
                'icon'      => '<span class="cauto-icon cauto-icon-point-up"></span>',
                'group'     => 'events',
                'step_indicator'    => [
                    'selector'      => '#cauto_step_click_alias',
                    'describe_text' => __(' to {#cauto_step_click_alias}', 'codecorun-test-automation')
                ],
                'callback'  => 'cauto_default_click_step' 
            ],
            'double-click' => [
                'label'     => __('Double Click', 'codecorun-test-automation'),
                'settings'      => [
                    [
                        'field' => 'select',
                        'attr'  => [
                            'id'    => 'cauto_step_double_click_selector_type',
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
                            'id'    => 'cauto_step_double_click_selector',
                            'class' => 'cauto-step-nodes cauto-check-text-step cauto-field wide'
                        ],
                        'label' => __('Selector', 'codecorun-test-automation')
                    ],
                    [
                        'field' => 'input',
                        'attr'  => [
                            'type'  => 'text',
                            'id'    => 'cauto_step_double_click_alias',
                            'class' => 'cauto-step-nodes cauto-check-text-step cauto-field wide',
                            'placeholder'   => __('Element temporary name', 'codecorun-test-automation')
                        ],
                        'label'         => __('Field Name', 'codecorun-test-automation')
                    ]
                ],
                'icon'      => '<span class="cauto-icon cauto-icon-point-up"></span>',
                'group'     => 'events',
                'step_indicator'    => [
                    'selector'      => '#cauto_step_double_click_alias',
                    'describe_text' => __(' to {#cauto_step_double_click_alias}', 'codecorun-test-automation')
                ],
                'callback'  => 'cauto_default_double_click_step'  
            ],
            'set-text'           => [
                'label'         => __('Set Text', 'codecorun-test-automation'),
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
                            'id'    => 'cauto_step_set_text_selector',
                            'type'  => 'text',
                            'class' => 'cauto-step-nodes cauto-set-text-step cauto-field wide'
                        ],
                        'label'     => __('Selector', 'codecorun-test-automation')
                    ],
                    [
                        'field' => 'input',
                        'attr'  => [
                            'id'    => 'cauto_step_set_text_selector_alias',
                            'type'  => 'text',
                            'class' => 'cauto-step-nodes cauto-set-text-step cauto-field wide'
                        ],
                        'label'     => __('Field Name', 'codecorun-test-automation')
                    ],
                    [
                        'field' => 'input',
                        'attr'  => [
                            'id'    => 'cauto_step_set_text',
                            'type'  => 'text',
                            'class' => 'cauto-step-nodes cauto-set-text-step cauto-field wide'
                        ],
                        'label'     => __('Text', 'codecorun-test-automation')
                    ]
                ],
                'group'     => 'events',
                'icon'      => '<span class="cauto-icon cauto-icon-keyboard"></span>',
                'step_indicator'    => [
                    'selector'      => ['#cauto_step_set_text_selector_alias', '#cauto_step_set_text'],
                    'describe_text' => __(' {#cauto_step_set_text_selector_alias} to {#cauto_step_set_text}', 'codecorun-test-automation')
                ],
                'callback'  => 'cauto_default_set_text_step'  
            ],
            'check_divider' => [
                'divider'    => true,
                'label'     => __('Check', 'codecorun-test-automation'), 
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
                    'describe_text' => __(' {#cauto_field_check_title_condition} {#cauto_field_check_title}', 'codecorun-test-automation')
                ],
                'callback'  => 'cauto_default_check_page_title_step'
            ],
        ];

        return apply_filters('cauto-ui-steps', $steps);
    }
}

new cauto_steps();