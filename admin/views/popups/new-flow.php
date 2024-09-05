<div class="cauto-popup" id="cauto-popup-new-flow">
    <div class="cauto-popup-content" id="cauto-new-flow">
        <h2><?php esc_html_e('Flow Details', 'autoqa-test-automation'); ?></h2>
        <div class="cauto-new-flow-fields">
            <?php do_action('cauto_load_ui', $data, 'fields'); ?>
        </div>
        <div class="cauto-modal-below-controls">
            <?php do_action('cauto_load_ui', $data, 'buttons'); ?>
        </div>
    </div>
</div>