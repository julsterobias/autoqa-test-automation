<?php 
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly 
?>
<div class="cuato-runner-indicator ended">
    <span class="cauto-warming-up"><?php esc_html_e('Preparing runner...', 'autoqa'); ?></span>
    <div class="cauto-runner-bars"></div>
    <div class="cauto-runner-completed">
        <div class="cauto-completed-content">
        <div class="result"><?php esc_html_e('No result found, contact developer', 'autoqa'); ?></div>
        </div>
    </div>
    <div class="cauto-runner-manual-ui">
        <?php do_action('autoqa-runner-manual-input-before-control', $data); ?>
        <div><?php esc_html_e('Paused by manual input, click continue if you are ready.', 'autoqa'); ?></div>
        <div><input type="button" class="cauto-runner-button" id="cauto-continue-run-runner" value="<?php esc_attr_e('Continue', 'autoqa'); ?>"></div>
        <?php do_action('autoqa-runner-manual-input-after-control', $data); ?>
    </div>


    <div class="autoqa-result-control-buttons">
        <?php do_action('autoqa-runner-result-before-buttons', $data); ?>
        <input type="button" class="cauto-runner-button primary" id="cauto-close-runner-result-result" value="<?php esc_attr_e('Close Result', 'autoqa'); ?>">
        <input type="button" class="cauto-runner-button" id="cauto-close-runner-modal-result" value="<?php esc_attr_e('Close Runner', 'autoqa'); ?>">
        <?php do_action('autoqa-runner-result-after-buttons', $data); ?>
    </div>
</div>