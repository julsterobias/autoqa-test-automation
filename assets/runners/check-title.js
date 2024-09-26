/**
 * 
 * 
 * 
 * cauto_default_check_page_title_step
 * @params object
 * 
 * 
 * 
 */
var cauto_default_check_page_title_step = (params = null) => {

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

    let page_title_expected = cauto_translate_variable_in_steps_field(params[1].value);

    let page_title      = document.title;
    let response_part   = ', Expected: '+ page_title_expected + ', Received: ' + page_title;
    

    try {
        
        switch(params[0].value) {
            case 'equals to':
                if (page_title === page_title_expected) {
                    return [
                        {
                            status: 'passed',
                            message: 'Matched: 1' + response_part
                        }
                    ];
                } else {
                    return [
                        {
                            status: 'failed',
                            message: 'Matched: 0' + response_part
                        }
                    ];
                }
                break;
            case 'not equals to':
                if (page_title !== page_title_expected) {
                    return [
                        {
                            status: 'passed',
                            message: 'Not equals to ' + page_title_expected + ' - Matched: 0' + response_part
                        }
                    ];
                } else {
                    return [
                        {
                            status: 'failed',
                            message: 'Not equals to ' + page_title_expected + ' - Matched: 1' + response_part
                        }
                    ];
                }
                break;
            case 'contains with':
                if (page_title.search(page_title_expected) >= 0) {
                    return [
                        {
                            status: 'passed',
                            message: 'Contains with ' + page_title_expected + ' - Matched: 1' + response_part
                        }
                    ];
                } else {
                    return [
                        {
                            status: 'failed',
                            message: 'Contains with ' + page_title_expected + ' - Matched: 0' + response_part
                        }
                    ];
                }
                break;
            case 'start with':
                if (page_title.search(page_title_expected) === 0) {
                    return [
                        {
                            status: 'passed',
                            message: 'Start with ' + page_title_expected + ' - Matched: 1' + response_part
                        }
                    ];
                } else {
                    return [
                        {
                            status: 'failed',
                            message: 'Start with ' + page_title_expected + ' - Matched: 0' + response_part
                        }
                    ];
                }
                break;
            case 'end with':
                let search_start    = page_title.search(page_title_expected);
                let substrin        = page_title.substring(search_start, page_title.length);
                if (substrin === page_title_expected) {
                    return [
                        {
                            status: 'passed',
                            message: 'End with ' + page_title_expected + ' - Matched: 1' + response_part
                        }
                    ];
                } else {
                    return [
                        {
                            status: 'failed',
                            message: 'End with ' + page_title_expected + ' - Matched: 0' + response_part
                        }
                    ];
                }
                break;
        }

    } catch(error) {
        console.error('Check Title Runner: '+error);
    }
}