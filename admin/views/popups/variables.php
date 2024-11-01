<?php 
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly 
?>
<div class="cauto-popup" id="cauto-popup-runner-variables">
    <div class="cauto-popup-content" id="cauto-popup-runner-variables-content">
        <label><?php esc_html_e('Insert Dynamic Value', 'autoqa'); ?></label>
        <input type="text" id="cauto-variable-field-select" class="cauto-field wide" value="" placeholder="<?php esc_attr_e('Search value name', 'autoqa'); ?>">
        <i><?php esc_html_e('Press ESC to close the variable modal','autoqa'); ?></i>
    </div>
</div>