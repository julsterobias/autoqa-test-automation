var cuato_active_selected_step = null;
var cauto_step_popup_step = '#cauto-popup-start-step';

jQuery(document).ready(function(){

    jQuery('#cauto-new-case').on('click', function(){
        jQuery('#cauto-popup-new-flow').fadeIn(200);
    });

    jQuery('body').on('click', '.cauto-cancel', function(){
        let parent = jQuery(this).closest('.cauto-popup');
        jQuery('#cauto-save-new-flow').removeAttr('data-edit');
        jQuery('#cauto-new-flow-name').val('');
        jQuery('#cauto-flow-stop-on-error').prop('checked', false);
        jQuery(parent).fadeOut(200);
    });

    jQuery('#cauto-save-new-flow').on('click', function(){
        let redirect_to = jQuery(this).data('redirect-to');
        cauto_do_save_flow(this, redirect_to);
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
        scroll: false,
        stop: function(event, ui){
            ui.helper.addClass('cauto-added-step');
            ui.helper.append('<input type="hidden">');
            let title_div = ui.helper.find('div');
            jQuery(title_div).append('<span class="cauto_describe_step_label"></span>');
        }
        
    });

    jQuery('body').on('click','.cauto_steps_builder .cauto-steps-draggable, .cauto_steps_builder .cauto-steps-el-saved' ,function(){
        jQuery('.cauto_steps_builder').find('li').removeClass('active');
        jQuery(this).toggleClass('active');
    });

    jQuery('body').on('dblclick','.cauto_steps_builder .cauto-steps-draggable, .cauto_steps_builder .cauto-steps-el-saved' ,function(){

        let type    = jQuery(this).find('div').data('step');
        jQuery(this).removeClass('active');
        if (type) {

            let no_settings = jQuery(this).find('div').data('no-settings');
            if (no_settings) {
                return;
            }

            cuato_active_selected_step = jQuery(this);
            jQuery('#cauto-popup-content-step').html('<div class="cauto-popup-loader"><span class="dashicons dashicons-update cauto-popup-loader cauto-loader"></span></div>');
                //show popup
            jQuery(cauto_step_popup_step).fadeIn(200, function(){
                cauto_build_step_settings(type);
            });
        }

    });

    jQuery('html').keyup(function(e){
        if(e.keyCode == 46 || e.keyCode == 8) {
            jQuery('.cauto_steps_builder').find('li.active').remove();
        }
    });

    //save step changes
    //save step configuration
    jQuery('body').on('click', '#cauto-save-step', function(){
        let parent = jQuery(this).closest('div#cauto-step-config-control-area');
        let fields = jQuery(parent).find('input[type=hidden]#cauto_step_config_field_ids').val();
        cauto_validate_set_step_config(fields);
        jQuery(this).prop('disabled', true);
        jQuery(this).find('span.dashicons').attr('class', 'dashicons dashicons-update cauto-icon cauto-loader');
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
        //save changes here
        jQuery(this).prop('disabled', true);
        jQuery('.cauto-cancel').prop('disabled', true);
        jQuery('#cauto-save-step').prop('disabled', true);
        cauto_do_save_step('delete_flow');
    });
    
    jQuery('#cauto-save-flow').on('click', function(){
        jQuery('#cauto-run-flow, #cauto-delete-flow').prop('disabled', true);
        jQuery(this).find('span.dashicons').attr('class', 'dashicons dashicons-update cauto-icon cauto-loader');
        cauto_do_save_step('flow_save');
    });

    //run the flow
    jQuery('#cauto-run-flow, .cauto-flow-run-flow').on('click', function(){
        let flow_id = jQuery(this).data('id');
        jQuery(this).find('span.dashicons').attr('class', 'dashicons dashicons-update cauto-icon cauto-loader');
        cauto_run_flow(flow_id);
    });

    jQuery('#cauto-edit-flow').on('click', function(){
        jQuery('#cauto-popup-new-flow').fadeIn(200);
        jQuery('#cauto-save-new-flow').attr('data-edit', cauto_ajax.flow_id);
        //get flow details to fill the fields
        cauto_get_flow_details();
    });

    jQuery('.cauto-flow-edit-flow').on('click', function(){
        let flow_id = jQuery(this).data('flow-id');
        jQuery('#cauto-popup-new-flow').fadeIn(200);
        jQuery('#cauto-save-new-flow').attr('data-edit', flow_id);
        jQuery('#cauto-save-new-flow').attr('data-redirect-to', 'main');
        cauto_get_flow_details(flow_id);
    });

    jQuery('body').on('click', '#cauto-results-list li',function(){
        jQuery('#cauto-results-list li').removeClass('active');
        jQuery(this).addClass('active');
        let runner_id = jQuery(this).data('runner-id');
        let flow_id   = jQuery('#cauto-results-list').data('flow-id');
        cauto_load_runner_results(runner_id, flow_id);
    });

    cuato_load_more_link();

    jQuery('.cauto-see-other-runners span').on('click', function(){
        cauto_load_more_runners();
    });

    //preload first runner
    let cauto_first_runner_res = jQuery('#cauto-results-list li:nth-child(1)');
    if (cauto_first_runner_res.length > 0) {
        let runner_id_ = jQuery(cauto_first_runner_res).data('runner-id');
        let flow_id_   = jQuery('#cauto-results-list').data('flow-id');
        if (runner_id_ && flow_id_) {
            jQuery(cauto_first_runner_res).addClass('active');
            cauto_load_runner_results(runner_id_, flow_id_);
        }
        
    }


    let cauto_current_field_variable_call = null;
    let cauto_my_cursor_last_pos = null;
    jQuery('body').on('keyup', '.cauto-variable-step', function(event){
        if (event.shiftKey && event.keyCode === 52) {
            cauto_my_cursor_last_pos = event.target.selectionStart;
            jQuery('#cauto-popup-runner-variables').show();
            cauto_get_available_variables();
            cauto_current_field_variable_call = jQuery(this);
        }
    });

    jQuery('#cauto-variable-field-select').on('keyup', function(e){
        if(e.which === 13) {
            jQuery('#cauto-popup-runner-variables').hide();
            if (cauto_current_field_variable_call) {
                let cauto_variable_selected = jQuery('#cauto-variable-field-select').val();
                let cauto_step_value_field  = jQuery(cauto_current_field_variable_call).val();
                
                //insert the generated code to previous cursor location
                if (cauto_my_cursor_last_pos) {
                    let con_part_left   = cauto_step_value_field.substr(0, cauto_my_cursor_last_pos);
                    let con_part_right  = cauto_step_value_field.substr(cauto_my_cursor_last_pos, cauto_step_value_field.length);
                    con_part_left = con_part_left + cauto_variable_selected;
                    jQuery(cauto_current_field_variable_call).val(con_part_left + con_part_right);
                }
                jQuery('#cauto-variable-field-select').val('');
            }
        } else if (e.key === 'Escape') {
            jQuery('#cauto-popup-runner-variables').hide();
            jQuery('#cauto-variable-field-select').val('');
        } 
    });

    jQuery('body').on('keydown', '.cauto-no-space-text-validation', function(event){
        if (event.keyCode === 32) {
            event.preventDefault();
        }
    });

    jQuery('.cauto-flow-delete-flow').on('click', function(){
        jQuery('#cauto-popup-delete-flow-confirmation').fadeIn(200);
        let flow_id = jQuery(this).data('flow-id');
        if (flow_id) {
            jQuery('#cauto-delete-flow-confirm').attr('data-flow-id', flow_id);
        }
    });

    jQuery('#cauto-clear-result').on('click', function(){
        jQuery('#cauto-popup-delete-results-confirmation').fadeIn(200);
        let flow_id = jQuery(this).data('flow-id');
        if (flow_id) {
            jQuery('#cauto-delete-results-confirm').attr('data-flow-id', flow_id);
        }
    });

    jQuery('#cauto-delete-flow-confirm').on('click', function(){
        let flow_id = jQuery(this).data('flow-id');
        jQuery(this).prop('disabled', true);
        jQuery('.cauto-cancel').prop('disabled', true);
        cauto_do_delete_flow(flow_id);
    });

    jQuery('#cauto-delete-results-confirm').on('click', function(){
        let flow_id = jQuery(this).data('flow-id');
        jQuery(this).prop('disabled', true);
        cauto_do_delete_flow_results(flow_id);
    });

    jQuery('#cauto-settings').on('click', function(){
        jQuery('#cauto-popup-settings').fadeIn(200);
    });

    jQuery('#cauto-save-settings').on('click', function(){
        jQuery(this).prop('disabled', true);
        jQuery('.cauto-cancel').prop('disabled', true);
        cauto_do_save_settings();
    });

    jQuery('body').on('click', '.cauto-send-keys-steps', function(){
        let key         = jQuery(this).data('key');
        let position    = jQuery('#cauto_step_send_keys')[0].selectionStart;
        let get_existing_value = jQuery('#cauto_step_send_keys').val();
        let keycode = '['+key+']';
        let con_part_left   = get_existing_value.substr(0, position);
        let con_part_right  = get_existing_value.substr(position, get_existing_value.length);
        con_part_left = con_part_left + keycode;
        jQuery('#cauto_step_send_keys').val(con_part_left + con_part_right);
        jQuery('#cauto_step_send_keys').focus();
    });

    jQuery('body').on('click', '.cauto-clear-select2', function(){
        let get_parent = jQuery(this).closest('.cauto-select-wrapper');
        let get_select = jQuery(get_parent).find('.cauto-select2-field, .cauto-select2-field-static');
        let get_first_opt = jQuery(get_select).find('option:first-child').val();
        jQuery(get_select).val(get_first_opt).trigger('change'); //clear other
    });


    jQuery('#cauto-popup-content-step').on('input', '.cauto-range-value-change', function(){
       let get_value    = jQuery(this).val(); 
       let parent       = jQuery(this).closest('div.cauto-input-wrapper');
       jQuery(parent).find('span.cauto-input-range-value span').text(get_value);
    });

});

