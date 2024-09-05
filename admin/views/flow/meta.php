<h3><a href="<?php echo admin_url(); ?>/tools.php?page=<?php echo esc_attr($this->settings_page); ?>&flow=<?php echo esc_attr($data['flow']->ID); ?>"><?php echo $data['flow']->post_title; ?></a></h3>
    <div class="cauto-flow-meta">
    <div class="cauto-details-last-run"><?php esc_attr_e('Last Run', 'autoqa-test-automation'); ?>
        <span><?php echo esc_html($data['metas']['last_run']); ?></span>
    </div>
    <div class="cauto-details-steps"><?php esc_attr_e('Steps', 'autoqa-test-automation'); ?>
        <span><?php echo esc_html($data['metas']['steps']); ?></span>    
    </div>
    <div class="cauto-details-status"><?php esc_attr_e('Status', 'autoqa-test-automation'); ?>
        <span><?php echo esc_html($data['metas']['status']); ?></span>
    </div>
</div>