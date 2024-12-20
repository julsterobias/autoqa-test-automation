var cauto_iam_leaving                   = false;
var cauto_paused_data                   = []; 
var cauto_runner_delay                  = cauto_runner.step_duration; 
var cauto_temp_runner_variables         = [];


window.onbeforeunload = function(){
    cauto_iam_leaving = true;
};

jQuery(window).on('load',function(){
    if (cauto_is_running_flow) {
        if (JSON.stringify(cauto_running_flow_data) !== '{}') {
            console.log('%cautoQA NOTICE: %cRunner is in action','color: yellow; font-weight: bold;','color: #00ff33; font-size: 14px');
            cauto_prepare_the_runner();
            jQuery('.cuato-runner-indicator').show();
        }
    }

    jQuery('#cauto-close-runner-modal-result').on('click',function(){
        window.parent.close();
    });

    jQuery('#cauto-close-runner-result-result').on('click', function(){
        jQuery('.cauto-runner-completed').hide();
        jQuery('.cuato-runner-indicator').removeClass('ended');
    });

    cauto_runner_delay = parseInt(cauto_runner_delay);
});


const cauto_prepare_the_runner = () => {
    jQuery.ajax( {
        type : "post",  
        url: cauto_runner.ajaxurl,
        data : {    
            action: 'cauto_prepare_runner', 
            nonce: cauto_runner.nonce,
            flow_id: cauto_running_flow_data.flow_id,
            runner_id: cauto_running_flow_data.runner_id
        },
        success: function( data ) {
            //response data
            if (data) {
                if (data.status === 'success') {
                    if (typeof data.variables !== 'undefined') {
                        cauto_temp_runner_variables = data.variables;
                    }
                    cauto_runner['runner_steps'] = data.runner_steps;
                    setTimeout(function(){
                        jQuery('.cauto-runner-bars').addClass('cauto-runner-bars-'+cauto_running_flow_data.runner_id)
                        jQuery('.cauto-runner-bars-'+cauto_running_flow_data.runner_id).html(data.bars);
                        jQuery('.cuato-runner-indicator').removeClass('ended');
                        cauto_do_run_runner();
                        jQuery('.cauto-warming-up').remove();
                    },2000);
                } else {
                    console.error(data.message);
                }
            }
        }
    });
}

