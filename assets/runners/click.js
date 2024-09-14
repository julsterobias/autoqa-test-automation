/**
 * 
 * cauto_default_click_step
 * @since 1.0.0
 * 
 * 
 */

var cauto_default_click_step = (params = null) => {

    if (!params) {
        return [
            {
                status: 'failed',
                message: 'Error:' + cauto_runner.unconfigured_msg
            }
        ];
    }
    
    if ( Array.isArray(params) ) {

        let attribute = (params[0].value)? params[0].value : null;
        if (!attribute) {
            return;
        }

        let click_type = (params[1].value)? params[1].value : null;
        if (!click_type) {
            return;
        }

        let selector = (params[2].value)? params[2].value : null;
        if (!selector) {
            return;
        }
    }

    let selector_string = '#cauto-element-no-found';
    let element = jQuery(selector_string);

    switch(attribute) {
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
            break;
    }

    if (element.length === 0) {
        return [
            {
                status: 'failed',
                message: "No element found"
            }
        ];
    }

    let position = element.offset();
    let width = element.outerWidth();
    let height = element.outerHeight();
    let middleX = position.left + (width / 2);
    let middleY = position.top + (height / 2);

    var event = new MouseEvent('click', {
        view: window,
        bubbles: true,
        cancelable: true,
        clientX: middleX,
        clientY: middleY 
    });
    let toelement = document.elementFromPoint(middleX, middleY);

    if (toelement) {
        toelement.dispatchEvent(event);
    }else {
        return [
            {
                status: 'failed',
                message: "No element found"
            }
        ];
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

    return [
        {
            status: 'passed',
            message: "Event is validated"
        }
    ];
}