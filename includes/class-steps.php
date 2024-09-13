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
                'label'     => __('Start', 'autoqa-test-automation'),
                'settings'      => [
                    [
                        'field' => 'input',
                        'attr'  => [
                            'type'  => 'text',
                            'id'    => 'cauto_start_name',
                            'class' => 'cauto-step-nodes cauto-start-step cauto-field wide'
                        ],
                        'label'         => __('Page URL', 'autoqa-test-automation')
                    ]
                ],
                'icon'              => '<span class="dashicons dashicons-laptop"></span>',
                'group'             => 'default',
                'step_indicator'    => [
                    'selector'      => '#cauto_start_name',
                    'describe_text' => __(' to open {#cauto_start_name}', 'autoqa-test-automation')
                ],
                'callback'          => 'cauto_default_do_start' 
            ],
            'events_divider'    => [
                'divider'       => true,
                'label'         => __('Input', 'autoqa-test-automation') 
            ],
            'click' => [
                'label'         => __('Click', 'autoqa-test-automation'),
                'settings'      => [
                    [
                        'field' => 'select',
                        'attr'  => [
                            'id'    => 'cauto_step_selector_type',
                            'class' => 'cauto-step-nodes cauto-check-text-step cauto-field'
                        ],
                        'label'     => __('Attribute', 'autoqa-test-automation'),
                        'options'   => [
                            'class' => __('Class', 'autoqa-test-automation'),
                            'id'    => __('ID', 'autoqa-test-automation'),
                            'xpath' => __('Xpath', 'autoqa-test-automation')
                        ]
                    ],
                    [
                        'field' => 'select',
                        'attr'  => [
                            'id'    => 'cauto_click_type_opt',
                            'class' => 'cauto-step-nodes cauto-check-text-step cauto-field wide'
                        ],
                        'label'     => __('Click Type', 'autoqa-test-automation'),
                        'options'   => [
                            'single'    => __('Single', 'autoqa-test-automation'),
                            'double'    => __('Double', 'autoqa-test-automation')
                        ]
                    ],
                    [
                        'field' => 'input',
                        'attr'  => [
                            'type'  => 'text',
                            'id'    => 'cauto_step_selector',
                            'class' => 'cauto-step-nodes cauto-check-text-step cauto-field wide'
                        ],
                        'label' => __('Selector', 'autoqa-test-automation')
                    ],
                    [
                        'field' => 'input',
                        'attr'  => [
                            'type'  => 'text',
                            'id'    => 'cauto_step_click_alias',
                            'class' => 'cauto-step-nodes cauto-check-text-step cauto-field wide',
                            'placeholder'   => __('Element temporary name', 'autoqa-test-automation')
                        ],
                        'label'         => __('Field Name', 'autoqa-test-automation')
                    ]
                ],
                'icon'      => '<span class="cauto-icon cauto-icon-point-up"></span>',
                'group'     => 'events',
                'step_indicator'    => [
                    'selector'      => '#cauto_step_click_alias',
                    'describe_text' => __(' to {#cauto_step_click_alias}', 'autoqa-test-automation')
                ],
                'callback'  => 'cauto_default_click_step' 
            ],
            'set-text'          => [
                'label'         => __('Set Text', 'autoqa-test-automation'),
                'settings'      => [
                    [
                        'field' => 'select',
                        'attr'  => [
                            'id'    => 'cauto_step_selector_type',
                            'class' => 'cauto-step-nodes cauto-check-text-step cauto-field'
                        ],
                        'label'     => __('Attribute', 'autoqa-test-automation'),
                        'options'   => [
                            'class' => __('Class', 'autoqa-test-automation'),
                            'id'    => __('ID', 'autoqa-test-automation'),
                            'xpath' => __('Xpath', 'autoqa-test-automation')
                        ]
                    ],
                    [
                        'field' => 'input',
                        'attr'  => [
                            'id'    => 'cauto_step_set_text_selector',
                            'type'  => 'text',
                            'class' => 'cauto-step-nodes cauto-set-text-step cauto-field wide'
                        ],
                        'label'     => __('Selector', 'autoqa-test-automation')
                    ],
                    [
                        'field' => 'input',
                        'attr'  => [
                            'id'    => 'cauto_step_set_text_selector_alias',
                            'type'  => 'text',
                            'class' => 'cauto-step-nodes cauto-set-text-step cauto-field wide'
                        ],
                        'label'     => __('Field Name (Alias)', 'autoqa-test-automation')
                    ],
                    [
                        'field' => 'input',
                        'attr'  => [
                            'id'    => 'cauto_step_set_text',
                            'type'  => 'text',
                            'class' => 'cauto-step-nodes cauto-set-text-step cauto-field wide'
                        ],
                        'label'     => __('Text', 'autoqa-test-automation')
                    ]
                ],
                'group'     => 'events',
                'icon'      => '<span class="cauto-icon cauto-icon-keyboard"></span>',
                'step_indicator'    => [
                    'selector'      => ['#cauto_step_set_text_selector_alias', '#cauto_step_set_text'],
                    'describe_text' => __(' {#cauto_step_set_text_selector_alias} to {#cauto_step_set_text}', 'autoqa-test-automation')
                ],
                'callback'  => 'cauto_default_set_text_step'  
            ],
            'reload' => [
                'label'         => __('Reload', 'autoqa-test-automation'),
                'settings'      => [],
                'icon'          => '<span class="dashicons dashicons-update"></span>',
                'group'         => 'events',
                'callback'      => 'cauto_default_reload_page',
                'no_settings'   => true
            ],
            'check_divider' => [
                'divider'    => true,
                'label'     => __('Check', 'autoqa-test-automation'), 
            ],
            'check-title' => [
                'label'     => __('Page Title', 'autoqa-test-automation'),
                'settings'      => [
                    [
                        'field' => 'select',
                        'attr'  => [
                            'id'    => 'cauto_field_check_title_condition',
                            'class' => 'cauto-step-nodes cauto-check-text-step cauto-field wide block'
                        ],
                        'label'     => __('Condition', 'autoqa-test-automation'),
                        'options'   => [
                            'equals to'         => __('Equals to', 'autoqa-test-automation'),
                            'not equals to'     => __('Not equals to', 'autoqa-test-automation'),
                            'contains with'     => __('Contains with', 'autoqa-test-automation'),
                            'start with'        => __('Start with', 'autoqa-test-automation'),
                            'end with'          => __('End with', 'autoqa-test-automation')
                        ]
                    ],
                    [
                        'field' => 'input',
                        'attr'  => [
                            'type'  => 'text',
                            'id'    => 'cauto_field_check_title',
                            'class' => 'cauto-step-nodes cauto-check-text-step cauto-field wide'
                        ],
                        'label' => __('Title', 'autoqa-test-automation')
                    ]
                ],
                'icon'      => '<span class="dashicons dashicons-admin-site"></span>',
                'group'     => 'check',
                'step_indicator'    => [
                    'selector'      => ['#cauto_field_check_title_condition', '#cauto_field_check_title'],
                    'describe_text' => __(' {#cauto_field_check_title_condition} {#cauto_field_check_title}', 'autoqa-test-automation')
                ],
                'callback'  => 'cauto_default_check_page_title_step'
            ],
        ];

        return apply_filters('cauto-ui-steps', $steps);
    }
}

new cauto_steps();