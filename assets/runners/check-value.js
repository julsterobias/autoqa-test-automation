/**
 * 
 * 
 * cauto_default_check_value_step
 * @since 1.0.0
 * 
 * 
 */
var cauto_default_check_value_step = (params = null) => {
    
    if (!params) {
        return [
            {
                status: 'failed',
                message: cauto_step_text.unconfigured_msg
            }
        ];
    }

    let field_attr      = null;
    let selector        = null;
    let value_expected   = '';
    
    if ( Array.isArray(params) ) {

        field_attr = (params[0].value)? params[0].value : null;
        if (!field_attr) {
            return;
        }

        selector = (params[1].value)? params[1].value : null;
        if (!selector) {
            return;
        }

        value_expected = (params[4].value)? params[4].value : '';
    }

    let element             = cauto_event_manager(selector, field_attr, null, '', true);
    let value_recieved      = jQuery(element).val();
    let passed_message      = 'Matched: 1, '+params[2].value+' - "' + value_recieved + '" ' + params[3].value + ' "' + value_expected + '"';
    let failed_message      = 'Matched: 0, '+params[2].value+' - "' + value_recieved + '" ' + params[3].value + ' "' + value_expected + '"';
    let type_error          = 'Matched: 0, The value is not numeric for "' + params[3].value + '" operation';


    let number_data_set     = [];

    try {
        
        switch(params[3].value) {
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

const cauto_check_data_type = (value_expected, value_recieved, type_error) => {

    if (isNaN(value_expected) || isNaN(value_recieved)) {
        return [
            {
                status: 'failed',
                message: type_error
            }
        ];
    }

    value_expected = Number(value_expected);
    value_recieved = Number(value_recieved);

    return [value_expected, value_recieved];
}