// save step on close
const cauto_do_save_step = (source = null) => {

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
                if (data.status === 'success') {
                    //do this after saving
                    if (source === 'flow_save') {
                        jQuery('#cauto-run-flow, #cauto-delete-flow').prop('disabled', false);
                        jQuery('#cauto-save-flow').find('span.cauto-icon').attr('class', 'dashicons dashicons-saved');
                    } else if (source === 'delete_flow') {
                        jQuery(cuato_active_selected_step).remove();
                        cuato_active_selected_step = null;
                    } else {
                        cauto_describe_step_action(jQuery('#cauto_step_config_describe').val());
                    }
                    jQuery(cauto_step_popup_step).fadeOut(200);
                    jQuery('.cauto-cancel').prop('disabled', false);
                    
                } else {
                    console.error(cauto_translable_labels.autoqa_error1); // c_error1
                }
            }
        }
    });

}

//set step describe
const cauto_describe_step_action = (describe = null) => {
    
    if (!describe) return;

    describe = JSON.parse(describe);

    if (typeof describe.describe_text === "undefined" || !describe.describe_text || describe.describe_text === '') {
        return;
    }

    let text = describe.describe_text;

    if (Array.isArray(describe.selector)) {
        for (let x in describe.selector) {
            text = text.replace('{'+describe.selector[x]+'}', jQuery(describe.selector[x]).val());    
        }
    } else {
        text = text.replace('{'+describe.selector+'}', jQuery(describe.selector).val());
    }

    jQuery(cuato_active_selected_step).addClass('cauto_step_set_wide');
    jQuery(cuato_active_selected_step).find('span.cauto_describe_step_label').html('<b>'+text+'</b>');
}

