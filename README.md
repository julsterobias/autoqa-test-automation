[
                        'field' => 'input',
                        'attr'  => [
                            'type'  => 'checkbox',
                            'id'    => 'cauto_test1_checkbox',
                            'class' => 'cauto-start-step-checkbox'
                        ],
                        'label'         => __('Checkbox 1', 'codecorun-test-automation')
                    ],
                    [
                        'field' => 'group',
                        'attr'  => [
                            'type'  => 'checkbox',
                            'id'    => '',
                            'class' => 'cauto-start-step-checkbox-group'
                        ],
                        'label'         => __('Group checkbox Label', 'codecorun-test-automation'),
                        'options'       => [
                            [
                                'label' => 'Group Checkbox 1',
                                'value' => 'gc1'
                            ],
                            [
                                'label' => 'Group Checkbox 2',
                                'value' => 'gc2'
                            ],
                            [
                                'label' => 'Group Checkbox 3',
                                'value' => 'gc3'
                            ]
                        ]
                    ],
                    [
                        'field' => 'group',
                        'attr'  => [
                            'type'  => 'radio',
                            'id'    => '',
                            'class' => 'cauto-start-step-radio-group',
                            'name'  => 'testradiogroup'
                        ],
                        'label'         => __('Group Radio Label', 'codecorun-test-automation'),
                        'options'       => [
                            [
                                'label' => 'Group Radio 1',
                                'value' => 'gc1'
                            ],
                            [
                                'label' => 'Group Radio 2',
                                'value' => 'gc2'
                            ],
                            [
                                'label' => 'Group Radio 3',
                                'value' => 'gc3'
                            ]
                        ]
                    ]