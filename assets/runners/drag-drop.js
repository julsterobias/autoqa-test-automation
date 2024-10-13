/**
 * 
 * 
 * cauto_default_drag_drop_step
 * @since 1.0.0
 * 
 * 
 */
var cauto_default_drag_drop_step = (params = []) => {

    if (!params || !Array.isArray(params)) {
        return [
            {
                status: 'failed',
                message: cauto_step_text.unconfigured_msg
            }
        ];
    }

    for (let x in params) {
        if (typeof params[x].value === 'undefined') {
            return [
                {
                    status: 'failed',
                    message: cauto_step_text.unconfigured_msg
                }
            ];
        }
    }

    let source_attr     = params[0].value;
    let source_selector = params[1].value;
    let source_alias    = params[2].value;

    let target_attr     = params[3].value;
    let target_selector = params[4].value;
    let target_alias    = params[5].value;

    let source_element  = cauto_event_manager(source_selector, source_attr, null, '', true);
    let target_element  = cauto_event_manager(target_selector, target_attr, null, '', true);

    source_element      = jQuery(source_element);
    target_element      = jQuery(target_element);

    let do_the_event    = 0;

    let check_elements = [
        {
            el: source_element,
            err_message: 'Source element is not found'
        },
        {
            el: target_element,
            err_message: 'Target element is not found'
        }
    ]

    for (let x in check_elements) {
        if (!Array.isArray(check_elements[x].el)) {
            if (jQuery(check_elements[x].el).length > 0) {
                do_the_event++;
            } else {
                //redundant fail safe
                return [
                    {
                        status: 'failed',
                        message: check_elements[x].err_message
                    }
                ];
            }
        } else {
            return check_elements[x].el;
        }

    }

    if (do_the_event > 1) {
        cauto_simulate_drag_drop_event(source_element, target_element);
    }
     
    return [
        {
            status: 'passed',
            message: source_alias + ' is dragged and dropped to ' + target_alias
        }
    ];
    
}


const cauto_simulate_drag_drop_event = (draggable, target_el) => {

    const draggableOffset = draggable.offset();
    const targetOffset = target_el.offset();

    const deltaX = targetOffset.left - draggableOffset.left;
    const deltaY = targetOffset.top - draggableOffset.top;

    const createMouseEvent = (type, x, y) => {
      return new MouseEvent(type, {
        view: window,
        bubbles: true,
        cancelable: true,
        clientX: x,
        clientY: y
      });
    };

    draggable[0].dispatchEvent(createMouseEvent('mousedown', draggableOffset.left, draggableOffset.top));
    draggable[0].dispatchEvent(createMouseEvent('mousemove', draggableOffset.left + deltaX / 2, draggableOffset.top + deltaY / 2));
    draggable[0].dispatchEvent(createMouseEvent('mousemove', targetOffset.left, targetOffset.top));
    draggable[0].dispatchEvent(createMouseEvent('mouseup', targetOffset.left, targetOffset.top));
    
}