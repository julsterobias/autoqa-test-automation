/**
 * 
 * 
 */

var cauto_default_upload_image_step = (params = null) => {

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
        }
    }

    let file_attr       = params[0].value;
    let file_selector   = params[1].value;
    let file_alias      = params[2].value;
    let file_type       = params[3].value;
    let file_width      = params[4].value;
    let file_height     = params[5].value;

    let element  = cauto_event_manager(file_selector, file_attr, null, '', true);

    let file_found  = false;

    if (!Array.isArray(element)) {
        if (jQuery(element).length > 0) {
            file_found = true;
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

   
    if (file_found) {

        jQuery.ajax( {
            type : "post",  
            url: cauto_runner.ajaxurl,
            data : {    
                action: 'cauto_generate_image_step',
                nonce: cauto_runner.nonce,
                type: file_type,
                width: file_width,
                height: file_height,
                file_alias: file_alias
            },
            success: function( data ) {
                //response data
                if (data) {
                    if (data.status === 'success') {
                        if (typeof data.image !== 'undefined') {
                            
                            if (cauto_paused_data.length > 0) {
                                let blob = cauto_base64_to_blob(data.image);
                                let file = new File([blob], data.filename, { type: "image/"+file_type });
                                let dataTransfer = new DataTransfer();
                                dataTransfer.items.add(file);
                                jQuery(element)[0].files = dataTransfer.files;
                                jQuery(element).trigger("change");
                                cuato_resume_paused_runner('passed', '- ' + file_type + ' is assigned to field ' + file_alias);
    
                            } else {
                                console.error(cauto_translable_labels['AutoQA Error: No payload found after the runner is paused. Please contact developer']);
                            }
    
                        } else {
                            console.error(cauto_translable_labels["autoQA Error: No data response from image generator, please contact developer"]);
                        }
    
                    } else {
                        console.error(cauto_translable_labels["autoQA Error: No data response from image generator, please contact developer"]);
                    }
                }
            }
        });

    }
    

    return [
        {
            pause: true
        }
    ];

}

const cauto_base64_to_blob = (base64) => {

    const parts = base64.split(',');
    const byteString = atob(parts[1]);
    const mimeType = parts[0].split(':')[1].split(';')[0];

    const arrayBuffer = new ArrayBuffer(byteString.length);
    const uint8Array = new Uint8Array(arrayBuffer);

    for (let i = 0; i < byteString.length; i++) {
        uint8Array[i] = byteString.charCodeAt(i);
    }

    return new Blob([arrayBuffer], { type: mimeType });
}