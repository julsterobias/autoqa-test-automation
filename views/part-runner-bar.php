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
<?php 
    $width = count($data['steps']);
    $width = 100 / $width;
    foreach ($data['steps'] as $index => $flow):
    $current = ($index === 0)? 'cauto_bar_loader' : null; 
?>
    <div class="cauto-bar <?php echo esc_attr($current); ?>" style="<?php echo 'width:'.esc_attr($width).'%;'; ?>"></div>
<?php endforeach; ?>