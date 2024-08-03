jQuery(document).ready(function(){

    jQuery('#cauto-new-case').on('click', function(){
        jQuery('#cauto-popup-new-flow').fadeIn(200);
    });

    jQuery('.cauto-cancel').on('click', function(){
        jQuery('#cauto-popup-new-flow').fadeOut(200);
    });

    jQuery('#cauto-save-new-flow').on('click', function(){
        cauto_do_save_flow(this);
    });

    //initialize sortable builder

    jQuery('.cauto_steps_builder').sortable({
        revert: true,
        forceHelperSize: true,
    });

    jQuery('.cauto-steps-el').draggable({
        connectToSortable: ".cauto_steps_builder",
        helper: 'clone',
        revert: "invalid",
        stop: function(event, ui){
            ui.helper.addClass('cauto-added-step');
        }
    });

    jQuery('body').on('dblclick','.cauto_steps_builder .cauto-steps-el' ,function(){

        let type = jQuery(this).find('div').data('step');
        if (type) {
            //show popup
            jQuery('#cauto-popup-start-step').fadeIn(200, function(){
                cauto_build_step_settings(type);
            });
        }

    });

});


var cauto_build_step_settings = (type) => {
    //generate UI via ajax
    jQuery.ajax( {
        type : "post",  
        url: cauto_ajax.ajaxurl,
        data : {    
            action: 'cauto_steps_ui', 
            nonce: cauto_ajax.nonce,
            type: type
        },
        success: function( data ) {
            //response data
            if (data) {
                data = JSON.parse(data);
                if (data.status === 'success') {
                    jQuery('#cauto-popup-start-step .cauto-popup-content').html(data.html);
                } else {
                    alert(data.message);
                }
            }
        }
    });
}


var cauto_do_save_flow = (btn) => {

    let flowname = jQuery('#cauto-new-flow-name').val();
    if (!flowname) {
        return;
    }
    
    let stop_on_error = jQuery('#cauto-flow-stop-on-error').is(':checked');

    jQuery(btn).find('span.cauto_button_text').text('Saving...');
    jQuery(btn).find('span.dashicons').removeClass('dashicons-saved').addClass('cauto-icon-spinner5 cauto-loader');
    jQuery('.cauto-cancel').prop('disabled', true);
    jQuery(btn).prop('disabled', true);

    jQuery.ajax( {
        type : "post",  
        url: cauto_ajax.ajaxurl,
        data : {    
            action: 'cauto_new_flow', 
            nonce: cauto_ajax.nonce,
            name: flowname,
            stop_on_error: stop_on_error
        },
        success: function( data ) {
            //response data
            if (data) {
                data = JSON.parse(data);
                if (data.status === 'success') {
                    window.location = data.redirect_to;
                } else {
                    alert(data.message);
                }
            }
        }
    });
}