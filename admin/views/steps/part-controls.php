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
<div class="cauto-modal-control-area">
    <div class="wrapper">
        <div class="col-50">
            <?php
                if (!empty($data['left_controls'])): 
                    $this->render_ui(['buttons' => $data['left_controls']], 'buttons', []); 
                endif;
            ?>
        </div>
        <div class="col-50" id="cauto-step-config-control-area">
            <?php
                if (!empty($data['right_controls'])): 
                    $this->render_ui(['buttons' => $data['right_controls']], 'buttons', []); 
                endif;
            ?>
            <!-- call this using hook, not important -->
            <input type="hidden" id="cauto_step_config_field_ids" value="<?php echo esc_attr(wp_json_encode($data['field_ids'])); ?>">
            <input type="hidden" id="cauto_step_config_describe" value="<?php echo esc_attr(wp_json_encode($data['step_indicator'])); ?>">
        </div>
    </div>
</div>