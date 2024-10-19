/**
 * 
 * 
 * cauto_default_empty_field_step
 * @since 1.0.0
 * 
 * 
 */

var cauto_default_empty_field_step = (params = null) => {

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

    let field_attr      = params[0].value;
    let selector        = params[1].value;
    let alias           = params[2].value;
    
    let element =  cauto_event_manager(selector, field_attr, 'click', '', true);

    if (!Array.isArray(element)) {

        if (jQuery(element).length > 0) {

            jQuery(element).val('');

            return [
                {
                    status: 'passed',
                    message: alias + ' ' + cauto_translable_labels['is emptied']
                }
            ];

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