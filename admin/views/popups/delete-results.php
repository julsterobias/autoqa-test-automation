<?php 
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly 
?>
<div class="cauto-popup" id="cauto-popup-delete-results-confirmation">
    <div class="cauto-popup-content" id="cauto-popup-delete-results-content">
        <h2><?php esc_html_e('Warning!', 'autoqa'); ?></h2>
        <div class="cauto-new-flow-fields">
            <p><?php esc_html_e('You are about to delete all results of this flow. If confirmed, this action cannot be undone.', 'autoqa') ?></p>
        </div>
        <div class="cauto-modal-below-controls">
            <?php do_action('cauto_load_delete_results_buttons', $data, 'buttons'); ?>
        </div>
    </div>
</div>