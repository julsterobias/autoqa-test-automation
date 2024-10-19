/**
 * 
 * cauto_default_wp_check_post_data
 * @since 1.0.0
 * 
 */

var cauto_default_wp_check_post_data = (params = null) => {

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


    let post        = (params[0].value === 'Current Post')? cauto_runner.post_id : params[0].value;
    let metadata    = params[1].value;
    let condition   = params[2].value;
    let value       = params[3].value;

    value = cauto_translate_variable_in_steps_field(value);

    jQuery.ajax( {
        type : "post",  
        url: cauto_runner.ajaxurl,
        data : {    
            action: 'cauto_step_check_post_metedata', 
            wp_post: post,
            nonce: cauto_runner.nonce,
            metadata: metadata,
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