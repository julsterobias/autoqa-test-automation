/**
 * 
 * 
 * cauto_manual_input_page
 * @since 1.0.0
 * 
 * 
 */
var cauto_default_manual_input_event = () => {
    jQuery('.cauto-runner-manual-ui').addClass('active');
    return [
        {
            status: 'passed',
            message: cauto_translable_labels['Runner is paused for manual input'],
            pause: true
        }
    ];
}

jQuery(document).ready(function(){
    jQuery('#cauto-continue-run-runner').on('click', function(){
        if (cauto_paused_data.length > 0) {
            cauto_do_run_runner(cauto_paused_data[0], cauto_paused_data[1]);
            jQuery('.cauto-runner-manual-ui').removeClass('active');
        } else {
            console.error(cauto_translable_labels['AutoQA Error: No payload found after the runner is paused. Please contact developer']);
        }
    });
});