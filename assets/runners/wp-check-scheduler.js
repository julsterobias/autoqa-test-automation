/**
 * 
 * 
 * cauto_default_wp_check_scheduler
 * @since 1.0.0
 * 
 * 
 */

var cauto_default_wp_check_scheduler = (params = null) => {

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
        }
    }

    let hook            = params[0].value;
    let condition       = params[1].value;

    jQuery.ajax( {
        type : "post",  
        url: cauto_runner.ajaxurl,
        data : {    
            action: 'cauto_step_check_scheduler', 
            hook: hook,
            nonce: cauto_runner.nonce,
            condition: condition
        },
        success: function( data ) {
            //response data
            if (data) {
                if (data.status === 'success') {

                    if (typeof data.step !== 'undefined') {
                        
                        if (cauto_paused_data.length > 0) {
                            
                            cuato_resume_paused_runner(data.step.status, data.step.message);

                        } else {
                            console.error(cauto_translable_labels['AutoQA Error: No payload found after the runner is paused to wait for. Please contact developer']);
                        }

                    } else {
                        console.error(cauto_translable_labels["autoQA Error: No data response from WP steps, please contact developer"]);
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