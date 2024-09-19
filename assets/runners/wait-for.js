/**
 * 
 * cauto_default_wait_for_selector
 * 
 * 
 */
var cauto_wait_for_time_duration = 0;
const cauto_wait_for_max_time_duration = 60;

var cauto_default_wait_for_selector = (params = null) => {

    if (!params || !Array.isArray(params)) {
        return [
            {
                status: 'failed',
                message: cauto_step_text.unconfigured_msg
            }
        ];
    }

    let field_attr      = (params[0].value)? params[0].value : null;
    let selector        = (params[1].value)? params[1].value : null;
    let alias           = (params[2].value)? params[2].value : '';
    let condition       = (params[3].value)? params[3].value : '';

    setTimeout(function(){
        cauto_check_element_to_display(selector, field_attr, condition, alias);
    }, 1000);

    return [
        {
            pause: true
        }
    ];

}

const cauto_check_element_to_display = (selector, field_attr, condition, alias) => {

    cauto_wait_for_time_duration++;

    let element = cauto_event_manager(selector, field_attr, null, '', true, true);

    switch (condition) {
        case 'to display': //check if element is displayed after waiting for 60 seconds

            let is_displayed = false;
            if ( element[1].isEqualNode(element[0][0]) ) {
                is_displayed = true;
            } else {
                is_displayed = false;
            }


            if ( !is_displayed && cauto_wait_for_time_duration < cauto_wait_for_max_time_duration ) {
                setTimeout(function(){
                    cauto_check_element_to_display(selector, field_attr, condition, alias);
                }, 1000);
            } else if (is_displayed && cauto_wait_for_time_duration < cauto_wait_for_max_time_duration) {
                if (cauto_paused_data.length > 0) {
                    let duration_ = cauto_wait_for_time_duration * 1000;
                    cauto_do_run_runner([
                        {
                            status: 'passed',
                            message: alias + ' is displayed after ' + duration_ + 'ms',
                            pause: true
                        }
                    ], cauto_paused_data[1]);

                } else {
                    console.error('AutoQA Error: No payload found after the runner is paused to wait for. Please contact developer');
                }
            } else {
                if (cauto_paused_data.length > 0) {
                    cauto_do_run_runner([
                        {
                            status: 'failed',
                            message: alias + ' still not displayed after ' +cauto_wait_for_max_time_duration+ ' seconds',
                            pause: true
                        }
                    ], cauto_paused_data[1]);
                } else {
                    console.error('AutoQA Error: No payload found after the runner is paused to wait for. Please contact developer');
                }
            }

        break;
        case 'to not display':

            let is_not_displayed = false;
            if ( element[1].isEqualNode(element[0][0]) ) {
                is_not_displayed = false;
            } else {
                is_not_displayed = true;
            }


            if ( !is_not_displayed && cauto_wait_for_time_duration < 60 ) {
                setTimeout(function(){
                    cauto_check_element_to_display(selector, field_attr, condition, alias);
                }, 1000);
            } else if (is_not_displayed && cauto_wait_for_time_duration < 60) {
                if (cauto_paused_data.length > 0) {
                    let duration_ = cauto_wait_for_time_duration * 1000;
                    cauto_do_run_runner([
                        {
                            status: 'passed',
                            message: alias + ' is not displayed after ' + duration_ + 'ms',
                            pause: true
                        }
                    ], cauto_paused_data[1]);

                } else {
                    console.error('AutoQA Error: No payload found after the runner is paused to wait for. Please contact developer');
                }
            } else {
                if (cauto_paused_data.length > 0) {
                    cauto_do_run_runner([
                        {
                            status: 'failed',
                            message: alias + ' still displayed after '+ cauto_wait_for_max_time_duration + 'seconds',
                            pause: true
                        }
                    ], cauto_paused_data[1]);
                } else {
                    console.error('AutoQA Error: No payload found after the runner is paused to wait for. Please contact developer');
                }
            }

        break;
    }

}