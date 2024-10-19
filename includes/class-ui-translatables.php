<?php 
/**
 * 
 * 
 * cauto_ui_translations
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

class cauto_ui_translatables
{
    public static function ui_text()
    {
        return [
            'admin'     => [
                'autoqa_error1'      => __('CAUTO ERROR: unable to save step, please contact support', 'autoqa-test-automation'),
                'autoqa_error2'      => __('CAUTO STEP FIELD ERROR: No element identifier was found for radio button', 'autoqa-test-automation'),
                'autoqa_error3'      => __('CAUTO STEP ERROR: No step indentifier is found. Please contact developer', 'autoqa-test-automation'),
                'autoqa_error4'      => __('CAUTO ERROR: Flow and Runner not found.', 'autoqa-test-automation')
            ],
            'runner'    => [
                'Action Passed:'                    => __('Action Passed:', 'autoqa-test-automation'),
                'is stored to data as'              => __('is stored to data as', 'autoqa-test-automation'),
                'Matched: 1'                        => __('Matched: 1', 'autoqa-test-automation'),
                'Matched: 0'                        => __('Matched: 0', 'autoqa-test-automation'),
                'Expected:'                          => __('Expected:', 'autoqa-test-automation'),
                'Received:'                          => __('Received:', 'autoqa-test-automation'),
                'operation'                         => __('operation', 'autoqa-test-automation'),
                'The value is not numeric for'      => __('The value is not numeric for', 'autoqa-test-automation'),
                'Check Data Runner:'                 => __('Check Data Runner:', 'autoqa-test-automation'),
                'Check Title Runner'                => __('Check Title Runner', 'autoqa-test-automation'),
                'Not equals to'                     => __('Not equals to', 'autoqa-test-automation'),
                'Contains with'                     => __('Contains with', 'autoqa-test-automation'), 
                'Start with'                        => __('Start with', 'autoqa-test-automation'), 
                'End with'                          => __('End with', 'autoqa-test-automation'),
                'is displayed'                      => __('is displayed', 'autoqa-test-automation'),
                'is displayed but not interactable' => __('is displayed but not interactable', 'autoqa-test-automation'),
                'is hidden'                         => __('is hidden', 'autoqa-test-automation'),
                'Runner can\'t compare element for visibility please contact developer' => __('Runner can\'t compare element for visibility please contact developer', 'autoqa-test-automation'),
                'Runner is delayed for'             => __('Runner is delayed for', 'autoqa-test-automation'),
                'Source element is not found'       => __('Source element is not found', 'autoqa-test-automation'),
                'Target element is not found'       => __('Target element is not found', 'autoqa-test-automation'),
                'is emptied'                        => __('is emptied', 'autoqa-test-automation'),
                'Runner is paused for manual input' => __('Runner is paused for manual input', 'autoqa-test-automation'),
                'AutoQA Error: No payload found after the runner is paused. Please contact developer'   => __('AutoQA Error: No payload found after the runner is paused. Please contact developer', 'autoqa-test-automation'),
                'Document scrolled down with'       => __('Document scrolled down with', 'autoqa-test-automation'),
                'AutoQA Error: No payload found after the runner is paused to wait for. Please contact developer'   => __('AutoQA Error: No payload found after the runner is paused to wait for. Please contact developer','autoqa-test-automation'),
                'Document scrolled up with'         => __('Document scrolled up with', 'autoqa-test-automation'),
                'are sent to'                       => __('are sent to', 'autoqa-test-automation'),
                'is set to'                         => __('is set to', 'autoqa-test-automation'),
                'Action Failed: Runner cannot find' => __('Action Failed: Runner cannot find', 'autoqa-test-automation'),
                'in the field'                      => __('in the field', 'autoqa-test-automation'), //last touch
                'Action failed: Runner cannot set'  => __('Action failed: Runner cannot set', 'autoqa-test-automation'),
                'to'                                => __('to', 'autoqa-test-automation'),
                'is stored as'                      => __('is stored as', 'autoqa-test-automation'),
                'Element attribute is unrecognizable to runner' => __('Element attribute is unrecognizable to runner', 'autoqa-test-automation'),
                'autoQA Error: No data response from image generator, please contact developer' => __('autoQA Error: No data response from image generator, please contact developer','autoqa-test-automation'),
                '- PDF is assigned to field'        => __('- PDF is assigned to field', 'autoqa-test-automation'),
                'autoQA Error: No data response from pdf generator, please contact developer' => __('autoQA Error: No data response from image generator, please contact developer', 'autoqa-test-automation'),
                'is displayed after'                => __('is displayed after', 'autoqa-test-automation'),
                'second(s)'                         => __('second(s)', 'autoqa-test-automation'),
                'still not displayed after'         => __('still not displayed after', 'autoqa-test-automation'),
                'is not displayed after'            => __('is not displayed after', 'autoqa-test-automation'),
                'have value after'                  => __('have value after', 'autoqa-test-automation'),
                'still no have value after'         => __('still no have value after', 'autoqa-test-automation'),
                'is enable after'                   => __('is enable after', 'autoqa-test-automation'),
                'still disabled after'              => __('still disabled after', 'autoqa-test-automation'),
                'is disable after'                  => __('is disable after', 'autoqa-test-automation'),
                'still enabled after'               => __('still enabled after', 'autoqa-test-automation'),
                'Runner can\'t verify the element to have enable property'  => __('Runner can\'t verify the element to have enable property', 'autoqa-test-automation'),
                'autoQA Error: No data response from WP steps, please contact developer'    => __('autoQA Error: No data response from WP steps, please contact developer', 'autoqa-test-automation'),
                'Matched 0: The element cannot be found.'   => __('Matched 0: The element cannot be found.', 'autoqa-test-automation'),
                'Matched is greater than 1: Multiple elements were found, but the specific event cannot be dispatched.' => __('Matched is greater than 1: Multiple elements were found, but the specific event cannot be dispatched.', 'autoqa-test-automation'),
                'Matched 0: The element cannot be found after dispatch.'    => __('Matched 0: The element cannot be found after dispatch.', 'autoqa-test-automation'),
                'Matched 1: Event is validated.'    => __('Matched 1: Event is validated.', 'autoqa-test-automation'),
                'The step is not configured'        => __('The step is not configured', 'autoqa-test-automation'),
                'Matched: 1, Expected:'             => __('Matched: 1, Expected:', 'autoqa-test-automation'),
                'Matched: 0, Expected:'             => __('Matched: 0, Expected:', 'autoqa-test-automation'),
                'Matched: 0, The value is not numeric for'  => __('Matched: 0, The value is not numeric for', 'autoqa-test-automation'),
                'Check Title Runner:'               => __('Check Title Runner:', 'autoqa-test-automation')

            ]
        ];
    }
}

new cauto_ui_translatables();