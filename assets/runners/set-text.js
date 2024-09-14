/**
 * 
 * 
 * cauto_default_hover_step
 * @since 1.0.0
 * 
 * 
 */
cauto_default_set_text_step = (params = null) => {

    if (!params) {
        return [
            {
                status: 'failed',
                message: cauto_step_text.unconfigured_msg
            }
        ];
    }

    let field_attr  = null;
    let selector    = null;
    let text        = null;
    
    if ( Array.isArray(params) ) {
        field_attr = (params[0].value)? params[0].value : null;
        if (!field_attr) {
            return;
        }

        selector = (params[1].value)? params[1].value : null;
        if (!selector) {
            return;
        }

        text = (params[3].value)? params[3].value : null;
    }
    
    return cauto_event_manager(selector, field_attr, 'click', [
        {
            event: 'set-text',
            value: text
        }
    ]);

}