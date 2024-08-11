var cuato_active_selected_step = null;
var cauto_step_popup_step = '#cauto-popup-start-step';

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

    jQuery('.cauto-steps-draggable').draggable({
        connectToSortable: ".cauto_steps_builder",
        helper: 'clone',
        revert: "invalid",
        stop: function(event, ui){
            ui.helper.addClass('cauto-added-step');
            ui.helper.append('<input type="hidden">');
            let title_div = ui.helper.find('div');
            jQuery(title_div).append('<span class="cauto_describe_step_label"></span>');
        }
    });

    jQuery('body').on('dblclick','.cauto_steps_builder .cauto-steps-draggable, .cauto_steps_builder .cauto-steps-el-saved' ,function(){

        let type    = jQuery(this).find('div').data('step');
        if (type) {
            cuato_active_selected_step = jQuery(this);
            jQuery('#cauto-popup-content-step').html('<div class="cauto-popup-loader"><span class="cauto-icon-spinner5 cauto-popup-loader cauto-loader"></span></div>');
            //show popup
            jQuery(cauto_step_popup_step).fadeIn(200, function(){
                cauto_build_step_settings(type);
            });
        }

    });

    //save step changes
    //save step configuration
    jQuery('body').on('click', '#cauto-save-step', function(){

        let parent = jQuery(this).closest('div#cauto-step-config-control-area');
        let fields = jQuery(parent).find('input[type=hidden]#cauto_step_config_field_ids').val();
        cauto_validate_set_step_config(fields);
        jQuery(this).prop('disabled', true);
        jQuery(this).find('span.dashicons').attr('class', 'cauto-icon-spinner5 cauto-icon cauto-loader');
        jQuery('.cauto-cancel').prop('disabled', true);
        jQuery('#cauto-delete-step').hide();
        //now save the data
        cauto_do_save_step();
        
    });

    jQuery('body').on('click', '#cauto-delete-step', function(){
        jQuery(this).hide();
        let confirm_wrapper = jQuery('#cauto-delete-step-confirm').closest('div.cauto_button_wrapper');
        jQuery(confirm_wrapper).removeClass('hidden');
    });

    jQuery('body').on('click', '#cauto-delete-step-confirm', function(){
        jQuery(cuato_active_selected_step).remove();
        cuato_active_selected_step = null;
        //save changes here
        jQuery(this).prop('disabled', true);
        jQuery('.cauto-cancel').prop('disabled', true);
        jQuery('#cauto-save-step').prop('disabled', true);
        cauto_do_save_step();
    });
    
    jQuery('#cauto-save-flow').on('click', function(){
        jQuery('#cauto-run-flow, #cauto-delete-flow').prop('disabled', true);
        jQuery(this).find('span.dashicons').attr('class', 'cauto-icon-spinner5 cauto-icon cauto-loader');
        cauto_do_save_step('flow_save');
    });

});

// save step on close
var cauto_do_save_step = (source = null) => {

    let flow_id = jQuery('#cauto-flow-id').val();

    let step_data = [];
    jQuery('.cauto_steps_builder').find('li.cauto-steps-el').each(function(){
        let type        = jQuery(this).find('div').data('step');
        let field_data  = jQuery(this).find('input[type=hidden]').val(); 
        step_data.push({
            step: type,
            record: field_data
        });
    });

    jQuery.ajax( {
        type : "post",  
        url: cauto_ajax.ajaxurl,
        data : {    
            action: 'cauto_do_save_flow', 
            nonce: cauto_ajax.nonce,
            step: JSON.stringify(step_data),
            flow_id: flow_id
        },
        success: function( data ) {
            //response data
            if (data) {
                data = JSON.parse(data);
                if (data.status === 'success') {
                    //do this after saving
                    if (source === 'flow_save') {
                        jQuery('#cauto-run-flow, #cauto-delete-flow').prop('disabled', false);
                        jQuery('#cauto-save-flow').find('span.cauto-icon').attr('class', 'dashicons dashicons-saved');
                    } else {
                        cauto_describe_step_action(jQuery('#cauto_step_config_describe').val());
                        jQuery(cauto_step_popup_step).fadeOut(200);
                    }
                    
                } else {
                    console.error('CAUTO ERROR: unable to save step, please contact support');
                }
            }
        }
    });

}

//set step describe
var cauto_describe_step_action = (describe = null) => {
    
    if (!describe) return;

    describe = JSON.parse(describe);

    let text = describe.describe_text;

    if (typeof describe.describe_text === "undefined" || !describe.describe_text || describe.describe_text === '') {
        return;
    }

    if (Array.isArray(describe.selector)) {
        for (let x in describe.selector) {
            text = text.replace('{'+describe.selector[x]+'}', jQuery(describe.selector[x]).val());    
        }
    } else {
        text = text.replace('{'+describe.selector+'}', jQuery(describe.selector).val());
    }

    jQuery(cuato_active_selected_step).addClass('cauto_step_set_wide');
    jQuery(cuato_active_selected_step).find('span.cauto_describe_step_label').text(text);
}

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
                case 'group':
                case 'toggle':
                    switch (fields[x].type) {
                        case 'checkbox':
                        case 'radio':
                            if (fields[x].id) {
                                if (jQuery('#'+fields[x].id).is(':checked')) {
                                    let checked_value = jQuery('#'+fields[x].id).val();
                                    fields[x]['value'] = (checked_value)? checked_value : true;
                                }
                            } else if (fields[x].class) {
                                let multi_checkbox = [];
                                jQuery('.'+fields[x].class).each(function(){
                                    if (jQuery(this).is(':checked')) {
                                        let checked_value = (jQuery(this).val())? jQuery(this).val() : true;
                                        multi_checkbox.push(checked_value);
                                    }
                                });
                                fields[x]['value'] = multi_checkbox;
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
    let flow_id         = jQuery('#cauto-flow-id').val();
    let get_saved_data  = jQuery(cuato_active_selected_step).find('input[type=hidden]').val();
    jQuery.ajax( {
        type : "post",  
        url: cauto_ajax.ajaxurl,
        data : {    
            action: 'cauto_steps_ui', 
            nonce: cauto_ajax.nonce,
            type: type,
            flow_id: flow_id,
            saved_data: get_saved_data
        },
        success: function( data ) {
            //response data
            if (data) {
                data = JSON.parse(data);
                if (data.status === 'success') {
                    jQuery(cauto_step_popup_step+' .cauto-popup-content').html(data.html);
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