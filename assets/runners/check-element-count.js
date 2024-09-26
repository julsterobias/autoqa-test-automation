/**
 * 
 * 
 * cauto_default_check_element_count_step
 * @since 1.0.0
 * 
 * 
 */

var cauto_default_check_element_count_step = (params = null) => {

    if (!params || !Array.isArray(params)) {
        return [
            {
                status: 'failed',
                message: cauto_step_text.unconfigured_msg
            }
        ];
    }

    for (let x in params) {
        if (typeof params[x].value === 'undefined') {
            return [
                {
                    status: 'failed',
                    message: cauto_step_text.unconfigured_msg
                }
            ];
            break;
        }
    }
    
    let field_attr          = (params[0].value)? params[0].value : null;
    let selector            = (params[1].value)? params[1].value : null;
    let alias               = (params[2].value)? params[2].value : '';
    let condition           = (params[3].value)? params[3].value : '';
    let value_expected      = (params[4].value)? parseInt(params[4].value) : '';

    let element             = cauto_prepare_element_selector(field_attr, selector);

    let value_recieved      = jQuery(element).length;
    let passed_message      = 'Matched: 1, Expected ' + alias + ' ' + condition + ' "' + value_expected + '", Received: "' + value_recieved + '"';
    let failed_message      = 'Matched: 0, Expected ' + alias + ' ' + condition + ' "' + value_expected + '", Received: "' + value_recieved + '"';
    let type_error          = 'Matched: 0, The value is not numeric for "' + condition + '" operation';

    try {
        switch(condition) {
            case 'is equals to':
                if (value_expected === value_recieved) {
                    return [
                        {
                            status: 'passed',
                            message: passed_message
                        }
                    ];
                } else {
                    return [
                        {
                            status: 'failed',
                            message: failed_message
                        }
                    ];
                }
                break;
            case 'is less than':

                number_data_set = cauto_check_data_type(value_recieved, value_expected, type_error);

                if (number_data_set.length === 1) {
                    return number_data_set;
                }

                if (number_data_set[0] < number_data_set[1]) {
                    return [
                        {
                            status: 'passed',
                            message: passed_message
                        }
                    ];
                } else {
                    return [
                        {
                            status: 'failed',
                            message: failed_message
                        }
                    ];
                }
                
                break;

            case 'is greater than':

                number_data_set = cauto_check_data_type(value_recieved, value_expected, type_error);

                if (number_data_set.length === 1) {
                    return number_data_set;
                }

                if (number_data_set[0] > number_data_set[1]) {
                    return [
                        {
                            status: 'passed',
                            message: passed_message
                        }
                    ];
                } else {
                    return [
                        {
                            status: 'failed',
                            message: failed_message
                        }
                    ];
                }

                break;

            case 'is less than or equal to':

                number_data_set = cauto_check_data_type(value_recieved, value_expected, type_error);

                if (number_data_set.length === 1) {
                    return number_data_set;
                }

                if (number_data_set[0] <= number_data_set[1]) {
                    return [
                        {
                            status: 'passed',
                            message: passed_message
                        }
                    ];
                } else {
                    return [
                        {
                            status: 'failed',
                            message: failed_message
                        }
                    ];
                }

            break;
            case 'is greater than or equal to':

                number_data_set = cauto_check_data_type(value_recieved, value_expected, type_error);

                if (number_data_set.length === 1) {
                    return number_data_set;
                }

                if (number_data_set[0] >= number_data_set[1]) {
                    return [
                        {
                            status: 'passed',
                            message: passed_message
                        }
                    ];
                } else {
                    return [
                        {
                            status: 'failed',
                            message: failed_message
                        }
                    ];
                }

            break;

        }

    } catch(error) {
        console.error('Check Title Runner: '+error);
    }
}