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
                            'class' => 'cauto-step-nodes cauto-set-click-step cauto-field wide',
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
                            'class' => 'cauto-step-nodes cauto-set-hover-step cauto-field wide',
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
                            'class' => 'cauto-step-nodes cauto-set-text-step cauto-field wide'
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
            'send-keys'          => [
                'label'         => __('Send Keys', 'autoqa-test-automation'),
                'settings'      => [
                    [
                        'field' => 'select',
                        'attr'  => [
                            'id'    => 'cauto_step_send_keys_selector_type',
                            'class' => 'cauto-step-nodes cauto-send-keys-step cauto-field'
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
                            'id'    => 'cauto_step_send_keys_selector',
                            'type'  => 'text',
                            'class' => 'cauto-step-nodes cauto-send-keys-step cauto-field wide'
                        ],
                        'label'     => __('Selector', 'autoqa-test-automation')
                    ],
                    [
                        'field' => 'input',
                        'attr'  => [
                            'id'    => 'cauto_step_send_keys_selector_alias',
                            'type'  => 'text',
                            'class' => 'cauto-step-nodes cauto-send-keys-step cauto-field wide'
                        ],
                        'label'     => __('Field Name (Alias)', 'autoqa-test-automation')
                    ],
                    [
                        'field' => 'input',
                        'attr'  => [
                            'id'    => 'cauto_step_send_keys',
                            'type'  => 'text',
                            'class' => 'cauto-step-nodes cauto-send-keys-step cauto-field wide'
                        ],
                        'label'     => __('Text', 'autoqa-test-automation')
                    ],
                    [
                        'field' => 'custom',
                        'html'  => '<div class="cauto-send-keys-buttons"><span class="cauto-send-keys-steps" data-key="enter">'.__('Enter', 'autoqa-test-automation').'</span><span class="cauto-send-keys-steps" data-key="tab">'.__('Tab', 'autoqa-test-automation').'</span></div>'
                    ]
                ],
                'group'     => 'events',
                'icon'      => '<span class="cauto-icon cauto-icon-send-keys"></span>',
                'step_indicator'    => [
                    'selector'      => ['#cauto_step_send_keys', '#cauto_step_send_keys_selector_alias'],
                    'describe_text' => __(' - "{#cauto_step_send_keys}" to {#cauto_step_send_keys_selector_alias}', 'autoqa-test-automation')
                ],
                'callback'  => 'cauto_default_send_keys_step'  
            ],
            'drag-drop'          => [
                'label'         => __('Drag Drop', 'autoqa-test-automation'),
                'settings'      => [
                    [
                        'field' => 'select',
                        'attr'  => [
                            'id'    => 'cauto_step_drag_drop_source_selector_type',
                            'class' => 'cauto-step-nodes cauto-drag-drop-source-step cauto-field wide'
                        ],
                        'label'     => __('Source Attribute', 'autoqa-test-automation'),
                        'options'   => [
                            'class' => __('Class', 'autoqa-test-automation'),
                            'id'    => __('ID', 'autoqa-test-automation'),
                            'xpath' => __('Xpath', 'autoqa-test-automation')
                        ]
                    ],
                    [
                        'field' => 'input',
                        'attr'  => [
                            'id'    => 'cauto_step_drag_drop_source_selector',
                            'type'  => 'text',
                            'class' => 'cauto-step-nodes cauto-drag-drop-source-step cauto-field wide'
                        ],
                        'label'     => __('Source Selector', 'autoqa-test-automation')
                    ],
                    [
                        'field' => 'input',
                        'attr'  => [
                            'id'    => 'cauto_step_drag_drop_source_alias_selector',
                            'type'  => 'text',
                            'class' => 'cauto-step-nodes cauto-drag-drop-source-alias-step cauto-field wide'
                        ],
                        'label'     => __('Alias', 'autoqa-test-automation')
                    ],
                    [
                        'field'     => 'custom',
                        'html'      => '<br/>'
                    ],
                    [
                        'field' => 'select',
                        'attr'  => [
                            'id'    => 'cauto_step_drag_drop_target_selector_type',
                            'class' => 'cauto-step-nodes cauto-drag-drop-target-step cauto-field wide'
                        ],
                        'label'     => __('Target Attribute', 'autoqa-test-automation'),
                        'options'   => [
                            'class' => __('Class', 'autoqa-test-automation'),
                            'id'    => __('ID', 'autoqa-test-automation'),
                            'xpath' => __('Xpath', 'autoqa-test-automation')
                        ]
                    ],
                    [
                        'field' => 'input',
                        'attr'  => [
                            'id'    => 'cauto_step_drag_drop_target_selector',
                            'type'  => 'text',
                            'class' => 'cauto-step-nodes cauto-drag-drop-target-step cauto-field wide'
                        ],
                        'label'     => __('Target Selector', 'autoqa-test-automation')
                    ],
                    [
                        'field' => 'input',
                        'attr'  => [
                            'id'    => 'cauto_step_drag_drop_target_alias_selector',
                            'type'  => 'text',
                            'class' => 'cauto-step-nodes cauto-drag-drop-target-alias-step cauto-field wide'
                        ],
                        'label'     => __('Alias', 'autoqa-test-automation')
                    ],

                ],
                'group'     => 'events',
                'icon'      => '<span class="cauto-icon cauto-icon-drag-drop"></span>',
                'step_indicator'    => [
                    'selector'      => ['#cauto_step_drag_drop_source_alias_selector', '#cauto_step_drag_drop_target_alias_selector'],
                    'describe_text' => __(' - {#cauto_step_drag_drop_source_alias_selector} to {#cauto_step_drag_drop_target_alias_selector}', 'autoqa-test-automation')
                ],
                'callback'  => 'cauto_default_drag_drop_step'  
            ],
            'empty-field'          => [
                'label'         => __('Empty Field', 'autoqa-test-automation'),
                'settings'      => [
                    [
                        'field' => 'select',
                        'attr'  => [
                            'id'    => 'cauto_step_empty_field_selector_type',
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
                            'id'    => 'cauto_step_empty_field_selector',
                            'type'  => 'text',
                            'class' => 'cauto-step-nodes cauto-empty-text-step cauto-field wide'
                        ],
                        'label'     => __('Selector', 'autoqa-test-automation')
                    ],
                    [
                        'field' => 'input',
                        'attr'  => [
                            'id'    => 'cauto_step_empty_field_selector_alias',
                            'type'  => 'text',
                            'class' => 'cauto-step-nodes cauto-empty-text-step cauto-field wide'
                        ],
                        'label'     => __('Field Name (Alias)', 'autoqa-test-automation')
                    ]
                ],
                'group'     => 'events',
                'icon'      => '<span class="cauto-icon cauto-icon-empty-field"></span>',
                'step_indicator'    => [
                    'selector'      => ['#cauto_step_empty_field_selector_alias'],
                    'describe_text' => __(' - {#cauto_step_empty_field_selector_alias}', 'autoqa-test-automation')
                ],
                'callback'  => 'cauto_default_empty_field_step'  
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
                            'class' => 'cauto-step-nodes cauto-set-select-step cauto-field wide'
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
            'scroll-to'        => [
                'label'         => __('Scroll', 'autoqa-test-automation'),
                'settings'      => [
                    [
                        'field' => 'select',
                        'attr'  => [
                            'id'    => 'cauto_step_scroll_type',
                            'class' => 'cauto-step-nodes cauto-check-select-step cauto-field'
                        ],
                        'label'     => __('Scroll To', 'autoqa-test-automation'),
                        'options'   => [
                            'down' => __('Down', 'autoqa-test-automation'),
                            'up'    => __('Up', 'autoqa-test-automation')
                        ]
                    ],
                    [
                        'field' => 'input',
                        'attr'  => [
                            'id'    => 'cauto_step_scroll_value',
                            'type'  => 'number',
                            'class' => 'cauto-step-nodes cauto-set-select-step cauto-field wide'
                        ],
                        'label'     => __('Distance (By Pixel)', 'autoqa-test-automation')
                    ]
                ],
                'group'     => 'events',
                'icon'      => '<span class="cauto-icon cauto-icon-event-scroll"></span>',
                'step_indicator'    => [
                    'selector'      => ['#cauto_step_scroll_type', '#cauto_step_scroll_value'],
                    'describe_text' => __(' - {#cauto_step_scroll_type} with distance of {#cauto_step_scroll_value}', 'autoqa-test-automation')
                ],
                'callback'  => 'cauto_default_set_scroll_to_step'  
            ],
            'upload-file-image'       => [
                'label'         => __('Upload Image File', 'autoqa-test-automation'),
                'settings'      => [
                    [
                        'field' => 'select',
                        'attr'  => [
                            'id'    => 'cauto_step_upload_image_selector_type',
                            'class' => 'cauto-step-nodes cauto-upload-file-step-type cauto-field'
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
                            'id'    => 'cauto_step_upload_image_selector',
                            'type'  => 'text',
                            'class' => 'cauto-step-nodes cauto-upload-image-step cauto-field wide'
                        ],
                        'label'     => __('File', 'autoqa-test-automation')
                    ],
                    [
                        'field' => 'input',
                        'attr'  => [
                            'id'    => 'cauto_step_upload_image_alias_selector',
                            'type'  => 'text',
                            'class' => 'cauto-step-nodes cauto-upload-image-alias-step cauto-field wide'
                        ],
                        'label'     => __('Image File Alias', 'autoqa-test-automation')
                    ],
                    [
                        'field' => 'select',
                        'attr'  => [
                            'id'    => 'cauto_step_upload_image_type',
                            'class' => 'cauto-step-nodes cauto-upload-file-file-step-type cauto-field'
                        ],
                        'label'     => __('File Type', 'autoqa-test-automation'),
                        'options'   => [
                            'png'   => __('PNG', 'autoqa-test-automation'),
                            'jpeg'  => __('JPEG', 'autoqa-test-automation')
                        ]
                    ],
                    [
                        'field'     => 'custom',
                        'html'      => '<div><label>'.__('Image Size','autoqa-test-automation').'</label></div>'
                    ],
                    [
                        'field' => 'input',
                        'attr'  => [
                            'id'    => 'cauto_step_upload_image_width_selector',
                            'type'  => 'range',
                            'class' => 'cauto-step-nodes cauto-upload-image-width-step cauto-field wide cauto-range-value-change',
                            'min'   => 0,
                            'max'   => 1000,
                            'value' => 200
                        ],
                        'label'     => __('Width', 'autoqa-test-automation')
                    ],
                    [
                        'field' => 'input',
                        'attr'  => [
                            'id'    => 'cauto_step_upload_image_height_selector',
                            'type'  => 'range',
                            'class' => 'cauto-step-nodes cauto-upload-image-height-step cauto-field wide cauto-range-value-change',
                            'min'   => 0,
                            'max'   => 1000,
                            'value' => 200
                        ],
                        'label'     => __('Height', 'autoqa-test-automation')
                    ],

                ],
                'group'     => 'events',
                'icon'      => '<span class="cauto-icon cauto-icon-upload-image"></span>',
                'step_indicator'    => [
                    'selector'      => ['#cauto_step_upload_image_alias_selector', '#cauto_step_upload_image_type'],
                    'describe_text' => __(' - {#cauto_step_upload_image_alias_selector} is set to upload "{#cauto_step_upload_image_type}"', 'autoqa-test-automation')
                ],
                'callback'  => 'cauto_default_upload_image_step'  
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
                            'class' => 'cauto-step-nodes cauto-check-text-alias-step cauto-field wide'
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
                            'end with'          => __('End with', 'autoqa-test-automation'),
                            'has any'           => __('Has any', 'autoqa-test-automation')
                        ],
                        'field-interact'    => [
                            'event'     => 'change',
                            'payload'   => [
                                'value'     => 'has any',
                                'target'    => ['#cauto_field_check_text_value'],
                                'action'     => 'hide'
                            ],
                            'callback'  => 'cauto_default_steps_hide_related'
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
                            'class' => 'cauto-step-nodes cauto-set-value-alias-step cauto-field wide'
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
                            'is greater than or equal to'    => __('Is greater than or equal to', 'autoqa-test-automation'),
                            'has any'              => __('Has any', 'autoqa-test-automation')
                        ],
                        'field-interact'    => [
                            'event'     => 'change',
                            'payload'   => [
                                'value'     => 'has any',
                                'target'    => ['#cauto_field_check_value'],
                                'action'     => 'hide'
                            ],
                            'callback'  => 'cauto_default_steps_hide_related'
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
                            'class' => 'cauto-step-nodes cauto-set-attribute-alias-step cauto-field wide'
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
                            'is greater than or equal to'    => __('Is greater than or equal to', 'autoqa-test-automation'),
                            'has any'              => __('Has any', 'autoqa-test-automation')
                        ],
                        'field-interact'    => [
                            'event'     => 'change',
                            'payload'   => [
                                'value'     => 'has any',
                                'target'    => ['#cauto_field_check_attribute_value'],
                                'action'     => 'hide'
                            ],
                            'callback'  => 'cauto_default_steps_hide_related'
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
                            'class' => 'cauto-step-nodes cauto-check-alias-el-count-step cauto-field wide'
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
                            'class' => 'cauto-step-nodes cauto-check-alias-visibility-step cauto-field wide'
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
                            'class' => 'cauto-step-nodes cauto-check-data-step cauto-field wide cauto-variable-step'
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
                            'is equals to'         => __('Is Equals to', 'autoqa-test-automation'),
                            'is not equals to'     => __('Is Not equals to', 'autoqa-test-automation'),
                            'is contains with'     => __('Is Contains with', 'autoqa-test-automation'),
                            'is start with'        => __('Is Start with', 'autoqa-test-automation'),
                            'is end with'          => __('Is End with', 'autoqa-test-automation'),
                            'is less than'         => __('Is less than', 'autoqa-test-automation'),
                            'is greater than'      => __('Is greater than', 'autoqa-test-automation'),
                            'is less than or equal to'       => __('Is less than or equal to', 'autoqa-test-automation'),
                            'is greater than or equal to'    => __('Is greater than or equal to', 'autoqa-test-automation'),
                            'has any'               => __('Has any', 'autoqa-test-automation')
                        ],
                        'field-interact'    => [
                            'event'     => 'change',
                            'payload'   => [
                                'value'     => 'has any',
                                'target'    => ['#cauto_field_check_data_value'],
                                'action'     => 'hide'
                            ],
                            'callback'  => 'cauto_default_steps_hide_related'
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
                    'selector'      => ['#cauto_field_check_data_selector', '#cauto_field_check_data_condition','#cauto_field_check_data_value'],
                    'describe_text' => __(' - {#cauto_field_check_data_selector} {#cauto_field_check_data_condition} {#cauto_field_check_data_value}', 'autoqa-test-automation')
                ],
                'callback'  => 'cauto_default_check_data_step'
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
                            'class' => 'cauto-step-nodes cauto-wait-to-step cauto-field wide'
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
            'wp_divider' => [
                'divider'    => true,
                'label'     => __('Wordpress', 'autoqa-test-automation'), 
            ],
            'wp-check-meta-value' => [
                'label'     => __('Check Meta', 'autoqa-test-automation'),
                'settings'      => [
                    [
                        'field' => 'select',
                        'attr'  => [
                            'id'        => 'cauto_field_check_meta_post_selector',
                            'class'     => 'cauto-step-nodes cauto-meta-key-post cauto-field wide cauto-select2-field'
                        ],
                        'select2'   => ['post', 'page', 'autoqa-automation'],
                        'label' => __('Post', 'autoqa-test-automation')
                    ],
                    [
                        'field' => 'input',
                        'attr'  => [
                            'type'  => 'text',
                            'id'    => 'cauto_field_check_meta_key_selector',
                            'class' => 'cauto-step-nodes cauto-meta-key-step cauto-field wide'
                        ],
                        'label' => __('Meta Key', 'autoqa-test-automation')
                    ],
                    [
                        'field' => 'select',
                        'attr'  => [
                            'id'    => 'cauto_field_check_meta_value_condition',
                            'class' => 'cauto-step-nodes cauto-check-data-step cauto-field wide block'
                        ],
                        'label'     => __('Condition', 'autoqa-test-automation'),
                        'options'   => [
                            'is equals to'                  => __('Is equals To', 'autoqa-test-automation'),
                            'is not equals to'              => __('Is not equals to', 'autoqa-test-automation'),
                            'is contains with'              => __('Is contains with', 'autoqa-test-automation'),
                            'is start with'                 => __('Is start With', 'autoqa-test-automation'),
                            'is end with'                   => __('Is end with', 'autoqa-test-automation'),
                            'is less than'                  => __('Is less Than', 'autoqa-test-automation'),
                            'is greater than'               => __('Is greater than', 'autoqa-test-automation'),
                            'is less than or equal to'      => __('Is less than or equal to', 'autoqa-test-automation'),
                            'is greater than or equal to'   => __('Is greater than or equal to', 'autoqa-test-automation'),
                            'has any'                       => __('Has any', 'autoqa-test-automation')
                        ],
                        'field-interact'    => [
                            'event'     => 'change',
                            'payload'   => [
                                'value'     => 'has any',
                                'target'    => ['#cauto_field_check_meta_value_selector'],
                                'action'     => 'hide'
                            ],
                            'callback'  => 'cauto_default_steps_hide_related'
                        ]
                    ],
                    [
                        'field' => 'textarea',
                        'attr'  => [
                            'id'            => 'cauto_field_check_meta_value_selector',
                            'class'         => 'cauto-step-nodes cauto-meta-key-value cauto-field wide cauto-variable-step',
                            'rows'           => 5,
                            'placeholder'   => __('Text or {JSON}', 'autoqa-test-automation')
                        ],
                        'help-text' => __('If the value is array convert them into JSON', 'autoqa-test-automation'),
                        'label' => __('Value', 'autoqa-test-automation')
                    ]
                    
                ],
                'icon'      => '<span class="cauto-icon cauto-icon-wp"></span>',
                'group'     => 'wordpress',
                'step_indicator'    => [
                    'selector'      => ['#cauto_field_check_meta_key_selector', '#cauto_field_check_meta_value_condition', '#cauto_field_check_meta_value_selector'],
                    'describe_text' => __(' - {#cauto_field_check_meta_key_selector} {#cauto_field_check_meta_value_condition} {#cauto_field_check_meta_value_selector}', 'autoqa-test-automation')
                ],
                'callback'  => 'cauto_default_wp_check_meta'
            ],
            'wp-check-transient-value' => [
                'label'     => __('Check Transient', 'autoqa-test-automation'),
                'settings'      => [
                    [
                        'field' => 'input',
                        'attr'  => [
                            'type'  => 'text',
                            'id'    => 'cauto_field_check_transient_key_selector',
                            'class' => 'cauto-step-nodes cauto-transient-key-step cauto-field wide'
                        ],
                        'label' => __('Transient Key', 'autoqa-test-automation')
                    ],
                    [
                        'field' => 'select',
                        'attr'  => [
                            'id'    => 'cauto_field_check_transient_value_condition',
                            'class' => 'cauto-step-nodes cauto-check-transient-step cauto-field wide block'
                        ],
                        'label'     => __('Condition', 'autoqa-test-automation'),
                        'options'   => [
                            'is equals to'                  => __('Is equals To', 'autoqa-test-automation'),
                            'is not equals to'              => __('Is not equals to', 'autoqa-test-automation'),
                            'is contains with'              => __('Is contains with', 'autoqa-test-automation'),
                            'is start with'                 => __('Is start With', 'autoqa-test-automation'),
                            'is end with'                   => __('Is end with', 'autoqa-test-automation'),
                            'is less than'                  => __('Is less Than', 'autoqa-test-automation'),
                            'is greater than'               => __('Is greater than', 'autoqa-test-automation'),
                            'is less than or equal to'      => __('Is less than or equal to', 'autoqa-test-automation'),
                            'is greater than or equal to'   => __('Is greater than or equal to', 'autoqa-test-automation'),
                            'has any'                       => __('Has any', 'autoqa-test-automation'),
                        ],
                        'field-interact'    => [
                            'event'     => 'change',
                            'payload'   => [
                                'value'     => 'has any',
                                'target'    => ['#cauto_field_check_transient_value_selector'],
                                'action'     => 'hide'
                            ],
                            'callback'  => 'cauto_default_steps_hide_related'
                        ]
                    ],
                    [
                        'field' => 'input',
                        'attr'  => [
                            'type'  => 'text',
                            'id'    => 'cauto_field_check_transient_value_selector',
                            'class' => 'cauto-step-nodes cauto-transient-value cauto-field wide cauto-variable-step'
                        ],
                        'label' => __('Value', 'autoqa-test-automation')
                    ]
                    
                ],
                'icon'      => '<span class="cauto-icon cauto-icon-wp"></span>',
                'group'     => 'wordpress',
                'step_indicator'    => [
                    'selector'      => ['#cauto_field_check_transient_key_selector', '#cauto_field_check_transient_value_condition', '#cauto_field_check_transient_value_selector'],
                    'describe_text' => __(' - {#cauto_field_check_transient_key_selector} {#cauto_field_check_transient_value_condition} {#cauto_field_check_transient_value_selector}', 'autoqa-test-automation')
                ],
                'callback'  => 'cauto_default_wp_check_transient'
            ],
            'wp-check-scheduler' => [
                'label'     => __('Check Scheduler', 'autoqa-test-automation'),
                'settings'      => [
                    [
                        'field' => 'input',
                        'attr'  => [
                            'type'  => 'text',
                            'id'    => 'cauto_field_check_scheduler_key_selector',
                            'class' => 'cauto-step-nodes cauto-meta-scheduler-step cauto-field wide'
                        ],
                        'label' => __('Hook', 'autoqa-test-automation')
                    ],
                    [
                        'field' => 'select',
                        'attr'  => [
                            'id'    => 'cauto_field_check_scheduler_value_condition',
                            'class' => 'cauto-step-nodes cauto-check-scheduler-step cauto-field wide block'
                        ],
                        'label'     => __('Condition', 'autoqa-test-automation'),
                        'options'   => [
                            'is scheduled'      => __('Is scheduled', 'autoqa-test-automation'),
                            'has run'          => __('Has run', 'autoqa-test-automation')
                        ]
                    ]
                    
                ],
                'icon'      => '<span class="cauto-icon cauto-icon-wp"></span>',
                'group'     => 'wordpress',
                'step_indicator'    => [
                    'selector'      => ['#cauto_field_check_scheduler_key_selector', '#cauto_field_check_scheduler_value_condition'],
                    'describe_text' => __(' - {#cauto_field_check_scheduler_key_selector} {#cauto_field_check_scheduler_value_condition}', 'autoqa-test-automation')
                ],
                'callback'  => 'cauto_default_wp_check_scheduler'
            ],
            'wp-check-post' => [
                'label'     => __('Check Post', 'autoqa-test-automation'),
                'settings'      => [
                    [
                        'field' => 'select',
                        'attr'  => [
                            'id'        => 'cauto_field_check_post_data_selector',
                            'class'     => 'cauto-step-nodes cauto-post-data-post cauto-field wide cauto-select2-field'
                        ],
                        'options'               => [ 'Current Post' => __('Current Post', 'autoqa-test-automation')],
                        'select2'               => ['any'],
                        'select2_allow_clear'   => true,
                        'label'         => __('Post', 'autoqa-test-automation')
                    ],
                    [
                        'field' => 'select',
                        'attr'  => [
                            'id'        => 'cauto_field_check_post_data_to_check',
                            'class'     => 'cauto-step-nodes cauto-check-post-data-step cauto-field wide block cauto-select2-field-static'
                        ],
                        'label'         => __('Post meta data', 'autoqa-test-automation'),
                        'options'       => [
                            'ID'                    => __('ID', 'autoqa-test-automation'),
                            'post_title'            => __('Post Title', 'autoqa-test-automation'),
                            'post_author'           => __('Author', 'autoqa-test-automation'),
                            'post_date '            => __('Post Date', 'autoqa-test-automation'),
                            'post_date_gmt '        => __('Post Date GMT', 'autoqa-test-automation'),
                            'post_content'          => __('Post Content', 'autoqa-test-automation'),
                            'post_excerpt'          => __('Post Excerpt', 'autoqa-test-automation'),
                            'post_status'           => __('Post Status', 'autoqa-test-automation'),
                            'comment_status '       => __('Comment Status', 'autoqa-test-automation'),
                            'ping_status'           => __('Ping Status', 'autoqa-test-automation'),
                            'post_password '        => __('Post Password', 'autoqa-test-automation'),
                            'post_name'             => __('Post Slug', 'autoqa-test-automation'),
                            'to_ping'               => __('To Ping', 'autoqa-test-automation'),
                            'pinged'                => __('Pinged', 'autoqa-test-automation'),
                            'post_modified'         => __('Post Modified', 'autoqa-test-automation'),
                            'post_modified_gmt'     => __('Post Modified GMT', 'autoqa-test-automation'),
                            'post_content_filtered' => __('Post Content Filtered', 'autoqa-test-automation'),
                            'post_parent '          => __('Post Parent ', 'autoqa-test-automation'),
                            'guid'                  => __('Guid', 'autoqa-test-automation'),
                            'menu_order'            => __('Menu Order', 'autoqa-test-automation'),
                            'post_type'             => __('Post Type', 'autoqa-test-automation'),
                            'post_mime_type'        => __('Post Mime Type', 'autoqa-test-automation'),
                            'comment_count'         => __('Comment Count', 'autoqa-test-automation'),
                            'filter'                => __('Filter', 'autoqa-test-automation')
                        ]
                    ],
                    [
                        'field' => 'select',
                        'attr'  => [
                            'id'    => 'cauto_field_check_transient_value_condition',
                            'class' => 'cauto-step-nodes cauto-check-transient-step cauto-field wide block'
                        ],
                        'label'     => __('Condition', 'autoqa-test-automation'),
                        'options'   => [
                            'is equals to'                  => __('Is equals To', 'autoqa-test-automation'),
                            'is not equals to'              => __('Is not equals to', 'autoqa-test-automation'),
                            'is contains with'              => __('Is contains with', 'autoqa-test-automation'),
                            'is start with'                 => __('Is start With', 'autoqa-test-automation'),
                            'is end with'                   => __('Is end with', 'autoqa-test-automation'),
                            'is less than'                  => __('Is less Than', 'autoqa-test-automation'),
                            'is greater than'               => __('Is greater than', 'autoqa-test-automation'),
                            'is less than or equal to'      => __('Is less than or equal to', 'autoqa-test-automation'),
                            'is greater than or equal to'   => __('Is greater than or equal to', 'autoqa-test-automation'),
                            'has any'                       => __('Has any', 'autoqa-test-automation'),
                        ],
                        'field-interact'    => [
                            'event'     => 'change',
                            'payload'   => [
                                'value'     => 'has any',
                                'target'    => ['#cauto_field_check_post_data_value_selector'],
                                'action'     => 'hide'
                            ],
                            'callback'  => 'cauto_default_steps_hide_related'
                        ]
                    ],
                    [
                        'field' => 'textarea',
                        'attr'  => [
                            'id'            => 'cauto_field_check_post_data_value_selector',
                            'class'         => 'cauto-step-nodes cauto-post_data-value-value cauto-field wide cauto-variable-step',
                            'rows'           => 5,
                            'placeholder'   => __('Text or {JSON}', 'autoqa-test-automation')
                        ],
                        'help-text' => __('If the value is array convert them into JSON', 'autoqa-test-automation'),
                        'label' => __('Value', 'autoqa-test-automation')
                    ]
                    
                ],
                'icon'      => '<span class="cauto-icon cauto-icon-wp"></span>',
                'group'     => 'wordpress',
                'step_indicator'    => [
                    'selector'      => ['#cauto_field_check_post_data_to_check', '#cauto_field_check_transient_value_condition', '#cauto_field_check_post_data_selector', '#cauto_field_check_post_data_value_selector'],
                    'describe_text' => __(' - {#cauto_field_check_post_data_to_check} of "{#cauto_field_check_post_data_selector}" if {#cauto_field_check_transient_value_condition} {#cauto_field_check_post_data_value_selector}', 'autoqa-test-automation')
                ],
                'callback'  => 'cauto_default_wp_check_post_data'
            ]
            
        ];

        return apply_filters('autoqa-steps', $steps);
    }
}

new cauto_steps();