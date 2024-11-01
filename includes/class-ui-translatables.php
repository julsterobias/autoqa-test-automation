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
                'autoqa_error1'      => __('CAUTO ERROR: unable to save step, please contact support', 'autoqa'),
                'autoqa_error2'      => __('CAUTO STEP FIELD ERROR: No element identifier was found for radio button', 'autoqa'),
                'autoqa_error3'      => __('CAUTO STEP ERROR: No step indentifier is found. Please contact developer', 'autoqa'),
                'autoqa_error4'      => __('CAUTO ERROR: Flow and Runner not found.', 'autoqa')
            ],
            'runner'    => [
                'Action Passed:'                    => __('Action Passed:', 'autoqa'),
                'is stored to data as'              => __('is stored to data as', 'autoqa'),
                'Matched: 1'                        => __('Matched: 1', 'autoqa'),
                'Matched: 0'                        => __('Matched: 0', 'autoqa'),
                'Expected:'                          => __('Expected:', 'autoqa'),
                'Received:'                          => __('Received:', 'autoqa'),
                'operation'                         => __('operation', 'autoqa'),
                'The value is not numeric for'      => __('The value is not numeric for', 'autoqa'),
                'Check Data Runner:'                 => __('Check Data Runner:', 'autoqa'),
                'Check Title Runner'                => __('Check Title Runner', 'autoqa'),
                'Not equals to'                     => __('Not equals to', 'autoqa'),
                'Contains with'                     => __('Contains with', 'autoqa'), 
                'Start with'                        => __('Start with', 'autoqa'), 
                'End with'                          => __('End with', 'autoqa'),
                'is displayed'                      => __('is displayed', 'autoqa'),
                'is displayed but not interactable' => __('is displayed but not interactable', 'autoqa'),
                'is hidden'                         => __('is hidden', 'autoqa'),
                'Runner can\'t compare element for visibility please contact developer' => __('Runner can\'t compare element for visibility please contact developer', 'autoqa'),
                'Runner is delayed for'             => __('Runner is delayed for', 'autoqa'),
                'Source element is not found'       => __('Source element is not found', 'autoqa'),
                'Target element is not found'       => __('Target element is not found', 'autoqa'),
                'is emptied'                        => __('is emptied', 'autoqa'),
                'Runner is paused for manual input' => __('Runner is paused for manual input', 'autoqa'),
                'AutoQA Error: No payload found after the runner is paused. Please contact developer'   => __('AutoQA Error: No payload found after the runner is paused. Please contact developer', 'autoqa'),
                'Document scrolled down with'       => __('Document scrolled down with', 'autoqa'),
                'AutoQA Error: No payload found after the runner is paused to wait for. Please contact developer'   => __('AutoQA Error: No payload found after the runner is paused to wait for. Please contact developer','autoqa'),
                'Document scrolled up with'         => __('Document scrolled up with', 'autoqa'),
                'are sent to'                       => __('are sent to', 'autoqa'),
                'is set to'                         => __('is set to', 'autoqa'),
                'Action Failed: Runner cannot find' => __('Action Failed: Runner cannot find', 'autoqa'),
                'in the field'                      => __('in the field', 'autoqa'), //last touch
                'Action failed: Runner cannot set'  => __('Action failed: Runner cannot set', 'autoqa'),
                'to'                                => __('to', 'autoqa'),
                'is stored as'                      => __('is stored as', 'autoqa'),
                'Element attribute is unrecognizable to runner' => __('Element attribute is unrecognizable to runner', 'autoqa'),
                'autoQA Error: No data response from image generator, please contact developer' => __('autoQA Error: No data response from image generator, please contact developer','autoqa'),
                '- PDF is assigned to field'        => __('- PDF is assigned to field', 'autoqa'),
                'autoQA Error: No data response from pdf generator, please contact developer' => __('autoQA Error: No data response from image generator, please contact developer', 'autoqa'),
                'is displayed after'                => __('is displayed after', 'autoqa'),
                'second(s)'                         => __('second(s)', 'autoqa'),
                'still not displayed after'         => __('still not displayed after', 'autoqa'),
                'is not displayed after'            => __('is not displayed after', 'autoqa'),
                'have value after'                  => __('have value after', 'autoqa'),
                'still no have value after'         => __('still no have value after', 'autoqa'),
                'is enable after'                   => __('is enable after', 'autoqa'),
                'still disabled after'              => __('still disabled after', 'autoqa'),
                'is disable after'                  => __('is disable after', 'autoqa'),
                'still enabled after'               => __('still enabled after', 'autoqa'),
                'Runner can\'t verify the element to have enable property'  => __('Runner can\'t verify the element to have enable property', 'autoqa'),
                'autoQA Error: No data response from WP steps, please contact developer'    => __('autoQA Error: No data response from WP steps, please contact developer', 'autoqa'),
                'Matched 0: The element cannot be found.'   => __('Matched 0: The element cannot be found.', 'autoqa'),
                'Matched is greater than 1: Multiple elements were found, but the specific event cannot be dispatched.' => __('Matched is greater than 1: Multiple elements were found, but the specific event cannot be dispatched.', 'autoqa'),
                'Matched 0: The element cannot be found after dispatch.'    => __('Matched 0: The element cannot be found after dispatch.', 'autoqa'),
                'Matched 1: Event is validated.'    => __('Matched 1: Event is validated.', 'autoqa'),
                'The step is not configured'        => __('The step is not configured', 'autoqa'),
                'Matched: 1, Expected:'             => __('Matched: 1, Expected:', 'autoqa'),
                'Matched: 0, Expected:'             => __('Matched: 0, Expected:', 'autoqa'),
                'Matched: 0, The value is not numeric for'  => __('Matched: 0, The value is not numeric for', 'autoqa'),
                'Check Title Runner:'               => __('Check Title Runner:', 'autoqa')

            ]
        ];
    }
}

new cauto_ui_translatables();