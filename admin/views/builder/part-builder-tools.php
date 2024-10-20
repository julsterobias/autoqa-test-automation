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
<?php if (!empty($data['is_results'])): ?>

<div class="builder-wrapper">
    <div class="wrapper cauto-runner-button-area">
        <?php do_action('cauto_load_runner_buttons', $data['flow_id']); ?>
    </div>
</div>    

<div class="builder-wrapper">
    <div class="wrapper">
        <div class="col-30">
            <div class="cauto-builder-steps cauto-runner-col" id="cauto-stepss">
                <?php do_action('cauto_load_runners', $data['results'], $data['flow_id'], $data['total']); ?>
            </div>
        </div>
        <div class="col-70">
            <div class="cauto-builder-area" id="cauto-result-steps">
                <span class="cauto-result-opening-messsage"><?php esc_html_e('Select runner to display the steps results', 'autoqa-test-automation'); ?></span>
            </div>
        </div>
    </div>
</div>

<?php else: ?>    
<div class="builder-wrapper">
    <div class="wrapper">
        <div class="col-20">
            <div class="cauto-builder-steps">
                <?php do_action('cauto_load_builder_steps'); ?>
            </div>
        </div>
        <div class="col-80">
            <div class="cauto-builder-area">
                <div class="builder-header">
                    <div class="builder-header-meta">
                        <?php do_action('cauto_load_builder_meta', $data['details']); ?> &nbsp;<?php echo esc_html($data['last_run']); ?>
                    </div>
                    <div class="builder-header-controls">
                        <?php do_action('cauto_load_builder_control', $data['details']); ?>
                    </div>
                </div>
                <div class="builder-site">
                    <ul class="cauto_steps_builder"><?php do_action('cauto_load_saved_steps'); ?></ul>
                </div>
            </div>
        </div>
    </div>
</div>
<?php 
endif;
?>