<div class="cauto-settings-wrappers">
    <div class="head">
        <div class="wrapper">
            <div class="col-70 title"><span><?php echo (!empty($data['details']))? $data['details']->post_title : __('autoQA', 'autoqa-test-automation'); ?></span> <span class="dashicons dashicons-edit" id="cauto-edit-flow" title="<?php esc_attr_e('Edit Flow', 'autoqa-test-automation'); ?>"></span></div>
            <div class="col-30 controls">
                <span class="version"><?php esc_html_e('Version', 'autoqa-test-automation'); ?> <?php echo CAUTO_PLUGIN_VERSION.' '.CAUTO_PLUGIN_VERSION_CODE; ?></span>
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
<?php 
if ((isset($_GET['flow']))): 
    do_action('cauto_load_flow_id', $_GET['flow']);
endif; 
?>