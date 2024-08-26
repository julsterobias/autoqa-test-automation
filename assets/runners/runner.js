jQuery(document).ready(function(){

    if (cauto_is_running_flow) {
        cauto_do_run_flow();
    }
});

var cauto_do_run_flow = () => {
    //load loader
    if (JSON.stringify(cauto_running_flow_data) !== '{}') {
        cauto_do_run_runner();
        jQuery('.cuato-runner-indicator').show();
    }
}

const cauto_do_run_runner = (response = null, index = 0) =>
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

                    if (data.message === 'reload') {
                        //do action for EOP
                    } else if (data.message === 'EOP') {
                        //do action for EOP
                        cuato_completed_runner_indicator(data.results);
                    } else {
                        let callback = data.payload.callback;
                        try {
                            let response = window[callback](data.payload.params);
                            let new_index = parseInt(data.payload.index);
                            new_index++;

                            if (!data.payload.abort) {
                                cauto_update_runner_indicator(response, new_index);
                                cauto_do_run_runner(response, new_index);
                            }
                            
                        } catch (error) {
                            console.error('Runner: '+error);
                        }
                    }
                } else if (data.status === 'continue') { 
                    
                    try {
                        
                        cuato_continue_runner_indicator(data.payload.index);
                        let response = window[data.payload.callback](data.payload.params);
                        cauto_do_run_runner(response, data.payload.index);

                    } catch (error) {
                        console.error('Runner: '+error);
                    }
                    
                } else {
                    console.error(data.message);
                } 
            }
        }
    });
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


const cuato_continue_runner_indicator = (index = 0) => {
    for (let x = 1; x <= index; x++) {
        jQuery('.cauto-runner-bars .cauto-bar:nth-child(' + x + ')').removeClass('cauto_bar_loader');
        jQuery('.cauto-runner-bars .cauto-bar:nth-child(' + x + ')').addClass('passed');
    }
    index++;
    jQuery('.cauto-runner-bars .cauto-bar:nth-child(' + index + ')').removeClass('passed');
    jQuery('.cauto-runner-bars .cauto-bar:nth-child(' + index + ')').removeClass('failed');
    jQuery('.cauto-runner-bars .cauto-bar:nth-child(' + index + ')').addClass('cauto_bar_loader');
}


const cuato_completed_runner_indicator = (results) => {
    if (results.length === 0) return;

    let i = 1;
    for(let x in results) {
        if (results[x][0].status) {
            jQuery('.cauto-runner-bars .cauto-bar:nth-child(' + i + ')').removeClass('cauto_bar_loader').removeClass('failed').addClass('passed');
        } else {
            jQuery('.cauto-runner-bars .cauto-bar:nth-child(' + i + ')').removeClass('cauto_bar_loader').removeClass('passed').addClass('failed');
        }
        i++;
    }
    
}