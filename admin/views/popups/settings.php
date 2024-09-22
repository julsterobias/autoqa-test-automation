<div class="cauto-popup" id="cauto-popup-settings">
    <div class="cauto-popup-content" id="cauto-popup-delete-flow-content">
        <h2><?php esc_html_e('Settings', 'autoqa-test-automation'); ?></h2>
        <div class="cauto-new-flow-fields">
            <?php do_action('cauto_load_settings_fields', $data); ?>
        </div>
        <div class="cauto-modal-below-controls">
            <?php do_action('cauto_load_settings_buttons', $data, 'buttons'); ?>
        </div>
    </div>
</div>