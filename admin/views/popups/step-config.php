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
<div class="cauto-popup" id="cauto-popup-start-step">
    <div class="cauto-popup-content" id="cauto-popup-content-step">
        <div class="cauto-popup-loader"><span class="dashicons dashicons-update cauto-popup-loader cauto-loader"></span></div>
    </div>
</div>