//save config
const cauto_validate_set_step_config = (fields = null) => {

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
                                console.error(cauto_translable_labels.autoqa_error2); //autoqa_error2
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
            console.error(cauto_translable_labels.autoqa_error3); //autoqa_error3
        }
    }

}

//I'll keep you for now
const cauto_create_unique_id = () => {
    return Math.round(new Date().getTime() + (Math.random() * 100));
}


const cauto_build_step_settings = (type) => {
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
                if (data.status === 'success') {
                    jQuery(cauto_step_popup_step+' .cauto-popup-content').html(data.html);
                } else {
                    alert(data.message);
                }
            }
            //trigger other UI behavior
            cauto_do_user_interact();
            cauto_init_select2_selects();
        }
    });
}


const cauto_do_save_flow = (btn, redirect_to = '') => {

    let flowname = jQuery('#cauto-new-flow-name').val();
    if (!flowname) {
        return;
    }
    
    let stop_on_error = jQuery('#cauto-flow-stop-on-error').is(':checked');

    jQuery(btn).find('span.cauto_button_text').text('Saving...');
    jQuery(btn).find('span.dashicons').removeClass('dashicons-saved').addClass('dashicons-update cauto-loader');
    jQuery('.cauto-cancel').prop('disabled', true);
    jQuery(btn).prop('disabled', true);

    let to_edit = jQuery(btn).data('edit');

    jQuery.ajax( {
        type : "post",  
        url: cauto_ajax.ajaxurl,
        data : {    
            action: 'cauto_new_flow', 
            nonce: cauto_ajax.nonce,
            name: flowname,
            stop_on_error: stop_on_error,
            is_edit: to_edit,
            redirect_to: redirect_to
        },
        success: function( data ) {
            //response data
            if (data) {
                if (data.status === 'success') {
                    window.location = data.redirect_to;
                } else {
                    console.error(data.message);
                }
            }
        }
    });
}


