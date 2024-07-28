jQuery(document).ready(function(){

    jQuery('#cauto-new-case').on('click', function(){
        jQuery('#cauto-popup-new-flow').fadeIn(200);
    });

    jQuery('.cauto-cancel').on('click', function(){
        jQuery('#cauto-popup-new-flow').fadeOut(200);
    });

    jQuery('#cauto-save-new-flow').on('click', function(){
        cauto_do_save_flow(this);
    });

});


var cauto_do_save_flow = (btn) => {
    jQuery(btn).find('span.cauto_button_text').text('Saving...');
    jQuery(btn).find('span.dashicons').removeClass('dashicons-saved').addClass('dashicons-update cauto-loader');
    jQuery('.cauto-cancel').prop('disabled', true);
    jQuery(btn).prop('disabled', true);
}