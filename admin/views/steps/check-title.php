<div class="cauto-step-config">
    <div class="cauto-step-config-header">
        <h3><?php esc_html_e('Check Title', 'codecorun-test-automation'); ?></h3>
    </div>
    <div class="cauto-fields auto-type-start">
        <?php if (!empty($data['config'])): 
            do_action('cauto_load_ui', ['fields' => $data['config']], 'fields', []);
        endif; ?>
    </div>
</div>