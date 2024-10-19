/**
 * 
 * 
 * cauto_default_store_element_data
 * @since 1.0.0
 * 
 * 
 */

var cauto_default_store_element_data = (params = null) => {

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

    let data_name       = params[0].value;
    let field_attr      = params[1].value;
    let selector        = params[2].value;
    let attr            = params[3].value;

    let element         = cauto_prepare_element_selector(field_attr, selector);

    if (element.length === 0) {
        return [
            {
                status: 'failed',
                message: cauto_translable_labels['Matched 0: The element cannot be found.']
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
                        message: cauto_translable_labels['Element attribute is unrecognizable to runner']
                    }
                ];
            }

            break;
    }

    data_to_store = cauto_translate_variable_in_steps_field(data_to_store)
    sessionStorage.setItem('$'+data_name, data_to_store);
    
    return [
        {
            status: 'passed',
            message: '"' + data_to_store + '" '+cauto_translable_labels['is stored as']+' "' + data_name + '"'
        }
    ];

    
}