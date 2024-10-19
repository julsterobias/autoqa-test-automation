/**
 * 
 * cauto_default_send_keys_step
 * 
 */


var cauto_default_send_keys_step = (params = null) => {
    
    if (!params || !Array.isArray(params)) {
        return [
            {
                status: 'failed',
                message: cauto_translable_labels['The step is not configured']
            }
        ];
    }

    for (let x in params) {
        if (typeof params[x].value === 'undefined') {
            return [
                {
                    status: 'failed',
                    message: cauto_translable_labels['The step is not configured']
                }
            ];
            break;
        }
    }

    let field_attr      = params[0].value;
    let selector        = params[1].value;
    let alias           = params[2].value;
    let keys            = params[3].value;  


    let element =  cauto_event_manager(selector, field_attr, 'click', '', true);

    if (!Array.isArray(element)) {

        if (jQuery(element).length > 0) {

            let defined_special = [
                { key: '[enter]', secret: '\n' },
                { key: '[tab]', secret: '\t' }
            ] 

            for (let i in defined_special){
                let shortcode = defined_special[i].key;
                keys = keys.replace(new RegExp(cauto_keys_escape_regexp(shortcode), 'g'), defined_special[i].secret);
            }

            let keyevents = ['keydown', 'keypress', 'keyup', 'change'];
            for (let x = 0; x < keys.length; x++) {
                for (let y in keyevents) {
                    cauto_keyboard_event_manager(
                        {
                            keyevent: keyevents[y],
                            char: keys[x],
                            element: element
                        }
                    )
                }
                //put the characters to the field
                jQuery(element).val(jQuery(element).val() + keys[x]);
            }

            return [
                {
                    status: 'passed',
                    message: '"' + keys + '" ' + cauto_translable_labels['are sent to'] + ' ' + alias
                }
            ];
            
        } else {
            //redundant fail safe
            return [
                {
                    status: 'failed',
                    message: cauto_translable_labels['Matched 0: The element cannot be found.']
                }
            ];
        }

    } else {
        return element;
    }
}

const cauto_keyboard_event_manager = (payload = {}) => {

    let get_codes = cauto_get_key_code(payload.char);
    let event = new KeyboardEvent(payload.keyevent, {
        key: payload.char,
        code: get_codes[1],
        keyCode: get_codes[0],
        bubbles: true,
        cancelable: true
    });
    payload.element.dispatchEvent(event);
}

const cauto_keys_escape_regexp = (string) => {
    return string.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
}

const cauto_get_key_code = (char) => {
    switch (char) {
        case 'a': return [65, 'KeyA']; 
        case 'b': return [66, 'KeyB']; 
        case 'c': return [67, 'KeyC']; 
        case 'd': return [68, 'KeyD']; 
        case 'e': return [69, 'KeyE']; 
        case 'f': return [70, 'KeyF']; 
        case 'g': return [71, 'KeyG']; 
        case 'h': return [72, 'KeyH']; 
        case 'i': return [73, 'KeyI']; 
        case 'j': return [74, 'KeyJ']; 
        case 'k': return [75, 'KeyK']; 
        case 'l': return [76, 'KeyL']; 
        case 'm': return [77, 'KeyM']; 
        case 'n': return [78, 'KeyN']; 
        case 'o': return [79, 'KeyO']; 
        case 'p': return [80, 'KeyP']; 
        case 'q': return [81, 'KeyQ']; 
        case 'r': return [82, 'KeyR']; 
        case 's': return [83, 'KeyS']; 
        case 't': return [84, 'KeyT']; 
        case 'u': return [85, 'KeyU']; 
        case 'v': return [86, 'KeyV']; 
        case 'w': return [87, 'KeyW']; 
        case 'x': return [88, 'KeyX']; 
        case 'y': return [89, 'KeyY']; 
        case 'z': return [90, 'KeyZ']; 
        case 'A': return [65, 'KeyA']; 
        case 'B': return [66, 'KeyB']; 
        case 'C': return [67, 'KeyC']; 
        case 'D': return [68, 'KeyD']; 
        case 'E': return [69, 'KeyE']; 
        case 'F': return [70, 'KeyF']; 
        case 'G': return [71, 'KeyG']; 
        case 'H': return [72, 'KeyH']; 
        case 'I': return [73, 'KeyI']; 
        case 'J': return [74, 'KeyJ']; 
        case 'K': return [75, 'KeyK']; 
        case 'L': return [76, 'KeyL']; 
        case 'M': return [77, 'KeyM']; 
        case 'N': return [78, 'KeyN']; 
        case 'O': return [79, 'KeyO']; 
        case 'P': return [80, 'KeyP']; 
        case 'Q': return [81, 'KeyQ']; 
        case 'R': return [82, 'KeyR']; 
        case 'S': return [83, 'KeyS']; 
        case 'T': return [84, 'KeyT']; 
        case 'U': return [85, 'KeyU']; 
        case 'V': return [86, 'KeyV']; 
        case 'W': return [87, 'KeyW']; 
        case 'X': return [88, 'KeyX']; 
        case 'Y': return [89, 'KeyY']; 
        case 'Z': return [90, 'KeyZ']; 
        case '0': return [48, 'Digit0']; 
        case '1': return [49, 'Digit1']; 
        case '2': return [50, 'Digit2']; 
        case '3': return [51, 'Digit3']; 
        case '4': return [52, 'Digit4']; 
        case '5': return [53, 'Digit5']; 
        case '6': return [54, 'Digit6']; 
        case '7': return [55, 'Digit7']; 
        case '8': return [56, 'Digit8']; 
        case '9': return [57, 'Digit9']; 
        case '!': return [49, 'Shift + 1']; 
        case '@': return [50, 'Shift + 2']; 
        case '#': return [51, 'Shift + 3']; 
        case '$': return [52, 'Shift + 4']; 
        case '%': return [53, 'Shift + 5']; 
        case '^': return [54, 'Shift + 6']; 
        case '&': return [55, 'Shift + 7']; 
        case '*': return [56, 'Shift + 8']; 
        case '(': return [57, 'Shift + 9']; 
        case ')': return [48, 'Shift + 0']; 
        case ' ': return [32, 'Space']; 
        case '\t': return [9, 'Tab'];  
        case '\n': return [13, 'Enter']; 
        default: return null; 
    }
};