var cauto_iam_leaving = false;

window.onbeforeunload = function(){
    cauto_iam_leaving = true;
};

jQuery(window).on('load',function(){
    if (cauto_is_running_flow) {
        if (JSON.stringify(cauto_running_flow_data) !== '{}') {
            cauto_do_run_runner();
            jQuery('.cuato-runner-indicator').show();
        }
    }
});

const cauto_do_run_runner = (response = [], index = 0, status = null) =>
{
    cauto_plot_runner_status(response, index, status);
    jQuery.ajax( {
        type : "post",  
        url: cauto_ajax.ajaxurl,
        data : {    
            action: 'execute_pre_run', 
            nonce: cauto_ajax.nonce,
            flow_id: cauto_running_flow_data.flow_id,
            runner_id: cauto_running_flow_data.runner_id,
            response: JSON.stringify(response),
            index: index
        },
        success: function( data ) {
            //response data
            if (data) {
                data = JSON.parse(data);
                if (data.status === 'success') {
                    try {
            
                        let callback    = data.payload.callback;
                        let response    = window[callback](data.payload.params);
                        let index       = data.payload.index;

                        if (response && !cauto_iam_leaving) {
                            index++;
                            
                            setTimeout(function(){
                                cauto_do_run_runner(response, index);
                            }, 3000);
                            
                        } else {
                            return;
                        }
                    } catch (error) {
                        console.error(error);
                    }

                } else if (data.status === 'continue') {
                    try {

                        let callback    = data.payload.callback;
                        let response    = window[callback](data.payload.params);
                        let index       = data.payload.index;

                        if (response && !cauto_iam_leaving) {
                            index++;

                            setTimeout(function(){
                                cauto_do_run_runner(response, index, true);
                            }, 3000);


                        } else {
                            return;
                        }
                    } catch (error) {
                        console.error(error);
                    }

                } else if (data.status === 'completed') {
                    cauto_render_test_results(data.payload);
                    return;
                }
            }
        }
    });
}

const cauto_plot_runner_status = (results = [], index, is_continue = false) => {

    let runner_steps  = cauto_ajax.runner_steps[cauto_running_flow_data.runner_id];
    
    if (results.length > 0) {
        let htmlclass = (results[0].status === 'passed')? 'passed' : 'failed';
        jQuery('.cauto-runner-bars div.cauto-bar:nth-child(' + index + ')').removeClass('cauto_bar_loader').addClass(htmlclass);
        let next_index = index;
        next_index++;
        jQuery('.cauto-runner-bars div.cauto-bar:nth-child(' + next_index + ')').addClass('cauto_bar_loader');

    } else if (results.length === 0 && runner_steps.length > 0) {
        let temp_x = 0;
        for (let x in runner_steps) {
            if ( typeof runner_steps[x].result != 'undefined' ) {
                let htmlclass = (runner_steps[x].result[0].status === 'passed')? 'passed' : 'failed';
                temp_x = x;
                temp_x++;
                jQuery('.cauto-runner-bars div.cauto-bar:nth-child(' + temp_x + ')').removeClass('cauto_bar_loader').addClass(htmlclass);
            } 
        } 

    } 
    if (is_continue) {
        index--;
        jQuery('.cauto-runner-bars div.cauto-bar:nth-child(' + index + ')').removeClass('cauto_bar_loader').addClass('passed');
    }
    
}

const cauto_render_test_results = (payload = []) => {
    
    jQuery('.cauto-runner-completed').show();

    if (payload.length === 0) return;

    jQuery('.cauto-completed-content div.result').html('<ul></ul>');
    for (let x in payload) {
        let step    = payload[x].step;
        let passed  = '<svg fill="#FFFFFF" xmlns="http://www.w3.org/2000/svg" width="800px" height="800px" viewBox="0 0 52 52" enable-background="new 0 0 52 52" xml:space="preserve"><path d="M26,2C12.7,2,2,12.7,2,26s10.7,24,24,24s24-10.7,24-24S39.3,2,26,2z M39.4,20L24.1,35.5 c-0.6,0.6-1.6,0.6-2.2,0L13.5,27c-0.6-0.6-0.6-1.6,0-2.2l2.2-2.2c0.6-0.6,1.6-0.6,2.2,0l4.4,4.5c0.4,0.4,1.1,0.4,1.5,0L35,15.5 c0.6-0.6,1.6-0.6,2.2,0l2.2,2.2C40.1,18.3,40.1,19.3,39.4,20z"/></svg>'; 
        let failed  = '<svg width="800px" height="800px" viewBox="0 0 512 512" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><title>error-filled</title><g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><g id="add" fill="#FFFFFF" transform="translate(42.666667, 42.666667)"><path d="M213.333333,3.55271368e-14 C331.136,3.55271368e-14 426.666667,95.5306667 426.666667,213.333333 C426.666667,331.136 331.136,426.666667 213.333333,426.666667 C95.5306667,426.666667 3.55271368e-14,331.136 3.55271368e-14,213.333333 C3.55271368e-14,95.5306667 95.5306667,3.55271368e-14 213.333333,3.55271368e-14 Z M262.250667,134.250667 L213.333333,183.168 L164.416,134.250667 L134.250667,164.416 L183.168,213.333333 L134.250667,262.250667 L164.416,292.416 L213.333333,243.498667 L262.250667,292.416 L292.416,262.250667 L243.498667,213.333333 L292.416,164.416 L262.250667,134.250667 Z" id="Combined-Shape"></path></g></g></svg>'
        let status      = '';
        let staus_class = 'no-status';
        if (typeof payload[x].result != 'undefined') { 
            if (payload[x].result[0].status === 'passed') {
                status      = passed; 
                staus_class = 'passed';
            }
            if (payload[x].result[0].status === 'failed') {
                status      = failed; 
                staus_class = 'failed';
            }
        }
        let message     = payload[x].result[0].message;
        let result_html = '<li>'
        +'<div class="status '+staus_class+'">'+ status +'</div>'
        +'<div class="meta">'
        +'<span class="step">'+ step +'</span>'
        +'<span class="message">'+ message +'</span>'
        +'</div>'
        +'</li>';

        jQuery('.cauto-completed-content div.result ul').append(result_html);

    }

}