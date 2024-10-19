/**
 * 
 *
 * cauto_default_do_start
 * @since 1.0.0
 * 
 *  
 */
var cauto_default_do_start = (params = null) => {

    if (!params || !Array.isArray(params)) {
        return [
            {
                status: 'failed',
                message: cauto_translable_labels['The step is not configured']
            }
        ];
    }

    for (let x in params) {
        if (typeof params[x].value === 'undefined') {
            return [
                {
                    status: 'failed',
                    message: cauto_translable_labels['The step is not configured']
                }
            ];
            break;
        }
    }

    let current_url     = String(window.location.href);
    current_url         = current_url.split("?flow");
    current_url         = current_url[0];
    let part_message    = ', '+cauto_translable_labels['Expected:']+' ' + params[0].value + ', ' +cauto_translable_labels['Received:']+ ' ' + current_url;

    try {
        if (params[0].value === current_url) {
            return [
                {
                    status: 'passed',
                    message: cauto_translable_labels['Matched: 1'] + part_message
                }
            ];
        } else {
            return [
                {
                    status: 'failed',
                    message: cauto_translable_labels['Matched: 0'] + part_message
                }
            ];
        }
        
    } catch (error) {
        console.error(cauto_translable_labels['Check Title Runner:'] + ' ' + error);
    }

}