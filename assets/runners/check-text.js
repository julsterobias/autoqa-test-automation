/**
 * 
 * 
 * cauto_default_check_text_step
 * @since 1.0.0
 * 
 * 
 */
cauto_default_check_text_step = (params = null) => {
    
    if (!params || !Array.isArray(params)) {
        return [
            {
                status: 'failed',
                message: cauto_step_text.unconfigured_msg
            }
        ];
    }

    let field_attr = (params[0].value)? params[0].value : null;
    let selector = (params[1].value)? params[1].value : null;
    let text_expected = (params[4].value)? params[4].value : '';

    let element             = cauto_event_manager(selector, field_attr, null, '', true);
    let text_recieved       = jQuery(element).text();
    let passed_message      = 'Matched: 1, Expected: '+params[2].value+ ' ' + params[3].value + ' "' + text_expected + '", Received: "'+ text_recieved + '"';
    let failed_message      = 'Matched: 0, Expected: '+params[2].value+ ' ' + params[3].value + ' "' + text_expected + '", Received: "'+ text_recieved + '"';

    try {
        
        switch(params[3].value) {
            case 'equals to':
                if (text_expected === text_recieved) {
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
            case 'not equals to':
                if (text_expected !== text_recieved) {
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
            case 'contains with':
                if (text_recieved.search(text_expected) >= 0) {
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
            case 'start with':
                if (text_recieved.search(text_expected) === 0) {
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
            case 'end with':
                let expected_length = text_expected.length;
                let substrin        = text_recieved.substring(text_recieved.length - expected_length);
                if (substrin === text_expected) {
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