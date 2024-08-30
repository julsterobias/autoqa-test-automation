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

const cauto_do_run_runner = (response = [], index = 0) =>
{
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

                        console.log('1:'+data.payload.index);
            
                        let callback    = data.payload.callback;
                        let response    = window[callback](data.payload.params);
                        let index       = data.payload.index;

                        

                        if (response && !cauto_iam_leaving) {
                            index++;
                            cauto_do_run_runner(response, index);
                        } else {
                            return;
                        }
                    } catch (error) {
                        console.error(error);
                    }
                } else if (data.status === 'continue') {
                    try {

                        console.log('2:'+data.payload.index);

                        let callback    = data.payload.callback;
                        let response    = window[callback](data.payload.params);
                        let index       = data.payload.index;

                      

                        if (response && !cauto_iam_leaving) {
                            index++;
                            cauto_do_run_runner(response, index);
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


/*const cauto_do_run_runner = (response = null, index = 0) =>
{
    jQuery.ajax( {
        type : "post",  
        url: cauto_ajax.ajaxurl,
        data : {    
            action: 'cauto_execute_runner', 
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

                    //let callback = data.payload.callback;
                    //let response = window[callback](data.payload.params);
                    
                } else if (data.status === 'continue') { 
                    
                    
                    
                } else {
                    console.error(data.message);
                } 
            }
        }
    });
}*/


const cauto_plot_runner_status_indicator = (result = null, type) => {
    console.log(result);
    if (result) {
        let el_index = 0;
        for (let x in result) {
            el_index++;
  
            if (result[x][0].status) {
                jQuery('.cauto-runner-bars .cauto-bar:nth-child(' + el_index + ')').removeClass('cauto_bar_loader');
                jQuery('.cauto-runner-bars .cauto-bar:nth-child(' + el_index + ')').addClass('passed');
            } else {
                jQuery('.cauto-runner-bars .cauto-bar:nth-child(' + el_index + ')').removeClass('cauto_bar_loader');
                jQuery('.cauto-runner-bars .cauto-bar:nth-child(' + el_index + ')').addClass('failed');
                console.error(result[x][0].message);
            }
        }
    }
} 

const cauto_update_runner_indicator = (result = null, index) => {
    
    if (!result) return;

    try {
        for (let x in result) {
            if (result[x].status) {
                jQuery('.cauto-runner-bars .cauto-bar:nth-child(' + index + ')').removeClass('cauto_bar_loader');
                jQuery('.cauto-runner-bars .cauto-bar:nth-child(' + index + ')').addClass('passed');
                index++;
                jQuery('.cauto-runner-bars .cauto-bar:nth-child(' + index + ')').removeClass('passed');
                jQuery('.cauto-runner-bars .cauto-bar:nth-child(' + index + ')').removeClass('failed');
                jQuery('.cauto-runner-bars .cauto-bar:nth-child(' + index + ')').addClass('cauto_bar_loader');
            } else {
                jQuery('.cauto-runner-bars .cauto-bar:nth-child(' + index + ')').removeClass('cauto_bar_loader');
                jQuery('.cauto-runner-bars .cauto-bar:nth-child(' + index + ')').addClass('failed');
                index++;
                jQuery('.cauto-runner-bars .cauto-bar:nth-child(' + index + ')').removeClass('passed');
                jQuery('.cauto-runner-bars .cauto-bar:nth-child(' + index + ')').removeClass('failed');
                jQuery('.cauto-runner-bars .cauto-bar:nth-child(' + index + ')').addClass('cauto_bar_loader');
            }
        }
    } catch (error) {
        console.error('Runner: '+error);
    }
    
}