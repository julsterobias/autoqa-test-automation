/**
 * 
 * 
 * cauto_default_check_visibilty_step
 * 
 * 
 */

var cauto_default_check_visibilty_step = (params = null) => {
    
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
    let condition       = (params[3].value)? params[3].value : '';

    let element             = cauto_event_manager(selector, field_attr, 'click', '', true, true); //refactor the parameters, pass them in one object instead.
    let passed_message      = 'Matched: 1, Expected: ' +alias+ ' ' +condition+ ', Received: ' +value_recieved;
    let failed_message      = 'Matched: 0, Expected: ' +alias+ ' ' +condition+ ', Received: ' +value_recieved;

    if (Array.isArray(element)) {

        if (element[0] === element[1]) {
            switch (condition) {

            }
        } else {
            return [
                {
                    status: 'failed',
                    message: 'Runner can\'t interact the element'
                }
            ];
        }

    } else {
        return [
            {
                status: 'failed',
                message: 'Runner can\'t compare element for visibility please contact developer'
            }
        ];
    }


}