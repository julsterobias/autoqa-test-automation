/**
 * 
 * cauto_default_click_step
 * @since 1.0.0
 * 
 * 
 */

var cauto_default_click_step = (params = null) => {

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

    let field_attr  = null;
    let click_type  = null;
    let selector    = null;
    let field_alias = '';
    
    if ( Array.isArray(params) ) {

        field_attr = (params[0].value)? params[0].value : null;
        if (!field_attr) {
            return;
        }

        click_type = (params[1].value)? params[1].value : null;
        if (!click_type) {
            return;
        }

        if (click_type === 'single') {
            click_type = 'click';
        } else {
            click_type = 'dblclick';
        }

        selector = (params[2].value)? params[2].value : null;
        if (!selector) {
            return;
        }

        field_alias = (params[3].value)? params[3].value : '';
    }

    return cauto_event_manager(selector, field_attr, click_type, field_alias);

}