/**
 * 
 * 
 * cauto_default_set_select_step
 * @since 1.0.0
 * 
 * 
 */
var cauto_default_set_select_step = (params = null) => {

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

    let field_attr  = null;
    let selector    = null;
    let value        = null;
    
    if ( Array.isArray(params) ) {
        field_attr = (params[0].value)? params[0].value : null;
        if (!field_attr) {
            return;
        }

        selector = (params[1].value)? params[1].value : null;
        if (!selector) {
            return;
        }

        value = (params[3].value)? params[3].value : null;
    }
    
    //manage event and validate element existing
    //emulate human bahaviour by clicking the field before doing their intentions
    let element =  cauto_event_manager(selector, field_attr, 'click', '', true);

    if (!Array.isArray(element)) {
        if (jQuery(element).length > 0) {

            let seleted_text = '';
            //let's check first if the value is belongs to text or not and auto assign them if found either.
            jQuery(element).find('option').each(function(){
                if (jQuery(this).val() === value || jQuery(this).text() === value) {
                    jQuery(this).prop('selected', true);
                    seleted_text = jQuery(this).text();
                }
            });


            if ( jQuery(element).val() === value || seleted_text === value) {
                return [
                    {
                        status: 'passed',
                        message: '"' + value + '" ' + cauto_translable_labels['is set to'] + ' ' + params[2].value
                    }
                ];
            } else {
                return [
                    {
                        status: 'failed',
                        message: cauto_translable_labels['Action Failed: Runner cannot find'] + ' "' + value + '" ' +cauto_translable_labels['in the field']+ ' ' + params[2].value
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