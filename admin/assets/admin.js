var cuato_active_selected_step = null;

jQuery(document).ready(function(){

    jQuery('#cauto-new-case').on('click', function(){
        jQuery('#cauto-popup-new-flow').fadeIn(200);
    });

    jQuery('body').on('click', '.cauto-cancel', function(){
        let parent = jQuery(this).closest('.cauto-popup');
        jQuery(parent).fadeOut(200);
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
            ui.helper.append('<input type="hidden">');
        }
    });

    jQuery('body').on('dblclick','.cauto_steps_builder .cauto-steps-el' ,function(){

        let type    = jQuery(this).find('div').data('step');
        if (type) {
            cuato_active_selected_step = jQuery(this);
            jQuery('#cauto-popup-content-step').html('<div class="cauto-popup-loader"><span class="cauto-icon-spinner5 cauto-popup-loader cauto-loader"></span></div>');
            //show popup
            jQuery('#cauto-popup-start-step').fadeIn(200, function(){
                cauto_build_step_settings(type);
            });
        }

    });

    //save step changes
    //save step configuration
    jQuery('body').on('click', '#cauto-save-step', function(){
        let parent = jQuery(this).closest('div#cauto-step-config-control-area');
        let fields = jQuery(parent).find('input[type=hidden]').val();
        cauto_validate_set_step_config(fields);
    });

});

//save config
var cauto_validate_set_step_config = (fields = null) => {

    if (!fields) return;

    fields = JSON.parse(fields);
    //it's gonna be a long code from here
    //let's optimize this as much as possible
    if (fields) {
        for (let x in fields) {
            switch (fields[x].field) {
                case 'input':
                case 'select':
                case 'textarea':
                case 'toggle':
                    switch (fields[x].type) {
                        case 'checkbox':
                        case 'radio':
                            if (fields[x].id) {
                                if (jQuery(fields[x].id).is(':checked')) {
                                    let checked_value = jQuery(fields[x].id).val();
                                    fields[x]['value'] = (checked_value)? checked_value : true;
                                }
                            } else if (fields[x].class) {
                                if (fields[x].type === 'checkbox') {
                                    let multi_checkbox = [];
                                    //loop with class
                                    //checkbox could have one or more value
                                    jQuery(fields[x].class).each(function(){
                                        if (jQuery(fields[x].class).is(':checked')) {
                                            let checked_value = (jQuery(fields[x].class).val())? jQuery(fields[x].class).val() : true;
                                            multi_checkbox.push(checked_value);
                                        }
                                    });
                                    fields[x]['value'] = multi_checkbox;
                                } else {
                                    if (jQuery(fields[x].class).is(':checked')) {
                                        let checked_value = jQuery(fields[x].class).val();
                                        fields[x]['value'] = (checked_value)? checked_value : true;
                                    }
                                }
                            } else {
                                //no identifier
                                console.error("CAUTO STEP FIELD ERROR: No element identifier was found for radio button");
                            }
                        break;
                        default:
                            let identifier = (fields[x].id)? '#'+fields[x].id : '.'+fields[x].id;
                            fields[x]['value'] = jQuery(identifier).val();
                        break;
                    }
                break;
                case 'editor': //let's see if we can set a field for textfield
                break;
                default: //custom field that I don't know
                break;
            }
        }


        if (cuato_active_selected_step) {
            jQuery(cuato_active_selected_step).find('input[type=hidden]').val(JSON.stringify(fields));
        } else {
            console.error("CAUTO STEP ERROR: No step indentifier is found. Please contact developer");
        }
    }

}

//I'll keep you for now
var cauto_create_unique_id = () => {
    return Math.round(new Date().getTime() + (Math.random() * 100));
}


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