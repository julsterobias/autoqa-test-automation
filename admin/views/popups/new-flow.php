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
<div class="cauto-popup" id="cauto-popup-new-flow">
    <div class="cauto-popup-content" id="cauto-new-flow">
        <h2><?php esc_html_e('Flow Details', 'autoqa'); ?></h2>
        <div class="cauto-new-flow-fields">
            <?php do_action('cauto_load_ui', $data, 'fields'); ?>
        </div>
        <div class="cauto-modal-below-controls">
            <?php do_action('cauto_load_ui', $data, 'buttons'); ?>
        </div>
    </div>
</div>