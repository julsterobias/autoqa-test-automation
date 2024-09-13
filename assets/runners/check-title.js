/**
 * 
 * 
 * 
 * start
 * @params object
 * 
 * 
 * 
 */
var cauto_default_check_page_title_step = (params = null) => {

    if (!params) {
        return [
            {
                status: 'failed',
                message: 'Error:' + cauto_runner.unconfigured_msg
            }
        ];
    }

    let page_title      = document.title;
    let response_part   = ', Expected: '+ params[1].value + ', Received: ' + page_title;

    try {
        
        switch(params[0].value) {
            case 'equals to':
                if (page_title === params[1].value) {
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
                if (page_title !== params[1].value) {
                    return [
                        {
                            status: 'passed',
                            message: 'Not equals to ' + params[1].value + ' - Matched: 0' + response_part
                        }
                    ];
                } else {
                    return [
                        {
                            status: 'failed',
                            message: 'Not equals to ' + params[1].value + ' - Matched: 1' + response_part
                        }
                    ];
                }
                break;
            case 'contains with':
                if (page_title.search(params[1].value) >= 0) {
                    return [
                        {
                            status: 'passed',
                            message: 'Contains with ' + params[1].value + ' - Matched: 1' + response_part
                        }
                    ];
                } else {
                    return [
                        {
                            status: 'failed',
                            message: 'Contains with ' + params[1].value + ' - Matched: 0' + response_part
                        }
                    ];
                }
                break;
            case 'start with':
                if (page_title.search(params[1].value) === 0) {
                    return [
                        {
                            status: 'passed',
                            message: 'Start with ' + params[1].value + ' - Matched: 1' + response_part
                        }
                    ];
                } else {
                    return [
                        {
                            status: 'failed',
                            message: 'Start with ' + params[1].value + ' - Matched: 0' + response_part
                        }
                    ];
                }
                break;
            case 'end with':
                let search_start    = page_title.search(params[1].value);
                let substrin        = page_title.substring(search_start, page_title.length);
                if (substrin === params[1].value) {
                    return [
                        {
                            status: 'passed',
                            message: 'End with ' + params[1].value + ' - Matched: 1' + response_part
                        }
                    ];
                } else {
                    return [
                        {
                            status: 'failed',
                            message: 'End with ' + params[1].value + ' - Matched: 0' + response_part
                        }
                    ];
                }
                break;
        }

    } catch(error) {
        console.error('Check Title Runner: '+error);
    }
}