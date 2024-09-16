/**
 * 
 * 
 * cauto_default_set_text_step
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
    
    //manage event and validate element existing
    //emulate human bahaviour by clicking the field before doing their intentions
    let element =  cauto_event_manager(selector, field_attr, 'click', '', true);

    if (!Array.isArray(element)) {
        if (jQuery(element).length > 0) {
            jQuery(element).val(text);

            if (jQuery(element).val() === text) {
                return [
                    {
                        status: 'passed',
                        message: '"' + text + '" is set to ' + params[2].value
                    }
                ];
            } else {
                return [
                    {
                        status: 'failed',
                        message: 'Action failed: Runner cannot set "' + text + '" to ' + params[2].value
                    }
                ];
            }
            
        } else {
            //redundant fail safe
            return [
                {
                    status: 'failed',
                    message: cauto_step_text.element_not_found
                }
            ];
        }
    } else {
        console.log('22222');
        return element;
    }

}