const cauto_do_run_runner = (response = [], index = 0, status = null) =>
{
    cauto_plot_runner_status(response, index, status);

    jQuery.ajax( {
        type : "post",  
        url: cauto_runner.ajaxurl,
        data : {    
            action: 'cauto_execute_pre_run', 
            nonce: cauto_runner.nonce,
            flow_id: cauto_running_flow_data.flow_id,
            runner_id: cauto_running_flow_data.runner_id,
            response: JSON.stringify(response),
            index: index
        },
        success: function( data ) {
            //response data
            if (data) {
                if (data.status === 'success') {
                    try {
            
                        let callback    = data.payload.callback;
                        let response    = window[callback](data.payload.params);
                        let index       = data.payload.index;

                        if (response && !cauto_iam_leaving) {
                            index++;

                            if (typeof response[0].pause !== 'undefined') {   
                                cauto_paused_data = [response, index];
                                return;
                            }                            
                            
                            setTimeout(function(){
                                cauto_do_run_runner(response, index);
                            }, cauto_runner_delay);
                            
                        } else {
                            return;
                        }
                    } catch (error) {
                        console.error('AutoQA error: '+error);
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
                        console.error('AutoQA error: '+error);
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

    let runner_steps  = cauto_runner.runner_steps[cauto_running_flow_data.runner_id];
    
    if (results.length > 0) {

        let htmlclass = (results[0].status === 'passed')? 'passed' : 'failed';
        jQuery('.cauto-runner-bars-'+cauto_running_flow_data.runner_id+' div.cauto-bar:nth-child(' + index + ')').removeClass('cauto_bar_loader').addClass(htmlclass);
        let next_index = index;
        next_index++;
        jQuery('.cauto-runner-bars-'+cauto_running_flow_data.runner_id+' div.cauto-bar:nth-child(' + next_index + ')').addClass('cauto_bar_loader');

    } else if (results.length === 0 && runner_steps.length > 0) {

        let temp_x = 0;
        for (let x in runner_steps) {
            if ( typeof runner_steps[x].result != 'undefined' ) {
                let htmlclass = (runner_steps[x].result[0].status === 'passed')? 'passed' : 'failed';
                temp_x = x;
                temp_x++;
                jQuery('.cauto-runner-bars-'+cauto_running_flow_data.runner_id+' div.cauto-bar:nth-child(' + temp_x + ')').removeClass('cauto_bar_loader').addClass(htmlclass);
            } 
        } 

    } 
    if (is_continue) {
        index--;
        jQuery('.cauto-runner-bars-'+cauto_running_flow_data.runner_id+' div.cauto-bar:nth-child(' + index + ')').removeClass('cauto_bar_loader').addClass('passed');
    }
    
}

const cauto_render_test_results = (payload = []) => {
    
    jQuery('.cauto-runner-completed, .autoqa-result-control-buttons').show();

    if (payload.length === 0) return;

    jQuery('.cauto-completed-content div.result').html('<ul></ul>');
    let temp_index = 0;
    let has_failed = false;

    for (let x in payload) {
        let step    = payload[x].step;
        temp_index++;
        let passed  = '<svg fill="#FFFFFF" xmlns="http://www.w3.org/2000/svg" width="800px" height="800px" viewBox="0 0 52 52" enable-background="new 0 0 52 52" xml:space="preserve"><path d="M26,2C12.7,2,2,12.7,2,26s10.7,24,24,24s24-10.7,24-24S39.3,2,26,2z M39.4,20L24.1,35.5 c-0.6,0.6-1.6,0.6-2.2,0L13.5,27c-0.6-0.6-0.6-1.6,0-2.2l2.2-2.2c0.6-0.6,1.6-0.6,2.2,0l4.4,4.5c0.4,0.4,1.1,0.4,1.5,0L35,15.5 c0.6-0.6,1.6-0.6,2.2,0l2.2,2.2C40.1,18.3,40.1,19.3,39.4,20z"/></svg>'; 
        let failed  = '<svg width="800px" height="800px" viewBox="0 0 512 512" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><title>error-filled</title><g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><g id="add" fill="#FFFFFF" transform="translate(42.666667, 42.666667)"><path d="M213.333333,3.55271368e-14 C331.136,3.55271368e-14 426.666667,95.5306667 426.666667,213.333333 C426.666667,331.136 331.136,426.666667 213.333333,426.666667 C95.5306667,426.666667 3.55271368e-14,331.136 3.55271368e-14,213.333333 C3.55271368e-14,95.5306667 95.5306667,3.55271368e-14 213.333333,3.55271368e-14 Z M262.250667,134.250667 L213.333333,183.168 L164.416,134.250667 L134.250667,164.416 L183.168,213.333333 L134.250667,262.250667 L164.416,292.416 L213.333333,243.498667 L262.250667,292.416 L292.416,262.250667 L243.498667,213.333333 L292.416,164.416 L262.250667,134.250667 Z" id="Combined-Shape"></path></g></g></svg>'
        let status      = '';
        let staus_class = 'no-status';
        let message = '--';

        if (typeof payload[x].result != 'undefined') { 
            if (payload[x].result[0].status === 'passed') {
                status      = passed; 
                staus_class = 'passed';
            }
            if (payload[x].result[0].status === 'failed') {
                status      = failed; 
                staus_class = 'failed';
                has_failed++;
            }
            message         = payload[x].result[0].message;
        } else {
            status      = failed; 
        }
        

        step = step
        .replace(/-/g, ' ')
        .replace(/^./, function(x){return x.toUpperCase()})

        let result_html = '<li>'
        +'<div class="status '+staus_class+'">'+ status +'</div>'
        +'<div class="meta">'
        +'<span class="step">'+ step +'</span>'
        +'<span class="message ' + staus_class + '">'+ message +'</span>'
        +'</div>'
        +'</li>';

        jQuery('.cauto-completed-content div.result ul').append(result_html);

        //update the plots
        jQuery('.cauto-runner-bars div.cauto-bar:nth-child(' + temp_index + ')').removeClass('cauto_bar_loader').addClass(staus_class);

    }

    jQuery('.cuato-runner-indicator').addClass('ended');

}

/**
 * 
 * 
 * create element
 * @param object
 * @returns object
 * https://github.com/julsterobias/simple-js-element-builder
 * 
 * 
 */
const js_el_generator = (args = new Object) => {
    
    if(Object.keys(args).length == 0)
        return;
    
    var element = document.createElement(args.type);
    var text = (args.text)? document.createTextNode(args.text) : document.createTextNode('');
    if(args.attributes){
        for(var x in args.attributes){
            element.setAttribute(args.attributes[x].attr, args.attributes[x].value);
        }
    }
    element.appendChild(text);
    if(args.type == 'select'){
        //create options
        if(args.options){
            for(var y in args.options){
                var option = document.createElement('option');
                option.value = args.options[y].value;
                option.text = args.options[y].text;
                
                var check_value = Array.isArray(args.value);
                if(check_value){
                    for(var b in args.value){
                        if(args.value[b] == args.options[y].value){
                            option.defaultSelected = true;
                        }
                    }
                }else{
                    if(args.value == args.options[y].value){
                        option.defaultSelected = true;
                    }
                }
                element.appendChild(option);
            }
        }
    }
    return element;
}


const cauto_get_element_by_xpath = (xpath = '') => {

    if (xpath === '') return;

    var result = document.evaluate(xpath, document, null, XPathResult.FIRST_ORDERED_NODE_TYPE, null);
    return result.singleNodeValue;
    
}

const cauto_event_manager = (selector, field_attr, event_type, alias = '', other_events = false, return_element = false) => {

    let element = cauto_prepare_element_selector(field_attr, selector);

    if (element.length === 0) {
        return [
            {
                status: 'failed',
                message: cauto_translable_labels['Matched 0: The element cannot be found.']
            }
        ];
    }

    if (element.length > 1) {
        return [
            {
                status: 'failed',
                message: cauto_translable_labels['Matched is greater than 1: Multiple elements were found, but the specific event cannot be dispatched.']
            }
        ];
    }

    let position = element.offset();
    let width = element.outerWidth();
    let height = element.outerHeight();
    let middleX = position.left + (width / 2);
    let middleY = position.top + (height / 2);

    let viewportX = middleX - window.scrollX;
    let viewportY = middleY - window.scrollY;

    var event = new MouseEvent(event_type, {
        view: window,
        bubbles: true,
        cancelable: true,
        clientX: viewportX,
        clientY: viewportY 
    });
    
    let toelement = document.elementFromPoint(viewportX, viewportY);

    if (event_type) {
        if (toelement) {
            toelement.dispatchEvent(event);
        } else {
            return [
                {
                    status: 'failed',
                    message: cauto_translable_labels['Matched 0: The element cannot be found after dispatch.']
                }
            ];
        }
    }

    //create marker
    let marker = js_el_generator(
        {
            type: 'span',
            text: '',
            attributes: [
                {
                    attr: 'class',
                    value: 'cauto-marker-on-event'
                }
            ]
        }
    );

    //set markers
    let markerx = middleX - (30 / 2);
    let markery = middleY - (30 / 2);
    jQuery(marker).css('left', markerx);
    jQuery(marker).css('top', markery);
    jQuery('body').append(marker);

    setTimeout(function(){
        jQuery(marker).remove();
    },300);

    if (return_element && other_events) {
        return [element, toelement];
    }

    //other events
    if (other_events && !return_element) {
        return toelement;
    }

    return [
        {
            status: 'passed',
            message: cauto_translable_labels['Action Passed:'] + ' '+ alias +' is ' + event_type + 'ed' //action-passed
        }
    ];

}


const cauto_check_data_type = (value_expected, value_recieved, type_error) => {

    if (isNaN(value_expected) || isNaN(value_recieved)) {
        return [
            {
                status: 'failed',
                message: type_error
            }
        ];
    }

    value_expected = Number(value_expected);
    value_recieved = Number(value_recieved);

    return [value_expected, value_recieved];
}

const cauto_prepare_element_selector = (field_attr = '', selector = '') => {

    let selector_string = '#cauto-element-not-found';
    let element = jQuery(selector_string);
    
    switch(field_attr) {
        case 'id': 
            let id_ind = selector.substring(0, 1);
            if (id_ind === '#') {
                selector_string = selector;
            } else {
                selector_string = '#' + selector;
            }
            element = jQuery(selector_string);
            break
        case 'class':
            let class_ind = selector.substring(0, 1);
            if (class_ind === '.') {
                selector_string = selector;
            } else {
                selector_string = '.' + selector;
            }
            element = jQuery(selector_string);
            break;
        case 'xpath':
            element = cauto_get_element_by_xpath(selector);
            element = jQuery(element);
            break;
    }

    return element;
}

const cauto_save_element_to_data_action = (data_name, data_to_store) => {
    jQuery.ajax( {
        type : "post",  
        url: cauto_runner.ajaxurl,
        data : {    
            action: 'cauto_save_element_step_data_to_transient', 
            nonce: cauto_runner.nonce,
            data_name: data_name,
            data_to_store: data_to_store
        },
        success: function( data ) {
            //response data
            if (data) {
                if (data.status === 'success') {
                    //continue the runner
                    cauto_do_run_runner([
                        {
                            status: 'passed',
                            message: '"'+data_to_store+'" ' +cauto_translable_labels['is stored to data as']+ ' '+data_name, //is-stored-to-data-as
                            pause: true
                        }
                    ], cauto_paused_data[1]);
                } else {
                    console.error('CAUTO ERROR: '+ data.message);
                }
            }
        }
    });
}


const cauto_translate_variable_in_steps_field = (data_to_store = null) => {
    
    if (data_to_store === '' || !data_to_store) return data_to_store;

    if (cauto_temp_runner_variables.length > 0) {
        for (let x in cauto_temp_runner_variables) {
            $converted_data = cauto_variable_date_data(cauto_temp_runner_variables[x]); 
            data_to_store = data_to_store.replace(cauto_temp_runner_variables[x], $converted_data);
        }
    }
    return data_to_store;

}


const cauto_variable_date_data = (type) => {

    var date    = new Date();
    var month   = ('0' + (date.getMonth() + 1)).slice(-2);
    var day     = ('0' + date.getDate()).slice(-2);
    var year    = date.getFullYear();
    var hours   = ('0' + date.getHours()).slice(-2);
    var minutes = ('0' + date.getMinutes()).slice(-2);
    var seconds = ('0' + date.getSeconds()).slice(-2); 

    switch(type) {
        case '$FullDate':
            return month + '-' + day + '-' + year + ' ' + hours + ':' + minutes + ':' + seconds;
            break;
        case '$Date':
            return month + '-' + day + '-' + year;
            break;
        case '$Month':
            return month;
            break;
        case '$Day':
            return day;
            break;
        case '$Year':
            return year;
            break;
        case '$Time':
            return year;
            break;
        case '$Hour':
            return hours;
            break;
        case '$Minute':
            return minutes;
            break;
        case '$Second':
            return seconds;
            break;
        case '$UnixTimeStamp':
            return Math.floor(Date.now() / 1000);
            break;
        default:
            return sessionStorage.getItem(type);
            break;
    }
    
}

const cuato_resume_paused_runner = (status, message) => {

    setTimeout(function(){
        cauto_do_run_runner([
            {
                status: status,
                message: message
            }
        ], cauto_paused_data[1]);

    }, cauto_runner_delay);

}