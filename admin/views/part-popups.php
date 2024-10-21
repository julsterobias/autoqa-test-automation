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
<?php do_action('cauto_load_new_flow'); ?>
<?php do_action('cauto_load_step_config'); ?>
<?php do_action('cauto_load_step_variables'); ?>
<?php do_action('cauto_load_delete_confirm'); ?>
<?php do_action('cauto_load_settings'); ?>
<?php do_action('cauto_load_delete_results_confirm'); ?>