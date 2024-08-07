<?php 
if (!empty($data['data']['buttons'])): 
    foreach ($data['data']['buttons'] as $button):
        switch ($button['field']):
            case 'button':
                $is_hidden = (isset($button['hidden']))? 'hidden' : null;
?>          <div class="cauto_button_wrapper <?php echo esc_attr($is_hidden); ?>">
            <?php echo (isset($button['text']))? $button['text'] : null; ?>
            <button <?php echo $button['iattr']; ?> ><?php echo (isset($button['icon']))? $button['icon'] : null; ?><span class="cauto_button_text"><?php echo esc_html($button['label']); ?></span></button></div>
<?php   
            break;
        endswitch;
    endforeach;
endif; 
?>