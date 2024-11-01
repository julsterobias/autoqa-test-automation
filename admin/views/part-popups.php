<?php 
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly 
?>
<?php do_action('cauto_load_new_flow'); ?>
<?php do_action('cauto_load_step_config'); ?>
<?php do_action('cauto_load_step_variables'); ?>
<?php do_action('cauto_load_delete_confirm'); ?>
<?php do_action('cauto_load_settings'); ?>
<?php do_action('cauto_load_delete_results_confirm'); ?>