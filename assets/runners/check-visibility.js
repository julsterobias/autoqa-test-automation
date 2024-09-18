/**
 * 
 * 
 * cauto_default_check_visibilty_step
 * 
 * 
 */

var cauto_default_check_visibilty_step = (params = null) => {
    
    if (!params || !Array.isArray(params)) {
        return [
            {
                status: 'failed',
                message: cauto_step_text.unconfigured_msg
            }
        ];
    }

    

}