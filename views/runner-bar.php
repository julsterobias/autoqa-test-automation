<?php if (!empty($data['steps'])): ?>
<div class="cuato-runner-indicator">
    <div class="cauto-runner-bars">
        <?php 
        $width = count($data['steps']);
        $width = 100 / $width;
        foreach ($data['steps'] as $index => $flow):
            $current = ($index === 0)? 'cauto_bar_loader' : null; 
        ?>
            <div class="cauto-bar <?php echo esc_attr($current); ?>" style="<?php echo 'width:'.$width.'%;'; ?>"></div>
        <?php endforeach; ?>
    </div>

    <div class="cauto-runner-completed">
        <div class="cauto-completed-content">
            <h3 class="status_title">--</h3>
            <div class="result"><?php esc_html_e('No result found, contact developer', 'autoqa-test-automation'); ?></div>
            <p><input type="button" id="cauto-close-runner-modal-result" value="<?php esc_attr_e('Close', 'autoqa-test-automation'); ?>"></p>
        </div>
    </div>
</div>
<?php endif; ?>