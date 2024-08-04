<div class="cauto-settings-wrappers">
    <div class="head">
        <div class="wrapper">
            <div class="col-50 title"><span><?php echo (!empty($data['details']))? $data['details']->post_title : __('Codecorun Test Automation', 'codecorun-test-automation'); ?></span></div>
            <div class="col-50 controls">
                <span class="version"><?php esc_html_e('Version', 'codecorun-test-automation'); ?> <?php echo CAUTO_PLUGIN_VERSION.' '.CAUTO_PLUGIN_VERSION_CODE; ?></span>
                <?php do_action('cauto_top_control'); ?>
            </div>
        </div>
    </div>

    <?php if (!empty($data['details'])): ?>
    <div class="cauto-flow-builder">
        <?php do_action('cauto_flow_builder', $data['details']); ?>
    </div>
    <?php else: ?>
    <div class="cauto-flow-builder">
        <?php do_action('cauto_render_flows'); ?>
    </div>
    <?php endif; ?>

    <?php $this->get_view('part-popups', ['path' => 'admin']); ?>
</div>