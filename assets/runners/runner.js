jQuery(document).ready(function(){
    if (cauto_is_running_flow) {
        cauto_do_run_flow();
    }
});

var cauto_do_run_flow = () => {
    //load loader
    jQuery('.cuato-runner-indicator').show();
}


/*jQuery('document').ready(function(){
    jQuery('body').on('click','#cauto-stop-runner', function(){
        cauto_stop_runner(this);
    });
});

jQuery(window).on('load',function(){
    //cauto_prepare_runner();
});

jQuery(window).on('beforeunload', function(event) {
    //cauto_abort_runner = true;
});

const cauto_stop_runner = (obj) => {

    jQuery(obj).text('Stopping...');
    jQuery(obj).prop('disabled', true);

    jQuery.ajax( {
        type : "post",  
        url: cauto_ajax.ajaxurl,
        data : {    
            action: 'cauto_do_stop_runner', 
            nonce: cauto_ajax.nonce
        },
        success: function( data ) {
            //response data
            if (data) {
                data = JSON.parse(data);
                if (data.status === 'success') {
                    //fill the bars if the runner started before
                    location.reload();
                } else {
                    console.error(data.message);
                } 
            }
        }
    });
}

const cauto_prepare_runner = () => {
    jQuery.ajax( {
        type : "post",  
        url: cauto_ajax.ajaxurl,
        data : {    
            action: 'cauto_prepare_exe_runner', 
            nonce: cauto_ajax.nonce,
            runner_id: cauto_ajax.cauto_runner_id,
            flow_id: cauto_ajax.cauto_flow_id
        },
        success: function( data ) {
            //response data
            if (data) {
                data = JSON.parse(data);
                if (data.status === 'success') {
                    //fill the bars if the runner started before
                    if (data.action === 'new') {
                        cauto_do_run_runner();
                    } else if (data.action === 'continue'){
                        //get completed steps and fill the UI
                        let con_index = parseInt(data.index);
                        if (con_index >= 1) {
                            cauto_do_run_runner(null, con_index);
                        }
                    } else {
                        console.error('Runner: Encountered unknown error during initial runtime, contact developer');
                    }
                    
                } else {
                    console.error(data.message);
                } 
            }
        }
    });
}

const cauto_do_run_runner = (response = null, index = 0) =>
{

    jQuery.ajax( {
        type : "post",  
        url: cauto_ajax.ajaxurl,
        data : {    
            action: 'cauto_execute_runner', 
            nonce: cauto_ajax.nonce,
            flow_id: cauto_ajax.cauto_flow_id,
            runner_id: cauto_ajax.cauto_runner_id,
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
                    } else {
                        let callback = data.payload.callback;
                        try {
                            let response = window[callback](data.payload.params);
                            let new_index = parseInt(data.payload.index);
                            new_index++;

                            if (!cauto_abort_runner) {
                                cauto_update_runner_indicator(response, new_index);
                                cauto_do_run_runner(response, new_index);
                            }
                            
                        } catch (error) {
                            console.error('Runner: '+error);
                        }
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
    
}*/