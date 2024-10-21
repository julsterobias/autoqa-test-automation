<?php 
if ( !function_exists( 'add_action' ) ){
    header( 'Status: 403 Forbidden' );
    header( 'HTTP/1.1 403 Forbidden' );
    exit();
}

if ( !function_exists( 'add_filter' ) ){
    header( 'Status: 403 Forbidden' );
    header( 'HTTP/1.1 403 Forbidden' );
    exit();
}
?>
<div class="cauto-saved-flows">
    <?php 
    if(!empty($data['flows'])): 
        foreach ($data['flows'] as $flow):
    ?>
            <div class="cauto-flow-el cauto-settings-wrappers">
                <div class="wrapper">
                    <div class="col-3 col">
                        <div class="cuato-flow-status <?php echo esc_attr($flow['status']); ?>">
                            <?php if ($flow['status'] === 'no-run'): ?>
                            <span class="dashicons dashicons-minus"></span>
                            <?php elseif ($flow['status'] === 'passed'): ?>
                                <span class="dashicons dashicons-saved"></span>
                            <?php else: ?>
                                <span class="dashicons dashicons-no"></span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="col-87 col-details">
                        <h3><a href="<?php echo esc_url(admin_url('tools.php?page='.$this->settings_page.'&flow='.$flow['ID'])); ?>"><?php echo esc_html($flow['name']); ?></a></h3>
                            <div class="cauto-flow-meta">
                            <div class="cauto-details-last-run"><?php esc_html_e('Last Run', 'autoqa-test-automation'); ?>
                                <span><?php echo esc_html($flow['last_run']); ?></span>
                            </div>
                            <div class="cauto-details-steps"><?php esc_html_e('Steps', 'autoqa-test-automation'); ?>
                                <span><?php echo esc_html($flow['steps_number']); ?></span>    
                            </div>
                        </div>
                    </div>
                    <div class="col-10 col">
                        <div class="cauto-details-run-control">
                            <?php do_action('cauto_flow_run_button', $flow); ?>
                        </div>
                    </div>
                </div>
            </div>
    <?php 
        endforeach;
    endif; ?>
</div>