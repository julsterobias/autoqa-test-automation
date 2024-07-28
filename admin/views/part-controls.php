<?php if (!empty($data['controls'])): ?>
<ul>
    <?php foreach ($data['controls'] as $control): ?>
    <li><button <?php echo $control['attr']; ?>><?php echo (isset($control['icon']))? $control['icon'] : null; ?><?php echo esc_html($control['label']); ?></button></li>
    <?php endforeach; ?>
</ul>
<?php endif; ?>