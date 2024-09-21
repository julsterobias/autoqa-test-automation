/**
 * 
 * 
 * cauto_default_store_element_data
 * @since 1.0.0
 * 
 * 
 */

var cauto_default_store_element_data = (params = null) => {

    if (!params) {
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

    let data_name       = params[0].value;
    let field_attr      = params[1].value;
    let selector        = params[2].value;
    let attr            = params[3].value;

    let element         = cauto_prepare_element_selector(field_attr, selector);

    if (element.length === 0) {
        return [
            {
                status: 'failed',
                message: cauto_step_text.element_not_found
            }
        ];
    }

    let data_to_store = null;

    switch (attr) {
        case 'value':
            data_to_store = jQuery(element).val();
            break;
        default:
            data_to_store = jQuery(element).attr(attr);
            if (!data_to_store) {
                return [
                    {
                        status: 'failed',
                        message: 'Element attribute is unrecognizable to runner'
                    }
                ];
            }

            break;
    }

    if (data_to_store) {
        //save the data as transient and pause the runner
        cauto_save_element_to_data_action(data_name, data_to_store);
        return [
            {
                pause: true
            }
        ];
    }

    
}