/**
 * 
 * cauto_default_upload_pdf_step
 * @since 1.0.0
 * 
 */

var cauto_default_upload_pdf_step = (params = null) => {
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

    let pdf_attr        = params[0].value;
    let pdf_selector    = params[1].value;
    let pdf_alias       = params[2].value;
    let pdf_content     = params[3].value;
    
    let element  = cauto_event_manager(pdf_selector, pdf_attr, null, '', true);

    let file_found  = false;

    if (!Array.isArray(element)) {
        if (jQuery(element).length > 0) {
            file_found = true;
        } else {
            //redundant fail safe
            return [
                {
                    status: 'failed',
                    message: cauto_step_text.element_not_found
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
                action: 'cauto_generate_pdf_step',
                nonce: cauto_runner.nonce,
                content: pdf_content,
                file_alias: pdf_alias
            },
            success: function( data ) {
                //response data
                if (data) {
              
                    if (data.status === 'success') {
    
                        if (typeof data.pdf !== 'undefined') {
                            
                            if (cauto_paused_data.length > 0) {

                                const pdfData = atob(data.pdf);
                                const arrayBuffer = new ArrayBuffer(pdfData.length);
                                const uint8Array = new Uint8Array(arrayBuffer);

                                for (let i = 0; i < pdfData.length; i++) {
                                    uint8Array[i] = pdfData.charCodeAt(i);
                                }

                                // Create a Blob from the binary data
                                let blob = new Blob([uint8Array], { type: 'application/pdf' });
                                let file = new File([blob], data.filename, { type: "application/pdf" });
                                let dataTransfer = new DataTransfer();
                                dataTransfer.items.add(file);
                                jQuery(element)[0].files = dataTransfer.files;
                                cuato_resume_paused_runner('passed', '- PDF is assigned to field ' + pdf_alias);

    
                            } else {
                                console.error('AutoQA Error: No payload found after the runner is paused. Please contact developer');
                            }
    
                        } else {
                            console.error("autoQA Error: No data response from image generator, please contact developer");
                        }
    
                    } else {
                        console.error("autoQA Error: No data response from image generator, please contact developer");
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