/**
 * 
 * 
 * cauto_default_set_scroll_to_step
 * @since 1.0.0
 * 
 * 
 */

var cauto_default_set_scroll_to_step = (params = null) => {

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


    let direction   = params[0].value;
    let distance    = parseInt(params[1].value);
  
    switch(direction) {
        case 'down':
            jQuery('html, body').animate({
                scrollTop: jQuery(window).scrollTop() + distance
            }, 200, function(){
                if (cauto_paused_data.length > 0) {
                    cauto_do_run_runner([
                        {
                            status: 'passed',
                            message: cauto_translable_labels['Document scrolled down with'] + ' '+distance,
                            pause: true
                        }
                    ], cauto_paused_data[1]);
                } else {
                    console.error(cauto_translable_labels['AutoQA Error: No payload found after the runner is paused to wait for. Please contact developer']);
                }
            });
            break;
        case 'up':
            jQuery('html, body').animate({
                scrollTop: jQuery(window).scrollTop() - distance
            }, 200,function(){
                if (cauto_paused_data.length > 0) {
                    cauto_do_run_runner([
                        {
                            status: 'passed',
                            message: cauto_translable_labels['Document scrolled up with'] + ' '+distance,
                            pause: true
                        }
                    ], cauto_paused_data[1]);
                } else {
                    console.error(cauto_translable_labels['AutoQA Error: No payload found after the runner is paused to wait for. Please contact developer']);
                }
            });
            break;
    }

    return [
        {
            pause: true
        }
    ];

}