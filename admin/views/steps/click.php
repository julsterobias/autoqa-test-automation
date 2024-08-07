<div class="cauto-step-config">
    <div class="cauto-step-config-header">
        <h3><?php esc_html_e('Click', 'codecorun-test-automation'); ?></h3>
    </div>
    <div class="cauto-fields auto-type-start">
        <?php if (!empty($data['config'])): 
            do_action('cauto_load_ui', ['fields' => $data['config']], 'fields', []);
        endif; ?>
    </div>
    <div class="cauto-step-controls">
        <?php do_action('cauto_step_controls', 'click', $data['field_ids'], $data['step_indicator']); ?>
    </div>
</div>