let cauto_abort_runner = false;

jQuery(window).on('load',function(){
    setTimeout(cauto_do_run_runner, 2000);
});

jQuery(window).on('beforeunload', function(event) {
    cauto_abort_runner = true;
});

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
    
}