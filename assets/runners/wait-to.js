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
                message: cauto_translable_labels['The step is not configured']
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

    let element             = cauto_event_manager(selector, field_attr, null, '', true, true);

    if (Array.isArray(element) && element.length === 1) {
        if (typeof element[0].status !== 'undefined') {
            if (element[0].status === 'failed') {
                cauto_do_run_runner(element, cauto_paused_data[1]);
                return;
            }
        }
    }

    let is_displayed = false;
    if ( element[1].isEqualNode(element[0][0]) ) {
        is_displayed = true;
    } else {
        is_displayed = false;
    }

    switch (condition) {
        case 'display': 

            if ( !is_displayed && cauto_wait_for_time_duration < cauto_wait_for_max_time_duration ) {
                setTimeout(function(){
                    cauto_check_element_to_display(selector, field_attr, condition, alias);
                }, 1000);
            } else if (is_displayed && cauto_wait_for_time_duration < cauto_wait_for_max_time_duration) {
                if (cauto_paused_data.length > 0) {
                    cauto_do_run_runner([
                        {
                            status: 'passed',
                            message: alias + ' ' +cauto_translable_labels['is displayed after']+ ' ' + cauto_wait_for_time_duration + ' ' + cauto_translable_labels['second(s)'],
                            pause: true
                        }
                    ], cauto_paused_data[1]);

                } else {
                    console.error(cauto_translable_labels['AutoQA Error: No payload found after the runner is paused to wait for. Please contact developer']);
                }
            } else {
                if (cauto_paused_data.length > 0) {
                    cauto_do_run_runner([
                        {
                            status: 'failed',
                            message: alias + ' ' +cauto_translable_labels['still not displayed after']+ ' ' + cauto_wait_for_max_time_duration + ' ' + cauto_translable_labels['second(s)'],
                            pause: true
                        }
                    ], cauto_paused_data[1]);
                } else {
                    console.error(cauto_translable_labels['AutoQA Error: No payload found after the runner is paused to wait for. Please contact developer']);
                }
            }

        break;
        case 'not display':

            if ( is_displayed && cauto_wait_for_time_duration < 60 ) {
                setTimeout(function(){
                    cauto_check_element_to_display(selector, field_attr, condition, alias);
                }, 1000);
            } else if (!is_displayed && cauto_wait_for_time_duration < 60) {
                if (cauto_paused_data.length > 0) {
                    cauto_do_run_runner([
                        {
                            status: 'passed',
                            message: alias + ' '+ cauto_translable_labels['is not displayed after'] +' ' + cauto_wait_for_time_duration + ' ' + cauto_translable_labels['second(s)'],
                            pause: true
                        }
                    ], cauto_paused_data[1]);

                } else {
                    console.error(cauto_translable_labels['AutoQA Error: No payload found after the runner is paused to wait for. Please contact developer']);
                }
            } else {
                if (cauto_paused_data.length > 0) {
                    cauto_do_run_runner([
                        {
                            status: 'failed',
                            message: alias + ' ' + cauto_translable_labels['still displayed after'] +' '+ cauto_wait_for_max_time_duration + ' ' + cauto_translable_labels['second(s)'],
                            pause: true
                        }
                    ], cauto_paused_data[1]);
                } else {
                    console.error(cauto_translable_labels['AutoQA Error: No payload found after the runner is paused to wait for. Please contact developer']);
                }
            }

        break;
        case 'have value':
            
            let have_value = (jQuery(element[0]).val())? true : false;

            if ( !have_value && cauto_wait_for_time_duration < 60 ) {
                setTimeout(function(){
                    cauto_check_element_to_display(selector, field_attr, condition, alias);
                }, 1000);
            } else if (have_value && cauto_wait_for_time_duration < 60) {
                if (cauto_paused_data.length > 0) {
                    cauto_do_run_runner([
                        {
                            status: 'passed',
                            message: alias + ' '+ cauto_translable_labels['have value after'] +' ' + cauto_wait_for_time_duration + ' ' + cauto_translable_labels['second(s)'],
                            pause: true
                        }
                    ], cauto_paused_data[1]);

                } else {
                    console.error(cauto_translable_labels['AutoQA Error: No payload found after the runner is paused to wait for. Please contact developer']);
                }
            } else {
                if (cauto_paused_data.length > 0) {
                    cauto_do_run_runner([
                        {
                            status: 'failed',
                            message: alias + ' ' + cauto_translable_labels['still no have value after'] + ' ' + cauto_wait_for_max_time_duration + ' ' + cauto_translable_labels['second(s)'],
                            pause: true
                        }
                    ], cauto_paused_data[1]);
                } else {
                    console.error(cauto_translable_labels['AutoQA Error: No payload found after the runner is paused to wait for. Please contact developer']);
                }
            }

        break;
        case 'enable':
            
            let is_enable = (!jQuery(element[0]).prop('disabled'))? true : false;

            if ( !is_enable && cauto_wait_for_time_duration < 60 ) {
                setTimeout(function(){
                    cauto_check_element_to_display(selector, field_attr, condition, alias);
                }, 1000);
            } else if (is_enable && cauto_wait_for_time_duration < 60) {
                if (cauto_paused_data.length > 0) {
                    cauto_do_run_runner([
                        {
                            status: 'passed',
                            message: alias + ' '+ cauto_translable_labels['is enable after'] +' ' + cauto_wait_for_time_duration + ' ' + cauto_translable_labels['second(s)'],
                            pause: true
                        }
                    ], cauto_paused_data[1]);
                } else {
                    console.error(cauto_translable_labels['AutoQA Error: No payload found after the runner is paused to wait for. Please contact developer']);
                }
            } else {
                if (cauto_paused_data.length > 0) {
                    cauto_do_run_runner([
                        {
                            status: 'failed',
                            message: alias + ' ' + cauto_translable_labels['still disabled after'] + ' '+ cauto_wait_for_max_time_duration + ' ' + cauto_translable_labels['second(s)'],
                            pause: true
                        }
                    ], cauto_paused_data[1]);
                } else {
                    console.error(cauto_translable_labels['AutoQA Error: No payload found after the runner is paused to wait for. Please contact developer']);
                }
            }

        break;
        case 'not enable':

            //cauto_verify_element_for_status(element);
            
            let is_disable = (jQuery(element[0]).prop('disabled'))? true : false;

            if ( !is_disable && cauto_wait_for_time_duration < 60 ) {
                setTimeout(function(){
                    cauto_check_element_to_display(selector, field_attr, condition, alias);
                }, 1000);
            } else if (is_disable && cauto_wait_for_time_duration < 60) {
                if (cauto_paused_data.length > 0) {
                    cauto_do_run_runner([
                        {
                            status: 'passed',
                            message: alias + ' ' +cauto_translable_labels['is disable after']+ ' ' + cauto_wait_for_time_duration + ' ' + cauto_translable_labels['second(s)'],
                            pause: true
                        }
                    ], cauto_paused_data[1]);

                } else {
                    console.error(cauto_translable_labels['AutoQA Error: No payload found after the runner is paused to wait for. Please contact developer']);
                }
            } else {
                if (cauto_paused_data.length > 0) {
                    cauto_do_run_runner([
                        {
                            status: 'failed',
                            message: alias + ' ' +cauto_translable_labels['still enabled after']+ ' '+ cauto_wait_for_max_time_duration + ' ' + cauto_translable_labels['second(s)'],
                            pause: true
                        }
                    ], cauto_paused_data[1]);
                } else {
                    console.error(cauto_translable_labels['AutoQA Error: No payload found after the runner is paused to wait for. Please contact developer']);
                }
            }

        break;
    }

}

const cauto_verify_element_for_status = (element) => {

    let abled_input_types   = ['INPUT', 'TEXTAREA', 'SELECT', 'BUTTON']; 
    //check field type
    let el_type = jQuery(element[0]).prop('tagName');

    console.log(el_type);

    if (!abled_input_types.includes(el_type)){
        if (cauto_paused_data.length > 0) {
            cauto_do_run_runner([
                {
                    status: 'failed',
                    message: cauto_translable_labels['Runner can\'t verify the element to have enable property'],
                    pause: true
                }
            ], cauto_paused_data[1]);

            return true;

        } else {
            console.error(cauto_translable_labels['AutoQA Error: No payload found after the runner is paused to wait for. Please contact developer']);
        }
    }
}