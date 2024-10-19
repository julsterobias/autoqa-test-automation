/**
 * 
 * 
 * cauto_default_store_data
 * @sincen 1.0.0
 * 
 * 
 */
var cauto_default_store_data = (params = null) => {

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

    let data_to_store   = cauto_translate_variable_in_steps_field(params[1].value);
    let data_name       = params[0].value;
    sessionStorage.setItem('$'+data_name, data_to_store);
    return [
        {
            status: 'passed',
            message: '"' + data_to_store + '" ' +cauto_translable_labels['is stored as']+ ' "' + data_name + '"'
        }
    ];

}