const cauto_run_flow = (flow_id) => {

    if (!flow_id) return;

    jQuery.ajax( {
        type : "post",  
        url: cauto_ajax.ajaxurl,
        data : {    
            action: 'cauto_setup_run_flow', 
            nonce: cauto_ajax.nonce,
            flow_id: flow_id
        },
        success: function( data ) {
            //response data
            if (data) {
                if (data.status === 'success') {
                    let url = data.url;
                    if (url !== '') {
                        window.open(url, "_blank");
                    } else {
                        console.error(cauto_translable_labels.autoqa_error4); //autoqa_error4
                    }

                    jQuery('#cauto-run-flow').find('span.cauto-icon').attr('class', 'dashicons dashicons-controls-play');

                } else {
                    console.error('CAUTO ERROR: '+ data.message);
                }
            }
            jQuery('#cauto-run-flow span.cauto-loader, .cauto-flow-run-flow span.cauto-loader').attr('class', 'dashicons dashicons-controls-play');
            
        }
    });

}

const cauto_get_flow_details = (flow_id = 0) => {

    flow_id = (flow_id > 0)? flow_id : cauto_ajax.flow_id;

    if (!flow_id) return;

    jQuery('#cauto-new-flow-name, #cauto-flow-stop-on-error, #cauto-save-new-flow, .cauto-cancel').prop('disabled', true);
    
    jQuery.ajax( {
        type : "post",  
        url: cauto_ajax.ajaxurl,
        data : {    
            action: 'cauto_get_flow_details_to_edit', 
            nonce: cauto_ajax.nonce,
            flow_id: flow_id
        },
        success: function( data ) {
            //response data
            if (data) {
                if (data.status === 'success') {
                    jQuery('#cauto-new-flow-name').val(data.data.title);
                   if (data.data.stop_on_error) {
                        jQuery('#cauto-flow-stop-on-error').prop('checked', true);
                   } else {
                        jQuery('#cauto-flow-stop-on-error').prop('checked', false);
                   }
                } else {
                    console.error('CAUTO ERROR: '+ data.message);
                }
            }
            jQuery('#cauto-new-flow-name, #cauto-flow-stop-on-error, #cauto-save-new-flow, .cauto-cancel').prop('disabled', false);
        }
    });

}


