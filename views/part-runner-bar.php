<?php 
    $width = count($data['steps']);
    $width = 100 / $width;
    foreach ($data['steps'] as $index => $flow):
    $current = ($index === 0)? 'cauto_bar_loader' : null; 
?>
    <div class="cauto-bar <?php echo esc_attr($current); ?>" style="<?php echo 'width:'.$width.'%;'; ?>"></div>
<?php endforeach; ?>