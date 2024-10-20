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

if (!empty($data['runners'])): 
    foreach ($data['runners'] as $result):    
?>
<li data-runner-id="<?php echo esc_attr($result['ID']); ?>">
    <div class="wrapper">
        <div class="cauto-result-m-status col-10">
            <?php 
            if (isset($result['flow_status'])): ?>
                <span class="dashicons dashicons-no"></span>
            <?php else: ?>
                <span class="dashicons dashicons-saved"></span>
            <?php endif; ?>                    
        </div>
            <div class="cauto-result-m-details col-80">
                <span class="cauto-result-meta-name"><?php echo esc_html($result['name']); ?></span>
                <span class="cauto-result-meta-date"><?php echo esc_html($result['date']); ?></span>
            </div>
            <div class="cauto-result-m-arrow col-10">
            <span class="dashicons dashicons-arrow-right-alt2"></span>
        </div>
    </div>      
</li>
<?php 
    endforeach;
endif; ?>