const cauto_load_runner_results = (runner_id = 0, flow_id = 0) => {
    
    if (runner_id === 0 || flow_id === 0) return;

    if (jQuery('#cauto-result-steps').hasClass('loading')) return;

    jQuery('#cauto-result-steps').addClass('loading');

    jQuery.ajax( {
        type : "post",  
        url: cauto_ajax.ajaxurl,
        data : {    
            action: 'cauto_load_runner_results', 
            nonce: cauto_ajax.nonce,
            runner_id: runner_id,
            flow_id: flow_id
        },
        success: function( data ) {
            //response data
            if (data) {
                if (data.status === 'success') {
                    jQuery('#cauto-result-steps').html(data.content);
                } else {
                    console.error('CAUTO ERROR: '+ data.message);
                }

                jQuery('#cauto-result-steps').removeClass('loading');
            }
            
        }
    });

}

const cuato_load_more_link = () => {
    let total_runners       = jQuery('#cauto-results-list').data('rucnt');
    let current_currents    = jQuery('#cauto-results-list li').length;
    if (total_runners === current_currents) {
        jQuery('.cauto-see-other-runners').remove();
    }
}

const cauto_load_more_runners = () => {

    if (jQuery('.cauto-see-other-runners span').hasClass('loading')) {
        return;
    }

    jQuery('.cauto-see-other-runners span').addClass('loading');
    
    let runner_count    = jQuery('#cauto-results-list li').length;
    let flow_id         = jQuery('#cauto-results-list').data('flow-id');

    jQuery.ajax( {
        type : "post",  
        url: cauto_ajax.ajaxurl,
        data : {    
            action: 'cauto_load_more_runner_results', 
            nonce: cauto_ajax.nonce,
            flow_id: flow_id,
            offset: runner_count
        },
        success: function( data ) {
            //response data
            if (data) {
                if (data.status === 'success') {
                    if (typeof data.content !== 'undefined') {
                        jQuery('#cauto-results-list').append(data.content);
                    }
                } else {
                    console.error('CAUTO ERROR: '+ data.message);
                }
            }
            let total_runners   = jQuery('#cauto-results-list').data('rucnt');
            let runner_count    = jQuery('#cauto-results-list li').length;
            if (runner_count >= total_runners ) {
                jQuery('.cauto-see-other-runners').remove();
            }
            jQuery('.cauto-see-other-runners span').removeClass('loading');
            
        }
    });

}

const cauto_get_available_variables = () => {

    jQuery('#cauto-variable-field-select').prop('disabled', true);
    jQuery('#cauto-variable-field-select').attr('placeholder', 'Loading variables...');
    jQuery.ajax( {
        type : "post",  
        url: cauto_ajax.ajaxurl,
        data : {    
            action: 'cauto_load_runner_variables', 
            nonce: cauto_ajax.nonce,
            flow_id: cauto_ajax.flow_id
        },
        success: function( data ) {
            //response data
            if (data) {
                if (data.status === 'success') {
                    if (typeof data.variables !== 'undefined') {
                        jQuery('#cauto-variable-field-select').autocomplete({source: data.variables});
                    }
                } else {
                    console.error('CAUTO ERROR: '+ data.message);
                }
            }
            jQuery('#cauto-variable-field-select').prop('disabled', false);
            jQuery('#cauto-variable-field-select').focus();
            jQuery('#cauto-variable-field-select').attr('placeholder', 'Search value name');
            
            
        }
    });
}

const cauto_do_delete_flow = ( flow_id = null ) => {

    if (!flow_id) return;

    jQuery.ajax( {
        type : "post",  
        url: cauto_ajax.ajaxurl,
        data : {    
            action: 'cauto_delete_flow', 
            nonce: cauto_ajax.nonce,
            flow_id: flow_id
        },
        success: function( data ) {
            //response data
            if (data) {
                if (data.status === 'success') {
                    window.location = data.redirect;
                } else {
                    console.error('CAUTO ERROR: '+ data.message);
                }
                jQuery('#cauto-delete-flow-confirm').prop('disabled', false);
                jQuery('.cauto-cancel').prop('disabled', false);
            }           
            
        }
    });

}

