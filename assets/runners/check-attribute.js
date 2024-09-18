/**
 * 
 * 
 * cauto_default_check_attribute_step
 * @since 1.0.0
 * 
 * 
 */
var cauto_default_check_attribute_step = (params = null) => {
    
    if (!params || !Array.isArray(params)) {
        return [
            {
                status: 'failed',
                message: cauto_step_text.unconfigured_msg
            }
        ];
    }
        
    let field_attr      = (params[0].value)? params[0].value : null;
    let selector        = (params[1].value)? params[1].value : null;
    let alias           = (params[2].value)? params[2].value : '';
    let attr_to_check   = (params[3].value)? params[3].value : '';
    let operation       = (params[4].value)? params[4].value : '';
    let value_expected  = (params[5].value)? params[5].value : '';

    let element             = cauto_event_manager(selector, field_attr, null, '', true);
    let value_recieved      = jQuery(element).attr(attr_to_check);
    let passed_message      = 'Matched: 1, Expected: ' +alias+ ' ' +attr_to_check+ '  ' +operation+ ' ' + value_expected + ', Received: ' +value_recieved;
    let failed_message      = 'Matched: 0, Expected: ' +alias+ ' ' +attr_to_check+ '  ' +operation+ ' ' + value_expected + ', Received: ' +value_recieved;
    let type_error          = 'Matched: 0, The value is not numeric for "' + params[3].value + '" operation';

    let number_data_set     = [];

    try {
        
        switch(params[4].value) {
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
            case 'is not equals to':
                if (value_expected !== value_recieved) {
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
            case 'is contains with':
                if (value_recieved.search(value_expected) >= 0) {
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
            case 'is start with':
                if (value_recieved.search(value_expected) === 0) {
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
            case 'is end with':
                let expected_length = value_expected.length;
                let substrin        = value_recieved.substring(value_recieved.length - expected_length);
                if (substrin === value_expected) {
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