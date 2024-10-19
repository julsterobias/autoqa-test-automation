/**
 * 
 * 
 * cauto_default_check_visibilty_step
 * 
 * 
 */

var cauto_default_check_visibilty_step = (params = null) => {
    
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

    let field_attr      = (params[0].value)? params[0].value : null;
    let selector        = (params[1].value)? params[1].value : null;
    let alias           = (params[2].value)? params[2].value : '';
    let condition       = (params[3].value)? params[3].value : '';

    let element             = cauto_event_manager(selector, field_attr, null, '', true, true); //refactor the parameters, pass them in one object instead.

    if (Array.isArray(element) && element.length === 1) {
        if (typeof element[0].status !== 'undefined') {
            if (element[0].status === 'failed') {
                return element;
            }
        }
    }

    if (Array.isArray(element)) {
        
        let is_displayed = false;

        if ( element[1].isEqualNode(element[0][0]) ) {
            is_displayed = true;
        } else {
            is_displayed = false;
        }

        switch (condition) {
            case 'is displayed':
                if (is_displayed) {
                    return [
                        {
                            status: 'passed',
                            message: alias + ' ' + cauto_translable_labels['is displayed']
                        }
                    ];
                } else {

                    let is_display  = jQuery(element[0]).css('display');
                    let is_visible  = jQuery(element[0]).css('visibility');

                    let is_virually_visible = true;

                    if (is_display === 'none' || is_visible === 'hidden' || is_visible === 'collapse') {
                        is_virually_visible = false;
                    }

                    if (is_virually_visible) {
                        return [
                            {
                                status: 'failed',
                                message: alias + ' ' + cauto_translable_labels['is displayed but not interactable']
                            }
                        ];
                    }
                    
                    return [
                        {
                            status: 'failed',
                            message: alias + ' ' + cauto_translable_labels['is hidden']
                        }
                    ];
                }
                break;
            case 'is hidden':
                
                if (!is_displayed) {

                    let is_display  = jQuery(element[0]).css('display');
                    let is_visible  = jQuery(element[0]).css('visibility');

                    let is_virually_visible = true;

                    if (is_display === 'none' || is_visible === 'hidden' || is_visible === 'collapse') {
                        is_virually_visible = false;
                    }

                    if (is_virually_visible) {
                        return [
                            {
                                status: 'failed',
                                message: alias + ' ' + cauto_translable_labels['is displayed but not interactable']
                            }
                        ];
                    }
                    return [
                        {
                            status: 'passed',
                            message: alias + ' ' + cauto_translable_labels['is hidden']
                        }
                    ];
                } else {
                    return [
                        {
                            status: 'failed',
                            message: alias + ' ' + cauto_translable_labels['is displayed']
                        }
                    ];
                }
                break;
        }

    } else {
        return [
            {
                status: 'failed',
                message: cauto_translable_labels['Runner can\'t compare element for visibility please contact developer']
            }
        ];
    }


}
