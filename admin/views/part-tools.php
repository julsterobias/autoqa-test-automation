<div class="cauto-settings-wrappers">
    <div class="head">
        <div class="wrap">
            <div class="col-20 title"><span><?php esc_attr_e('Codecorun Test Automation', 'codecorun-test-automation'); ?></span></div>
            <div class="col-80 controls">
                <?php do_action('cauto_top_control'); ?>
            </div>
        </div>
    </div>

    <?php $this->get_view('part-popups', ['path' => 'admin']); ?>
</div>