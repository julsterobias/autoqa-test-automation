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
                            'class' => 'cauto-step-nodes cauto-start-step cauto-field wide cauto-variable-step'
                        ],
                        'label'         => __('Page URL', 'autoqa-test-automation')
                    ]
                ],
                'icon'              => '<span class="cauto-icon cauto-icon-start"></span>',
                'group'             => 'default',
                'step_indicator'    => [
                    'selector'      => '#cauto_start_name',
                    'describe_text' => __(' - to open {#cauto_start_name}', 'autoqa-test-automation')
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
                            'class' => 'cauto-step-nodes cauto-set-click-step cauto-field'
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
                            'class' => 'cauto-step-nodes cauto-set-click-step cauto-field wide'
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
                            'class' => 'cauto-step-nodes cauto-set-click-step cauto-field wide'
                        ],
                        'label' => __('Selector', 'autoqa-test-automation')
                    ],
                    [
                        'field' => 'input',
                        'attr'  => [
                            'type'  => 'text',
                            'id'    => 'cauto_step_click_alias',
                            'class' => 'cauto-step-nodes cauto-set-click-step cauto-field wide cauto-variable-step',
                            'placeholder'   => __('Element temporary name', 'autoqa-test-automation')
                        ],
                        'label'         => __('Field Name (Alias)', 'autoqa-test-automation')
                    ]
                ],
                'icon'      => '<span class="cauto-icon cauto-icon-click"></span>',
                'group'     => 'events',
                'step_indicator'    => [
                    'selector'      => '#cauto_step_click_alias',
                    'describe_text' => __(' - to {#cauto_step_click_alias}', 'autoqa-test-automation')
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
                            'class' => 'cauto-step-nodes cauto-set-hover-step cauto-field'
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
                            'class' => 'cauto-step-nodes cauto-set-hover-step cauto-field wide'
                        ],
                        'label' => __('Selector', 'autoqa-test-automation')
                    ],
                    [
                        'field' => 'input',
                        'attr'  => [
                            'type'  => 'text',
                            'id'    => 'cauto_step_hover_alias',
                            'class' => 'cauto-step-nodes cauto-set-hover-step cauto-field wide cauto-variable-step',
                            'placeholder'   => __('Element temporary name', 'autoqa-test-automation')
                        ],
                        'label'         => __('Field Name', 'autoqa-test-automation')
                    ]
                ],
                'icon'      => '<span class="cauto-icon cauto-icon-hover"></span>',
                'group'     => 'events',
                'step_indicator'    => [
                    'selector'      => '#cauto_step_hover_alias',
                    'describe_text' => __(' - on {#cauto_step_hover_alias}', 'autoqa-test-automation')
                ],
                'callback'  => 'cauto_default_hover_step' 
            ],
            'manual-input' => [
                'label'         => __('Manual Input', 'autoqa-test-automation'),
                'settings'      => [],
                'icon'          => '<span class="cauto-icon cauto-icon-manual"></span>',
                'group'         => 'events',
                'callback'      => 'cauto_default_manual_input_event',
                'no_settings'   => true
            ],
            'set-text'          => [
                'label'         => __('Set Text', 'autoqa-test-automation'),
                'settings'      => [
                    [
                        'field' => 'select',
                        'attr'  => [
                            'id'    => 'cauto_step_set_text_selector_type',
                            'class' => 'cauto-step-nodes cauto-set-text-step cauto-field'
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
                            'class' => 'cauto-step-nodes cauto-set-text-step cauto-field wide cauto-variable-step'
                        ],
                        'label'     => __('Field Name (Alias)', 'autoqa-test-automation')
                    ],
                    [
                        'field' => 'input',
                        'attr'  => [
                            'id'    => 'cauto_step_set_text',
                            'type'  => 'text',
                            'class' => 'cauto-step-nodes cauto-set-text-step cauto-field wide cauto-variable-step'
                        ],
                        'label'     => __('Value or Text', 'autoqa-test-automation')
                    ]
                ],
                'group'     => 'events',
                'icon'      => '<span class="cauto-icon cauto-icon-text"></span>',
                'step_indicator'    => [
                    'selector'      => ['#cauto_step_set_text_selector_alias', '#cauto_step_set_text'],
                    'describe_text' => __(' - {#cauto_step_set_text_selector_alias} to {#cauto_step_set_text}', 'autoqa-test-automation')
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
                            'class' => 'cauto-step-nodes cauto-check-select-step cauto-field'
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
                            'class' => 'cauto-step-nodes cauto-set-select-step cauto-field wide'
                        ],
                        'label'     => __('Selector', 'autoqa-test-automation')
                    ],
                    [
                        'field' => 'input',
                        'attr'  => [
                            'id'    => 'cauto_step_set_select_selector_alias',
                            'type'  => 'text',
                            'class' => 'cauto-step-nodes cauto-set-select-step cauto-field wide cauto-variable-step'
                        ],
                        'label'     => __('Field Name (Alias)', 'autoqa-test-automation')
                    ],
                    [
                        'field' => 'input',
                        'attr'  => [
                            'id'    => 'cauto_step_set_select',
                            'type'  => 'text',
                            'class' => 'cauto-step-nodes cauto-set-select-step cauto-field wide cauto-variable-step'
                        ],
                        'label'     => __('Value or Text', 'autoqa-test-automation')
                    ]
                ],
                'group'     => 'events',
                'icon'      => '<span class="cauto-icon cauto-icon-select"></span>',
                'step_indicator'    => [
                    'selector'      => ['#cauto_step_set_select_selector_alias', '#cauto_step_set_select'],
                    'describe_text' => __(' - {#cauto_step_set_select_selector_alias} to {#cauto_step_set_select}', 'autoqa-test-automation')
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
                'label'     => __('Check Page Title', 'autoqa-test-automation'),
                'settings'      => [
                    [
                        'field' => 'select',
                        'attr'  => [
                            'id'    => 'cauto_field_check_title_condition',
                            'class' => 'cauto-step-nodes cauto-check-title-step cauto-field wide block'
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
                            'class' => 'cauto-step-nodes cauto-check-title-step cauto-field wide cauto-variable-step'
                        ],
                        'label' => __('Title', 'autoqa-test-automation')
                    ]
                ],
                'icon'      => '<span class="cauto-icon cauto-icon-page-title"></span>',
                'group'     => 'check',
                'step_indicator'    => [
                    'selector'      => ['#cauto_field_check_title_condition', '#cauto_field_check_title'],
                    'describe_text' => __(' - {#cauto_field_check_title_condition} {#cauto_field_check_title}', 'autoqa-test-automation')
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
                            'class' => 'cauto-step-nodes cauto-check-text-step cauto-field wide'
                        ],
                        'label'     => __('Selector', 'autoqa-test-automation')
                    ],
                    [
                        'field' => 'input',
                        'attr'  => [
                            'id'    => 'cauto_step_check_text_alias_selector',
                            'type'  => 'text',
                            'class' => 'cauto-step-nodes cauto-check-text-alias-step cauto-field wide cauto-variable-step'
                        ],
                        'label'     => __('Field Name (Alias)', 'autoqa-test-automation')
                    ],
                    [
                        'field' => 'select',
                        'attr'  => [
                            'id'    => 'cauto_field_check_text_condition',
                            'class' => 'cauto-step-nodes cauto-check-text-condition-step cauto-field wide block'
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
                            'id'    => 'cauto_field_check_text_value',
                            'class' => 'cauto-step-nodes cauto-check-text-value-step cauto-field wide cauto-variable-step'
                        ],
                        'label' => __('Text', 'autoqa-test-automation')
                    ]
                ],
                'icon'      => '<span class="cauto-icon cauto-icon-check-text"></span>',
                'group'     => 'check',
                'step_indicator'    => [
                    'selector'      => ['#cauto_step_check_text_alias_selector', '#cauto_field_check_text_condition', '#cauto_field_check_text_value'],
                    'describe_text' => __(' - {#cauto_step_check_text_alias_selector} {#cauto_field_check_text_condition} {#cauto_field_check_text_value}', 'autoqa-test-automation')
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
                            'class' => 'cauto-step-nodes cauto-check-value-step cauto-field'
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
                            'class' => 'cauto-step-nodes cauto-set-value-step cauto-field wide'
                        ],
                        'label'     => __('Selector', 'autoqa-test-automation')
                    ],
                    [
                        'field' => 'input',
                        'attr'  => [
                            'id'    => 'cauto_step_check_value_alias_selector',
                            'type'  => 'text',
                            'class' => 'cauto-step-nodes cauto-set-value-alias-step cauto-field wide cauto-variable-step'
                        ],
                        'label'     => __('Field Name (Alias)', 'autoqa-test-automation')
                    ],
                    [
                        'field' => 'select',
                        'attr'  => [
                            'id'    => 'cauto_field_check_value_condition',
                            'class' => 'cauto-step-nodes cauto-check-value-step cauto-field wide block'
                        ],
                        'label'     => __('Condition', 'autoqa-test-automation'),
                        'options'   => [
                            'is equals to'         => __('Equals to', 'autoqa-test-automation'),
                            'is not equals to'     => __('Not equals to', 'autoqa-test-automation'),
                            'is contains with'     => __('Contains with', 'autoqa-test-automation'),
                            'is start with'        => __('Start with', 'autoqa-test-automation'),
                            'is end with'          => __('End with', 'autoqa-test-automation'),
                            'is less than'         => __('Is less than', 'autoqa-test-automation'),
                            'is greater than'      => __('Is greater than', 'autoqa-test-automation'),
                            'is less than or equal to'       => __('Is less than or equal to', 'autoqa-test-automation'),
                            'is greater than or equal to'    => __('Is greater than or equal to', 'autoqa-test-automation')
                        ]
                    ],
                    [
                        'field' => 'input',
                        'attr'  => [
                            'type'  => 'text',
                            'id'    => 'cauto_field_check_value',
                            'class' => 'cauto-step-nodes cauto-check-value-step cauto-field wide cauto-variable-step'
                        ],
                        'label' => __('Value', 'autoqa-test-automation')
                    ]
                ],
                'icon'      => '<span class="cauto-icon cauto-icon-check-value"></span>',
                'group'     => 'check',
                'step_indicator'    => [
                    'selector'      => ['#cauto_step_check_value_alias_selector', '#cauto_field_check_value_condition', '#cauto_field_check_value'],
                    'describe_text' => __(' - {#cauto_step_check_value_alias_selector} {#cauto_field_check_value_condition} {#cauto_field_check_value}', 'autoqa-test-automation')
                ],
                'callback'  => 'cauto_default_check_value_step'
            ],
            'check-attribute' => [
                'label'     => __('Check Attribute', 'autoqa-test-automation'),
                'settings'      => [
                    [
                        'field' => 'select',
                        'attr'  => [
                            'id'    => 'cauto_step_check_attribute_selector_type',
                            'class' => 'cauto-step-nodes cauto-check-attribute-step cauto-field'
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
                            'class' => 'cauto-step-nodes cauto-set-attribute-step cauto-field wide'
                        ],
                        'label'     => __('Selector', 'autoqa-test-automation')
                    ],
                    [
                        'field' => 'input',
                        'attr'  => [
                            'id'    => 'cauto_step_check_attribute_alias_selector',
                            'type'  => 'text',
                            'class' => 'cauto-step-nodes cauto-set-attribute-alias-step cauto-field wide cauto-variable-step'
                        ],
                        'label'     => __('Field Name (Alias)', 'autoqa-test-automation')
                    ],
                    [
                        'field' => 'input',
                        'attr'  => [
                            'type'  => 'text',
                            'id'    => 'cauto_field_check_attribute_attr',
                            'class' => 'cauto-step-nodes cauto-check-attribute-step cauto-field wide'
                        ],
                        'label' => __('Attribute', 'autoqa-test-automation')
                    ],
                    [
                        'field' => 'select',
                        'attr'  => [
                            'id'    => 'cauto_field_check_attribute_condition',
                            'class' => 'cauto-step-nodes cauto-check-attribute-step cauto-field wide block'
                        ],
                        'label'     => __('Condition', 'autoqa-test-automation'),
                        'options'   => [
                            'is equals to'         => __('Equals to', 'autoqa-test-automation'),
                            'is not equals to'     => __('Not equals to', 'autoqa-test-automation'),
                            'is contains with'     => __('Contains with', 'autoqa-test-automation'),
                            'is start with'        => __('Start with', 'autoqa-test-automation'),
                            'is end with'          => __('End with', 'autoqa-test-automation'),
                            'is less than'         => __('Is less than', 'autoqa-test-automation'),
                            'is greater than'      => __('Is greater than', 'autoqa-test-automation'),
                            'is less than or equal to'       => __('Is less than or equal to', 'autoqa-test-automation'),
                            'is greater than or equal to'    => __('Is greater than or equal to', 'autoqa-test-automation')
                        ]
                    ],
                    [
                        'field' => 'input',
                        'attr'  => [
                            'type'  => 'text',
                            'id'    => 'cauto_field_check_attribute_value',
                            'class' => 'cauto-step-nodes cauto-check-attribute-step cauto-field wide cauto-variable-step'
                        ],
                        'label' => __('Value', 'autoqa-test-automation')
                    ]
                ],
                'icon'      => '<span class="cauto-icon cauto-icon-check-attribute"></span>',
                'group'     => 'check',
                'step_indicator'    => [
                    'selector'      => ['#cauto_step_check_attribute_alias_selector', '#cauto_field_check_attribute_attr', '#cauto_field_check_attribute_condition','#cauto_field_check_attribute_value'],
                    'describe_text' => __(' - {#cauto_step_check_attribute_alias_selector} {#cauto_field_check_attribute_attr} {#cauto_field_check_attribute_condition} {#cauto_field_check_attribute_value}', 'autoqa-test-automation')
                ],
                'callback'  => 'cauto_default_check_attribute_step'
            ],
            'check-element-count' => [
                'label'     => __('Check Element Count', 'autoqa-test-automation'),
                'settings'      => [
                    [
                        'field' => 'select',
                        'attr'  => [
                            'id'    => 'cauto_step_check_el_count_selector_type',
                            'class' => 'cauto-step-nodes cauto-check-el-count-step cauto-field'
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
                            'id'    => 'cauto_field_check_el_count_selector',
                            'class' => 'cauto-step-nodes cauto-check-el-count-step cauto-field wide'
                        ],
                        'label' => __('Selector', 'autoqa-test-automation')
                    ],
                    [
                        'field' => 'input',
                        'attr'  => [
                            'type'  => 'text',
                            'id'    => 'cauto_field_check_el_count_alias_selector',
                            'class' => 'cauto-step-nodes cauto-check-alias-el-count-step cauto-field wide cauto-variable-step'
                        ],
                        'label' => __('Field Name (Alias)', 'autoqa-test-automation')
                    ],
                    [
                        'field' => 'select',
                        'attr'  => [
                            'id'    => 'cauto_field_check_el_count_condition',
                            'class' => 'cauto-step-nodes cauto-check-el-count-step cauto-field wide block'
                        ],
                        'label'     => __('Condition', 'autoqa-test-automation'),
                        'options'   => [
                            'is equals to'         => __('Equals to', 'autoqa-test-automation'),
                            'is less than'         => __('Is less than', 'autoqa-test-automation'),
                            'is greater than'      => __('Is greater than', 'autoqa-test-automation'),
                            'is less than or equal to'       => __('Is less than or equal to', 'autoqa-test-automation'),
                            'is greater than or equal to'    => __('Is greater than or equal to', 'autoqa-test-automation')
                        ]
                    ],
                    [
                        'field' => 'input',
                        'attr'  => [
                            'type'  => 'text',
                            'id'    => 'cauto_field_check_el_count_number_selector',
                            'class' => 'cauto-step-nodes cauto-check-alias-el-count-number-step cauto-field wide cauto-variable-step'
                        ],
                        'label' => __('Number', 'autoqa-test-automation')
                    ],
                ],
                'icon'      => '<span class="cauto-icon cauto-icon-element-count"></span>',
                'group'     => 'check',
                'step_indicator'    => [
                    'selector'      => ['#cauto_field_check_el_count_alias_selector', '#cauto_field_check_el_count_condition', '#cauto_field_check_el_count_number_selector'],
                    'describe_text' => __(' - {#cauto_field_check_el_count_alias_selector} {#cauto_field_check_el_count_condition} {#cauto_field_check_el_count_number_selector}', 'autoqa-test-automation')
                ],
                'callback'  => 'cauto_default_check_element_count_step'
            ],
            'check-visibility' => [
                'label'     => __('Check Visibility', 'autoqa-test-automation'),
                'settings'      => [
                    [
                        'field' => 'select',
                        'attr'  => [
                            'id'    => 'cauto_step_check_visibility_selector_type',
                            'class' => 'cauto-step-nodes cauto-check-visibility-step cauto-field'
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
                            'class' => 'cauto-step-nodes cauto-check-visibility-step cauto-field wide'
                        ],
                        'label' => __('Selector', 'autoqa-test-automation')
                    ],
                    [
                        'field' => 'input',
                        'attr'  => [
                            'type'  => 'text',
                            'id'    => 'cauto_field_check_visibility_alias_selector',
                            'class' => 'cauto-step-nodes cauto-check-alias-visibility-step cauto-field wide cauto-variable-step'
                        ],
                        'label' => __('Field Name (Alias)', 'autoqa-test-automation')
                    ],
                    [
                        'field' => 'select',
                        'attr'  => [
                            'id'    => 'cauto_field_check_visibilty_condition',
                            'class' => 'cauto-step-nodes cauto-check-visibility-step cauto-field wide block'
                        ],
                        'label'     => __('Condition', 'autoqa-test-automation'),
                        'options'   => [
                            'is displayed'      => __('Is Displayed', 'autoqa-test-automation'),
                            'is hidden'         => __('Is Hidden', 'autoqa-test-automation')
                        ]
                    ]
                ],
                'icon'      => '<span class="cauto-icon cauto-icon-check-visibility"></span>',
                'group'     => 'check',
                'step_indicator'    => [
                    'selector'      => ['#cauto_field_check_visibility_alias_selector', '#cauto_field_check_visibilty_condition'],
                    'describe_text' => __(' - {#cauto_field_check_visibility_alias_selector} {#cauto_field_check_visibilty_condition}', 'autoqa-test-automation')
                ],
                'callback'  => 'cauto_default_check_visibilty_step'
            ],
            'check-data' => [
                'label'     => __('Check Data', 'autoqa-test-automation'),
                'settings'      => [
                    [
                        'field' => 'input',
                        'attr'  => [
                            'type'  => 'text',
                            'id'    => 'cauto_field_check_data_selector',
                            'class' => 'cauto-step-nodes cauto-check-data-step cauto-field wide'
                        ],
                        'label' => __('Data Name', 'autoqa-test-automation')
                    ],
                    [
                        'field' => 'select',
                        'attr'  => [
                            'id'    => 'cauto_field_check_data_condition',
                            'class' => 'cauto-step-nodes cauto-check-data-step cauto-field wide block'
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
                            'id'    => 'cauto_field_check_data_value',
                            'class' => 'cauto-step-nodes cauto-check-data-value-step cauto-field wide cauto-variable-step'
                        ],
                        'label' => __('Value', 'autoqa-test-automation')
                    ],
                ],
                'icon'      => '<span class="cauto-icon cauto-icon-check-data"></span>',
                'group'     => 'check',
                'step_indicator'    => [
                    'selector'      => ['#cauto_field_check_attribute_attr', '#cauto_field_check_attribute_condition','#cauto_field_check_attribute_value'],
                    'describe_text' => __(' - {#cauto_field_check_attribute_attr} {#cauto_field_check_attribute_condition} {#cauto_field_check_attribute_value}', 'autoqa-test-automation')
                ],
                'callback'  => 'cauto_default_check_visibilty_step'
            ],
            'delay_divider' => [
                'divider'    => true,
                'label'     => __('Delay', 'autoqa-test-automation'), 
            ],
            'delay-runner' => [
                'label'     => __('Delay', 'autoqa-test-automation'),
                'settings'      => [
                    [
                        'field' => 'input',
                        'attr'  => [
                            'type'  => 'number',
                            'id'    => 'cauto_field_delay_runner_for',
                            'class' => 'cauto-step-nodes cauto-delay-runner-step cauto-field wide',
                            'min'   => 0,
                            'max'   => 60
                        ],
                        'label' => __('Delay runner for second(s)', 'autoqa-test-automation')
                    ]
                ],
                'icon'      => '<span class="cauto-icon cauto-icon-delay"></span>',
                'group'     => 'delay',
                'step_indicator'    => [
                    'selector'      => ['#cauto_field_delay_runner_for'],
                    'describe_text' => __(' - runner for {#cauto_field_delay_runner_for} second(s)', 'autoqa-test-automation')
                ],
                'callback'  => 'cauto_default_delay_runner_for'
            ],
            'wait-to' => [
                'label'     => __('Wait to', 'autoqa-test-automation'),
                'settings'      => [
                    [
                        'field' => 'select',
                        'attr'  => [
                            'id'    => 'cauto_step_wait_to_selector_type',
                            'class' => 'cauto-step-nodes cauto-wait-to-step cauto-field'
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
                            'id'    => 'cauto_field_wait_to_selector',
                            'class' => 'cauto-step-nodes cauto-wait-to-step cauto-field wide'
                        ],
                        'label' => __('Selector', 'autoqa-test-automation')
                    ],
                    [
                        'field' => 'input',
                        'attr'  => [
                            'type'  => 'text',
                            'id'    => 'cauto_field_wait_to_alias_selector',
                            'class' => 'cauto-step-nodes cauto-wait-to-step cauto-field wide cauto-variable-step'
                        ],
                        'label' => __('Element Name (Alias)', 'autoqa-test-automation')
                    ],
                    [
                        'field' => 'select',
                        'attr'  => [
                            'id'    => 'cauto_step_wait_to_selector_action',
                            'class' => 'cauto-step-nodes cauto-wait-to-step cauto-field wide'
                        ],
                        'label'     => __('Action', 'autoqa-test-automation'),
                        'options'   => [
                            'display'            => __('Display', 'autoqa-test-automation'),
                            'not display'        => __('Not Display', 'autoqa-test-automation'),
                            'have value'         => __('Have Value', 'autoqa-test-automation'),
                            'enable'             => __('Enable', 'autoqa-test-automation'),
                            'not enable'         => __('Not Enable', 'autoqa-test-automation')
                        ]
                    ]
                ],
                'icon'      => '<span class="cauto-icon cauto-icon-wait-for"></span>',
                'group'     => 'delay',
                'step_indicator'    => [
                    'selector'      => ['#cauto_field_wait_to_alias_selector', '#cauto_step_wait_to_selector_action'],
                    'describe_text' => __(' - {#cauto_field_wait_to_alias_selector} {#cauto_step_wait_to_selector_action}', 'autoqa-test-automation')
                ],
                'callback'  => 'cauto_default_wait_for_selector'
            ],
            'data_divider' => [
                'divider'    => true,
                'label'     => __('Store Data', 'autoqa-test-automation'), 
            ],
            'store-element-data' => [
                'label'     => __('Element Data', 'autoqa-test-automation'),
                'settings'      => [
                    [
                        'field' => 'input',
                        'attr'  => [
                            'type'  => 'text',
                            'id'    => 'cauto_field_store_data_from_variable_name_selector',
                            'class' => 'cauto-step-nodes cauto-store-data-from-variable-name-step cauto-field wide cauto-no-space-text-validation'
                        ],
                        'label' => __('Name - space not allowed and make sure it\'s unique', 'autoqa-test-automation')
                    ],
                    [
                        'field' => 'select',
                        'attr'  => [
                            'id'    => 'cauto_step_store_data_from_selector_type',
                            'class' => 'cauto-step-nodes cauto-store-data-from-step cauto-field wide'
                        ],
                        'label'     => __('Identifier', 'autoqa-test-automation'),
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
                            'id'    => 'cauto_field_store_data_from_selector',
                            'class' => 'cauto-step-nodes cauto-store-data-from-step cauto-field wide'
                        ],
                        'label' => __('Selector', 'autoqa-test-automation')
                    ],
                    [
                        'field' => 'input',
                        'attr'  => [
                            'type'  => 'text',
                            'id'    => 'cauto_field_store_data_from_attribute_selector',
                            'class' => 'cauto-step-nodes cauto-store-data-from-attribute-step cauto-field wide'
                        ],
                        'label' => __('Attribute', 'autoqa-test-automation')
                    ]
                    
                ],
                'icon'      => '<span class="cauto-icon cauto-icon-store-element-data"></span>',
                'group'     => 'data',
                'step_indicator'    => [
                    'selector'      => ['#cauto_field_store_data_from_attribute_selector', '#cauto_field_store_data_from_variable_name_selector'],
                    'describe_text' => __(' - Store {#cauto_field_store_data_from_attribute_selector} as {#cauto_field_store_data_from_variable_name_selector}', 'autoqa-test-automation')
                ],
                'callback'  => 'cauto_default_store_element_data'
            ],
            'store-data' => [
                'label'     => __('Store Data', 'autoqa-test-automation'),
                'settings'      => [
                    [
                        'field' => 'input',
                        'attr'  => [
                            'type'  => 'text',
                            'id'    => 'cauto_field_store_data_name_selector',
                            'class' => 'cauto-step-nodes cauto-store-data-variable-name-step cauto-field wide'
                        ],
                        'label' => __('Name', 'autoqa-test-automation')
                    ],
                    [
                        'field' => 'input',
                        'attr'  => [
                            'type'  => 'text',
                            'id'    => 'cauto_field_store_data_value_selector',
                            'class' => 'cauto-step-nodes cauto-store-data-value-step cauto-field wide cauto-variable-step'
                        ],
                        'label' => __('Value', 'autoqa-test-automation')
                    ]
                    
                ],
                'icon'      => '<span class="cauto-icon cauto-icon-store-data"></span>',
                'group'     => 'data',
                'step_indicator'    => [
                    'selector'      => ['#cauto_field_store_data_value_selector', '#cauto_field_store_data_name_selector'],
                    'describe_text' => __(' - Store the "{#cauto_field_store_data_value_selector}" as {#cauto_field_store_data_name_selector}', 'autoqa-test-automation')
                ],
                'callback'  => 'cauto_default_store_data'
            ],
            
        ];

        return apply_filters('autoqa-steps', $steps);
    }
}

new cauto_steps();