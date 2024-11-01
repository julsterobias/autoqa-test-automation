<?php 
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly 
?>
<?php 
if (!empty($data['data']['buttons'])): 
    foreach ($data['data']['buttons'] as $button):
        $is_hidden = (isset($button['hidden']))? 'hidden' : null;
        switch ($button['field']):
            case 'button':
?>          <div class="cauto_button_wrapper <?php echo esc_attr($is_hidden); ?>">
            <?php echo (isset($button['text']))? esc_html($button['text']) : null; ?>
            <button <?php echo htmlspecialchars_decode(esc_attr($button['iattr'])); ?> ><?php echo (isset($button['icon']))? htmlspecialchars_decode(esc_html($button['icon'])) : null; ?><span class="cauto_button_text"><?php echo esc_html($button['label']); ?></span></button></div>
<?php   
            break;
            case 'a':
?>
            <div class="cauto_button_wrapper <?php echo esc_attr($is_hidden); ?>">
            <?php echo (isset($button['text']))? esc_html($button['text']) : null; ?>
            <a <?php echo htmlspecialchars_decode(esc_attr($button['iattr'])); ?> ><?php echo (isset($button['icon']))? htmlspecialchars_decode(esc_html($button['icon'])) : null; ?><span class="cauto_button_text"><?php echo esc_html($button['label']); ?></span></a></div>
<?php
            break;
        endswitch;
    endforeach;
endif; 
?>