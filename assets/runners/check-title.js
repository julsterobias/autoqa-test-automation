/**
 * 
 * 
 * 
 * start
 * @param object
 * 
 * 
 * 
 */
var cauto_default_check_page_title_step = (param = null) => {

    let page_title      = document.title;
    let response_part   = ', Expected: '+ param[1].value + ', Received: ' + page_title;

    try {
        
        switch(param[0].value) {
            case 'equals to':
                if (page_title === param[1].value) {
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
                if (page_title !== param[1].value) {
                    return [
                        {
                            status: 'passed',
                            message: 'Matched: 0' + response_part
                        }
                    ];
                } else {
                    return [
                        {
                            status: 'failed',
                            message: 'Matched: 1' + response_part
                        }
                    ];
                }
                break;
            case 'contains with':
                if (page_title.search(param[1].value) >= 0) {
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
            case 'start with':
                if (page_title.search(param[1].value) === 0) {
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
            case 'end with':
                let search_start    = page_title.search(param[1].value);
                let substrin        = test.substring(search_start, page_title.length);
                if (substrin === param[1].value) {
                    return [
                        {
                            status: 'passed',
                            message: 'Matched: 1' + response_part
                        }
                    ];
                } else {
                    return [
                        {
                            status: 'false',
                            message: 'Matched: 0' + response_part
                        }
                    ];
                }
                break;
        }

    } catch(error) {
        console.error('Check Title Runner: '+error);
    }
}