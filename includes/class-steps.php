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
                'icon'              => '<span class="cauto-icon cauto-icon-start"></span>',
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
                'icon'      => '<span class="cauto-icon cauto-icon-click"></span>',
                'group'     => 'events',
                'step_indicator'    => [
                    'selector'      => '#cauto_step_click_alias',
                    'describe_text' => __(' to {#cauto_step_click_alias}', 'autoqa-test-automation')
                ],
                'callback'  => 'cauto_default_click_step' 
            ],
            'hover' => [
                'label'         => __('Hover', 'autoqa-test-automation'),
                'settings'      => [
                    [
                        'field' => 'select',
                        'attr'  => [
                            'id'    => 'cauto_step_hover_selector_type',
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
                            'type'  => 'text',
                            'id'    => 'cauto_step_hover_selector',
                            'class' => 'cauto-step-nodes cauto-check-text-step cauto-field wide'
                        ],
                        'label' => __('Selector', 'autoqa-test-automation')
                    ],
                    [
                        'field' => 'input',
                        'attr'  => [
                            'type'  => 'text',
                            'id'    => 'cauto_step_hover_alias',
                            'class' => 'cauto-step-nodes cauto-check-text-step cauto-field wide',
                            'placeholder'   => __('Element temporary name', 'autoqa-test-automation')
                        ],
                        'label'         => __('Field Name', 'autoqa-test-automation')
                    ]
                ],
                'icon'      => '<span class="cauto-icon cauto-icon-click"></span>',
                'group'     => 'events',
                'step_indicator'    => [
                    'selector'      => '#cauto_step_hover_alias',
                    'describe_text' => __(' on {#cauto_step_hover_alias}', 'autoqa-test-automation')
                ],
                'callback'  => 'cauto_default_hover_step' 
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
                'icon'      => '<span class="cauto-icon cauto-icon-text"></span>',
                'step_indicator'    => [
                    'selector'      => ['#cauto_step_set_text_selector_alias', '#cauto_step_set_text'],
                    'describe_text' => __(' {#cauto_step_set_text_selector_alias} to {#cauto_step_set_text}', 'autoqa-test-automation')
                ],
                'callback'  => 'cauto_default_set_text_step'  
            ],
            'set-select'        => [
                'label'         => __('Set Select', 'autoqa-test-automation'),
                'settings'      => [
                    [
                        'field' => 'select',
                        'attr'  => [
                            'id'    => 'cauto_step_select_selector_type',
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
                            'id'    => 'cauto_step_set_select_selector',
                            'type'  => 'text',
                            'class' => 'cauto-step-nodes cauto-set-text-step cauto-field wide'
                        ],
                        'label'     => __('Selector', 'autoqa-test-automation')
                    ],
                    [
                        'field' => 'input',
                        'attr'  => [
                            'id'    => 'cauto_step_set_select_selector_alias',
                            'type'  => 'text',
                            'class' => 'cauto-step-nodes cauto-set-text-step cauto-field wide'
                        ],
                        'label'     => __('Field Name (Alias)', 'autoqa-test-automation')
                    ],
                    [
                        'field' => 'input',
                        'attr'  => [
                            'id'    => 'cauto_step_set_select',
                            'type'  => 'text',
                            'class' => 'cauto-step-nodes cauto-set-text-step cauto-field wide'
                        ],
                        'label'     => __('Value or Text', 'autoqa-test-automation')
                    ]
                ],
                'group'     => 'events',
                'icon'      => '<span class="cauto-icon cauto-icon-select"></span>',
                'step_indicator'    => [
                    'selector'      => ['#cauto_step_set_select_selector_alias', '#cauto_step_set_text'],
                    'describe_text' => __(' {#cauto_step_set_select_selector_alias} to {#cauto_step_set_text}', 'autoqa-test-automation')
                ],
                'callback'  => 'cauto_default_set_select_step'  
            ],
            'reload' => [
                'label'         => __('Reload', 'autoqa-test-automation'),
                'settings'      => [],
                'icon'          => '<span class="cauto-icon cauto-icon-reload"></span>',
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
                'icon'      => '<span class="cauto-icon cauto-icon-page-title"></span>',
                'group'     => 'check',
                'step_indicator'    => [
                    'selector'      => ['#cauto_field_check_title_condition', '#cauto_field_check_title'],
                    'describe_text' => __(' {#cauto_field_check_title_condition} {#cauto_field_check_title}', 'autoqa-test-automation')
                ],
                'callback'  => 'cauto_default_check_page_title_step'
            ],
            'check-text' => [
                'label'     => __('Check Text', 'autoqa-test-automation'),
                'settings'      => [
                    [
                        'field' => 'select',
                        'attr'  => [
                            'id'    => 'cauto_step_check_text_selector_type',
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
                            'id'    => 'cauto_step_check_text_selector',
                            'type'  => 'text',
                            'class' => 'cauto-step-nodes cauto-set-text-step cauto-field wide'
                        ],
                        'label'     => __('Selector', 'autoqa-test-automation')
                    ],
                    [
                        'field' => 'select',
                        'attr'  => [
                            'id'    => 'cauto_field_check_text_condition',
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
                            'id'    => 'cauto_field_check_text',
                            'class' => 'cauto-step-nodes cauto-check-text-step cauto-field wide'
                        ],
                        'label' => __('Text', 'autoqa-test-automation')
                    ]
                ],
                'icon'      => '<span class="cauto-icon cauto-icon-check-text"></span>',
                'group'     => 'check',
                'step_indicator'    => [
                    'selector'      => ['#cauto_field_check_text_condition', '#cauto_field_check_text'],
                    'describe_text' => __(' {#cauto_field_check_text_condition} {#cauto_field_check_text}', 'autoqa-test-automation')
                ],
                'callback'  => 'cauto_default_check_text_step'
            ],
            'check-value' => [
                'label'     => __('Check Value', 'autoqa-test-automation'),
                'settings'      => [
                    [
                        'field' => 'select',
                        'attr'  => [
                            'id'    => 'cauto_step_check_value_selector_type',
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
                            'id'    => 'cauto_step_check_value_selector',
                            'type'  => 'text',
                            'class' => 'cauto-step-nodes cauto-set-text-step cauto-field wide'
                        ],
                        'label'     => __('Selector', 'autoqa-test-automation')
                    ],
                    [
                        'field' => 'select',
                        'attr'  => [
                            'id'    => 'cauto_field_check_value_condition',
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
                            'id'    => 'cauto_field_check_text',
                            'class' => 'cauto-step-nodes cauto-check-text-step cauto-field wide'
                        ],
                        'label' => __('Value', 'autoqa-test-automation')
                    ]
                ],
                'icon'      => '<span class="cauto-icon cauto-icon-check-text"></span>',
                'group'     => 'check',
                'step_indicator'    => [
                    'selector'      => ['#cauto_field_check_text_condition', '#cauto_field_check_text'],
                    'describe_text' => __(' {#cauto_field_check_text_condition} {#cauto_field_check_text}', 'autoqa-test-automation')
                ],
                'callback'  => 'cauto_default_check_text_step'
            ],
            'check-attribute' => [
                'label'     => __('Check Attribute', 'autoqa-test-automation'),
                'settings'      => [
                    [
                        'field' => 'select',
                        'attr'  => [
                            'id'    => 'cauto_step_check_attribute_selector_type',
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
                            'id'    => 'cauto_step_check_attribute_selector',
                            'type'  => 'text',
                            'class' => 'cauto-step-nodes cauto-set-text-step cauto-field wide'
                        ],
                        'label'     => __('Selector', 'autoqa-test-automation')
                    ],
                    [
                        'field' => 'select',
                        'attr'  => [
                            'id'    => 'cauto_field_check_attribute_condition',
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
                            'id'    => 'cauto_field_check_attribute_attr',
                            'class' => 'cauto-step-nodes cauto-check-text-step cauto-field wide'
                        ],
                        'label' => __('Attribute', 'autoqa-test-automation')
                    ],
                    [
                        'field' => 'input',
                        'attr'  => [
                            'type'  => 'text',
                            'id'    => 'cauto_field_check_attribute_value',
                            'class' => 'cauto-step-nodes cauto-check-text-step cauto-field wide'
                        ],
                        'label' => __('Value', 'autoqa-test-automation')
                    ]
                ],
                'icon'      => '<span class="cauto-icon cauto-icon-check-attribute"></span>',
                'group'     => 'check',
                'step_indicator'    => [
                    'selector'      => ['#cauto_field_check_attribute_attr', '#cauto_field_check_attribute_condition','#cauto_field_check_attribute_value'],
                    'describe_text' => __(' {#cauto_field_check_attribute_attr} {#cauto_field_check_attribute_condition} {#cauto_field_check_attribute_value}', 'autoqa-test-automation')
                ],
                'callback'  => 'cauto_default_check_attribute_step'
            ],
            'check-visibility' => [
                'label'     => __('Check Visibility', 'autoqa-test-automation'),
                'settings'      => [
                    [
                        'field' => 'select',
                        'attr'  => [
                            'id'    => 'cauto_step_check_visibility_selector_type',
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
                            'type'  => 'text',
                            'id'    => 'cauto_field_check_visibility_selector',
                            'class' => 'cauto-step-nodes cauto-check-text-step cauto-field wide'
                        ],
                        'label' => __('Selector', 'autoqa-test-automation')
                    ],
                    [
                        'field' => 'select',
                        'attr'  => [
                            'id'    => 'cauto_field_check_visibilty_condition',
                            'class' => 'cauto-step-nodes cauto-check-text-step cauto-field wide block'
                        ],
                        'label'     => __('Condition', 'autoqa-test-automation'),
                        'options'   => [
                            'is displayed'      => __('Is Displayed', 'autoqa-test-automation'),
                            'is hidden'         => __('Is Hidden', 'autoqa-test-automation'),
                            'is exists'         => __('Is Exists', 'autoqa-test-automation'),
                            'is not existing'   => __('Is Not Existing', 'autoqa-test-automation')
                        ]
                    ]
                ],
                'icon'      => '<span class="cauto-icon cauto-icon-check-visibility"></span>',
                'group'     => 'check',
                'step_indicator'    => [
                    'selector'      => ['#cauto_field_check_attribute_attr', '#cauto_field_check_attribute_condition','#cauto_field_check_attribute_value'],
                    'describe_text' => __(' {#cauto_field_check_attribute_attr} {#cauto_field_check_attribute_condition} {#cauto_field_check_attribute_value}', 'autoqa-test-automation')
                ],
                'callback'  => 'cauto_default_check_attribute_step'
            ],
        ];

        return apply_filters('cauto-ui-steps', $steps);
    }
}

new cauto_steps();