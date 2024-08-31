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
                    alert('Test completed!');
                    return;
                }
            }
        }
    });
}

const cauto_plot_runner_status = (results = [], index, status = false) => {

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

    if (status) {
        console.log(runner_steps, results, index);
    }
    
}