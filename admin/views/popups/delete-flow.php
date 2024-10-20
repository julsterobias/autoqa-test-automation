<?php 
if ( !function_exists( 'add_action' ) ){
    header( 'Status: 403 Forbidden' );
    header( 'HTTP/1.1 403 Forbidden' );
    exit();
}

if ( !function_exists( 'add_filter' ) ){
    header( 'Status: 403 Forbidden' );
    header( 'HTTP/1.1 403 Forbidden' );
    exit();
}
?>
<div class="cauto-popup" id="cauto-popup-delete-flow-confirmation">
    <div class="cauto-popup-content" id="cauto-popup-delete-flow-content">
        <h2><?php esc_html_e('Warning!', 'autoqa-test-automation'); ?></h2>
        <div class="cauto-new-flow-fields">
            <p><?php esc_html_e('You are about to delete this flow. If confirmed, all related runners will also be deleted. This action cannot be undone.', 'autoqa-test-automation') ?></p>
        </div>
        <div class="cauto-modal-below-controls">
            <?php do_action('cauto_load_delete_buttons', $data, 'buttons'); ?>
        </div>
    </div>
</div>