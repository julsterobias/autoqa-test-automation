/**
 * 
 * 
 * cauto_default_wp_check_meta
 * @since 1.0.0
 * 
 * 
 */
cauto_default_wp_check_meta = (params = null) => {
    
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
            break;
        }
    }

    let wp_post     = params[0].value;
    let meta_key    = params[1].value;
    let condition   = params[2].value;
    let value       = params[3].value;    

    jQuery.ajax( {
        type : "post",  
        url: cauto_runner.ajaxurl,
        data : {    
            action: 'cauto_step_check_meta_key', 
            wp_post: wp_post,
            nonce: cauto_runner.nonce,
            key: meta_key,
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