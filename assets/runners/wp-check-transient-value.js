/**
 * 
 * 
 * cauto_default_wp_check_transient
 * @since 1.0.0
 * 
 * 
 */

var cauto_default_wp_check_transient = (params = null) => {

    if (!params || !Array.isArray(params)) {
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
        }
    }

    let key             = params[0].value;
    let condition       = params[1].value;
    let value           = params[2].value;

    value = cauto_translate_variable_in_steps_field(value);

    jQuery.ajax( {
        type : "post",  
        url: cauto_runner.ajaxurl,
        data : {    
            action: 'cauto_step_check_transient_value', 
            key: key,
            nonce: cauto_runner.nonce,
            condition: condition,
            value: value
        },
        success: function( data ) {
            //response data
            if (data) {
                if (data.status === 'success') {

                    if (typeof data.step !== 'undefined') {
                        
                        if (cauto_paused_data.length > 0) {
                            
                            cuato_resume_paused_runner(data.step.status, data.step.message);

                        } else {
                            console.error('AutoQA Error: No payload found after the runner is paused to wait for. Please contact developer');
                        }

                    } else {
                        console.error("autoQA Error: No data response from WP steps, please contact developer");
                    }

                } else {
                    console.error(data.message);
                }
            }
        }
    });

    return [
        {
            pause: true
        }
    ];
    

}