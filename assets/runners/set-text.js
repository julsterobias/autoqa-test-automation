/**
 * 
 * 
 * cauto_default_set_text_step
 * @since 1.0.0
 * 
 * 
 */
cauto_default_set_text_step = (params = null) => {

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

    let field_attr  = params[0].value;
    let selector    = params[1].value;

    let text        = cauto_translate_variable_in_steps_field(params[3].value);
    
    //manage event and validate element existing
    //emulate human bahaviour by clicking the field before doing their intentions
    let element     =  cauto_event_manager(selector, field_attr, 'click', '', true);

    if (!Array.isArray(element)) {
        if (jQuery(element).length > 0) {
            jQuery(element).val(text);

            if (jQuery(element).val() === text) {
                return [
                    {
                        status: 'passed',
                        message: '"' + text + '" '+cauto_translable_labels['is set to']+' ' + params[2].value
                    }
                ];
            } else {
                return [
                    {
                        status: 'failed',
                        message: cauto_translable_labels['Action failed: Runner cannot set'] + ' "' + text + '" ' +cauto_translable_labels['to']+ ' ' + params[2].value
                    }
                ];
            }
            
        } else {
            //redundant fail safe
            return [
                {
                    status: 'failed',
                    message: cauto_translable_labels['Matched 0: The element cannot be found.']
                }
            ];
        }
    } else {
        return element;
    }

}