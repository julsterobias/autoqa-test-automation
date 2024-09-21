/**
 * 
 * 
 * cauto_default_store_data
 * @sincen 1.0.0
 * 
 * 
 */
var cauto_default_store_data = (params = null) => {

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
    let value           = params[1].value;

    //save the data as transient and pause the runner
    cauto_save_element_to_data_action(data_name, value);
    
    return [
        {
            pause: true
        }
    ];

}