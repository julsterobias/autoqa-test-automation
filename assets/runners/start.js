/**
 * 
 *
 * cauto_default_do_start
 * @since 1.0.0
 * 
 *  
 */
var cauto_default_do_start = (params = null) => {

    if (!params) {
        return [
            {
                status: 'failed',
                message: cauto_step_text.unconfigured_msg
            }
        ];
    }

    let current_url     = String(window.location.href);
    current_url         = current_url.split("?flow");
    current_url         = current_url[0];
    let part_message    = ', Expected: ' + params[0].value + ', Received: ' + current_url;

    try {
        if (params[0].value === current_url) {
            return [
                {
                    status: 'passed',
                    message: 'Matched: 1' + part_message
                }
            ];
        } else {
            return [
                {
                    status: 'failed',
                    message: 'Matched: 0' + part_message
                }
            ];
        }
        
    } catch (error) {
        console.error('Check Title Runner: ' + error);
    }

}