const cauto_do_delete_flow_results = ( flow_id = null ) => {

    if (!flow_id) return;

    jQuery.ajax( {
        type : "post",  
        url: cauto_ajax.ajaxurl,
        data : {    
            action: 'cauto_delete_flow_results', 
            nonce: cauto_ajax.nonce,
            flow_id: flow_id
        },
        success: function( data ) {
            //response data
            if (data) {
                if (data.status === 'success') {
                    location.reload();
                } else {
                    console.error('CAUTO ERROR: '+ data.message);
                }
                jQuery('#cauto-delete-flow-confirm').prop('disabled', false);
                jQuery('.cauto-cancel').prop('disabled', false);
            }           
            
        }
    });

}


const cauto_do_save_settings = () => {

    let duration = jQuery('#cauto-settings-runner-duration').val();
    jQuery.ajax( {
        type : "post",  
        url: cauto_ajax.ajaxurl,
        data : {    
            action: 'cauto_save_settings', 
            nonce: cauto_ajax.nonce,
            duration: duration
        },
        success: function( data ) {
            //response data
            if (data) {
                if (data.status === 'success') {
                    location.reload();
                } else {
                    console.error('CAUTO ERROR: '+ data.message);
                }
            }           
        }
    });
}

const cauto_do_user_interact = () => {

    jQuery('.cauto-fields').find('.cauto-step-nodes').each(function(){
        let interact = jQuery(this).data('interact');
        let value    = jQuery(this).val();
        if (interact) {
            if (typeof interact.event !== 'undefined') {
                let callback    = interact.callback;
                let id          = jQuery(this).attr('id');
                if (value === 'has any') {
                    window[callback](interact.payload, this);
                }
                jQuery('body').on(interact.event, '#'+id, function(){
                    window[callback](interact.payload, this);
                });
            }
            
        } 
    });
    
}

var cauto_default_steps_hide_related = (params = {}, obj) => {

    if (typeof params.value !== 'undefined' && typeof params.target !== 'undefined' && typeof params.action !== 'undefined') {
        let el_type     = obj.tagName.toLowerCase();
        let field_val   = null;
        switch (el_type) {
            case 'input':
                let type = jQuery(obj).attr('type');
                switch(type) {
                    case 'checkbox':
                    case 'radio':
                        if (jQuery(obj).is(':checked')) {
                            field_val = jQuery(obj).val();
                        }
                        break;
                    default:
                        field_val = jQuery(obj).val();
                        break;
                }
            case 'select':
            case 'textarea':
                field_val = jQuery(obj).val();
                break;
            default:
                break;
        }

        if (field_val === params.value) {
            switch (params.action) {
                case 'hide':
                    for (let x in params.target) {
                        jQuery(params.target[x]).val('');
                        jQuery(params.target[x]).prop('checked', false);
                        jQuery(params.target[x]).closest('.cauto-ui-wrapper').addClass('hide');
                    }
                    break;
                default:
                    break;
            }
        } else {
            for (let x in params.target) {
                jQuery(params.target[x]).closest('.cauto-ui-wrapper').removeClass('hide');
            }
        }
    }
}

var cauto_init_select2_selects = () => {

    if (jQuery('.cauto-select2-field').length > 0) {
        jQuery('.cauto-select2-field').each(function() {
            let get_source = jQuery(this).data('select-source');
            if (get_source) {
                //do init
                get_source = JSON.stringify(get_source);
                jQuery(this).select2({
                    ajax: {
                        url: cauto_ajax.ajaxurl,
                        dataType: 'json',
                        delay: 250,
                        data: function(params) {
                            return {
                                action: 'cauto_get_select2_data',  
                                search: params.term,
                                source: get_source,
                                nonce: cauto_ajax.nonce
                            };
                        },
                        processResults: function(data) {
                            return {
                                results: jQuery.map(data, function(item) {
                                    return {
                                        id: item.id,
                                        text: item.text
                                    };
                                })
                            };
                        },
                        cache: true
                    },
                    minimumInputLength: 1 
                });

            }
        });
    }

    if (jQuery('.cauto-select2-field-static').length > 0) {
        jQuery('.cauto-select2-field-static').each(function() {
                jQuery(this).select2();
        });
    }
}